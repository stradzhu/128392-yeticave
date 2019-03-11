<?php

require_once('init.php');

$errors = [];
$form = [];

if (count($user)) {
    header('Location: /');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $required_fileds = ['email', 'password'];

    foreach ($required_fileds as $value) {
        if (isset($_POST[$value]) && !empty(trim($_POST[$value]))) {
            $form[$value] = trim($_POST[$value]);
        } else {
            $form[$value] = NULL;
            $errors[$value] = 'Это поле необходимо заполнить';
        }
    }

    // Валидация email
    if ($form['email'] && !filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Вы ввели некорректный e-mail';
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

    // Если все хорошо, то проверим логин/пароль
    if (!count($errors)) {

        $form['email'] = mysqli_real_escape_string($connect, $form['email']);

        $sql = "SELECT * FROM users WHERE email = '{$form['email']}'";
        $result = mysqli_query($connect, $sql);

        $authentication = $result ? mysqli_fetch_array($result, MYSQLI_ASSOC) : NULL;

        if ($authentication) {
            if (password_verify($form['password'], $authentication['password'])) {
                $_SESSION['user_id'] = $authentication['id'];
                header('Location: /');
                exit;
            }
            else {
                $errors['password'] = 'Неверный пароль';
            }
        }
        else {
            $errors['email'] = 'Такой пользователь не найден';
        }
    }
}

$categories_template = include_template('categories.php', [
    'categories' => $categories
]);

$content = include_template('login.php', [
    'categories_template' => $categories_template,
    'categories' => $categories,
    'errors' => $errors,
    'form' => $form
]);

$page = include_template('layout.php', [
    'title' => 'Вход',
    'user' => $user,
    'content' => $content,
    'categories' => $categories
]);

print $page;
