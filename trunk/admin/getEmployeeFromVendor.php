<?php require_once('../Connections/dw_conn.php'); ?>
<?php header("Content-Type: text/xml; charset=utf-8");
include_once('start.php'); ?>
<?php
$colname_rsEmployerPlan = "-1";
if (isset($_GET['dg_key'])) {
  $colname_rsEmployerPlan = (get_magic_quotes_gpc()) ? $_GET['dg_key'] : addslashes($_GET['dg_key']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployerPlan = sprintf("SELECT employee.employee_id, employee.email, employee.firstname, employee.ssn, employee.employer_id, employee.lastname FROM employee, employee_vendor as ev WHERE ev.vendor_id = %s and ev.employee_id = employee.employee_id", $colname_rsEmployerPlan);
$rsEmployerPlan = mysql_query($query_rsEmployerPlan, $dw_conn) or die(mysql_error());
$row_rsEmployerPlan = mysql_fetch_assoc($rsEmployerPlan);
$totalRows_rsEmployerPlan = mysql_num_rows($rsEmployerPlan);
?>
<?php
$xml = '<?xml version="1.0" ?>
<root>'; 
if($totalRows_rsEmployerPlan) {
	do { 
		$id = $row_rsEmployerPlan['employee_id'];
		$xml .= '<message id="' . $row_rsEmployerPlan['employee_id'] . '">'; 
		$xml .= '<id>' . $row_rsEmployerPlan['employee_id'] . '</id>';
		$xml .= '<name>' . htmlspecialchars($row_rsEmployerPlan['lastname'].', '.$row_rsEmployerPlan['firstname'].' / '.$encryption->processDecrypt('ssn', $row_rsEmployerPlan['ssn'])) . '</name>'; 
		$xml .= '</message>'; 
	} while ($row_rsEmployerPlan = mysql_fetch_assoc($rsEmployerPlan)); 
}
$xml .= '</root>'; 
echo $xml; 
?>
<?php
mysql_free_result($rsEmployerPlan);
?>