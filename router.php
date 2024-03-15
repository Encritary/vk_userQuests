<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

use encritary\userQuests\App;
use encritary\userQuests\request\Request;

$app = new App();
$response = $app->handleRequest(Request::fromGlobals());
$response->echo();