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

function time_lot_close($time)
{
    $one_day = 86400;
    $timer = $time - time();

    if ($timer <=0) {
        $text = 'закончился';
    } else if ($timer <= ($one_day * 3)) {
        $hour = floor($timer / 60 / 60);
        $minute  = floor(($timer - $hour * 60 * 60) / 60);
        $text = sprintf('%02d:%02d', $hour, $minute);
    } else {
        $day = floor($timer / $one_day);
        $text = $day . ($day < 5 ? ' дня' : ' дней');
    }

    return $text;
}
