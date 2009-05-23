<?php
if($_SERVER['HTTP_HOST']=="localhost") {
	define('HTTPPATH', 'http://localhost/employer2');
	define('DOCPATH', 'C:/Program Files/xampp/htdocs/employer2');
} else {
	define('HTTPPATH', 'http://10000projects.info/domains/employer2');
	define('DOCPATH', '/home37b/sub004/sc29722-KLXJ/www/domains/employer2');
}
require_once ("Smarty.class.php"); 
$smarty = new Smarty; 
//$smarty->caching = true;
$smarty->compile_check = true;
//$smarty->cache_lifetime = 30; // 30 seconds 
$smarty->compile_dir = DOCPATH."/templates/one/compile";
$smarty->template_dir = DOCPATH."/templates/one/html";
//$smarty->cache_dir = DOCPATH."/templates/one/cache";

function __autoload($classname) {
	include("classes/class-{$classname}.php");
}
?>