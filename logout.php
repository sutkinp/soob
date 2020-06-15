<?php
setcookie("auth","", time()-3600, '/');
header("Location:ifora.php");
exit;


