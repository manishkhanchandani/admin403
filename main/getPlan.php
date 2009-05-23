<?php require_once('dw_conn.php'); ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
header("Cache-Control: no-cache, must-revalidate" );
header("Pragma: no-cache" );
header("Content-Type: text/xml; charset=utf-8"); 
?>
<?php
$query = "select ev.plan_id as id, p.plan_name as name, v.name as vendorname from employer_vendor as ev, vendor_plan as p, vendor as v WHERE ev.plan_id = p.plan_id and ev.vendor_id = v.vendor_id and ev.employer_id = '".$_GET['employer_id']."'";
$rs = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_assoc($rs);
$totalRows = mysql_num_rows($rs);
?>
<?php
$xml = '<?xml version="1.0" ?>
<root>'; 
if($totalRows) {
	do { 
		$id = $row['id'];
		$xml .= '<message id="' . $row['id'] . '">'; 
		$xml .= '<id>' . $row['id'] . '</id>';
		$xml .= '<name>' . htmlspecialchars($row['vendorname']." (".$row['name'].")") . '</name>'; 
		$xml .= '</message>'; 
	} while ($row = mysql_fetch_assoc($rs)); 
}
$xml .= '</root>'; 
echo $xml; 
?>
<?php
mysql_free_result($rs);
?>