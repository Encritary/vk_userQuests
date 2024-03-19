<?php

declare(strict_types=1);

namespace encritary\userQuests\exception;

enum ErrorCode: int{

	case MethodNotSpecified = 1;
	case ControllerNotFound = 2;
	case MethodNotFound = 3;
	case MissingParameter = 4;
	case BadParameterFormat = 5;
	case ModelNotFound = 6;

	case QuestAlreadyCompleted = 7;
	case QuestRequirementsNotFulfilled = 8;

	case InternalError = 0xff;
}