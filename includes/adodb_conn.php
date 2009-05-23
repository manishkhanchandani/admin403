<?php
include_once('adodb/adodb.inc.php');
if($_SERVER['HTTP_HOST']=="localhost") {
	$dsn = 'mysql://user:password@localhost/employer'; 
} else {
	$dsn = 'mysql://rekha_m1:mrekha@remote-mysql3.servage.net/rekha_m1'; 
}
$db = ADONewConnection($dsn);  # no need for Connect()
$db->SetFetchMode(ADODB_FETCH_ASSOC);
?>