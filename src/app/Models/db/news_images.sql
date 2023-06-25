CREATE TABLE IF NOT EXISTS `news_images` (
    `id`
        BIGINT
        NOT NULL
        AUTO_INCREMENT,
    `id_news`
        BIGINT
        NOT NULL,

    `cover`
        TINYINT UNSIGNED
        NOT NULL
        DEFAULT '0',
    `index`
        INT UNSIGNED
        NOT NULL,
        DEFAULT '0',

    `url`
        VARCHAR(128)
        NOT NULL,
    `description`
        VARCHAR(256)
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
    KEY `IK_ID_NEWS` (`id_news`),
    KEY `IK_COVER` (`cover`)
)
ENGINE = InnoDB
DEFAULT CHARSET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci
