<?php

require_once('init.php');

$id = intval($_GET['id'] ?? 0);
$sql = 'SELECT l.title, l.description, l.image_path, IFNULL(MAX(b.price), l.price) AS price, l.date_end, l.bet_step, c.name AS category '
    . 'FROM lots l '
    . 'LEFT JOIN bets b ON l.id = b.lot_id '
    . 'LEFT JOIN categories c ON l.category_id = c.id '
    . 'WHERE (l.date_end > NOW()) AND (l.user_id_winner IS NULL) AND l.id = ' . $id . ' '
    . 'GROUP BY l.id';
$result = mysqli_query($connect, $sql);
$lot = $result ? mysqli_fetch_assoc($result) : [];

$categories_template = include_template('categories.php', [
    'categories' => $categories
]);

if ($lot) {
    // Нужно добавить "минимальная ставка", сразу посчитать это SQL запросе я не смог
    $lot['bet_min'] = $lot['price'] + $lot['bet_step'];
    $content = include_template('lot.php', [
        'categories_template' => $categories_template,
        'lot' => $lot
    ]);
} else {
    http_response_code(404);
    $content = include_template('404.php', [
        'categories_template' => $categories_template
    ]);
}

$page = include_template('layout.php', [
    'title' => $lot['title'],
    'user' => $user,
    'content' => $content,
    'categories' => $categories
]);

print $page;
