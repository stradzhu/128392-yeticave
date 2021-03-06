<?php

/**
 * Функция для разделения числа на рязрады
 * @param integer $number Число для форматирования
 * @return string Разделенное число на разряды
 */
function price_format($number)
{
    $number = ceil($number);
    $number = number_format($number, 0, ',', '&nbsp;');

    return $number;
}

/**
 * Функция шаблонизатор
 * @param string Файл шаблона
 * @param mixed[] Параметры передаваемые в шаблон
 * @return string Готовая часть разметки страницы
 */
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

/**
 * Отображение окончания лота в вариантах "закончился", "ЧЧ:ММ" или "X дней"
 * @param string $time Дата и время
 * @return string Окончание лота в вариантах "закончился", "ЧЧ:ММ" или "X дней"
 */
function time_lot_close($time)
{
    $one_day = 86400;
    $timer = strtotime($time) - time();

    if ($timer <= 0) {
        $text = 'закончился';
    } else if ($timer <= ($one_day * 3)) {
        $hour = floor($timer / 60 / 60);
        $minute = floor(($timer - $hour * 60 * 60) / 60);
        $text = sprintf('%02d:%02d', $hour, $minute);
    } else {
        $day = floor($timer / $one_day);
        $text = $day . ($day < 5 ? ' дня' : ' дней');
    }

    return $text;
}

/**
 * Отображение времени добавления ставки в удобном формате "только что", "XX мин назад" или "XX ч назад"
 * @param string $time Дата и время
 * @return string Время добавления ставки в удобном формате "только что", "XX мин назад" или "XX ч назад"
 */
function bets_add_friendly($time)
{
    $one_minute = 60;
    $one_hour = 3600;
    $one_day = 86400;

    $bet_added = time() - strtotime($time);

    if ($bet_added < $one_minute) {
        $text = 'только что';
    } elseif ($bet_added < $one_hour) {
        $text = floor($bet_added / $one_minute) . ' мин назад';
    } elseif ($bet_added < $one_day) {
        $text = floor($bet_added / $one_hour) . ' ч назад';
    } else {
        $text = date('d.m.y в H:i', strtotime($time));
    }

    return $text;
}

/**
 * Получение информации о пользователей
 * @param resource $connect Ресурс соединения с базой данных
 * @return array Информация о пользователе
 */
function get_user_info($connect)
{
    if (isset($_SESSION['user_id'])) {
        $sql = 'SELECT id, name, image_path FROM users WHERE id = ' . $_SESSION['user_id'];
        $result = mysqli_query($connect, $sql);
        return $result ? mysqli_fetch_assoc($result) : [];
    }

    return [];
}

/**
 * Получение списка категорий
 * @param resource $connect Ресурс соединения с базой данных
 * @return array Результат запроса
 */
function get_categories_list($connect)
{
    $sql = 'SELECT id, name, icon FROM categories';
    $result = mysqli_query($connect, $sql);
    return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
}

/**
 * Проверка даты на формат Y-m-d или d.m.Y (и после, преобразованием в Y-m-d)
 * @param string Дата
 * @return string Дата в формате Y-m-d или NULL
 */
function check_date_format($date)
{
    // Проверим, соответсвует ли дата формату Y-m-d
    $dt = DateTime::createFromFormat("Y-m-d", $date);
    if ($dt !== false && !array_sum($dt::getLastErrors())) {
        return $date;
    }

    // Проверим, соответсвует ли дата формату d.m.Y и преобразуем в Y-m-d
    $dt = DateTime::createFromFormat("d.m.Y", $date);
    if ($dt !== false && !array_sum($dt::getLastErrors())) {
        return date_format($dt, 'Y-m-d');
    }

    return NULL;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = null;

            if (is_int($value)) {
                $type = 'i';
            } else if (is_string($value)) {
                $type = 's';
            } else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);
    }

    return $stmt;
}

/**
 * Формирование готовой страницы для показа ошибок
 * @param integer $code Код ошибки
 * @param string $title Заголовок ошибки
 * @param string $text Текст ошибки
 * @param array $categories Список категорий
 * @param array $auser Информация о пользователе
 */
function get_page_error($code, $title, $text, $categories, $user)
{
    http_response_code($code);

    $categories_template = include_template('categories.php', [
        'categories' => $categories
    ]);

    $content = include_template('404.php', [
        'categories_template' => $categories_template,
        'title' => $title,
        'text' => $text
    ]);

    $page = include_template('layout.php', [
        'title' => $title,
        'user' => $user,
        'content' => $content,
        'categories' => $categories
    ]);

    print $page;
    exit();
}
