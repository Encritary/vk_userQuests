<?php

declare(strict_types=1);

namespace encritary\userQuests\exception;

use encritary\userQuests\response\http\HttpCode;
use Throwable;

class SqlException extends AppException{

	public function __construct(string $message = "", ?Throwable $previous = null, HttpCode $httpCode = HttpCode::InternalServerError){
		parent::__construct($message, $httpCode, ErrorCode::SqlError->value, $previous);
	}
}