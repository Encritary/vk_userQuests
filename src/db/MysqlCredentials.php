<?php

declare(strict_types=1);

namespace encritary\userQuests\db;

namespace encritary\userQuests\db;

final class MysqlCredentials{

	public function __construct(
		public readonly string $host,
		public readonly string $user,
		public readonly string $password,
		public readonly string $db,
		public readonly int $port = 3306
	){}
}