<?php
class logincookie2 {
	public function __construct($arrays, $var, $parent) {
		if($arrays) {
			foreach($arrays as $key => $value) {
				setcookie("$parent[$var][$key]", $value, 0, "/");
			}
		}
	}
}
?>