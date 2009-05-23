<?php require_once('../Connections/dw_conn.php'); ?>
<?php header("Content-Type: text/xml; charset=utf-8"); ?>
<?php
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployerPlan = "SELECT e.name, e.employer_id FROM employer as e";
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