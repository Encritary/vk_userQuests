<?php

namespace encritary\userQuests\db;

use mysqli;
use mysqli_sql_exception;

final class Db{

	private static MysqlCredentials $credentials;
	private static ?mysqli $db = null;

	public static function init(MysqlCredentials $credentials) : void{
		self::$credentials = $credentials;
	}

	public static function get() : mysqli{
		if(self::$db !== null){
			try{
				if(!self::$db->ping()){
					self::$db = null;
				}
			}catch(mysqli_sql_exception){ // MySQL server has gone away
				self::$db = null;
			}
		}
		if(self::$db === null){
			// Connect first time or reconnect, if gone away
			self::$db = new mysqli(
				self::$credentials->host,
				self::$credentials->user,
				self::$credentials->password,
				self::$credentials->db,
				self::$credentials->port
			);
		}
		return self::$db;
	}
}