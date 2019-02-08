<?php

function price_format($number)
{
    $number = ceil($number);
    $number = number_format($number, 0, ',', '&nbsp;');

    return $number;
}

function include_template($name, $data)
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}
