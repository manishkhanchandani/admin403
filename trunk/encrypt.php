
include_once('start.php');
$encryption = new encryption;

foreach($_POST as $key => $value) {
	$_POST[$key] = $encryption->processEncrypt($key, $value);
}

foreach($_POST as $key => $value) {
	$_POST[$key] = $encryption->processDecrypt($key, $value);
}