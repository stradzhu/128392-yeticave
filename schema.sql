-- Только для тестирования, чтобы каждый раз базу вручную не удалять
-- DROP DATABASE IF EXISTS yeticave_128392;

CREATE DATABASE yeticave_128392
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE yeticave_128392;

CREATE TABLE categories (
  id    INT UNSIGNED AUTO_INCREMENT,
  name  VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE users (
  id          INT UNSIGNED AUTO_INCREMENT,
  date_add    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  email       VARCHAR(255) NOT NULL,
  name        VARCHAR(255) NOT NULL,
  password    VARCHAR(255) NOT NULL,
  image_path  VARCHAR(255) NOT NULL,
  contact     VARCHAR(255) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE lots (
  id              INT UNSIGNED AUTO_INCREMENT,
  date_add        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  title           VARCHAR(255) NOT NULL,
  description     VARCHAR(1000) NOT NULL,
  image_path      VARCHAR(255) NOT NULL,
  price           INT UNSIGNED NOT NULL,
  date_end        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  bet_step        INT UNSIGNED NOT NULL,
  category_id     INT UNSIGNED NOT NULL,
  user_id_author  INT UNSIGNED NOT NULL,
  user_id_winner  INT UNSIGNED NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id_author) REFERENCES users (id),
  FOREIGN KEY (user_id_winner) REFERENCES users (id),
  FOREIGN KEY (category_id) REFERENCES categories (id)
);

CREATE TABLE bets (
  id        INT UNSIGNED AUTO_INCREMENT,
  date_add  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  price     INT UNSIGNED NOT NULL,
  user_id   INT UNSIGNED NOT NULL,
  lot_id    INT UNSIGNED NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users (id),
  FOREIGN KEY (lot_id) REFERENCES lots (id)
);
