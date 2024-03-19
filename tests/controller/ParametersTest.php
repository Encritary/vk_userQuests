<?php

declare(strict_types=1);

namespace encritary\userQuestsUnit\controller;

use encritary\userQuests\controller\exception\BadParameterFormatException;
use encritary\userQuests\controller\exception\MissingParameterException;
use encritary\userQuests\controller\Parameters;
use PHPUnit\Framework\TestCase;
use const PHP_INT_MAX;

class ParametersTest extends TestCase{

	public function testString() : void{
		$param = Parameters::string('test', ['test' => "test"]);
		self::assertEquals("test", $param);
	}

	public function testStringDefault() : void{
		$param = Parameters::string('test', [], "test2");
		self::assertEquals("test2", $param);
	}

	public function testStringNotFound() : void{
		$this->expectException(MissingParameterException::class);
		Parameters::string('test', []);
	}

	public function testStringWithSize() : void{
		$param = Parameters::stringWithSize('test', ['test' => "test2"], 10);
		self::assertEquals("test2", $param);
	}

	public function testStringTooShort() : void{
		$this->expectException(BadParameterFormatException::class);
		$this->expectExceptionMessage("characters long");
		Parameters::stringWithSize('test', ['test' => "t"], 10, 2);
	}

	public function testStringTooLong() : void{
		$this->expectException(BadParameterFormatException::class);
		$this->expectExceptionMessage("characters long");
		Parameters::stringWithSize('test', ['test' => "test_test"], 7);
	}

	public function testUint() : void{
		$param = Parameters::uint('test', ['test' => "42"]);
		self::assertEquals(42, $param);
	}

	public function testUintNegative() : void{
		$this->expectException(BadParameterFormatException::class);
		$this->expectExceptionMessage("expected to be unsigned int");
		Parameters::uint('test', ['test' => "-42"]);
	}

	public function testUint32() : void{
		$param = Parameters::uint32('test', ['test' => "42"]);
		self::assertEquals(42, $param);
	}

	public function testUint32TooLarge() : void{
		$this->expectException(BadParameterFormatException::class);
		$this->expectExceptionMessage("is too large for 32-bit unsigned int");
		Parameters::uint32('test', ['test' => (string) PHP_INT_MAX]);
	}
}