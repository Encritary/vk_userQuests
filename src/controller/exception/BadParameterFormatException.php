<?php

declare(strict_types=1);

namespace encritary\userQuests\controller\exception;

use encritary\userQuests\exception\AppException;
use encritary\userQuests\exception\ErrorCode;
use encritary\userQuests\response\http\HttpCode;
use Throwable;

class BadParameterFormatException extends AppException{

	public function __construct(string $message = "", HttpCode $httpCode = HttpCode::BadRequest, ?Throwable $previous = null){
		parent::__construct($message, $httpCode, ErrorCode::BadParameterFormat->value, $previous);
	}
}