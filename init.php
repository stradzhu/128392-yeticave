<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$db = [
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'database' => 'yeticave_128392'
];

$connect = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);

if (!$connect) {
    echo mysqli_connect_error();
    exit;
}

mysqli_set_charset($connect, "utf8");
