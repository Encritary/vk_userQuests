<?php

declare(strict_types=1);

namespace encritary\userQuests\controller;

use encritary\userQuests\controller\exception\BadParameterFormatException;
use encritary\userQuests\controller\exception\MissingParameterException;
use function ctype_digit;
use function explode;
use function strlen;

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
		return (string) $args[$parameter];
	}

	/**
	 * @throws MissingParameterException
	 */
	public static function stringWithSize(string $parameter, array $args, int $maxLength, int $minLength = 1, ?string $default = null) : string{
		$str = self::string($parameter, $args, $default);
		if(strlen($str) < $minLength || strlen($str) > $maxLength){
			throw new BadParameterFormatException("Parameter $parameter expected to be string from $minLength to $maxLength characters long");
		}
		return $str;
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

	/**
	 * @throws MissingParameterException
	 * @throws BadParameterFormatException
	 */
	public static function uint32(string $parameter, array $args, ?int $default = null) : int{
		$uint = self::uint($parameter, $args, $default);
		if($uint > 0xffffffff){
			throw new BadParameterFormatException("Parameter $parameter is too large for 32-bit unsigned int");
		}
		return $uint;
	}

	/**
	 * @throws MissingParameterException
	 * @throws BadParameterFormatException
	 */
	public static function uint32List(string $parameter, array $args, ?array $default = null) : array{
		if(!isset($args[$parameter]) && $default !== null){
			return $default;
		}

		$str = self::string($parameter, $args);
		$result = [];
		foreach(explode(",", $str) as $strItem){
			if(!ctype_digit($strItem)){
				throw new BadParameterFormatException("Parameter $parameter expected to be list of unsigned 32-bit integers, got item $strItem");
			}

			$item = (int) $strItem;
			if($item > 0xffffffff){
				throw new BadParameterFormatException("Parameter $parameter has an item too big for 32-bit unsigned integer: $item");
			}
			$result[] = $item;
		}
		return $result;
	}

	private function __construct(){}
}