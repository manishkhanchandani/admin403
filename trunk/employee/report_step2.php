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
<?php
if($_GET['report_view']==1) {	
	$employee_id = $_COOKIE['employee']['employee_id'];
	if($_GET['plan_id']) {
		$plan_id = $_GET['plan_id'];
	} else {
		$plan_id = "%";
	}
	$query = "select sum(ec.sra_pretax) as pretax, sum(ec.sra_roth) as roth, vp.plan_name, ec.employee_id, ec.vendor_id, ec.employer_id, ec.contribution_date from employee_contribution as ec, vendor_plan as vp Where ec.plan_id like '".$plan_id."' and ec.employee_id like '".$employee_id."' and ec.plan_id = vp.plan_id";
	if($_GET['fromDate']) {
		$f = $_GET['fromDate'];
	} else {
		$f = date('Y')."-01-01";
	}
	if($_GET['toDate']) {
		$t = $_GET['toDate'];
	} else {
		$t = date('Y-m-d');
	}			
	$query .= " AND ec.contribution_date between '".$f."' and '".$t."' GROUP BY vp.plan_id ORDER BY ec.employee_id, ec.plan_id";
	$rsPlan = mysql_query($query) or die('error in plan'.mysql_error());
	if(mysql_num_rows($rsPlan)>0) {
		while($rowPlan = mysql_fetch_array($rsPlan)) {
			$return[] = $rowPlan;
		}
	} else {
		$return = NULL;
	}
} else if($_GET['report_view']==2){	
	$employee_id = $_COOKIE['employee']['employee_id'];
	if($_GET['plan_id']) {
		$plan_id = $_GET['plan_id'];
	} else {
		$plan_id = "%";
	}
	$query = "select ec.sra_pretax as pretax, ec.sra_roth as roth, vp.plan_name, ec.employee_id, ec.vendor_id, ec.employer_id, ec.contribution_date from employee_contribution as ec, vendor_plan as vp Where ec.plan_id like '".$plan_id."' and ec.employee_id like '".$employee_id."' and ec.plan_id = vp.plan_id";
	if($_GET['fromDate']) {
		$f = $_GET['fromDate'];
	} else {
		$f = date('Y')."-01-01";
	}
	if($_GET['toDate']) {
		$t = $_GET['toDate'];
	} else {
		$t = date('Y-m-d');
	}			
	$query .= " AND ec.contribution_date between '".$f."' and '".$t."' ORDER BY ec.employee_id, ec.plan_id";
	$rsPlan = mysql_query($query) or die('error in plan'.mysql_error());
	if(mysql_num_rows($rsPlan)>0) {
		while($rowPlan = mysql_fetch_array($rsPlan)) {
			$return[] = $rowPlan;
		}
	} else {
		$return = NULL;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Contribution History</title>
<!-- InstanceEndEditable -->
<?php include('css.php'); ?>
<?php include('js.php'); ?>
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>

<body>
<?php include('head.php'); ?>
<!-- InstanceBeginEditable name="EditRegion3" -->
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Contribution History</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
	  	<?php if($return) { ?>
			<table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
				<tr>
					<td class="thcview2">Employee</td>
					<td class="thcview2"><?php echo DISPLAYPLANNAME;?></td>
					<?php if($_GET['report_view']==2) { ?>
					<td class="thcview2">Date</td>
					<?php } ?>
					<td class="thcview2">SRA Pretax</td>
					<td class="thcview2">SRA Roth</td>
				</tr>	
				<?php foreach($return as $key => $value) { ?>  
				<tr>
					<td class="tdcview2"><?php $TFM_nest4 = $value['employee_id']; if ($lastTFM_nest4 != $TFM_nest4) { $lastTFM_nest4 = $TFM_nest4; $returnEmployee = getEmployeeDetails($value['employee_id']); echo $encryption->processDecrypt('ssn', $returnEmployee['ssn']); } //End of Basic-UltraDev Simulated Nested Repeat?>&nbsp;</td>
					<td class="tdcview2"><?php $TFM_nest3 = $value['plan_name']; if ($lastTFM_nest3 != $TFM_nest3) { $lastTFM_nest3 = $TFM_nest3; echo $value['plan_name']; } //End of Basic-UltraDev Simulated Nested Repeat?>&nbsp;</td>
					<?php if($_GET['report_view']==2) { ?>
					<td class="tdcview2"><?php echo $value['contribution_date']; ?>&nbsp;</td>
					<?php } ?>
					<td class="tdcview2"><?php echo $value['pretax']; ?>&nbsp;</td>
					<td class="tdcview2"><?php echo $value['roth']; ?>&nbsp;</td>
				</tr>
				<?php } ?>
			</table>
	  	<?php } else { ?>
		No Record Found.
		<?php } ?>
	  </td>
	</tr>
</table>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body>
<!-- InstanceEnd --></html>