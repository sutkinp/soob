<?php
require_once('inc/MAIL5.php');
require_once('inc/MIME5.php');
require_once('inc/SMTP5.php');
require_once('inc/FUNC5.php');
require_once('inc/config.php');
require_once('inc/lib.inc.php');

$link = mysqli_connect('localhost','admin','RiverPark32959','sutkin');
if (!$link) {
    die('Ошибка при подключении: ' . mysqli_connect_error());
}

//валидация имени
if ($_POST['name'] && strlen(trim($_POST['name']))>3)
{
    $_POST['name'] = htmlspecialchars($_POST['name'], ENT_COMPAT | ENT_HTML401 | ENT_QUOTES);
}
else
{
    $error_int[] = 'name';
}

//валидация и проверка даты
$date_Y = substr($_POST['date'], 0, 4);
$date_m = substr($_POST['date'], 5, 2);
$date_d = substr($_POST['date'], 8, 2);

if (
    !checkdate($date_m, $date_d, $date_Y) ||
    !preg_match('/^\d{2}\:\d{2}$/',$_POST['time']) ||
    !preg_match('/^\d{4}\-\d{2}-\d{2}$/',$_POST['date']) ||
    $date_Y.$date_m.$date_d < date('Ymd')
    )
    {
        $error_int[] = 'date';
    }

//валидация телефона
$_POST['tel'] = (int)$_POST['tel'];

//валидация e-mail
if (!preg_match('/^[a-z0-9][-_\.a-z0-9]*@[a-z0-9\._-]+\.[a-z]{2,7}$/i', $_POST['email']))
{
    $error_int[] = 'email';
}

//валидация пожеланий
if (strlen($_POST['other']) < 1000 )
{
    $_POST['other'] = htmlspecialchars($_POST['other'], ENT_COMPAT | ENT_HTML401 | ENT_QUOTES);
}
else
{
    $error_int[] = 'other';
}

if (is_array($error_int))
{
    header("Location: addfeedback.php?err=".implode(',',$error_int));
    exit;
}

$sql = "
INSERT INTO tasks SET
name = '{$_POST['name']}'
,email = '{$_POST['email']}'
,tel = '{$_POST['tel']}'
,other= '{$_POST['other']}'
,datetime = STR_TO_DATE('{$_POST['date']} {$_POST['time']}','%Y-%m-%d %H:%i')
";
mysqli_query($link, $sql);

$mail = "Новая запись. Ссылка: http://beta.rp.lc/soob/edittask.php?id=".mysqli_insert_id($link)."";
SendMail('sutkinp@gmail.com', 'Админ', 'sutkinp@gmail.com', 'Пользователь', 'Восстановление Пароля', $mail);

header("Location:addfeedback.php?add=1");


