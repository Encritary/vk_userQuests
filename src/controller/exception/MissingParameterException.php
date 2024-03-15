<?php

declare(strict_types=1);

namespace encritary\userQuests\controller\exception;

use encritary\userQuests\exception\AppException;
use encritary\userQuests\exception\ErrorCode;
use encritary\userQuests\response\http\HttpCode;
use Throwable;

class MissingParameterException extends AppException{

	public readonly string $parameter;

	public function __construct(string $parameter, HttpCode $httpCode = HttpCode::BadRequest, ?Throwable $previous = null){
		$this->parameter = $parameter;
		parent::__construct("Parameter $parameter is required", $httpCode, ErrorCode::MissingParameter->value, $previous);
	}
}