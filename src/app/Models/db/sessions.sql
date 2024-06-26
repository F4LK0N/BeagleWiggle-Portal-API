CREATE TABLE IF NOT EXISTS `sessions` (
    `id`
        BIGINT
        NOT NULL
        AUTO_INCREMENT,

    `id_user`
        BIGINT
        NOT NULL,
    
    `ts_state`
        TINYINT(1)
        NOT NULL
        DEFAULT '1',
    
    `token`
        VARCHAR(255)
        NOT NULL,

    `expires`
        TIMESTAMP
        NOT NULL,

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

    KEY `IK_ID_USER` (`id_user`),

    KEY `IK_TS_STATE` (`ts_state`),

    UNIQUE KEY `UK_NAME` (`token`)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
