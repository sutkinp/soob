<?php

#что принимает скрипт через GET:
#page - int
$_GET['page'] = (int)$_GET['page'];
if ($_GET['page'] < 1)
    $_GET['page'] = 1;

#sort - set
if(!in_array($_GET['sort'], array('name','email','status')))
{
    $_GET['sort'] = 'name'; //сортировка по умолчанию
}

#desc - bool
$_GET['desc'] = (int)$_GET['desc'];


$per_page = 3;
$page = 0;
$start = ($_GET['page'] - 1) * $per_page;

$link = mysqli_connect('localhost','admin','RiverPark32959','sutkin');
if (!$link)
{
    die('Ошибка при подключении: ' . mysqli_connect_error());
}

$link->set_charset("utf8");

//собираем пагинатор
$sql_res1 = mysqli_query($link, "SELECT count(*) FROM tasks");
$row = mysqli_fetch_row($sql_res1);
$rows = $row[0];
$num_pages = ceil($rows/$per_page);

if($num_pages > 1) {
    $pagination = "<nav aria-label='Page navigation example'>
               <ul class='pagination'>
               <li class='page-item" . ($_GET['page'] == 1 ? ' disabled' : '') . "'><a class='page-link' href='index.php?page=" . ($_GET['page'] - 1) . "&sort=" . $_GET['sort'] . ($_GET['desc'] ? '&desc=1' : '') . "'>Пред</a></li>";
    while ($page++ < $num_pages) {
        $pagination .= "<li class='page-item" . ($page == $_GET['page'] ? ' active' : '') . "'><a class='page-link' href='index.php?page=" . $page . "&sort=" . $_GET['sort'] . ($_GET['desc'] ? '&desc=1' : '') . "'>" . $page . "</a></li>";
    }
    $pagination .= "<li class='page-item" . ($_GET['page'] >= $num_pages ? ' disabled' : '') . "'><a class='page-link' href='index.php?page=" . ($_GET['page'] + 1) . "&sort=" . $_GET['sort'] . ($_GET['desc'] ? '&desc=1' : '') . "'>След</a></li></ul></nav>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Задачник</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
</head>

<body>
<div style="height: 50%" class="jumbotron text-center">
    <h1>Задачник</h1>
    <p>Список задач для слушателей курса</p>
<?
if ($_COOKIE['auth'] == sha1('admin'.$_SERVER['REMOTE_ADDR']))
{
    $isAdmin = 1;
    $adm = '';
    echo "
        Добро пожаловать Админ
        <div style='text-align: center'> 
        <a class='btn btn-primary' href='logout.php' role='button'>Выйти</a>
        <a class='btn btn-primary' href='addtask.php' role='button'>Добавить</a>
        </div>";
}
else
{
    $adm='display:none';
    echo "
        <div style='text-align: center'> 
        <a class='btn btn-primary' href='auth.php' role='button'>Войти</a>
        <a class='btn btn-primary' href='addtask.php' role='button'>Добавить задачу</a>
        </div>";
}
?>
</div>
<div class="container">
    <?=$pagination?>
    <?=($_GET['add']==1 ? '<p style="color: green;font-weight: bold;border: dashed 1px green;text-align: center;">Запись добавлена</p>' : '')?>
    <?=($_GET['add']==2) ? '<p style="color: orange;font-weight: bold;border: dashed 1px orange;text-align: center;">Запись изменена</p>' : ''?>
    <?=($_GET['add']==4) ? '<p style="color: darkslategray;font-weight: bold;border: dashed 1px darkslategray;text-align: center;">Запись не изменена</p>' : ''?>
    <?=($_GET['add']==3) ? '<p style="color: red;font-weight: bold;border: dashed 1px red;text-align: center;">Ошибка редактирования записи</p>' : ''?>
    <table class="table">
        <thead>
        <tr>
            <th width="5%" scope="col">#</th>
            <th width="17%" scope="col"><a href="index.php?sort=name<?=($_GET['desc'] ? '' : '&desc=1')?>"><?=($_GET['sort'] == 'name' ? ($_GET['desc'] ? '&uArr;' : '&dArr;') : "")?>Имя</a></th>
            <th width="17%" scope="col"><a href="index.php?sort=email<?=($_GET['desc'] ? '' : '&desc=1')?>"><?=($_GET['sort'] == 'email' ? ($_GET['desc'] ? '&uArr;' : '&dArr;') : "")?>E-mail</a></th>
            <th width="40%" scope="col">Задача</th>
            <th width="15%" scope="col"><a href="index.php?sort=status<?=($_GET['desc'] ? '' : '&desc=1')?>"><?=($_GET['sort'] == 'status' ? ($_GET['desc'] ? '&uArr;' : '&dArr;') : "")?>Статус</a></th>
            <?=($isAdmin ? "<th width='5%' scope='col'>Ред.</th>" :'')?>
        </tr>
        </thead>
        <tbody>
<?php

$sql_res = mysqli_query($link, "SELECT * 
        FROM tasks
        ORDER BY {$_GET['sort']} ".($_GET['desc'] ? "DESC" : "")."        
        LIMIT {$start},{$per_page}
        ");

if (mysqli_errno($link))
    echo mysqli_error($sql);

while (is_array($tasks = mysqli_fetch_array($sql_res, MYSQLI_ASSOC)))
    {
        echo "
        <tr>
            <td>{$tasks['id']}</td>
            <td style='word-break: break-word;'>{$tasks['name']}</td>
            <td style='word-break: break-word;'>{$tasks['email']}</td>
            <td style='word-break: break-word;'>{$tasks['task']}</td>
            <td>".($tasks['status']==1 ? 'Выполнено' : 'Не выполнено').($tasks['edited']==1 ? '<BR>Отредактированно' : '')."</td>
            ".($isAdmin ? "<td><a href='edittask.php?id=".$tasks['id']."'>✎</a></td>" : '') ."
        </tr>";
    }
?>

        </tbody>
    </table>
    <?=$pagination?>
</div>
</body>
</html>