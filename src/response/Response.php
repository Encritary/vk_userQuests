<?php

declare(strict_types=1);

namespace encritary\userQuests\response;

use encritary\userQuests\response\http\HttpCode;
use encritary\userQuests\view\View;

class Response{

	public function __construct(
		public readonly View $view,
		public readonly HttpCode $httpCode = HttpCode::Ok
	){}

	public function echo() : void{
		foreach($this->view->getHeaders() as $headerName => $val){
			header($headerName . ': ' . $val);
		}
		echo $this->view->encode();
	}
}