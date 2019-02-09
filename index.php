<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once('functions.php');

$title = 'Главная';

$user = [
    'name' => 'Дмитрий',
    'avatar' => '/img/avatar.jpg'
];

$categories = [
    [
        'name' => 'Доски и лыжи',
        'icon' => 'boards',
    ],
    [
        'name' => 'Крепления',
        'icon' => 'attachment',
    ],
    [
        'name' => 'Ботинки',
        'icon' => 'boots',
    ],
    [
        'name' => 'Одежда',
        'icon' => 'clothing',
    ],
    [
        'name' => 'Инструменты',
        'icon' => 'tools',
    ],
    [
        'name' => 'Разное',
        'icon' => 'other',
    ]
];

$products = [
    [
        'name' => '2014 Rossignol District Snowboard',
        'category' => 'Доски и лыжи',
        'price' => '10999',
        'image' => 'img/lot-1.jpg',
        'time' => '1549411200' // 6 февраля 2019 г., 0:00:00 UTC
    ],
    [
        'name' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => 'Доски и лыжи',
        'price' => '159999',
        'image' => 'img/lot-2.jpg',
        'time' => '1549670400' // 9 февраля 2019 г., 0:00:00 UTC
    ],
    [
        'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => 'Крепления',
        'price' => '8000',
        'image' => 'img/lot-3.jpg',
        'time' => '1549756800' // 10 февраля 2019 г., 0:00:00 UTC
    ],
    [
        'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'category' => 'Ботинки',
        'price' => '10999',
        'image' => 'img/lot-4.jpg',
        'time' => '1549843200' // 11 февраля 2019 г., 0:00:00 UTC
    ],
    [
        'name' => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => 'Одежда',
        'price' => '7500',
        'image' => 'img/lot-5.jpg',
        'time' => '1549929600' // 12 февраля 2019 г., 0:00:00 UTC
    ],
    [
        'name' => 'Маска Oakley Canopy',
        'category' => 'Разное',
        'price' => '5400',
        'image' => 'img/lot-6.jpg',
        'time' => '1550620800' // 20 февраля 2019 г., 0:00:00 UTC
    ]
];

$content = include_template('index.php', [
    'categories' => $categories,
    'products' => $products
]);

$page = include_template('layout.php', [
    'title' => $title,
    'user' => $user,
    'content' => $content,
    'categories' => $categories
]);

print $page;
