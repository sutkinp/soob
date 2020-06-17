<?
require_once('inc/MAIL5.php');
require_once('inc/MIME5.php');
require_once('inc/SMTP5.php');
require_once('inc/FUNC5.php');
require_once('inc/lib.inc.php');

if ($_POST['add'] == 1) {
    //валидация имени
    if ($_POST['name'] && strlen(trim($_POST['name'])) > 3) {
        $_POST['name'] = htmlspecialchars(str_replace("\\", "\\\\", $_POST['name']), ENT_COMPAT | ENT_HTML401 | ENT_QUOTES);

    } else {
        $error_int[] = 'name';
    }

    //валидация и проверка даты
    $date_Y = substr($_POST['date'], 0, 4);
    $date_m = substr($_POST['date'], 5, 2);
    $date_d = substr($_POST['date'], 8, 2);

    if (
        !checkdate($date_m, $date_d, $date_Y) ||
        !preg_match('/^\d{2}\:\d{2}$/', $_POST['time']) ||
        !preg_match('/^\d{4}\-\d{2}-\d{2}$/', $_POST['date']) ||
        $date_Y . $date_m . $date_d < date('Ymd')
    ) {
        $error_int[] = 'date';
    }

    //валидация телефона
    $_POST['tel'] = (int)$_POST['tel'];

    //валидация e-mail
    if (!preg_match('/^[a-z0-9][-_\.a-z0-9]*@[a-z0-9\._-]+\.[a-z]{2,7}$/i', $_POST['email'])) {
        $error_int[] = 'email';
    }

    //валидация пожеланий
    if (strlen($_POST['other']) < 1000) {
        $_POST['other'] = htmlspecialchars(str_replace("\\", "\\\\", $_POST['other']), ENT_HTML401 | ENT_QUOTES);

    } else
        {
        $error_int[] = 'other';
        }

    if (is_array($error_int)) {
        header("Location: addfeedback.php?err=" . implode(',', $error_int));
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
    mquery($sql);

    $mail = "
Новая заявка на просмотр:<BR>
Имя: {$_POST['name']}<BR> 
Контактные данные: {$_POST['email']}, {$_POST['tel']}<BR> 
Время просмотра: {$_POST['date']} {$_POST['time']}<BR>
Пожелания: {$_POST['other']}<BR>";
    SendMail('botsoob@gmail.com', 'Админ', 'sutkinp@gmail.com', 'Пользователь', 'Новая запись', $mail, null , true);
    header("Location:addfeedback.php?add=1");
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Добавление записи</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
</head>
<body>

<div class="jumbotron text-center">
    <h1><a href="index.php">iFORA<a/></h1>
    <p>Форма обратной связи</p>
</div>
<div class="container">
    <?=($_GET['add']==1 ? '<p style="color: green;font-weight: bold; text-align: center;">Запись добавлена</p>' : '')?>
    <form method="post" action="addfeedback.php">
    <div class="form-group">
        <?=(in_array('name', explode(',',$_GET['err'])) ? "<label style='color: #057e15' for='InputName'>Введите корректное имя</label>" : "<label for='exampleInputPassword1'>Имя</label>")?>
        <input name="name" type="name" class="form-control" id="InputName" aria-describedby="emailHelp" placeholder="Введите ваше имя" required="">
    </div>
    <div class="form-group">
        <?=(in_array('email', explode(',',$_GET['err']))  ? "<label style='color: red' for='InputEmail'>Введите корректный E-mail</label>" : "<label for='InputEmail'>E-mail</label>")?>
        <input name="email" type="email" class="form-control" id="InputEmail" placeholder="Введите E-mail" required="">
    </div>

    <div class="form-group">
        <?=(in_array('tel', explode(',',$_GET['err']))  ? "<label style='color: red' for='tel-input'>Введите корректный номер телефона</label>" : "<label for='tel-input'>Телефон</label>")?>
        <input name="tel" type="number" class="form-control"  placeholder="Введите ваш телефон" value="" id="tel-input" required="">
    </div>

    <div class="form-group">
        <?=(in_array('other', explode(',',$_GET['err']))  ? "<label style='color: red' for='other'>Превышена допустимая длина текста</label>" : "<label for='other'>Пожелания</label>")?>
        <textarea class="form-control" name="other" id="FormControlTextarea1" rows="2" placeholder="Введите ваши пожелания"></textarea >
    </div>
    <div class="form-group">
        <?=(in_array('date', explode(',',$_GET['err']))  ? "<label style='color: red' for='date-input'>Введите корректную дату и время просмотра</label>" : "<label for='date-input'>Дата и время просмотра</label>")?>
            <input name='date' class="form-control" type="date" value="" id="date-input" required="">
    </div>
    <div>
            <input name='time' class="form-control" type="time" value="" id="time-input" required="">
    </div>
    <div>
            <input name='add' class="form-control" value="1" id="add" style="display: none">
    </div>
    <BR>
    <button type="submit" class="btn btn-primary">Добавить</button>
    <a class='btn btn-primary' href='addfeedback.php' role='button'>Очистить</a>
</form>
</div>
</body>