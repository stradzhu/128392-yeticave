<?php

require_once('init.php');

$errors = [];
$form = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $form['email'] = mysqli_real_escape_string($connect, $_POST['email'] ?? NULL);
    $form['password'] = mysqli_real_escape_string($connect, $_POST['password'] ?? NULL);

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
