<!DOCTYPE html>
<html lang="en">
<head>
    <title>Добавление задачи</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
</head>
<body>
<div class="jumbotron text-center">
    <h1><a href="index.php">Задачник<a/></h1>
    <p>Добавление новой задачи</p>
</div>
<div class="container">
<form method="post" action="addtaskSQL.php">
    <div class="form-group">
        <?=(in_array('name', explode(',',$_GET['err'])) ? "<label style='color: red' for='InputName'>Введите корректное имя</label>" : "<label for='exampleInputPassword1'>Имя</label>")?>
        <input name="name" type="name" class="form-control" id="InputName" aria-describedby="emailHelp" placeholder="Введите ваше имя" required="">
    </div>
    <div class="form-group">
        <?=(in_array('email', explode(',',$_GET['err']))  ? "<label style='color: red' for='InputEmail'>Введите корректный E-mail</label>" : "<label for='InputEmail'>E-mail</label>")?>
        <input name="email" type="email" class="form-control" id="InputEmail" placeholder="Введите E-mail" required="">
    </div>
    <div class="form-group">
        <label for="exampleFormControlTextarea1">Задача</label>
        <textarea class="form-control" name="task" id="exampleFormControlTextarea1" rows="3" placeholder="Введите текст задачи" required=""></textarea >
    </div>
    <button type="submit" class="btn btn-primary">Добавить</button>
    <a class='btn btn-primary' href='index.php' role='button'>Отмена</a>
</form>
</div>
</body>