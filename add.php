<?php

require_once('init.php');

$errors = [];
$form = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Валидация имени
    $form['name'] = mysqli_real_escape_string($connect, $_POST['name'] ?? NULL);
    $form['name'] = $form['name'] ? mb_substr($form['name'], 0, 255) : NULL;

    // Валидация категории. Проверим, существует ли такая по id
    $sql = 'SELECT id FROM categories WHERE id = ' . intval($_POST['category'] ?? 0);
    $result = mysqli_query($connect, $sql);
    $form['category'] = $result ? mysqli_fetch_assoc($result)['id'] : NULL;

    // Валидация сообщения
    $form['message'] = mysqli_real_escape_string($connect, $_POST['message'] ?? NULL);
    $form['message'] = mb_substr($form['message'], 0, 1000);

    // Валидация изображения
    $form['image'] = NULL;
    if (isset($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $file_type = mime_content_type($tmp_name);

        if ($file_type === 'image/jpeg')
            $file_extension = 'jpg';
        else if ($file_type === 'image/png') {
            $file_extension = 'png';
        }

        $form['image'] = in_array($file_type, ['image/jpeg', 'image/png']) ? $tmp_name : NULL;
    }

    // Валидация rate. Должен быть больше 0 и меньше 1 000 000 (максимальное сам придумал)
    $form['rate'] = intval($_POST['rate'] ?? 0);
    $form['rate'] = ($form['rate'] > 0 && $form['rate'] < 1000000) ? $form['rate'] : NULL;

    // Валидация step. Должен быть больше 0 и меньше 1 000 (максимальное сам придумал)
    $form['step'] = intval($_POST['step'] ?? 0);
    $form['step'] = ($form['step'] > 0 && $form['step'] < 1000) ? $form['step'] : NULL;

    // Валидация даты окончания лота. Минимум 1 день, максимум 14-ть дней (сам придумал)
    $form['date'] = mysqli_real_escape_string($connect, $_POST['date'] ?? NULL);
    if (check_date_format($form['date'])) {
        $date_start = date('Y-m-d', strtotime('+1 day'));
        $date_end = date('Y-m-d', strtotime('+14 day'));
        if (($form['date'] < $date_start) || ($form['date'] > $date_end)) $form['date'] = NULL;
    } else {
        $form['date'] = NULL;
    }

    // Проверим, есть ли ошибки после валидации
    foreach ($form as $key => $value) {
        $value ? '' : $errors[$key] = true;
    }

    // Если все хорошо, копируем файл и добавляем запись в базу
    if (!count($errors)) {
        $form['date'] .= ' 23:59:59'; // я считаю, что аукцион нужно завершить, когда закончится день

        $tmp_name = $form['image'];
        $form['image'] = 'uploads/' . uniqid() . '.' . $file_extension;
        move_uploaded_file($tmp_name, $form['image']);

        $sql = "INSERT INTO lots (date_add, title, description, image_path, price, date_end, bet_step, category_id, user_id_author) VALUES "
            . "(NOW(), '{$form['name']}', '{$form['message']}', '{$form['image']}', '{$form['rate']}', '{$form['date']}', '{$form['step']}', '{$form['category']}', 1)";
        $result = mysqli_query($connect, $sql);

        if ($result) {
            $lot_id = mysqli_insert_id($connect);
            header('Location: /lot.php?id=' . $lot_id);
        } else {
            echo mysqli_error($connect);
        }
        exit;
    }
}

$categories_template = include_template('categories.php', [
    'categories' => $categories
]);

$content = include_template('add-lot.php', [
    'categories_template' => $categories_template,
    'categories' => $categories,
    'errors' => $errors,
    'form' => $form
]);

$page = include_template('layout.php', [
    'title' => 'Добавить лот',
    'user' => $user,
    'content' => $content,
    'categories' => $categories
]);

print $page;
