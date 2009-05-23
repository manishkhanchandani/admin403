<?php
include('start.php');
$Common->logout('admin');
/*
setcookie("admin[admin_id]","",0,"/");
setcookie("admin[email]","",0,"/");
setcookie("admin[name]","",0,"/");
*/
header("Location: ".HTTPPATH."/main/login.php");
exit;
?>