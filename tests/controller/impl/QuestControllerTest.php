<?php

declare(strict_types=1);

namespace encritary\userQuestsUnit\controller\impl;

use encritary\userQuests\controller\impl\QuestController;
use encritary\userQuests\exception\ErrorCode;
use encritary\userQuests\model\impl\Quest;
use encritary\userQuests\model\impl\User;
use encritary\userQuests\request\Request;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use function implode;

class QuestControllerTest extends TestCase{
	use ControllerTestTrait;

	public const TEST_QUEST_NAME = "test";
	public const TEST_QUEST_COST = 50;

	public const TEST_USER_INITIAL_BALANCE = 40;

	private QuestController $controller;

	protected function setUp() : void{
		$this->controller = new QuestController();
	}

	public function testQuestNew() : int{
		$response = $this->controller->new(
			new Request('http://localhost:8080/quest.new', [
				'name' => self::TEST_QUEST_NAME,
				'cost' => self::TEST_QUEST_COST
			])
		);
		$data = self::getJsonViewData($response);
		self::assertEquals('success', $data['status']);

		$questId = $data['result']['quest_id'];
		self::assertIsInt($questId);

		return $questId;
	}

	#[Depends('testQuestNew')]
	public function testQuestGet(int $questId) : void{
		$response = $this->controller->get(
			new Request('http://localhost:8080/quest.get', ['id' => $questId])
		);
		$data = self::getJsonViewData($response);
		self::assertEquals('success', $data['status']);

		$quest = $data['result']['quest'];
		self::assertEquals(self::TEST_QUEST_NAME, $quest['name']);
		self::assertEquals(self::TEST_QUEST_COST, $quest['cost']);
	}

	#[Depends('testQuestNew')]
	public function testCompleteQuest(int $questId) : void{
		$user = new User("test", self::TEST_USER_INITIAL_BALANCE);
		$user->insert();

		$response = $this->controller->completeQuest(
			new Request('http://localhost:8080/quest.completeQuest', [
				'user_id' => $user->id,
				'quest_id' => $questId
			])
		);
		$data = self::getJsonViewData($response);
		self::assertEquals('success', $data['status']);

		$quest = $data['result']['quest'];
		self::assertEquals(self::TEST_QUEST_NAME, $quest['name']);
		self::assertEquals(self::TEST_QUEST_COST, $quest['cost']);

		$userBalance = $data['result']['user_balance'];
		self::assertEquals(self::TEST_USER_INITIAL_BALANCE + self::TEST_QUEST_COST, $userBalance);
	}

	#[Depends('testQuestNew')]
	public function testQuestNewWithRequirements(int $questId) : int{
		$quest2 = new Quest('test2', 150);
		$quest2->insert();

		$response = $this->controller->new(
			new Request('http://localhost:8080/quest.new', [
				'name' => 'test3',
				'cost' => 150,
				'required_quests' => implode(',', [$questId, $quest2->id])
			])
		);
		$data = self::getJsonViewData($response);
		self::assertEquals('success', $data['status']);

		$newQuestId = $data['result']['quest_id'];
		self::assertIsInt($newQuestId);

		return $newQuestId;
	}

	#[Depends('testQuestNewWithRequirements')]
	public function testCompleteQuestUnfulfilledRequirements(int $questWithRequirementsId) : void{
		$user = new User("test", 0);
		$user->insert();

		$response = $this->controller->completeQuest(
			new Request('http://localhost:8080/quest.completeQuest', [
				'user_id' => $user->id,
				'quest_id' => $questWithRequirementsId
			])
		);
		$data = self::getJsonViewData($response);
		self::assertEquals('error', $data['status']);
		self::assertEquals(ErrorCode::QuestRequirementsNotFulfilled->value, $data['error']['code']);
	}
}