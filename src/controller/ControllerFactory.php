<?php

declare(strict_types=1);

namespace encritary\userQuests\controller;

use encritary\userQuests\controller\exception\ControllerNotFoundException;
use InvalidArgumentException;

final class ControllerFactory{

	private static self $instance;

	public static function init() : void{
		self::$instance = new self;
	}

	public static function getInstance() : self{
		return self::$instance;
	}

	/** @var Controller[] */
	private array $controllers = [];

	public function __construct(){
		self::$instance = $this;
	}

	/**
	 * @return Controller[]
	 */
	public function getAll() : array{
		return $this->controllers;
	}

	public function get(string $controllerName) : Controller{
		if(!isset($this->controllers[$controllerName])){
			throw new ControllerNotFoundException($controllerName);
		}
		return $this->controllers[$controllerName];
	}

	public function register(Controller $controller) : void{
		if(isset($this->controllers[$controller->getName()])){
			throw new InvalidArgumentException("Controller {$controller->getName()} is already registered");
		}
		$controller->setup();
		$this->controllers[$controller->getName()] = $controller;
	}
}