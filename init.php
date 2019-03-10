<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once('functions.php');

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

session_start();

$user = get_user_info($connect);
$categories = get_categories_list($connect);
