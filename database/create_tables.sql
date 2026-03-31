-- ============================================================
-- Скрипт создания базы данных и таблиц для проекта Shorturl
-- Сгенерировано на основе запросов, используемых в коде
-- ============================================================

-- Создание базы данных (при необходимости раскомментировать)
-- CREATE DATABASE IF NOT EXISTS shorturl_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE shorturl_db;

-- ------------------------------------------------------------
-- Таблица пользователей (user)
-- ------------------------------------------------------------
-- Используется в: data/UserHelper.php
--
-- Запросы из кода:
--   SELECT id FROM user WHERE email LIKE '...'
--   SELECT id FROM user WHERE email LIKE '...' AND pass LIKE '...'
--   SELECT id FROM user WHERE email = '...'
--   SELECT ticket FROM user WHERE id = '...'
--   INSERT INTO user (name, email, pass, reg_date) VALUES (...)
--   UPDATE user SET ticket = '...' WHERE id = '...'
--   UPDATE user SET pass = '...' WHERE email = '...'
-- ------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `user` (
    `id`       INT UNSIGNED    NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор пользователя',
    `name`     VARCHAR(255)    NOT NULL                COMMENT 'Имя пользователя',
    `email`    VARCHAR(255)    NOT NULL                COMMENT 'Email-адрес пользователя',
    `pass`     VARCHAR(128)    NOT NULL                COMMENT 'Хеш пароля (SHA512/256)',
    `reg_date` INT UNSIGNED    NOT NULL DEFAULT 0      COMMENT 'Дата регистрации (UNIX timestamp)',
    `ticket`   VARCHAR(128)    DEFAULT NULL             COMMENT 'Тикет сессии (SHA512/256)',

    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_user_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Таблица пользователей';


-- ------------------------------------------------------------
-- Таблица сокращённых ссылок (shorturl)
-- ------------------------------------------------------------
-- Используется в: data/ShorturlHelper.php
--
-- Запросы из кода:
--   SELECT id, url, status, disabled_date FROM shorturl WHERE code LIKE '...'
--   SELECT url, code, disabled_date, status FROM shorturl WHERE id = '...'
--   SELECT id, url, code, disabled_date FROM shorturl WHERE user_id = '...' AND status = '1'
--   SELECT id FROM shorturl WHERE code LIKE '...'
--   INSERT INTO shorturl (url, code, disabled_date, status, user_id) VALUES (...)
--   UPDATE shorturl SET code = '...', disabled_date = '...' WHERE id = '...'
--   UPDATE shorturl SET status = '0' WHERE id = '...'
-- ------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `shorturl` (
    `id`            INT UNSIGNED    NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор ссылки',
    `url`           TEXT            NOT NULL                COMMENT 'Оригинальный URL',
    `code`          VARCHAR(16)     NOT NULL                COMMENT 'Код сокращённой ссылки (от 3 до 16 символов)',
    `disabled_date` INT UNSIGNED    NOT NULL DEFAULT 0      COMMENT 'Дата деактивации (UNIX timestamp, 0 — бессрочно)',
    `status`        TINYINT(1)      NOT NULL DEFAULT 1      COMMENT 'Статус ссылки (1 — активна, 0 — удалена)',
    `user_id`       INT UNSIGNED    NOT NULL                COMMENT 'Идентификатор владельца (user.id)',

    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_shorturl_code` (`code`),
    KEY `idx_shorturl_user_id` (`user_id`),
    KEY `idx_shorturl_status` (`status`),

    CONSTRAINT `fk_shorturl_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Таблица сокращённых ссылок';
