<?php

declare(strict_types=1);

namespace encritary\userQuests\view;

interface View{

	/**
	 * @return string[]
	 */
	public function getHeaders() : array;

	public function encode() : string;
}