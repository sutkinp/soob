<?php

//если редактируем и не админ, то нет доступа
if ($_POST['id'] > 0 && $_COOKIE['auth'] != sha1('admin'.$_SERVER['REMOTE_ADDR']))
{
    header("Location:index.php?add=3");
    exit;
}


$link = mysqli_connect('##','##','##','##');
if (!$link) {
    die('Ошибка при подключении: ' . mysqli_connect_error());
}

//валидация статуса
$status = ($_POST['status'] == 1 ? 1 : 0);

//валидация id
if ($_POST['id'])
{
    $id = (int)$_POST['id'];
}

//валидация имени
if ($_POST['name'])
{
    $name = htmlspecialchars($_POST['name'], ENT_COMPAT | ENT_HTML401 | ENT_QUOTES);
}
else
{
    $error_int[] = 'name';
}

//валидация e-mail
if (preg_match('/^[a-z0-9][-_\.a-z0-9]*@[a-z0-9\._-]+\.[a-z]{2,7}$/i', $_POST['email']))
{
    $email = $_POST['email'];
}
else
{
    $error_int[] = 'email';
}

//валидация задачи
if ($_POST['task'])
{
    $task = htmlspecialchars($_POST['task'], ENT_COMPAT | ENT_HTML401 | ENT_QUOTES);
}
else
{
    $error_int[] = 'task';
}

if (is_array($error_int))
{
    if($id > 0)
        header("Location: edittask.php?id=".$id."&err=".implode(',',$error_int));
    else
        header("Location: addtask.php?err=".implode(',',$error_int));
    exit;
}

if ($_POST['id'] > 0)
{
    $sql_res = mysqli_query($link, "SELECT * FROM tasks WHERE id = '{$id}'");
    $oldtask = mysqli_fetch_array($sql_res, MYSQLI_ASSOC);

    //проверка на наличие изменений
    if($oldtask['name'] == $name && $oldtask['email'] == $email && $oldtask['task'] == $task && $oldtask['status'] == $status)
    {
        header("Location:index.php?add=4");
        exit;
    }
    if($oldtask['edited'] == 1 || $oldtask['task'] != $task)
        $edited = 1;
    else
        $edited = 0;

    $sql = "UPDATE tasks SET
        name = '{$name}'
        ,email = '{$email}'
        ,task = '{$task}'
        ,status = '{$status}'
        ,edited = '{$edited}'
        WHERE id = '{$id}'
        ";
    $add = 2;
}
else
{
    $sql = "INSERT INTO tasks SET
            name = '{$name}'
            ,email = '{$email}'
            ,task = '{$task}'
            ";
    $add = 1;
}

mysqli_query($link, $sql);

header("Location:index.php?add={$add}");

