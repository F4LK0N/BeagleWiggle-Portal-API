CREATE TABLE IF NOT EXISTS `tags` (
    `id`
        BIGINT
        NOT NULL
        AUTO_INCREMENT,

    `url`
        VARCHAR(128)
        NOT NULL,
    `name`
        VARCHAR(128)
        NOT NULL,

    `ts_state`
        TINYINT(1)
        NOT NULL
        DEFAULT '1',
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

    -- INDEX
    KEY `IK_TS_STATE` (`ts_state`),
    FULLTEXT `IK_URL` (`url`),

    -- CONSTRAIN
    UNIQUE KEY `UK_NAME` (`name`),
    UNIQUE KEY `UK_URL` (`url`)
)
ENGINE = InnoDB
DEFAULT CHARSET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci
