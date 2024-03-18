<?php

declare(strict_types=1);

namespace encritary\userQuests\controller\impl;

use encritary\userQuests\controller\AttributedController;
use encritary\userQuests\controller\Parameters;
use encritary\userQuests\controller\Route;
use encritary\userQuests\model\impl\User;
use encritary\userQuests\request\Request;
use encritary\userQuests\response\Response;
use encritary\userQuests\response\SuccessResponse;

class UserController extends AttributedController{

	#[Route]
	public function new(Request $request) : Response{
		$name = Parameters::string('name', $request->args);

		$user = new User($name);
		$user->insert();

		return new SuccessResponse(['user_id' => $user->id]);
	}

	#[Route]
	public function get(Request $request) : Response{
		$id = Parameters::uint32('id', $request->args);

		$user = User::get($id);
		return new SuccessResponse(['user' => [
			'name' => $user->name,
			'balance' => $user->balance
		]]);
	}

	public function getName() : string{
		return 'user';
	}
}