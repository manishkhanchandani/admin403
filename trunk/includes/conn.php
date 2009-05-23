<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
if($_SERVER['HTTP_HOST']=="localhost") {
	$hostname_conn = "localhost";
	$database_conn = "employer";
	$username_conn = "user";
	$password_conn = "password";
	$conn = mysql_pconnect($hostname_conn, $username_conn, $password_conn) or trigger_error(mysql_error(),E_USER_ERROR); 
	mysql_select_db($database_conn, $conn) or die('could not select db');
} else {
	$hostname_conn = "remote-mysql3.servage.net";
	$database_conn = "rekha_m1";
	$username_conn = "rekha_m1";
	$password_conn = "mrekha";
	$conn = mysql_pconnect($hostname_conn, $username_conn, $password_conn) or trigger_error(mysql_error(),E_USER_ERROR); 
	mysql_select_db($database_conn, $conn) or die('could not select db');
}
?>