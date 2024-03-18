<?php

declare(strict_types=1);

namespace encritary\userQuests\controller\impl;

use encritary\userQuests\controller\AttributedController;
use encritary\userQuests\controller\Parameters;
use encritary\userQuests\controller\Route;
use encritary\userQuests\model\impl\Quest;
use encritary\userQuests\request\Request;
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

	public function getName() : string{
		return 'quest';
	}
}