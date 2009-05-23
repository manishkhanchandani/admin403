<?php require_once('../Connections/dw_conn.php'); ?>
<?php
header("Content-Type: text/html;charset=iso-8859-1"); 
/* Demo file for the chained select module - created by Batur Orkun */
		
$query = "select * from employer_vendor as ev, vendor_plan as p, employer as e WHERE ev.plan_id = p.plan_id and p.vendor_id = '".$_GET['dg_key']."' and ev.employer_id = e.employer_id GROUP BY ev.employer_id";
$rs1 = mysql_query($query) or die(1);
if(mysql_num_rows($rs1)) {
while($rec1 = mysql_fetch_array($rs1)) {
	$field[] = $rec1['name'].";".$rec1['employer_id'];
}
echo implode(' | ', $field);
} else {
	echo 'no item';
}

?>