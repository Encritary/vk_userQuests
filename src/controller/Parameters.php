<?php

declare(strict_types=1);

namespace encritary\userQuests\controller;

use encritary\userQuests\controller\exception\BadParameterFormatException;
use encritary\userQuests\controller\exception\MissingParameterException;
use function ctype_digit;

final class Parameters{

	/**
	 * @throws MissingParameterException
	 */
	public static function string(string $parameter, array $args, ?string $default = null) : string{
		if(!isset($args[$parameter])){
			if($default !== null){
				return $default;
			}
			throw new MissingParameterException($parameter);
		}
		return $args[$parameter];
	}

	/**
	 * @throws MissingParameterException
	 * @throws BadParameterFormatException
	 */
	public static function uint(string $parameter, array $args, ?int $default = null) : int{
		if(!isset($args[$parameter]) && $default !== null){
			return $default;
		}

		$str = self::string($parameter, $args);
		if(!ctype_digit($str)){
			throw new BadParameterFormatException("Parameter $parameter expected to be unsigned int");
		}
		return (int) $str;
	}

	private function __construct(){}
}