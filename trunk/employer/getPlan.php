<?php require_once('../Connections/dw_conn.php'); ?>
<?php header("Content-Type: text/xml; charset=utf-8"); ?>
<?php
$colname_rsEmployerPlan = "-1";
if (isset($_COOKIE['employer']['employer_id'])) {
  $colname_rsEmployerPlan = (get_magic_quotes_gpc()) ? $_COOKIE['employer']['employer_id'] : addslashes($_COOKIE['employer']['employer_id']);
}
$colvendor_rsEmployerPlan = "-1";
if (isset($_GET['vendor_id'])) {
  $colvendor_rsEmployerPlan = (get_magic_quotes_gpc()) ? $_GET['vendor_id'] : addslashes($_GET['vendor_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployerPlan = sprintf("SELECT p.plan_id, p.plan_name, p.vendor_id FROM employer_vendor as ev, vendor_plan as p WHERE ev.plan_id = p.plan_id and ev.employer_id = %s AND p.vendor_id = %s GROUP BY p.plan_id", $colname_rsEmployerPlan,$colvendor_rsEmployerPlan);
$rsEmployerPlan = mysql_query($query_rsEmployerPlan, $dw_conn) or die(mysql_error());
$row_rsEmployerPlan = mysql_fetch_assoc($rsEmployerPlan);
$totalRows_rsEmployerPlan = mysql_num_rows($rsEmployerPlan);
?>
<?php
$xml = '<?xml version="1.0" ?>
<root>'; 
if($totalRows_rsEmployerPlan) {
	do { 
		$id = $row_rsEmployerPlan['plan_id'];
		$xml .= '<message id="' . $row_rsEmployerPlan['plan_id'] . '">'; 
		$xml .= '<id>' . $row_rsEmployerPlan['plan_id'] . '</id>';
		$xml .= '<name>' . htmlspecialchars($row_rsEmployerPlan['plan_name']) . '</name>'; 
		$xml .= '</message>'; 
	} while ($row_rsEmployerPlan = mysql_fetch_assoc($rsEmployerPlan)); 
}
$xml .= '</root>'; 
echo $xml; 
?>
<?php
mysql_free_result($rsEmployerPlan);
?>