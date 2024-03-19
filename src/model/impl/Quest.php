<?php

declare(strict_types=1);

namespace encritary\userQuests\model\impl;

use encritary\userQuests\db\Db;
use encritary\userQuests\model\exception\ModelNotFoundException;
use encritary\userQuests\model\Model;
use PDO;
use PDOStatement;

class Quest extends Model{

	public static function get(int $id) : self{
		$db = Db::get();
		$stmt = $db->prepare(<<<QUERY
SELECT name, cost
FROM quests
WHERE id = ?
QUERY);
		$stmt->bindValue(1, $id);
		$stmt->execute();

		$row = $stmt->fetch();
		if($row === false){
			throw new ModelNotFoundException("Quest with ID $id not found");
		}

		[$name, $cost] = $row;
		return new self($name, (int) $cost, $id);
	}


	public function __construct(
		public string $name,
		public int $cost = 0,
		public ?int $id = null
	){}

	protected function prepareInsert(PDO $db) : PDOStatement{
		$stmt = $db->prepare(<<<QUERY
INSERT INTO quests
(name, cost)
VALUES (?, ?)
QUERY);
		$stmt->bindValue(1, $this->name);
		$stmt->bindValue(2, $this->cost);
		return $stmt;
	}

	protected function afterInsert(PDO $db) : void{
		$this->id = (int) $db->lastInsertId();
	}
}