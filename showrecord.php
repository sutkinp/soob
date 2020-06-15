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
    <title>Просмотр записи</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
</head>
<body>
<div class="jumbotron text-center">
    <h1><a href="ifora.php">iFora<a/></h1>
    <p>Просмотр записи №<?=$_GET['id']?></p>
</div>
<div class="container">
<form method="post" action="addtaskSQL.php">
    <div class="form-group">
        <label for='exampleInputPassword1'>Имя</label>
        <input name="id" value="<?=$res['id']?>" style="display: none">
        <input disabled value="<?=$res['name']?>" name="name" type="name" class="form-control" id="InputName" >
    </div>
    <div class="form-group">
        <label for='InputEmail'>Телефон</label>
        <input disabled value="<?=$res['tel']?>" name="tel" type="tel" class="form-control" aria-describedby="emailHelp" id="Inputtel">
    </div>
    <div class="form-group">
        <label for='InputEmail'>E-mail</label>
        <input disabled value="<?=$res['email']?>" name="email" type="email" class="form-control" aria-describedby="emailHelp" id="InputEmail">
    </div>
    <div class="form-group">
        <label for='InputEmail'>Дата/время просмотра</label>
        <input disabled value="<?=$res['datetime']?>" name="datetime" type="text" class="form-control" aria-describedby="emailHelp" id="Inputdate">
    </div>
    <div class="form-group">
        <label for="exampleFormControlTextarea1">Пожелания</label>
        <textarea disabled class="form-control" name="other" id="exampleFormControlTextarea1" rows="3"><?=$res['task']?></textarea>
    </div>

    <a class='btn btn-primary' href='ifora.php' role='button'>Назад</a>
</form>
</div>