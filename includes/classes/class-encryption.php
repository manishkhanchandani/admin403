<?php
require_once 'Crypt/Blowfish.php';
class encryption {	
	private $fields = array('ssn', 'account_number');
	public function convert($hexString) {
		$hexLenght = strlen($hexString);
		// only hex numbers is allowed
		if ($hexLenght % 2 != 0 || preg_match("/[^\da-fA-F]/",$hexString)) return FALSE;
		unset($binString);
		for ($x = 1; $x <= $hexLenght/2; $x++) {
			$binString .= chr(hexdec(substr($hexString,2 * $x - 2,2)));
		}
		return $binString;
	} 
	public function convertText($text, $type="e", $key="") {
		if(!$key) $key = 'JKjVXtFdY3NNT6Fp6U9uM3m5eeWbtqXWrR5qwWpyM9b8SFSdWVK2vruN';
		$bf = new Crypt_Blowfish($key);
		$this->bf = $bf;
	}
	public function convertText2($text, $type="e", $key="") {
		if(!$this->bf) $this->convertText($text, $typ, $key);
		if($type=="d") {
			$plaintext = trim($this->bf->decrypt($this->convert(trim($text))));
			return $plaintext;
		} else {
			$encrypted = $this->bf->encrypt($text);
			$temp = bin2hex($encrypted);
			return $temp;	
		}
	}
	public function processEncrypt($k, $field) {
		if(in_array($k, $this->fields)) {
			if($field) $field = $this->convertText2($field, 'e', ENCKEY);
		}
		return $field;
	}
	public function processDecrypt($k, $field) {
		if(in_array($k, $this->fields)) {
			if($field) $field = $this->convertText2($field, 'd', ENCKEY);
		}
		return $field;
	}
}
/* USAGE */
/*
$encryption = new encryption;
$ret = $encryption->convertText($_GET['text'], $_GET['type'], $_GET['key']);
echo $ret;
*/
?>
