CREATE TABLE users (
    id int unsigned not null auto_increment primary key,
    name varchar(32) not null,
    balance int unsigned not null default 0
);

CREATE TABLE quests (
    id int unsigned not null auto_increment primary key,
    name varchar(128) not null,
    cost int unsigned not null
);

CREATE TABLE user_completed_quests (
    user_id int unsigned not null,
    quest_id int unsigned not null,
    completed_at timestamp not null default now(),
    UNIQUE KEY (user_id, quest_id),
    FOREIGN KEY (user_id) REFERENCES users (id),
    FOREIGN KEY (quest_id) REFERENCES quests (id)
);