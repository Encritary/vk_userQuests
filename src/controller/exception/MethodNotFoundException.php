<?php

declare(strict_types=1);

namespace encritary\userQuests\controller\exception;

use encritary\userQuests\exception\AppException;
use encritary\userQuests\exception\ErrorCode;
use encritary\userQuests\response\http\HttpCode;
use Throwable;

class MethodNotFoundException extends AppException{

	public readonly string $methodName;

	public function __construct(string $methodName, HttpCode $httpCode = HttpCode::NotFound, ?Throwable $previous = null){
		$this->methodName = $methodName;
		parent::__construct("Method '$methodName' not found", $httpCode, ErrorCode::MethodNotFound->value, $previous);
	}
}