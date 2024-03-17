<?php

declare(strict_types=1);

namespace encritary\userQuests\config;

use encritary\userQuests\db\MysqlCredentials;
use JsonException;
use function file_exists;
use function file_get_contents;
use function json_decode;
use const JSON_THROW_ON_ERROR;

class Config{

	public static function fromFile(string $path) : self{
		if(!file_exists($path)){
			throw new ConfigException("Config file $path doesn't exist");
		}

		$contents = file_get_contents($path);
		if($contents === false){
			throw new ConfigException("Couldn't read file $path");
		}

		try{
			$data = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
		}catch(JsonException $e){
			throw new ConfigException("Couldn't parse JSON: {$e->getMessage()}", $e);
		}

		return self::fromArray($data);
	}

	public static function fromArray(array $data) : self{
		if(!isset($data['db'])){
			throw new ConfigException("Missing DB connection credentials");
		}

		$credentials = $data['db'];
		foreach(['host', 'user', 'password', 'db'] as $required){
			if(!isset($credentials[$required])){
				throw new ConfigException("Missing DB connection credential: $required");
			}
		}

		return new self(
			new MysqlCredentials(
				$credentials['host'],
				$credentials['user'],
				$credentials['password'],
				$credentials['db'],
				$credentials['port'] ?? 3306
			)
		);
	}

	public function __construct(
		public readonly MysqlCredentials $dbCredentials
	){}
}