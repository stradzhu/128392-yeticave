<?php

function priceFormat($number, $currency = '&#8381;')
{
    $number = ceil($number);
    $number = number_format($number, 0, ',', '&nbsp;');

    return $number . '&nbsp;' . $currency;
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
