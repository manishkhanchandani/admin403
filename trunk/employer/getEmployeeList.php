<?php require_once('../Connections/dw_conn.php'); 

header("Content-Type: text/xml; charset=utf-8"); 

$colname_rsEmployee = "-1";
if (isset($_GET['employer_id'])) {
  $colname_rsEmployee = (get_magic_quotes_gpc()) ? $_GET['employer_id'] : addslashes($_GET['employer_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployee = sprintf("SELECT * FROM employee WHERE employer_id = %s", $colname_rsEmployee);
$rsEmployee = mysql_query($query_rsEmployee, $dw_conn) or die(mysql_error());
$row_rsEmployee = mysql_fetch_assoc($rsEmployee);
$totalRows_rsEmployee = mysql_num_rows($rsEmployee);


$xml = '<?xml version="1.0" ?>
<root>'; 
if($totalRows_rsEmployee) {
	do { 
		$id = $row_rsEmployee['employee_id'];
		$xml .= '<message id="' . $row_rsEmployee['employee_id'] . '">'; 
		$xml .= '<id>' . $row_rsEmployee['employee_id'] . '</id>';
		$xml .= '<name>' . htmlspecialchars($row_rsEmployee['firstname']) . '</name>'; 
		$xml .= '</message>'; 
	} while ($row_rsEmployee = mysql_fetch_assoc($rsEmployee)); 
}
$xml .= '</root>'; 
echo $xml; 

mysql_free_result($rsEmployee);
?>
