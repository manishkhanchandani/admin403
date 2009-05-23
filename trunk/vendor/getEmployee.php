<?php require_once('../Connections/dw_conn.php'); ?>
<?php header("Content-Type: text/xml; charset=utf-8");
include_once('start.php'); ?>
<?php
$colname_rsRecord = "-1";
if (isset($_GET['plan_id'])) {
  $colname_rsRecord = (get_magic_quotes_gpc()) ? $_GET['plan_id'] : addslashes($_GET['plan_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsRecord = sprintf("SELECT e.employee_id, e.firstname FROM employee_vendor as ev, employee as e WHERE ev.employee_id = e.employee_id and ev.plan_id = %s", $colname_rsRecord);
$rsRecord = mysql_query($query_rsRecord, $dw_conn) or die(mysql_error());
$row_rsRecord = mysql_fetch_assoc($rsRecord);
$totalRows_rsRecord = mysql_num_rows($rsRecord);
?>
<?php
$xml = '<?xml version="1.0" ?>
<root>'; 
if($totalRows_rsRecord) {
	do { 
		$id = $row_rsRecord['employee_id'];
		$xml .= '<message id="' . $row_rsRecord['employee_id'] . '">'; 
		$xml .= '<id>' . $row_rsRecord['employee_id'] . '</id>';
		$xml .= '<name>' . htmlspecialchars($row_rsRecord['firstname']) . '</name>'; 
		$xml .= '</message>'; 
	} while ($row_rsRecord = mysql_fetch_assoc($rsRecord)); 
}
$xml .= '</root>'; 
echo $xml; 
?>
<?php
mysql_free_result($rsRecord);
?>