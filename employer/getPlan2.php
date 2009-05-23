<?php require_once('../Connections/dw_conn.php'); ?>
<?php header("Content-Type: text/xml; charset=utf-8"); ?>
<?php
$colname_rsEmployeePlan = "-1";
if (isset($_GET['employee_id'])) {
  $colname_rsEmployeePlan = (get_magic_quotes_gpc()) ? $_GET['employee_id'] : addslashes($_GET['employee_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployeePlan = sprintf("SELECT p.plan_name, p.plan_id FROM employee_vendor as ev, vendor_plan as p WHERE ev.plan_id = p.plan_id and ev.employee_id = %s", $colname_rsEmployeePlan);
$rsEmployeePlan = mysql_query($query_rsEmployeePlan, $dw_conn) or die(mysql_error());
$row_rsEmployeePlan = mysql_fetch_assoc($rsEmployeePlan);
$totalRows_rsEmployeePlan = mysql_num_rows($rsEmployeePlan);
?>
<?php
$xml = '<?xml version="1.0" ?>
<root>'; 
if($totalRows_rsEmployeePlan) {
	do { 
		$id = $row_rsEmployeePlan['plan_id'];
		$xml .= '<message id="' . $row_rsEmployeePlan['plan_id'] . '">'; 
		$xml .= '<id>' . $row_rsEmployeePlan['plan_id'] . '</id>';
		$xml .= '<name>' . htmlspecialchars($row_rsEmployeePlan['plan_name']) . '</name>'; 
		$xml .= '</message>'; 
	} while ($row_rsEmployeePlan = mysql_fetch_assoc($rsEmployeePlan)); 
}
$xml .= '</root>'; 
echo $xml; 
?>
<?php
mysql_free_result($rsEmployeePlan);
?>