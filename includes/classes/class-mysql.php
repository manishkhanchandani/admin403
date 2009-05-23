<?php 
class mysql {
	public $conn;
	public $u = "employer";
	public $p = "employer";
	public $d = "employer";
	public $h = "mysql1027.servage.net";	
	
	public function __construct() {
		$conn = mysql_connect($this->h,$this->u,$this->p) or die("could not connect");
		mysql_select_db($this->d, $conn) or die("could not select db");
	}
}
?>