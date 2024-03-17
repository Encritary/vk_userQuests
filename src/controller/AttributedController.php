<?php

declare(strict_types=1);

namespace encritary\userQuests\controller;

use encritary\userQuests\controller\exception\MethodNotFoundException;
use encritary\userQuests\request\Request;
use encritary\userQuests\response\Response;
use ReflectionObject;
use UnexpectedValueException;

abstract class AttributedController implements Controller{

	/** @var array<string, callable> */
	private array $methods = [];

	public function __construct(){
		$obj = new ReflectionObject($this);
		foreach($obj->getMethods() as $method){
			foreach($method->getAttributes(Route::class) as $attr){
				$methodName = $attr->getArguments()['method'] ?? $method->getName();

				if(isset($this->methods[$methodName])){
					throw new UnexpectedValueException("Method $methodName declared twice");
				}

				$numParams = $method->getNumberOfRequiredParameters();

				if($numParams === 1){
					$param = $method->getParameters()[0];
					if($param->getType()->getName() !== Request::class || $param->allowsNull()){
						throw new UnexpectedValueException($obj->getName() . "::" . $methodName . ": Expected parameter 'request' to be non-null " . Request::class);
					}
				}elseif($numParams !== 0){
					throw new UnexpectedValueException($obj->getName() . "::" . $methodName . ": Expected from zero to one required parameter 'request'");
				}

				if($method->getReturnType()->getName() !== Response::class){
					throw new UnexpectedValueException($obj->getName() . "::" . $methodName . ": Expected return type to be " . Response::class);
				}

				$this->methods[$methodName] = [$this, $methodName];
			}
		}
	}

	public function execute(string $name, Request $request) : Response{
		if(!isset($this->methods[$name])){
			throw new MethodNotFoundException("{$this->getName()}.$name");
		}
		return ($this->methods[$name])($request);
	}
}