-- Только для тестирования, чтобы каждый раз базу вручную не удалять
-- DROP DATABASE IF EXISTS yeticave;

CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE categories (
  id    INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name  VARCHAR(255) NOT NULL
);

CREATE TABLE bets (
  id        INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  date_add  DATETIME DEFAULT CURRENT_TIMESTAMP,
  price     INT(11) UNSIGNED NOT NULL,
  user_id   INT(11) NOT NULL,
  lot_id    INT(11) NOT NULL
);

CREATE TABLE users (
  id          INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  date_add    DATETIME DEFAULT CURRENT_TIMESTAMP,
  email       VARCHAR(255) NOT NULL UNIQUE,
  name        VARCHAR(255) NOT NULL,
  password    VARCHAR(255) NOT NULL,
  image_path  VARCHAR(255),
  contact     VARCHAR(255)
);

CREATE TABLE lots (
  id              INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  date_add        DATETIME DEFAULT CURRENT_TIMESTAMP,
  title           VARCHAR(255) NOT NULL,
  description     VARCHAR(1000),
  image_path      VARCHAR(255),
  price           INT(11) NOT NULL,
  date_end        DATETIME DEFAULT CURRENT_TIMESTAMP,
  bet_step        INT(11) NOT NULL,
  category_id     INT(11) NOT NULL,
  user_id_author  INT(11) NOT NULL,
  user_id_winner  INT(11) NOT NULL
);
