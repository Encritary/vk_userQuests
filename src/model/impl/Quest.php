<?php

declare(strict_types=1);

namespace encritary\userQuests\model\impl;

use encritary\userQuests\db\Db;
use encritary\userQuests\model\exception\ModelNotFoundException;
use encritary\userQuests\model\Model;
use mysqli_stmt;

class Quest extends Model{

	public static function get(int $id) : self{
		$db = Db::get();
		$stmt = $db->prepare(<<<QUERY
SELECT name, cost
FROM quests
WHERE id = ?
QUERY);
		$stmt->bind_param('i', $id);
		$stmt->execute();

		$result = $stmt->get_result();
		if($result->num_rows === 0){
			throw new ModelNotFoundException("Quest with ID $id not found");
		}

		[$name, $cost] = $result->fetch_row();
		return new self($name, (int) $cost, $id);
	}


	public function __construct(
		public string $name,
		public int $cost = 0,
		public ?int $id = null
	){}

	protected function prepareInsert() : mysqli_stmt{
		$db = Db::get();
		$stmt = $db->prepare(<<<QUERY
INSERT INTO quests
(name, cost)
VALUES (?, ?)
QUERY);
		$stmt->bind_param('si', $this->name, $this->cost);
		return $stmt;
	}

	protected function afterInsert(mysqli_stmt $stmt) : void{
		$this->id = $stmt->insert_id;
	}
}