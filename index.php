<?php

require_once('init.php');

$sql = 'SELECT l.id, l.title, IFNULL(MAX(b.price), l.price) AS price, l.image_path, l.date_add, l.date_end, c.name AS category '
    . 'FROM lots l '
    . 'LEFT JOIN bets b ON l.id = b.lot_id '
    . 'LEFT JOIN categories c ON l.category_id = c.id '
    . 'WHERE (l.date_end > NOW()) AND (l.user_id_winner IS NULL) '
    . 'GROUP BY l.id '
    . 'ORDER BY l.date_add DESC '
    . 'LIMIT 9';
$result = mysqli_query($connect, $sql);
$lots = $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];

$content = include_template('index.php', [
    'categories' => $categories,
    'lots' => $lots
]);

$page = include_template('layout.php', [
    'title' => 'Главная',
    'user' => $user,
    'content' => $content,
    'categories' => $categories
]);

print $page;
