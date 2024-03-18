<?php

declare(strict_types=1);

namespace encritary\userQuests\model\impl;

use encritary\userQuests\db\Db;
use encritary\userQuests\model\Model;
use Generator;
use mysqli_stmt;
use function time;

class UserCompletedQuest extends Model{

	public static function countForUser(User $user) : int{
		$db = Db::get();

		$stmt = $db->prepare(<<<QUERY
SELECT COUNT(*)
FROM user_completed_quests
WHERE user_id = ?
QUERY
		);
		$stmt->bind_param('i', $user->id);
		$stmt->execute();

		$result = $stmt->get_result();
		return $result->fetch_row()[0];
	}

	public static function iterateForUser(User $user, int $count, int $offset) : Generator{
		$db = Db::get();

		$stmt = $db->prepare(<<<QUERY
SELECT ucq.quest_id, q.name AS quest_name, q.cost AS quest_cost,
       UNIX_TIMESTAMP(ucq.completed_at)
FROM user_completed_quests ucq
LEFT JOIN quests q ON q.id = ucq.quest_id
WHERE ucq.user_id = ? 
LIMIT ? OFFSET ?
QUERY);
		$stmt->bind_param('iii', $user->id, $count, $offset);
		$stmt->execute();

		$result = $stmt->get_result();
		while(($row = $result->fetch_row()) !== null){
			[$questId, $questName, $questCost, $completedAt] = $row;
			yield new self(
				$user,
				new Quest($questName, $questCost, $questId),
				$completedAt
			);
		}
	}

	public static function find(User $user, Quest $quest) : ?self{
		$db = Db::get();

		$stmt = $db->prepare(<<<QUERY
SELECT UNIX_TIMESTAMP(completed_at)
FROM user_completed_quests
WHERE user_id = ? AND quest_id = ? 
QUERY);
		$stmt->bind_param('ii', $user->id, $quest->id);
		$stmt->execute();

		$result = $stmt->get_result();
		if($result->num_rows === 0){
			return null;
		}

		$completedAt = (int) $result->fetch_row()[0];
		return new self($user, $quest, $completedAt);
	}

	public int $completedAt;

	public function __construct(
		public User $user,
		public Quest $quest,
		?int $completedAt = null
	){
		$this->completedAt = $completedAt ?? time();
	}

	protected function prepareInsert() : mysqli_stmt{
		$db = Db::get();
		$stmt = $db->prepare(<<<QUERY
INSERT INTO user_completed_quests
(user_id, quest_id, completed_at)
VALUES (?, ?, FROM_UNIXTIME(?))
QUERY);
		$stmt->bind_param('iii', $this->user->id, $this->quest->id, $this->completedAt);
		return $stmt;
	}
}