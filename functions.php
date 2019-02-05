<?php

function priceFormat($number, $currency = '&#8381;')
{
    $number = ceil($number);
    $number = number_format($number, 0, ',', '&nbsp;');

    return $number . '&nbsp;' . $currency;
}
