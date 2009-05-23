<?php
class Email {
	function emailvalidity($email) {
		if (eregi('^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$', $email)) {
			// this is a valid email domain!
			return 1;
		} else {
			// this email domain doesn't exist! bad dog! no biscuit!
			return 0;
		}
	}
}
?>