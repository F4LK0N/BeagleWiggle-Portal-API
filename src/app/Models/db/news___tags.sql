CREATE TABLE IF NOT EXISTS `news___tags` (
    `id`
        BIGINT
        NOT NULL
        AUTO_INCREMENT,

    `id_news`
        BIGINT
        NOT NULL,
    `id_tag`
        BIGINT
        NOT NULL,

    PRIMARY KEY (`id`),

    -- INDEX
    KEY `IK_ID_NEWS` (`id_news`)
)
ENGINE = InnoDB
DEFAULT CHARSET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci
