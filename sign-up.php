<?php

require_once('init.php');

$errors = [];
$form = [];

if (count($user)) {
    header('Location: /');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $required_fileds = ['email', 'password', 'name', 'message'];

    foreach ($required_fileds as $value) {
        if (isset($_POST[$value]) && !empty(trim($_POST[$value]))) {
            $form[$value] = trim($_POST[$value]);
        } else {
            $form[$value] = NULL;
            $errors[$value] = 'Это поле необходимо заполнить';
        }
    }

    // Валидация email
    if ($form['email']) {
        if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Вы ввели некорректный e-mail';
        } else {
            $sql = "SELECT email FROM users WHERE email = '{$form['email']}'";
            $result = mysqli_query($connect, $sql);
            if (mysqli_fetch_assoc($result)) {
                $errors['email'] = 'Данный email уже используется';
            }
        }
    }

    // Валидация пароля
    if ($form['password']) {
        $length = mb_strlen($form['password'], 'UTF-8');
        if ($length < 6) {
            $errors['password'] = 'Пароль должен быть не менее 6-ти символов';
        } elseif ($length > 100) {
            $errors['password'] = 'Пароль должен быть не более 100-ти символов';
        }
    }

    // Валидация имени
    if ($form['name']) {
        $length = mb_strlen($form['name'], 'UTF-8');
        if ($length < 2) {
            $errors['name'] = 'Имя должно быть не менее 2-ух символов';
        } elseif ($length > 255) {
            $errors['name'] = 'Имя должно быть не более 255-ти символов';
        }
    }

    // Валидация контактных данных
    if ($form['message']) {
        $length = mb_strlen($form['message'], 'UTF-8');
        if ($length < 5) {
            $errors['message'] = 'Контактные данные должны быть не менее 5-ти символов';
        } elseif ($length > 255) {
            $errors['message'] = 'Контактные данные должны быть не более 255-ти символов';
        }
    }

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

        $form['email'] = mysqli_real_escape_string($connect, $form['email']);
        $form['name'] = mysqli_real_escape_string($connect, $form['name']);
        $form['message'] = mysqli_real_escape_string($connect, $form['message']);

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
            $error_title = '500 Ошибка сервера';
            $error_text = mysqli_error($connect);
            get_page_error(500, $error_title, $error_text, $categories, $user);
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
