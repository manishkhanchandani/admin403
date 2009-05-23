<?php require_once('../Connections/dw_conn.php'); ?>
<?php
header("Content-Type: text/html;charset=iso-8859-1"); 
/* Demo file for the chained select module - created by Batur Orkun */
		
$query = "select p.plan_name, p.plan_id, v.name from employer_vendor as ev, vendor_plan as p, vendor as v WHERE ev.plan_id = p.plan_id and ev.employer_id = '".$_GET['dg_key']."' and p.vendor_id = v.vendor_id order by v.name, p.plan_name";
$rs1 = mysql_query($query) or die(1);
if(mysql_num_rows($rs1)) {
while($rec1 = mysql_fetch_array($rs1)) {
	$field[] = $rec1['plan_name']."(".$rec1['name'].")".";".$rec1['plan_id'];
}
echo implode(' | ', $field);
} else {
	echo 'no item';
}

?> 