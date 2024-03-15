<?php

declare(strict_types=1);

namespace encritary\userQuests\view;

use function json_encode;

class JsonView implements View{

	public function __construct(
		public array $data
	){}

	public function getHeaders() : array{
		return ['Content-Type' => 'application/json'];
	}

	public function encode() : string{
		return json_encode($this->data);
	}
}