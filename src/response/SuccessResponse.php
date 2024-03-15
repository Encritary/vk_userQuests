<?php

declare(strict_types=1);

namespace encritary\userQuests\response;

use encritary\userQuests\response\http\HttpCode;
use encritary\userQuests\view\JsonView;

class SuccessResponse extends Response{

	public function __construct(mixed $data, HttpCode $httpCode = HttpCode::Ok){
		parent::__construct(new JsonView([
			'status' => 'success',
			'result' => $data
		]), $httpCode);
	}
}