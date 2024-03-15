<?php

declare(strict_types=1);

namespace encritary\userQuests\model;

use encritary\userQuests\exception\SqlException;
use mysqli_sql_exception;
use mysqli_stmt;

abstract class Model{

	public function insert() : void{
		$stmt = $this->prepareInsert();
		try{
			$stmt->execute();
		}catch(mysqli_sql_exception $e){
			throw new SqlException($e->getMessage(), $e);
		}
		$this->afterInsert($stmt);
	}

	abstract protected function prepareInsert() : mysqli_stmt;

	abstract protected function afterInsert(mysqli_stmt $stmt) : void;
}