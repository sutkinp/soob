<?php
$link = mysqli_connect('localhost','login','pwd','sutkin');
if (!$link)
{
    die('Ошибка при подключении: ' . mysqli_connect_error());
}
$link->set_charset("utf8");

function SendMail($from, $fname, $to, $tname, $subj, $mail, $attach = null, $ishtml = false)
{
    global $_das, $das;
    if($_das['config']['IsTest'] > 0) return; #Not send if test mode

    $m = new MAIL5;
    $m->From($from, $fname, 'utf-8');
    if(is_array($to))
    {
        for($i=0;$i<count($to);$i++)
            if($to[$i] != "" && $tname[$i] != "")
                $m->AddTo($to[$i], $tname[$i], 'utf-8');
    }
    else $m->AddTo($to, $tname, 'utf-8');
    $m->Subject($subj, 'utf-8');
    if (!$ishtml) {
        $m->Text($mail, 'utf-8');
    } else {
        $m->html($mail, 'utf-8');
    }
    if($attach != null){
        $m->attach($attach['content'], $attach['type'], $attach['name'], 'utf-8');
    }

    $smtp = SMTP5::connect('smtp.gmail.com',465,'botsoob@gmail.com','!@#123qwe','ssl');
    $m->Send($smtp);
}

function mquery($sql){
    global $link;
    $R = mysqli_query($link, $sql);
//    if (mysqli_errno($link)){echo mysqli_error($link);}
    return $R;
}