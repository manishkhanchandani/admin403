<?php
class logincookie {
	public function __construct($arrays, $var) {
		if($arrays) {
			foreach($arrays as $key => $value) {
				setcookie("user[$var][$key]", $value, 0, "/");
			}
		}
	}
}
?>