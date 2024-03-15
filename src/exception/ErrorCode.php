<?php

declare(strict_types=1);

namespace encritary\userQuests\exception;

enum ErrorCode: int{

	case MethodNotSpecified = 1;
	case ControllerNotFound = 2;
	case MethodNotFound = 3;
	case MissingParameter = 4;
	case BadParameterFormat = 5;

	case ConfigError = 0xf0;
	case SqlError = 0xf1;
}