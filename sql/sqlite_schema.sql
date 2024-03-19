CREATE TABLE users (
    id integer primary key not null,
    name varchar(32) not null,
    balance int unsigned not null default 0
);

CREATE TABLE quests (
    id integer primary key not null,
    name varchar(128) not null,
    cost int unsigned not null
);

CREATE TABLE user_completed_quests (
    user_id integer unsigned not null,
    quest_id integer unsigned not null,
    completed_at integer not null,
    UNIQUE (user_id, quest_id),
    FOREIGN KEY (user_id) REFERENCES users (id),
    FOREIGN KEY (quest_id) REFERENCES quests (id)
);