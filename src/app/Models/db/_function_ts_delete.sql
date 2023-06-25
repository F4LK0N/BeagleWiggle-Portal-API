DROP FUNCTION IF EXISTS TS_DELETE;

CREATE FUNCTION IF NOT EXISTS TS_DELETE (table_name VARCHAR(255), table_id BIGINT)    
RETURNS TIMESTAMP
NOT DETERMINISTIC
MODIFIES SQL DATA
BEGIN
    DECLARE ts_deleted TIMESTAMP;
    SELECT CURRENT_TIMESTAMP INTO ts_deleted;
    
    IF table_name = 'tags' THEN
        UPDATE `tags`   SET `ts_state`=0, `ts_deleted` = ts_deleted   WHERE `id` = table_id;
    END IF;

    IF table_name = 'categories' THEN
        UPDATE `categories`   SET `ts_state`=0, `ts_deleted` = ts_deleted   WHERE `id` = table_id;
    END IF;

    IF table_name = 'news' THEN
        UPDATE `news`   SET `ts_state`=0, `ts_deleted` = ts_deleted   WHERE `id` = table_id;
    END IF;

    IF table_name = 'sessions' THEN
        UPDATE `sessions`   SET `ts_state`=0, `ts_deleted` = ts_deleted   WHERE `id` = table_id;
    END IF;

    IF table_name = 'users' THEN
        UPDATE `users`   SET `ts_state`=0, `ts_deleted` = ts_deleted   WHERE `id` = table_id;
    END IF;

    RETURN ts_deleted;
END;
