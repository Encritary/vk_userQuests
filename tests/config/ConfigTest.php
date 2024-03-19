<?php

declare(strict_types=1);

namespace encritary\userQuestsUnit\config;

use encritary\userQuests\config\Config;
use encritary\userQuests\config\ConfigException;
use PHPUnit\Framework\TestCase;
use function file_put_contents;
use function sys_get_temp_dir;
use function tempnam;
use function unlink;

class ConfigTest extends TestCase{

	public function testFromFile() : void{
		$path = tempnam(sys_get_temp_dir(), 'unit');
		file_put_contents($path, <<<JSON
{
  "db": {
    "dsn": "mysql:host=127.0.0.1;dbname=user_quests",
    "username": "someuser",
    "password": "123456"
  }
}
JSON);

		$config = Config::fromFile($path);
		self::assertEquals("123456", $config->dbCredentials->password);
	}

	public function testFromArray() : void{
		$config = Config::fromArray([
			'db' => [
				'dsn' => "mysql:host=127.0.0.1;dbname=user_quests",
				'username' => "someuser",
				'password' => "123456"
			]
		]);
		self::assertEquals("123456", $config->dbCredentials->password);
	}

	public function testNonExistingFile() : void{
		// Make non-existing file
		$path = tempnam(sys_get_temp_dir(), 'unit');
		unlink($path);

		$this->expectException(ConfigException::class);
		$this->expectExceptionMessage("doesn't exist");

		Config::fromFile($path);
	}

	public function testJsonSyntaxError() : void{
		$path = tempnam(sys_get_temp_dir(), 'unit');
		file_put_contents($path, "test");

		$this->expectException(ConfigException::class);
		$this->expectExceptionMessage("Couldn't parse JSON");

		Config::fromFile($path);
		unlink($path);
	}
}
