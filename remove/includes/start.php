<?php
if($_SERVER['HTTP_HOST']=="localhost") {
	define('HTTPPATH', "http://".$_SERVER['HTTP_HOST']."/admin403b");
	define('DOCPATH', $_SERVER['DOCUMENT_ROOT']."/admin403b");
	define('TEMPLATE', 'new');
	define('DISPLAYPLANNAME', 'Product');
	
	define('SITEMAIL','asimonson@verityinvest.com');
	define('SITENAME', 'Verity Investments');
	define('ADMINMAIL','asimonson@verityinvest.com');
	define('ENCKEY', 'JKjVXtFdY3NNT6Fp6U9uM3m5eeWbtqXWrR5qwWpyM9b8SFSdWVK2vruN');
	function __autoload($classname) {
		include("classes/class-{$classname}.php");
	}
	$encryption = new encryption;
} else {
	define('HTTPPATH', "https://".$_SERVER['HTTP_HOST']."/admin403b");
	define('DOCPATH', $_SERVER['DOCUMENT_ROOT']."/admin403b");
	define('TEMPLATE', 'new');
	define('DISPLAYPLANNAME', 'Product');
	
	define('SITEMAIL','asimonson@verityinvest.com');
	define('SITENAME', 'Verity Investments');
	define('ADMINMAIL','asimonson@verityinvest.com');
	define('ENCKEY', 'JKjVXtFdY3NNT6Fp6U9uM3m5eeWbtqXWrR5qwWpyM9b8SFSdWVK2vruN');
	function __autoload($classname) {
		include("classes/class-{$classname}.php");
	}
	$encryption = new encryption;
}
include('adodb/adodb-exceptions.inc.php'); # load code common to ADOdb
include('adodb/adodb.inc.php'); # load code common to ADOdb 

$ADODB_CACHE_DIR = DOCPATH.'/ADODB_cache'; 
//$dbFrameWork = &ADONewConnection('mysql');  # create a connection 
//$dbFrameWork->Connect('remote-mysql3.servage.net','framework2008','framework2008','framework2008');# connect to MySQL, framework db
try { 
	$dbFrameWork = &ADONewConnection('mysql');  # create a connection 
	if($_SERVER['HTTP_HOST']=="localhost") {
		//$dbFrameWork->Connect('localhost','admin403bnrnew','Z7wjyYrxaGWnTwGE','admin403bnrnew');# connect to MySQL, framework db
		$dbFrameWork->Connect('localhost','user','Z7wjyYrxaGWnTwGE','admin403');# connect to MySQL, framework db
	} else {
		$dbFrameWork->Connect('localhost','admin403bnrnew','Z7wjyYrxaGWnTwGE','admin403bnrnew');# connect to MySQL, framework db
	}
} catch (exception $e) { 
	echo 'Loading in 5 seconds. If page does not refresh in 5 seconds, please refresh manually.<meta http-equiv="refresh" content="5">';
	mail('mkgxy@mkgalaxy.com','error at '.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'],'error at '.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].' at '.date('r'));
	//echo "<pre>";var_dump($e); adodb_backtrace($e->gettrace());
	exit;
} 
$Common = new Common($dbFrameWork);

// monitor website
$Common->monitorEachSite();
?>
