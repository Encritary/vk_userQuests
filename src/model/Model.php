<?php

declare(strict_types=1);

namespace encritary\userQuests\model;

use encritary\userQuests\db\Db;
use PDO;
use PDOStatement;

abstract class Model{

	public function insert() : void{
		$db = Db::get();
		$stmt = $this->prepareInsert($db);
		$stmt->execute();
		$this->afterInsert($db);
	}

	protected function afterInsert(PDO $db) : void{

	}

	abstract protected function prepareInsert(PDO $db) : PDOStatement;
}