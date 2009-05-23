<?php
class logoutcookie2 {
	public function __construct($arrays, $var, $parent) {
		if($arrays) {
			foreach($arrays as $key => $value) {
				setcookie("$parent[$var][$key]", "", 0, "/");
			}
		}
	}
}
?>