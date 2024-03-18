<?php

declare(strict_types=1);

namespace encritary\userQuests\model\impl;

use encritary\userQuests\db\Db;
use encritary\userQuests\model\Model;
use mysqli_stmt;

class User extends Model{

	public function __construct(
		public string $name,
		public int $balance = 0,
		public ?int $id = null
	){}

	protected function prepareInsert() : mysqli_stmt{
		$db = Db::get();
		$stmt = $db->prepare(<<<QUERY
INSERT INTO users
(name, balance)
VALUES (?, ?)
QUERY);
		$stmt->bind_param('si', $this->name, $this->balance);
		return $stmt;
	}

	protected function afterInsert(mysqli_stmt $stmt) : void{
		$this->id = $stmt->insert_id;
	}
}