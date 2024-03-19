<?php

declare(strict_types=1);

namespace encritary\userQuestsUnit\controller\impl;

use encritary\userQuests\db\Db;
use encritary\userQuests\db\DbCredentials;
use encritary\userQuests\response\Response;
use encritary\userQuests\view\JsonView;

trait ControllerTestTrait{

	public static function setUpBeforeClass() : void{
		Db::init(new DbCredentials('sqlite::memory:'));

		$db = Db::get();
		$db->exec(<<<QUERY
CREATE TABLE IF NOT EXISTS users (
    id integer primary key not null,
    name varchar(32) not null,
    balance int unsigned not null default 0
);
QUERY);
		$db->exec(<<<QUERY
CREATE TABLE IF NOT EXISTS quests (
    id integer primary key not null,
    name varchar(128) not null,
    cost int unsigned not null
);
QUERY);
		$db->exec(<<<QUERY
CREATE TABLE IF NOT EXISTS user_completed_quests (
    user_id integer unsigned not null,
    quest_id integer unsigned not null,
    completed_at integer not null,
    UNIQUE (user_id, quest_id),
    FOREIGN KEY (user_id) REFERENCES users (id),
    FOREIGN KEY (quest_id) REFERENCES quests (id)
);
QUERY);
	}

	protected static function getJsonViewData(Response $response) : array{
		$view = $response->view;
		self::assertInstanceOf(JsonView::class, $view);
		return $view->data;
	}
}