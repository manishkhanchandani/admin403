<?php
function middleware($text, $type="e") {
	$encryption = new encryption;
	$ret = $encryption->convertText("manish", $_GET['type'], $_GET['key']);
}
?>