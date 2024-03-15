<?php

declare(strict_types=1);

namespace encritary\userQuests\controller;

use Attribute;

#[Attribute]
class Route{

	public readonly ?string $method;

	public function __construct(?string $method = null){
		$this->method = $method;
	}
}