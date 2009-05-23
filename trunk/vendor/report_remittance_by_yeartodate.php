<?php require_once('../Connections/dw_conn.php'); ?>
<?php
include_once('start.php');
function getEmployeeDetails($employee_id) {
	$sql = "select * from employee where employee_id = '".$employee_id."'";
	$rs = mysql_query($sql) or die('error');
	$rec = mysql_fetch_array($rs);
	return $rec;
}
function getVendorDetails($vendor_id) {
	$sql = "select * from vendor where vendor_id = '".$vendor_id."'";
	$rs = mysql_query($sql) or die('error');
	$rec = mysql_fetch_array($rs);
	return $rec;
}
function getEmployerDetails($employer_id) {
	$sql = "select * from employer where employer_id = '".$employer_id."'";
	$rs = mysql_query($sql) or die('error');
	$rec = mysql_fetch_array($rs);
	return $rec;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Vendor :: Remittance By Year To Date</title>
<!-- InstanceEndEditable -->
<?php include('css.php'); ?>
<?php include('js.php'); ?>
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>

<body>
<?php include('head.php'); ?>
<!-- InstanceBeginEditable name="EditRegion3" -->
<?php 
	 	  
			$vendor_id = $_COOKIE['vendor']['vendor_id'];
			$employer_id = '%';
			$plan_id = "%";		
				
				$query = "select sum(ec.sra_pretax) as pretax, sum(ec.sra_roth) as roth, vp.plan_name, ec.vendor_id, ec.employer_id from employee_contribution as ec, vendor_plan as vp Where ec.plan_id like '".$plan_id."' and ec.employer_id like '".$employer_id."' and ec.vendor_id like '".$vendor_id."' and ec.plan_id = vp.plan_id";
					$f = date('Y')."-01-01";
					$t = date('Y-m-d');
				$query .= " AND ec.contribution_date between '".$f."' and '".$t."' GROUP BY ec.plan_id ORDER BY ec.vendor_id, ec.employer_id, ec.plan_id";
				$rsPlan = mysql_query($query) or die('error in plan'.mysql_error());
	?>
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Remittance By Year To Date</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
	  	<?php
	  		if(mysql_num_rows($rsPlan)==0) {
				echo 'no record found';
			} else {
		?>
<table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
      <tr>
        <td class="thcview2">Vendor</td>
        <td class="thcview2">Employer</td>
        <td class="thcview2">Plans</td>
        <td class="thcview2">SRA Pretax</td>
        <td class="thcview2">SRA Roth</td>
        </tr>
		<?php
					while($recPlan = mysql_fetch_array($rsPlan)) {
					
	  ?>
      <tr>
        <td class="tdcview2"><?php $TFM_nest1 = $recPlan['vendor_id']; if ($lastTFM_nest1 != $TFM_nest1) { $lastTFM_nest1 = $TFM_nest1; $vendorDetails = getVendorDetails($recPlan['vendor_id']); echo $vendorDetails['name']; } //End of Basic-UltraDev Simulated Nested Repeat?></td>
        <td class="tdcview2"><?php $TFM_nest2 = $recPlan['employer_id']; if ($lastTFM_nest2 != $TFM_nest2) { $lastTFM_nest2 = $TFM_nest2; $employerDetails = getEmployerDetails($recPlan['employer_id']); echo $employerDetails['name']; } //End of Basic-UltraDev Simulated Nested Repeat?>&nbsp;</td>
        <td class="tdcview2"><?php $TFM_nest3 = $recPlan['plan_name']; if ($lastTFM_nest3 != $TFM_nest3) { $lastTFM_nest3 = $TFM_nest3; echo $recPlan['plan_name']; } //End of Basic-UltraDev Simulated Nested Repeat?>&nbsp;</td>
        <td class="tdcview2"><?php echo $recPlan['pretax']; ?>&nbsp;</td>
        <td class="tdcview2"><?php echo $recPlan['roth']; ?>&nbsp;</td>
        </tr>
      <?php }  ?>
    </table>
		<?php } ?>
      </td>
    </tr>
</table>
<br />    
<p>&nbsp;</p>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body>
<!-- InstanceEnd --></html>