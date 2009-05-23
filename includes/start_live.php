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
?>
