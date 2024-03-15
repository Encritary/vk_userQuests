<?php

declare(strict_types=1);

namespace encritary\userQuests\response;

use encritary\userQuests\exception\AppException;
use encritary\userQuests\response\http\HttpCode;
use encritary\userQuests\view\JsonView;
use Exception;

class ErrorResponse extends Response{

	public static function fromException(Exception $e) : self{
		if($e instanceof AppException){
			return new self($e->getMessage(), $e->getCode(), $e->httpCode);
		}
		return new self($e->getMessage(), $e->getCode(), HttpCode::InternalServerError);
	}

	public function __construct(string $message, int $code, HttpCode $httpCode = HttpCode::BadRequest){
		parent::__construct(new JsonView([
			'status' => 'error', 'error' => [
				'code' => $code,
				'message' => $message
			]
		]), $httpCode);
	}
}