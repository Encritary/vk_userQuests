<?php

declare(strict_types=1);

namespace encritary\userQuests\controller\impl;

use encritary\userQuests\controller\AttributedController;
use encritary\userQuests\controller\Parameters;
use encritary\userQuests\controller\Route;
use encritary\userQuests\exception\ErrorCode;
use encritary\userQuests\model\impl\Quest;
use encritary\userQuests\model\impl\User;
use encritary\userQuests\model\impl\UserCompletedQuest;
use encritary\userQuests\request\Request;
use encritary\userQuests\response\ErrorResponse;
use encritary\userQuests\response\Response;
use encritary\userQuests\response\SuccessResponse;

class QuestController extends AttributedController{

	#[Route]
	public function new(Request $request) : Response{
		$name = Parameters::string('name', $request->args);
		$cost = Parameters::uint32('cost', $request->args);

		$quest = new Quest($name, $cost);
		$quest->insert();
		return new SuccessResponse(['quest_id' => $quest->id]);
	}

	#[Route]
	public function get(Request $request) : Response{
		$id = Parameters::uint32('id', $request->args);

		$quest = Quest::get($id);
		return new SuccessResponse(['quest' => [
			'name' => $quest->name,
			'cost' => $quest->cost
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