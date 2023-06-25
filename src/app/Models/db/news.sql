CREATE TABLE IF NOT EXISTS `news` (
    `id`
        BIGINT
        NOT NULL
        AUTO_INCREMENT,
    `id_category`
        BIGINT
        NOT NULL,


    `published`
        TINYINT(1)
        NOT NULL
        DEFAULT '0',
    `step`
        TINYINT UNSIGNED
        NOT NULL
        DEFAULT '0',
    `version`
        INT UNSIGNED
        NOT NULL
        DEFAULT '1',


    `url`
        VARCHAR(128)
        NOT NULL,


    `cover_title`
        VARCHAR(128)
        NOT NULL,
    `cover_description`
        VARCHAR(255)
        NOT NULL
        DEFAULT '',


    `title`
        VARCHAR(255)
        NOT NULL,
    `description`
        VARCHAR(512)
        NOT NULL
        DEFAULT '',
    `content`
        TEXT
        NOT NULL,


    `source_name`
        VARCHAR(64)
        NOT NULL
        DEFAULT '',
    `source_url`
        VARCHAR(512)
        NOT NULL
        DEFAULT '',


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
    `ts_published`
        TIMESTAMP
        NULL,
    `ts_deleted`
        TIMESTAMP
        NULL,

    PRIMARY KEY (`id`),

    -- INDEX
    KEY `IK_TS_STATE` (`ts_state`),
    KEY `IK_ID_CATEGORY` (`id_category`),
    KEY `IK_PUBLISHED` (`published`),
    KEY `IK_STEP` (`step`),
    FULLTEXT `IK_URL` (`url`),

    -- CONSTRAIN
    UNIQUE KEY `UK_TITLE` (`title`),
    UNIQUE KEY `UK_COVER_TITLE` (`cover_title`),
    UNIQUE KEY `UK_URL` (`url`)
)
ENGINE = InnoDB
DEFAULT CHARSET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci
