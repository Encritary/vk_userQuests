<?php

declare(strict_types=1);

namespace encritary\userQuests\model;

use mysqli_stmt;

abstract class Model{

	public function insert() : void{
		$stmt = $this->prepareInsert();
		$stmt->execute();
		$this->afterInsert($stmt);
	}

	abstract protected function prepareInsert() : mysqli_stmt;

	abstract protected function afterInsert(mysqli_stmt $stmt) : void;
}