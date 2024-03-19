<?php

declare(strict_types=1);

namespace encritary\userQuests\controller\impl;

use encritary\userQuests\controller\AttributedController;
use encritary\userQuests\controller\Parameters;
use encritary\userQuests\controller\Route;
use encritary\userQuests\exception\ErrorCode;
use encritary\userQuests\model\impl\Quest;
use encritary\userQuests\model\impl\QuestRequirement;
use encritary\userQuests\model\impl\User;
use encritary\userQuests\model\impl\UserCompletedQuest;
use encritary\userQuests\request\Request;
use encritary\userQuests\response\ErrorResponse;
use encritary\userQuests\response\Response;
use encritary\userQuests\response\SuccessResponse;
use function count;
use function implode;

class QuestController extends AttributedController{

	#[Route]
	public function new(Request $request) : Response{
		$name = Parameters::stringWithSize('name', $request->args, 128);
		$cost = Parameters::uint32('cost', $request->args);
		$requiredQuestIds = Parameters::uint32List('required_quests', $request->args, []);

		$quest = new Quest($name, $cost);
		$quest->insert();
		foreach($requiredQuestIds as $requiredQuestId){
			$requiredQuest = Quest::get($requiredQuestId);

			$requirement = new QuestRequirement($quest, $requiredQuest);
			$requirement->insert();
		}
		return new SuccessResponse(['quest_id' => $quest->id]);
	}

	#[Route]
	public function get(Request $request) : Response{
		$id = Parameters::uint32('id', $request->args);

		$quest = Quest::get($id);
		$questRequirements = QuestRequirement::getForQuest($quest);

		$requiredQuestsData = [];
		foreach($questRequirements as $requirement){
			$requiredQuestsData[] = [
				'id' => $requirement->requiredQuest->id,
				'name' => $requirement->requiredQuest->name,
				'cost' => $requirement->requiredQuest->cost
			];
		}

		return new SuccessResponse(['quest' => [
			'name' => $quest->name,
			'cost' => $quest->cost,
			'required_quests' => $requiredQuestsData
		]]);
	}

	#[Route]
	public function completeQuest(Request $request) : Response{
		$userId = Parameters::uint32('user_id', $request->args);
		$questId = Parameters::uint32('quest_id', $request->args);

		$user = User::get($userId);
		$quest = Quest::get($questId);

		$completedQuest = UserCompletedQuest::find($user, $quest);
		if($completedQuest !== null){
			return new ErrorResponse("User $userId already completed quest $questId on $completedQuest->completedAt", ErrorCode::QuestAlreadyCompleted->value);
		}

		$unfulfilledRequirements = [];

		$questRequirements = QuestRequirement::getForQuest($quest);
		foreach($questRequirements as $requirement){
			$completedRequirement = UserCompletedQuest::find($user, $requirement->requiredQuest);
			if($completedRequirement === null){
				$unfulfilledRequirements[] = $requirement->requiredQuest->id;
			}
		}

		if(count($unfulfilledRequirements) > 0){
			return new ErrorResponse("User $userId didn't complete required quests: " . implode(", ", $unfulfilledRequirements), ErrorCode::QuestRequirementsNotFulfilled->value);
		}

		$completedQuest = new UserCompletedQuest($user, $quest);
		$completedQuest->insert();

		$user->incrementBalance($quest->cost);

		return new SuccessResponse([
			'quest' => [
				'name' => $quest->name,
				'cost' => $quest->cost
			],
			'user_balance' => $user->balance
		]);
	}

	#[Route]
	public function getCompletedQuests(Request $request) : Response{
		$userId = Parameters::uint32('user_id', $request->args);
		$count = Parameters::uint32('count', $request->args, 200);
		$offset = Parameters::uint32('offset', $request->args, 0);

		$user = User::get($userId);

		$total = UserCompletedQuest::countForUser($user);

		$items = [];
		foreach(UserCompletedQuest::iterateForUser($user, $count, $offset) as $completedQuest){
			$items[] = [
				'quest' => [
					'id' => $completedQuest->quest->id,
					'name' => $completedQuest->quest->name,
					'cost' => $completedQuest->quest->cost
				],
				'completed_at' => $completedQuest->completedAt
			];
		}

		return new SuccessResponse([
			'balance' => $user->balance,
			'count' => $total,
			'items' => $items
		]);
	}

	public function getName() : string{
		return 'quest';
	}
}