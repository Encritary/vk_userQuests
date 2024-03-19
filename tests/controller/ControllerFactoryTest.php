<?php

declare(strict_types=1);

namespace encritary\userQuestsUnit\controller;

use encritary\userQuests\controller\Controller;
use encritary\userQuests\controller\ControllerFactory;
use encritary\userQuests\controller\exception\ControllerNotFoundException;
use encritary\userQuests\request\Request;
use encritary\userQuests\response\Response;
use encritary\userQuests\response\SuccessResponse;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use function quoted_printable_decode;

class ControllerFactoryTest extends TestCase{

	public function testRegisterGet() : void{
		$factory = new ControllerFactory();

		$controller = new DummyController();
		$factory->register($controller);

		self::assertEquals($controller, $factory->get('test'));
	}

	public function testControllerNotFound() : void{
		$factory = new ControllerFactory();

		$this->expectException(ControllerNotFoundException::class);
		$factory->get('test');
	}

	public function testRegisterSameName() : void{
		$factory = new ControllerFactory();

		$factory->register(new DummyController());

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("is already registered");
		$factory->register(new DummyController());
	}

	public function testSetupCalled() : void{
		$factory = new ControllerFactory();

		$controller = new DummyController();
		$factory->register($controller);

		self::assertTrue($controller->isSetUp);
	}
}
