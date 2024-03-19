<?php

declare(strict_types=1);

namespace encritary\userQuests\config;

use encritary\userQuests\db\DbCredentials;
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
			throw new ConfigException("Couldn't parse JSON: {$e->getMessage()}", 0, $e);
		}

		return self::fromArray($data);
	}

	public static function fromArray(array $data) : self{
		if(!isset($data['db'])){
			throw new ConfigException("Missing DB connection credentials");
		}

		$credentials = $data['db'];
		if(!isset($credentials['dsn'])){
			throw new ConfigException("Missing DB connection DSN credential");
		}

		return new self(
			new DbCredentials(
				$credentials['dsn'],
				$credentials['username'] ?? null,
				$credentials['password'] ?? null
			)
		);
	}

	public function __construct(
		public readonly DbCredentials $dbCredentials
	){}
}