<?php

declare(strict_types=1);

namespace encritary\userQuests\model\impl;

use encritary\userQuests\db\Db;
use encritary\userQuests\model\Model;
use Generator;
use PDO;
use PDOStatement;
use function time;

class UserCompletedQuest extends Model{

	public static function countForUser(User $user) : int{
		$db = Db::get();

		$stmt = $db->prepare(<<<QUERY
SELECT COUNT(*)
FROM user_completed_quests
WHERE user_id = ?
QUERY);
		$stmt->execute([$user->id]);

		return $stmt->fetch()[0];
	}

	public static function iterateForUser(User $user, int $count, int $offset) : Generator{
		$db = Db::get();

		$stmt = $db->prepare(<<<QUERY
SELECT ucq.quest_id, q.name AS quest_name, q.cost AS quest_cost,
       ucq.completed_at
FROM user_completed_quests ucq
LEFT JOIN quests q ON q.id = ucq.quest_id
WHERE ucq.user_id = ? 
ORDER BY ucq.completed_at DESC
LIMIT ? OFFSET ?
QUERY);
		$stmt->bindValue(1, $user->id);
		$stmt->bindValue(2, $count, PDO::PARAM_INT);
		$stmt->bindValue(3, $offset, PDO::PARAM_INT);
		$stmt->execute();

		while(($row = $stmt->fetch()) !== false){
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
SELECT completed_at
FROM user_completed_quests
WHERE user_id = ? AND quest_id = ? 
QUERY);
		$stmt->execute([$user->id, $quest->id]);

		$row = $stmt->fetch();
		if($row === false){
			return null;
		}

		$completedAt = (int) $row[0];
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

	protected function prepareInsert(PDO $db) : PDOStatement{
		$stmt = $db->prepare(<<<QUERY
INSERT INTO user_completed_quests
(user_id, quest_id, completed_at)
VALUES (?, ?, ?)
QUERY);
		$stmt->bindValue(1, $this->user->id);
		$stmt->bindValue(2, $this->quest->id);
		$stmt->bindValue(3, $this->completedAt);
		return $stmt;
	}
}