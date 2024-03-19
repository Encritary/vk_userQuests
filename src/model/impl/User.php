<?php

declare(strict_types=1);

namespace encritary\userQuests\model\impl;

use encritary\userQuests\db\Db;
use encritary\userQuests\model\exception\ModelNotFoundException;
use encritary\userQuests\model\Model;
use PDO;
use PDOStatement;
use function var_dump;

class User extends Model{

	public static function get(int $id) : self{
		$db = Db::get();
		$stmt = $db->prepare(<<<QUERY
SELECT name, balance
FROM users
WHERE id = ?
QUERY);
		$stmt->bindValue(1, $id, PDO::PARAM_INT);
		$stmt->execute();

		$row = $stmt->fetch();
		if($row === false){
			throw new ModelNotFoundException("User with ID $id not found");
		}

		[$name, $balance] = $row;
		return new self($name, (int) $balance, $id);
	}

	public function __construct(
		public string $name,
		public int $balance = 0,
		public ?int $id = null
	){}

	protected function prepareInsert(PDO $db) : PDOStatement{
		$stmt = $db->prepare(<<<QUERY
INSERT INTO users
(name, balance)
VALUES (?, ?)
QUERY);
		$stmt->bindValue(1, $this->name);
		$stmt->bindValue(2, $this->balance);
		return $stmt;
	}

	protected function afterInsert(PDO $db) : void{
		$this->id = (int) $db->lastInsertId();
	}

	public function incrementBalance(int $balanceDiff) : void{
		$db = Db::get();
		$stmt = $db->prepare(<<<QUERY
UPDATE users
SET balance = balance + ?
WHERE id = ?
QUERY);
		$stmt->execute([$balanceDiff, $this->id]);

		$this->balance += $balanceDiff;
	}
}