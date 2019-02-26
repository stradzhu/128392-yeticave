<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once('init.php');
require_once('functions.php');


$sql = 'SELECT name, image_path FROM users WHERE id = 1';
$result = mysqli_query($connect, $sql);
$user = $result ? mysqli_fetch_assoc($result) : [];


$sql = 'SELECT name, icon FROM categories';
$result = mysqli_query($connect, $sql);
$categories = $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];


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
