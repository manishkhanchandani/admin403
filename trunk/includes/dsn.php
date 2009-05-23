<?php
require_once 'MDB2.php';
if($_SERVER['HTTP_HOST']=="localhost") {
	$dsn = array(
		'phptype'  => 'mysql',
		'username' => 'user',
		'password' => 'password',
		'hostspec' => 'localhost',
		'database' => 'employer',
	);
} else {
	$dsn = array(
		'phptype'  => 'mysql',
		'username' => 'employer',
		'password' => 'employer',
		'hostspec' => 'mysql1027.servage.net',
		'database' => 'employer',
	);
}
$options = array(
    'debug'       => 2,
    'portability' => MDB2_PORTABILITY_ALL,
);

// uses MDB2::factory() to create the instance
// and also attempts to connect to the host
$mdb2 =& MDB2::connect($dsn, $options);

if (PEAR::isError($mdb2)) {
    die($mdb2->getMessage());
}
?>