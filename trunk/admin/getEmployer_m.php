<?php require_once('../Connections/dw_conn.php'); ?>
<?php header("Content-Type: text/xml; charset=utf-8"); ?>
<?php
$colname_rsEmployerPlan = "-1";
if (isset($_GET['dg_key'])) {
  $colname_rsEmployerPlan = (get_magic_quotes_gpc()) ? $_GET['dg_key'] : addslashes($_GET['dg_key']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployerPlan = sprintf("SELECT e.name, e.employer_id FROM employer_vendor as ev, vendor_plan as p, employer as e WHERE ev.plan_id = p.plan_id AND p.vendor_id = %s AND ev.employer_id = e.employer_id GROUP BY ev.employer_id", $colname_rsEmployerPlan);
$rsEmployerPlan = mysql_query($query_rsEmployerPlan, $dw_conn) or die(mysql_error());
$row_rsEmployerPlan = mysql_fetch_assoc($rsEmployerPlan);
$totalRows_rsEmployerPlan = mysql_num_rows($rsEmployerPlan);
?>
<?php
$xml = '<?xml version="1.0" ?>
<root>'; 
if($totalRows_rsEmployerPlan) {
	do { 
		$id = $row_rsEmployerPlan['employer_id'];
		$xml .= '<message id="' . $row_rsEmployerPlan['employer_id'] . '">'; 
		$xml .= '<id>' . $row_rsEmployerPlan['employer_id'] . '</id>';
		$xml .= '<name>' . htmlspecialchars($row_rsEmployerPlan['name']) . '</name>'; 
		$xml .= '</message>'; 
	} while ($row_rsEmployerPlan = mysql_fetch_assoc($rsEmployerPlan)); 
}
$xml .= '</root>'; 
echo $xml; 
?>
<?php
mysql_free_result($rsEmployerPlan);
?>