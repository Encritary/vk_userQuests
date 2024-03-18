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

	public function getName() : string{
		return 'user';
	}
}