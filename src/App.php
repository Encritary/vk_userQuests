<?php

declare(strict_types=1);

namespace encritary\userQuests;

use encritary\userQuests\config\Config;
use encritary\userQuests\controller\ControllerFactory;
use encritary\userQuests\controller\impl\QuestController;
use encritary\userQuests\controller\impl\UserController;
use encritary\userQuests\db\Db;
use encritary\userQuests\request\Request;
use encritary\userQuests\response\Response;
use encritary\userQuests\router\Router;

final class App{

	private Router $router;

	public function __construct(){
		$config = Config::fromFile('config.json');

		Db::init($config->dbCredentials);

		ControllerFactory::init();

		$controllerFactory = ControllerFactory::getInstance();
		$controllerFactory->register(new UserController());
		$controllerFactory->register(new QuestController());

		$this->router = new Router();
	}

	public function handleRequest(Request $request) : Response{
		return $this->router->execute($request);
	}
}