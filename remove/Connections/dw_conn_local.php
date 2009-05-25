<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_dw_conn = "localhost";
$database_dw_conn = "admin403svn";
$username_dw_conn = "user";
$password_dw_conn = "password";
$dw_conn = mysql_connect($hostname_dw_conn, $username_dw_conn, $password_dw_conn) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_select_db($database_dw_conn, $dw_conn) or die('could not found db');
?>