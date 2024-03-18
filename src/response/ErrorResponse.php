<?php

declare(strict_types=1);

namespace encritary\userQuests\response;

use encritary\userQuests\exception\AppException;
use encritary\userQuests\exception\ErrorCode;
use encritary\userQuests\response\http\HttpCode;
use encritary\userQuests\view\JsonView;
use Throwable;
use function get_class;

class ErrorResponse extends Response{

	public static function fromThrowable(Throwable $e) : self{
		if($e instanceof AppException){
			return new self($e->getMessage(), $e->getCode(), $e->httpCode);
		}
		return new self(get_class($e) . " [{$e->getCode()}]: {$e->getMessage()}", ErrorCode::InternalError->value, HttpCode::InternalServerError);
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