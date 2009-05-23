<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
header("Cache-Control: no-cache, must-revalidate" );
header("Pragma: no-cache" );
?>
<?php
include_once('dw_conn.php');
include_once('start.php');
include_once('functions.php');
include_once('import_functions.php');
showTransactionTable($_GET['transaction_id']);
?>