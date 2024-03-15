<?php

declare(strict_types=1);

namespace encritary\userQuests\controller\exception;

use encritary\userQuests\exception\AppException;
use encritary\userQuests\exception\ErrorCode;
use encritary\userQuests\response\http\HttpCode;
use Throwable;

class ControllerNotFoundException extends AppException{

	public readonly string $controllerName;

	public function __construct(string $controllerName, HttpCode $httpCode = HttpCode::NotFound, ?Throwable $previous = null){
		$this->controllerName = $controllerName;
		parent::__construct("Controller '$controllerName' not found", $httpCode, ErrorCode::ControllerNotFound->value, $previous);
	}
}