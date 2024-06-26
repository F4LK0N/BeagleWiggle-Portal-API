CREATE TABLE IF NOT EXISTS `users` (
    `id`
        BIGINT
        NOT NULL
        AUTO_INCREMENT,

    `ts_state`
        TINYINT(1)
        NOT NULL
        DEFAULT '1',

    `access_type`
        ENUM(
            'DEV',
            'MASTER',
            'ADMIN',
            'MANAGER',
            'EDITOR',
            'READER'
        )
        NOT NULL
        DEFAULT 'READER',

    `email`
        VARCHAR(100)
        NOT NULL,
    
    `pass`
        TEXT
        NOT NULL,

    `name`
        VARCHAR(255)
        NOT NULL,

    `surname`
        VARCHAR(255)
        NOT NULL
        DEFAULT '',

    `ts_created`
        TIMESTAMP
        NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    `ts_modified`
        TIMESTAMP
        NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    `ts_deleted`
        TIMESTAMP
        NULL,

    PRIMARY KEY (`id`),

    KEY `IK_TS_STATE` (`ts_state`),

    UNIQUE KEY `UK_EMAIL` (`email`)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
