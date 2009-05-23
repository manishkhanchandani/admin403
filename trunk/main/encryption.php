<?php
require_once 'Crypt/Blowfish.php';
function convert($hexString) {
	$hexLenght = strlen($hexString);
	// only hex numbers is allowed
	if ($hexLenght % 2 != 0 || preg_match("/[^\da-fA-F]/",$hexString)) return FALSE;
	unset($binString);
	for ($x = 1; $x <= $hexLenght/2; $x++) {
		$binString .= chr(hexdec(substr($hexString,2 * $x - 2,2)));
	}
	return $binString;
} 
function convertText($text, $type="e", $key="") {
	if(!$key) $key = 'JKjVXtFdY3NNT6Fp6U9uM3m5eeWbtqXWrR5qwWpyM9b8SFSdWVK2vruN';
	$bf = new Crypt_Blowfish($key);
	if($type=="d") {
		$plaintext = $bf->decrypt(convert(trim($text)));
		return trim($plaintext);
	} else {
		$encrypted = $bf->encrypt($text);	
		return bin2hex($encrypted);	
	}
}
$ret = convertText($_GET['text'], $_GET['type'], $_GET['key']);
?>