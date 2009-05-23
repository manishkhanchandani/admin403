<?php require_once('../Connections/dw_conn.php'); ?>
<?php
header("Content-Type: text/html;charset=iso-8859-1"); 
/* Demo file for the chained select module - created by Batur Orkun */
		
$query = "select * from employee_vendor as ev, vendor_plan as p WHERE ev.plan_id = p.plan_id and ev.employee_id = '".$_GET['dg_key']."'";
$rs1 = mysql_query($query) or die(1);
if(mysql_num_rows($rs1)) {
while($rec1 = mysql_fetch_array($rs1)) {
	$field[] = $rec1['plan_name'].";".$rec1['plan_id'];
}
echo implode(' | ', $field);
} else {
	echo 'no item';
}

?> 