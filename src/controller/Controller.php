<?php

declare(strict_types=1);

namespace encritary\userQuests\controller;

use encritary\userQuests\request\Request;
use encritary\userQuests\response\Response;

interface Controller{

	public function execute(string $name, Request $request) : Response;

	public function getName() : string;
}