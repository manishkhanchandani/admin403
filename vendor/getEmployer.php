<?php require_once('../Connections/dw_conn.php'); ?>
<?php header("Content-Type: text/xml; charset=utf-8"); ?>
<?php
$colname_rsRecord = "-1";
if (isset($_GET['plan_id'])) {
  $colname_rsRecord = (get_magic_quotes_gpc()) ? $_GET['plan_id'] : addslashes($_GET['plan_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsRecord = sprintf("SELECT e.employer_id, e.name FROM employer_vendor as ev, employer as e WHERE ev.employer_id = e.employer_id and ev.plan_id = %s", $colname_rsRecord);
$rsRecord = mysql_query($query_rsRecord, $dw_conn) or die(mysql_error());
$row_rsRecord = mysql_fetch_assoc($rsRecord);
$totalRows_rsRecord = mysql_num_rows($rsRecord);
?>
<?php
$xml = '<?xml version="1.0" ?>
<root>'; 
if($totalRows_rsRecord) {
	do { 
		$id = $row_rsRecord['employer_id'];
		$xml .= '<message id="' . $row_rsRecord['employer_id'] . '">'; 
		$xml .= '<id>' . $row_rsRecord['employer_id'] . '</id>';
		$xml .= '<name>' . htmlspecialchars($row_rsRecord['name']) . '</name>'; 
		$xml .= '</message>'; 
	} while ($row_rsRecord = mysql_fetch_assoc($rsRecord)); 
}
$xml .= '</root>'; 
echo $xml; 
?>
<?php
mysql_free_result($rsRecord);
?>