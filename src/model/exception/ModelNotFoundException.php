<?php

declare(strict_types=1);

namespace encritary\userQuests\model\exception;

use encritary\userQuests\exception\AppException;
use encritary\userQuests\exception\ErrorCode;
use encritary\userQuests\response\http\HttpCode;
use Throwable;

class ModelNotFoundException extends AppException{

	public function __construct(string $message = "", ?Throwable $previous = null){
		parent::__construct($message, HttpCode::NotFound, ErrorCode::ModelNotFound->value, $previous);
	}
}