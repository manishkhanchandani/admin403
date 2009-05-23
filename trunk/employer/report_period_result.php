<?php require_once('../Connections/dw_conn.php'); ?>
<?php
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
<title>Report Result</title>
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

			$employer_id = $_COOKIE['employer']['employer_id'];
			$plan_id = "%";
			$query = "select sum(ec.sra_pretax) as pretax, sum(ec.sra_roth) as roth, vp.plan_name, ec.vendor_id, ec.employer_id, ec.contribution_date from employee_contribution as ec, vendor_plan as vp Where ec.plan_id like '".$plan_id."' and ec.employer_id like '".$employer_id."' and ec.plan_id = vp.plan_id";
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
				$query .= " AND ec.contribution_date between '".$f."' and '".$t."' GROUP BY ec.contribution_date ORDER BY ec.contribution_date, ec.employer_id, ec.vendor_id, ec.plan_id";
				$rsPlan = mysql_query($query) or die('error in plan'.mysql_error());
				?>
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Remittance Per Period Results</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
      <tr>
        <td class="thcview2">Date</td>
        <td class="thcview2">Employer</td>
        <td class="thcview2">Vendor</td>
        <td class="thcview2">Plans</td>
        <td class="thcview2">SRA Pretax</td>
        <td class="thcview2">SRA Roth</td>
        </tr>
		<?php
		
					while($recPlan = mysql_fetch_array($rsPlan)) {
		?>
      <tr>
        <td class="tdcview2"><?php echo date('M y', strtotime($recPlan['contribution_date'])); ?>&nbsp;</td>
        <td class="tdcview2"><?php $TFM_nest2 = $recPlan['employer_id']; if ($lastTFM_nest2 != $TFM_nest2) { $lastTFM_nest2 = $TFM_nest2; $employerDetails = getEmployerDetails($recPlan['employer_id']); echo $employerDetails['name']; } //End of Basic-UltraDev Simulated Nested Repeat?>&nbsp;</td>
        <td class="tdcview2"><?php $TFM_nest4 = $recPlan['vendor_id']; if ($lastTFM_nest4 != $TFM_nest4) { $lastTFM_nest4 = $TFM_nest4; $v = getVendorDetails($recPlan['vendor_id']);  echo $v['name']; } //End of Basic-UltraDev Simulated Nested Repeat?>&nbsp;</td>
        <td class="tdcview2"><?php $TFM_nest3 = $recPlan['plan_name']; if ($lastTFM_nest3 != $TFM_nest3) { $lastTFM_nest3 = $TFM_nest3; echo $recPlan['plan_name']; } //End of Basic-UltraDev Simulated Nested Repeat?>&nbsp;</td>
        <td class="tdcview2"><?php echo $recPlan['pretax']; ?>&nbsp;</td>
        <td class="tdcview2"><?php echo $recPlan['roth']; ?>&nbsp;</td>
        </tr>
		<?php
			}
		?>
    </table>
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