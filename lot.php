<?php

require_once('init.php');

$errors = [];
$form = [];

$id = intval($_GET['id'] ?? 0);
$sql = 'SELECT l.id, l.title, l.description, l.image_path, IFNULL(MAX(b.price), l.price) AS price, l.date_end, l.bet_step, c.name AS category, l.user_id_author, l.user_id_winner '
    . 'FROM lots l '
    . 'LEFT JOIN bets b ON l.id = b.lot_id '
    . 'LEFT JOIN categories c ON l.category_id = c.id '
    . 'WHERE l.id = ' . $id . ' '
    . 'GROUP BY l.id';
$result = mysqli_query($connect, $sql);
$lot = $result ? mysqli_fetch_assoc($result) : [];

if (!$lot) {
    $error_title = '404 Страница не найдена';
    $error_text = 'Данной страницы не существует на сайте';
    get_page_error(404, $error_title, $error_text, $categories, $user);
}

// Нужно добавить "минимальная ставка", т.к. сразу посчитать это в SQL запросе я не смог
$lot['bet_min'] = $lot['price'] + $lot['bet_step'];

// Получим информацию о ставках
$sql = 'SELECT b.date_add, b.price, b.user_id, u.name '
    . 'FROM bets b '
    . 'LEFT JOIN users u ON b.user_id = u.id '
    . 'WHERE b.lot_id = ' . $id . ' '
    . 'ORDER BY b.date_add DESC';
$result = mysqli_query($connect, $sql);
$bets = $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!count($user)) {
        $error_title = '403 Доступ запрещен';
        $error_text = 'Добавление ставки доступно только для зарегистрированных пользователей';
        get_page_error(403, $error_title, $error_text, $categories, $user);
    }

    if ($user['id'] === $lot['user_id_author']) {
        $error_title = '403 Доступ запрещен';
        $error_text = 'Вы не можете добавить ставку, т.к. вы автор этого лота';
        get_page_error(403, $error_title, $error_text, $categories, $user);
    }

    if (isset($bets[0]) && ($bets[0]['user_id'] === $user['id'])) {
        $error_title = '403 Доступ запрещен';
        $error_text = 'Вы не можете добавить ставку, т.к. ваша ставка является последней';
        get_page_error(403, $error_title, $error_text, $categories, $user);
    }

    if ((time() - strtotime($lot['date_end']) > 0) || $lot['user_id_winner']) {
        $error_title = '403 Доступ запрещен';
        $error_text = 'Вы не можете добавить ставку, т.к. лот уже закрыт';
        get_page_error(403, $error_title, $error_text, $categories, $user);
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
        if ($form['cost'] >= $lot['bet_min']) {
            $sql = "INSERT INTO bets (date_add, price, user_id, lot_id) VALUES "
                . "(NOW(), {$form['cost']}, {$user['id']}, {$lot['id']})";
            $result = mysqli_query($connect, $sql);

            if ($result) {
                $lot['price'] = $form['cost'];
                $lot['bet_min'] = $lot['price'] + $lot['bet_step'];

                array_unshift($bets, [
                    'date_add' => date('Y-m-d H:i:s'),
                    'price' => $lot['price'],
                    'user_id' => $user['id'],
                    'name' => $user['name']
                ]);
            } else {
                $error_title = '500 Ошибка сервера';
                $error_text = mysqli_error($connect);
                get_page_error(500, $error_title, $error_text, $categories, $user);
            }
        } else {
            $errors['cost'] = "Минимальная ставка - {$lot['bet_min']}";
        }
    }
}


$categories_template = include_template('categories.php', [
    'categories' => $categories
]);

$content = include_template('lot.php', [
    'categories_template' => $categories_template,
    'lot' => $lot,
    'bets' => $bets,
    'user' => $user,
    'errors' => $errors,
    'form' => $form
]);

$page = include_template('layout.php', [
    'title' => $lot['title'],
    'user' => $user,
    'content' => $content,
    'categories' => $categories
]);

print $page;
