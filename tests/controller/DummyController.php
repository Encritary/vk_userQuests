<?php

declare(strict_types=1);

namespace encritary\userQuestsUnit\controller;

use encritary\userQuests\controller\Controller;
use encritary\userQuests\request\Request;
use encritary\userQuests\response\Response;
use encritary\userQuests\response\SuccessResponse;

class DummyController implements Controller{
	public bool $isSetUp = false;

	public function execute(string $methodName, Request $request) : Response{
		return new SuccessResponse("");
	}

	public function getName() : string{
		return "test";
	}

	public function setup() : void{
		$this->isSetUp = true;
	}
}