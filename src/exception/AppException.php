<?php

declare(strict_types=1);

namespace encritary\userQuests\exception;

use encritary\userQuests\response\http\HttpCode;
use Exception;
use RuntimeException;
use Throwable;

class AppException extends RuntimeException{

	public readonly HttpCode $httpCode;

	public function __construct(string $message = "", HttpCode $httpCode = HttpCode::BadRequest, int $code = 0, ?Throwable $previous = null){
		$this->httpCode = $httpCode;
		parent::__construct($message, $code, $previous);
	}
}