<?php
require_once('inc/lib.inc.php');


// Разлогин
if ($_GET['logout']==1)
{
    setcookie("auth","", time()-3600, '/');
    header("Location:index.php");
}


//  выгрузка в csv
if ($_GET['exp']==1)
{
    header("Content-type: text/csv; charset=utf-8");
    header("Content-Disposition: attachment; filename=export.csv");
    $sql = "
SELECT *
FROM tasks
ORDER BY datetime DESC
";
    $sql_res = mquery($sql);
    while (is_array($T = mysqli_fetch_array($sql_res, MYSQLI_ASSOC)))
    {
        echo "\"".htmlspecialchars_decode(implode("\";\"" , mb_convert_encoding (str_replace(" &quot; " , " \"\" " , $T),"CP1251")) ,ENT_QUOTES)."\"\n";
    }
    exit;
}

#что принимает скрипт через GET:
#page - int
$_GET['page'] = (int)$_GET['page'];
if ($_GET['page'] < 1)
    $_GET['page'] = 1;

#sort - set
if(!in_array($_GET['sort'], array('name','tel','email','datetime')))
{
    $_GET['sort'] = 'name'; //сортировка по умолчанию
}

#desc - bool
$_GET['desc'] = (int)$_GET['desc'];


$per_page = 10;
$page = 0;
$start = ($_GET['page'] - 1) * $per_page;



//собираем пагинатор
$sql_res1 = mquery("SELECT count(*) FROM tasks");
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
    <title>Обращения</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
</head>

<body>
<div style="height: 50%" class="jumbotron text-center">
    <h1>iFora</h1>
    <p>Список обращений</p>
<?
if ($_COOKIE['auth'] == sha1('admin'.$_SERVER['REMOTE_ADDR']))
{
    echo "
Добро пожаловать Админ
<div style='text-align: center'> 
<a class='btn btn-primary' href='index.php?logout=1' role='button'>Выйти</a>
<a class='btn btn-primary' href='index.php?exp=1' role='button'>Выгрузить в CSV</a>
<a class='btn btn-primary' href='addfeedback.php' role='button'>Добавить запись</a>
</div></div>
<div class='container'>
".$pagination."
<table class='table'>
<thead>
<tr>
    <th width='5%' scope='col'>#</th>
    <th width='10%' scope='col'><a href='index.php?sort=name".($_GET['desc'] ? '' : '&desc=1')."'>".($_GET['sort'] == 'name' ? ($_GET['desc'] ? '&uArr;' : '&dArr;') : '')."Имя</a></th>
    <th width='17%' scope='col'><a href='index.php?sort=tel".($_GET['desc'] ? '' : '&desc=1')."'>".($_GET['sort'] == 'tel' ? ($_GET['desc'] ? '&uArr;' : '&dArr;') : '')."Телефон</a></th>
    <th width='17%' scope='col'><a href='index.php?sort=email".($_GET['desc'] ? '' : '&desc=1')."'>".($_GET['sort'] == 'email' ? ($_GET['desc'] ? '&uArr;' : '&dArr;') : '')."E-mail</a></th>
    <th width='40%' scope='col'>Пожелания</th>
    <th width='20%' scope='col'><a href='index.php?sort=datetime".($_GET['desc'] ? '' : '&desc=1')."'>".($_GET['sort'] == 'datetime' ? ($_GET['desc'] ? '&uArr;' : '&dArr;') : '')."Время</a></th>
</tr>
</thead>
<tbody>";

    $sql = "
SELECT * 
FROM tasks 
ORDER BY {$_GET['sort']}".($_GET['desc'] ? 'DESC' : '')."
LIMIT {$start},{$per_page}
    ";

    $sql_res = mquery($sql);

    while (is_array($tasks = mysqli_fetch_array($sql_res, MYSQLI_ASSOC)))
    {
        echo "
<tr>
    <td>{$tasks['id']}</td>
    <td style='word-break: break-word;'>{$tasks['name']}</td>
    <td>{$tasks['tel']}</td>
    <td>{$tasks['email']}</td>
    <td style='word-break: break-word;'>{$tasks['other']}</td>
    <td>".substr($tasks['datetime'],0,16)."</td>
</tr>";
    }

    echo "
</tbody>
</table>
{$pagination}";

}
else
{
    $adm='display:none';
    echo "
<div style='text-align: center'> 
<a class='btn btn-primary' href='auth.php' role='button'>Войти</a>
<a class='btn btn-primary' href='addfeedback.php' role='button'>Добавить запись</a>
</div></div>
<div class='conteiner'>
<center><h2>Вам необходимо авторизоватся для просмотра записей</h2></center>
</div>
        ";
}
?>

</div>
</body>
</html>
