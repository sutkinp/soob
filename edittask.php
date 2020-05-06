<?php
$link = mysqli_connect('localhost','admin','RiverPark32959','sutkin');
$sql = "SELECT * from tasks
        WHERE id='{$_GET['id']}'";
$sql_res = mysqli_query($link, $sql);
$res = mysqli_fetch_array($sql_res, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Изменение задачи</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
</head>
<body>
<div class="jumbotron text-center">
    <h1><a href="/">Задачник<a/></h1>
    <p>Редактировние задачи №<?=$_GET['id']?></p>
</div>
<div class="container">
<form method="post" action="addtaskSQL.php">
    <div class="form-group">
        <?=(in_array('name', explode(',',$_GET['err'])) ? "<label style='color: red' for='InputName'>Введите корректное имя</label>" : "<label for='exampleInputPassword1'>Имя</label>")?>
        <input name="id" value="<?=$res['id']?>" style="display: none">
        <input value="<?=$res['name']?>" name="name" type="name" class="form-control" id="InputName" >
    </div>
    <div class="form-group">
        <?=(in_array('email', explode(',',$_GET['err'])) ? "<label style='color: red' for='InputEmail'>Введите корректный E-mail</label>" : "<label for='InputEmail'>E-mail</label>")?>
        <input value="<?=$res['email']?>" name="email" type="email" class="form-control" aria-describedby="emailHelp" id="InputEmail">
    </div>
    <div class="form-group">
        <label for="exampleFormControlTextarea1">Задача</label>
        <textarea class="form-control" name="task" id="exampleFormControlTextarea1" rows="3"><?=$res['task']?></textarea>
    </div>
    <div class="form-check">
        <input name="status" value="1" <?if ($res['status']) echo 'checked'?> type="checkbox" class="form-check-input" id="exampleCheck1">
        <label class="form-check-label" for="exampleCheck1" >Выполнено</label>
    </div>
    <button type="submit" class="btn btn-primary">Сохранить</button>
    <a class='btn btn-primary' href='index.php' role='button'>Отмена</a>
</form>
</div>