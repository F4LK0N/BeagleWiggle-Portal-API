CREATE TABLE IF NOT EXISTS `auth` (
    `id`
        BIGINT
        NOT NULL
        AUTO_INCREMENT,
    
    `email`
        VARCHAR(64)
        NOT NULL,
    
    `pass`
        VARCHAR(64)
        NOT NULL,

    PRIMARY KEY (`id`),

    UNIQUE KEY `UK_EMAIL` (`email`)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
