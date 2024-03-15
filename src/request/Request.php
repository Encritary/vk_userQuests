<?php

declare(strict_types=1);

namespace encritary\userQuests\request;

class Request{

	public static function fromGlobals() : self{
		return new self($_SERVER['REQUEST_URI'], $_REQUEST);
	}

	public function __construct(
		public readonly string $uri,
		public readonly array $args
	){}
}