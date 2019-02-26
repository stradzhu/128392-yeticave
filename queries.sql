USE yeticave_128392;

-- Запрос на добавление списка категорий
INSERT INTO categories (`name`, `icon`) VALUES
('Доски и лыжи', 'boards'),
('Крепления', 'attachment'),
('Ботинки', 'boots'),
('Одежда', 'clothing'),
('Инструменты', 'tools'),
('Разное', 'other');

-- Добавление пользователей
INSERT INTO users (`email`, `name`, `password`, `image_path`, `contact`) VALUES
('dima@html.com', 'Дмитрий', '123456', '/img/avatar.jpg', '+38 (000) 1234 567'),
('aleks@php.com', 'Алексей', 'aleks_st', '/img/avatar.jpg', '+7 (493) 1234 5678');

-- Добавление списка объявлений (лотов)
INSERT INTO lots (`date_add`, `title`, `description`, `image_path`, `price`, `date_end`, `bet_step`, `category_id`, `user_id_author`, `user_id_winner`) VALUES
('2019-02-01 12:00:00', '2014 Rossignol District Snowboard', 'Отличная доска! Использую как забор', '/img/lot-1.jpg', 10999, '2019-02-15 12:00:00', 100, 1, 1, 2),
('2019-02-05 13:00:00', 'DC Ply Mens 2016/2017 Snowboard', 'Состояние среднее, есть трещины и вмятины от зубов', '/img/lot-2.jpg', 159999, '2019-02-20 13:00:00', 150, 1, 2, 1),
('2019-02-10 14:00:00', 'Крепления Union Contact Pro 2015 года размер L/XL', 'Идельное крепление для любых вещей', '/img/lot-3.jpg', 8000, '2019-02-25 14:00:00', 100, 2, 1, NULL),
('2019-02-15 15:00:00', 'Ботинки для сноуборда DC Mutiny Charocal', 'В таких ботинках даже летом не стыдно ходить', '/img/lot-4.jpg', 10999, '2019-03-10 15:00:00', 100, 3, 1, NULL),
('2019-02-20 16:00:00', 'Куртка для сноуборда DC Mutiny Charocal', 'Размер XXXXXL. На больших сноубордистов', '/img/lot-5.jpg', 7500, '2019-03-15 16:00:00', 50, 4, 2, NULL),
('2019-02-25 17:00:00', 'Маска Oakley Canopy', 'Продаю за ненадобностью. Когда покупал, думал, что сварочная маска', '/img/lot-6.jpg', 5400, '2019-03-20 17:00:00', 50, 6, 2, NULL);

-- Добавление ставок к объявлению (лоту)
INSERT INTO bets (`date_add`, `price`, `user_id`, `lot_id`) VALUES
('2019-02-02 17:00:00', 11099, 1, 1),
('2019-02-03 17:00:00', 11199, 2, 1),
('2019-02-04 17:00:00', 11299, 1, 1),
('2019-02-05 17:00:00', 11399, 2, 1),
('2019-02-05 17:00:00', 160149, 1, 2),
('2019-02-05 17:00:00', 8100, 1, 3),
('2019-02-06 17:00:00', 11500, 1, 4),
('2019-02-07 17:00:00', 11800, 2, 4),
('2019-02-08 17:00:00', 7700, 2, 5),
('2019-02-09 17:00:00', 8000, 1, 5),
('2019-02-10 17:00:00', 8500, 2, 5);

-- Получить все категории
SELECT name FROM categories;

-- Получить самые новые, открытые лоты. Каждый лот должен включать
-- название, стартовую цену, ссылку на изображение, цену, название категории
-- Промежуточный результат, id для удобства выведен http://joxi.ru/1A5VjRVUn9PE8r
-- Готовый результат http://joxi.ru/EA4VEaVUwbRQ5m
SELECT l.title, l.price AS start_price, l.image_path,IFNULL(MAX(b.price),l.price) AS now_price, c.name AS category, l.date_add
FROM lots l
LEFT JOIN bets b ON l.id = b.lot_id
LEFT JOIN categories c ON l.category_id = c.id
WHERE (l.date_end > NOW()) -- AND (l.user_id_winner IS NULL)
GROUP BY l.id
ORDER BY l.date_add DESC;

-- Показать лот по его id. Получите также название категории, к которой принадлежит лот
SELECT c.name, l.title, l.description
FROM lots l
JOIN categories c ON l.category_id = c.id
WHERE l.id = 1;

-- Обновить название лота по его идентификатору
UPDATE lots l
SET title = 'Новое название лота'
WHERE l.id = 1;

-- Получить список самых свежих ставок для лота по его идентификатору
SELECT b.date_add, b.price
FROM bets b
WHERE b.lot_id = 1
ORDER BY b.date_add DESC;
