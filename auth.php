<?php
if ($_POST['name'] || $_POST['pwd'])
{
    if ($_POST['name'] == 'admin' && $_POST['pwd'] == '123') {
        setcookie('auth', sha1($_POST['name'] . $_SERVER['REMOTE_ADDR']), time() + 3600, '/');
        header("Location:index.php");
        exit;
    }
    else
    {
        $auth_err = 'style="background-color: #EFB0B8"';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
</head>
<body class="text-center">

        <form class="form-signin" action="auth.php" method="post">
            <h1 class="h3 mb-3 font-weight-normal">Авторизуйтесь</h1>
            <label for="inputEmail" class="sr-only">Логин</label>
            <input <?=$auth_err?> name="name" type="login" id="inputEmail" class="form-control" placeholder="Логин" required="" autofocus="">
            <label for="inputPassword" class="sr-only">Пароль</label>
            <input <?=$auth_err?> name="pwd" type="password" id="inputPassword" class="form-control" placeholder="Пароль" required="">
            <button class="btn btn-primary" type="submit">Войти</button>
            <a class='btn btn-primary' href='index.php' role='button'>Отмена</a>
        </form>

</body>


