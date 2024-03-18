<?php

declare(strict_types=1);

namespace encritary\userQuests\response;

use encritary\userQuests\response\http\HttpCode;
use encritary\userQuests\view\View;
use function http_response_code;

class Response{

	public function __construct(
		public readonly View $view,
		public readonly HttpCode $httpCode = HttpCode::Ok
	){}

	public function echo() : void{
		http_response_code($this->httpCode->value);
		foreach($this->view->getHeaders() as $headerName => $val){
			header($headerName . ': ' . $val);
		}
		echo $this->view->encode();
	}
}