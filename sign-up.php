<?php

require_once('init.php');

$errors = [];
$form = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $form['email'] = mysqli_real_escape_string($connect, $_POST['email'] ?? NULL);
    $form['password'] = mysqli_real_escape_string($connect, $_POST['password'] ?? NULL);
    $form['name'] = mysqli_real_escape_string($connect, $_POST['name'] ?? NULL);
    $form['message'] = mysqli_real_escape_string($connect, $_POST['message'] ?? NULL);

    // Валидация email
    if ($form['email']) {
        if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Вы ввели некорректный e-mail';
        } else {
            $sql = "SELECT email FROM users WHERE email = '{$form['email']}'";
            $result = mysqli_query($connect, $sql);
            mysqli_fetch_assoc($result) ? $errors['email'] = 'Данный email уже используется' : '';
        }
    } else {
        $errors['email'] = 'Введите e-mail';
    }

    // Валидация пароля
    $form['password'] ? '' : $errors['password'] = 'Введите пароль';

    // Валидация имени
    $form['name'] ? '' : $errors['name'] = 'Введите имя';

    // Валидация контактных данных
    $form['message'] ? '' : $errors['message'] = 'Напишите как с вами связаться';

    // Валидация изображения
    if (isset($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name'])) {

        $tmp_name = $_FILES['image']['tmp_name'];
        $file_type = mime_content_type($tmp_name);

        if ($file_type === 'image/jpeg')
            $file_extension = 'jpg';
        else if ($file_type === 'image/png') {
            $file_extension = 'png';
        }

        if (in_array($file_type, ['image/jpeg', 'image/png'])) {
            $form['image'] = $tmp_name;
        } else {
            $errors['image'] = 'Загрузите jpg, jpeg или png изображение';
        }
    } else {
        $form['image'] = NULL;
    }

    // Если все хорошо, копируем файл и добавляем запись в базу
    if (!count($errors)) {

        $form['password'] = password_hash($form['password'], PASSWORD_DEFAULT);

        // На вский случай, ограничим максимальную длину строки
        $form['name'] = mb_substr($form['name'], 0, 255);
        $form['message'] = mb_substr($form['message'], 0, 255);

        if ($form['image']) {
            $tmp_name = $form['image'];
            $form['image'] = 'uploads/' . uniqid() . '.' . $file_extension;
            move_uploaded_file($tmp_name, $form['image']);
        }

        $sql = "INSERT INTO users (date_add, email, name, password, image_path, contact) VALUES "
            . "(NOW(), '{$form['email']}', '{$form['name']}', '{$form['password']}', '{$form['image']}', '{$form['message']}')";
        $result = mysqli_query($connect, $sql);

        if ($result) {
            header('Location: /login.php');
        } else {
            echo mysqli_error($connect);
        }
        exit;
    }
}

$categories_template = include_template('categories.php', [
    'categories' => $categories
]);

$content = include_template('sign-up.php', [
    'categories_template' => $categories_template,
    'categories' => $categories,
    'errors' => $errors,
    'form' => $form
]);

$page = include_template('layout.php', [
    'title' => 'Регистрация нового аккаунта',
    'user' => $user,
    'content' => $content,
    'categories' => $categories
]);

print $page;
