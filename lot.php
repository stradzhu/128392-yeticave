<?php

require_once('init.php');

$errors = [];
$form = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!count($user)) {
        $error_title = '403 Доступ запрещен';
        $error_text = 'Добавление ставки доступно только для зарегистрированных пользователей';
        get_page_error(403, $error_title, $error_text, $categories, $user);
    }

    $form['id'] = intval($_POST['id'] ?? 0);

    if (!$form['id']) {
        $error_title = '500 Ошибка сервера';
        $error_text = 'Попробуйте добавить ставку позднее';
        get_page_error(500, $error_title, $error_text, $categories, $user);
    }

    if (isset($_POST['cost'])) {
        // Нужно помочь пользователю и убрать пробелы, если он их ввел, например, 12 567
        $_POST['cost'] = str_replace(' ', '', $_POST['cost']);

        if (intval($_POST['cost'])) {
            $form['cost'] = intval($_POST['cost']);
        } else {
            $form['cost'] = NULL;
            $errors['cost'] = 'Это поле необходимо заполнить';
        }
    }

    if (!count($errors)) {

        $sql = 'SELECT IFNULL(MAX(b.price), l.price) AS price, l.bet_step '
            . 'FROM lots l '
            . 'LEFT JOIN bets b ON l.id = b.lot_id '
            . 'WHERE (l.date_end > NOW()) AND (l.user_id_winner IS NULL) AND l.id = ' . $form['id'] . ' '
            . 'GROUP BY l.id';
        $result = mysqli_query($connect, $sql);

        if ($result) {
            $lot = mysqli_fetch_assoc($result);
            $lot['bet_min'] = $lot['price'] + $lot['bet_step'];

            if ($form['cost'] >= $lot['bet_min']) {
                $sql = "INSERT INTO bets (date_add, price, user_id, lot_id) VALUES "
                    . "(NOW(), {$form['cost']}, {$user['id']}, {$form['id']})";
                $result = mysqli_query($connect, $sql);

                if ($result) {
                    header('Location: /lot.php?id=' . $form['id']);
                    exit;
                } else {
                    $error_title = '500 Ошибка сервера';
                    $error_text = mysqli_error($connect);
                    get_page_error(500, $error_title, $error_text, $categories, $user);
                }
            } else {
                $errors['cost'] = "Минимальная ставка - {$lot['bet_min']}";
            }

        } else {
            $error_title = '500 Ошибка сервера';
            $error_text = mysqli_error($connect);
            get_page_error(500, $error_title, $error_text, $categories, $user);
        }
    }
}

$id = intval($_GET['id'] ?? 0);
$sql = 'SELECT l.id, l.title, l.description, l.image_path, IFNULL(MAX(b.price), l.price) AS price, l.date_end, l.bet_step, c.name AS category, l.user_id_author '
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
        'lot' => $lot,
        'user' => $user,
        'errors' => $errors,
        'form' => $form
    ]);
} else {
    $error_title = '404 Страница не найдена';
    $error_text = 'Данной страницы не существует на сайте';
    get_page_error(404, $error_title, $error_text, $categories, $user);
}

$page = include_template('layout.php', [
    'title' => $lot['title'],
    'user' => $user,
    'content' => $content,
    'categories' => $categories
]);

print $page;
