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
    <h1><a href="ifora.php">iFORA<a/></h1>
    <p>Форма обратной связи</p>
</div>
<div class="container">
    <?=($_GET['add']==1 ? '<p style="color: green;font-weight: bold; text-align: center;">Запись добавлена</p>' : '')?>
    <form method="post" action="addfeedbackSQL.php">
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
    <BR>
    <button type="submit" class="btn btn-primary">Добавить</button>
    <a class='btn btn-primary' href='addfeedback.php' role='button'>Очистить</a>
</form>
</div>
</body>