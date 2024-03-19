<?php

namespace encritary\userQuests\db;

use PDO;
use PDOException;
use function stristr;

final class Db{

	private static DbCredentials $credentials;
	private static ?PDO $db = null;

	public static function init(DbCredentials $credentials) : void{
		self::$credentials = $credentials;
		self::$db = null;
	}

	public static function get() : PDO{
		if(self::$db !== null){
			try{
				self::$db->query('SELECT 1');
			}catch(PDOException $e){
				if($e->getCode() === 'HY000' && stristr($e->getMessage(), 'server has gone away')){
					self::$db = null;
				}else{
					throw $e;
				}
			}
		}
		if(self::$db === null){
			// Connect first time or reconnect, if gone away
			self::$db = self::createConnection();
		}
		return self::$db;
	}

	protected static function createConnection() : PDO{
		return new PDO(
			self::$credentials->dsn,
			self::$credentials->username,
			self::$credentials->password,
			[
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			]
		);
	}
}