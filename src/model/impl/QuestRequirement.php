<?php

declare(strict_types=1);

namespace encritary\userQuests\model\impl;

use encritary\userQuests\db\Db;
use encritary\userQuests\model\Model;
use PDO;
use PDOStatement;

class QuestRequirement extends Model{

	/**
	 * @return QuestRequirement[]
	 */
	public static function getForQuest(Quest $quest) : array{
		$db = Db::get();

		$stmt = $db->prepare(<<<QUERY
SELECT qr.required_quest_id, q.name AS required_quest_name, q.cost AS required_quest_cost
FROM quest_requirements qr
LEFT JOIN quests q ON q.id = qr.required_quest_id
WHERE qr.quest_id = ? 
QUERY);
		$stmt->bindValue(1, $quest->id);
		$stmt->execute();

		$result = [];
		while(($row = $stmt->fetch()) !== false){
			[$requiredQuestId, $requiredQuestName, $requiredQuestCost] = $row;
			$requiredQuest = new Quest($requiredQuestName, $requiredQuestCost, $requiredQuestId);
			$result[] = new self($quest, $requiredQuest);
		}
		return $result;
	}

	public function __construct(
		public Quest $quest,
		public Quest $requiredQuest
	){}

	protected function prepareInsert(PDO $db) : PDOStatement{
		$stmt = $db->prepare(<<<QUERY
INSERT INTO quest_requirements
(quest_id, required_quest_id)
VALUES (?, ?)
QUERY);
		$stmt->bindValue(1, $this->quest->id);
		$stmt->bindValue(2, $this->requiredQuest->id);
		return $stmt;
	}
}