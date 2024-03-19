<?php

declare(strict_types=1);

namespace encritary\userQuestsUnit\controller\impl;

use encritary\userQuests\controller\impl\UserController;
use encritary\userQuests\request\Request;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

class UserControllerTest extends TestCase{
	use ControllerTestTrait;

	public const TEST_USER_NAME = "test";

	private UserController $controller;

	protected function setUp() : void{
		$this->controller = new UserController();
	}

	public function testUserNew() : int{
		$response = $this->controller->new(
			new Request('http://localhost:8080/user.new', ['name' => self::TEST_USER_NAME])
		);
		$data = self::getJsonViewData($response);
		self::assertEquals('success', $data['status']);

		$userId = $data['result']['user_id'];
		self::assertIsInt($userId);

		return $userId;
	}

	#[Depends('testUserNew')]
	public function testUserGet(int $userId) : void{
		$response = $this->controller->get(
			new Request('http://localhost:8080/user.get', ['id' => $userId])
		);
		$data = self::getJsonViewData($response);
		self::assertEquals('success', $data['status']);

		$user = $data['result']['user'];
		self::assertEquals(self::TEST_USER_NAME, $user['name']);
	}
}