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
<title>Admin Report Result</title>
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
	
	if($_GET['VendorSubmit']) { 
	 	  
		if($_GET['vendor_id']) {
			$vendor_id = $_GET['vendor_id'];
		} else {
			$vendor_id = "%";
		}
		
		if($_GET['employer_id']) {
			$employer_id = $_GET['employer_id'];
		} else {
			$employer_id = "%";
		}
		if($_GET['plan_id']) {
			$plan_id = $_GET['plan_id'];
		} else {
			$plan_id = "%";
		}
		
				
				$query = "select sum(ec.sra_pretax) as pretax, sum(ec.sra_roth) as roth, vp.plan_name, ec.vendor_id, ec.employer_id from employee_contribution as ec, vendor_plan as vp Where ec.plan_id like '".$plan_id."' and ec.employer_id like '".$employer_id."' and ec.vendor_id like '".$vendor_id."' and ec.plan_id = vp.plan_id";
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
				$query .= " AND ec.contribution_date between '".$f."' and '".$t."' GROUP BY ec.plan_id ORDER BY ec.vendor_id, ec.employer_id, ec.plan_id";
				$rsPlan = mysql_query($query) or die('error in plan'.mysql_error());
	?>
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Search Result :: Vendor Report</font></td>
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
    
<?php } ?>
    <?php if($_GET['EmployerSubmit']) { 
				if($_GET['employer_id2']) {
			$employer_id = $_GET['employer_id2'];
		} else {
			$employer_id = "%";
		}
		
		if($_GET['plan_id2']) {
			$plan_id = $_GET['plan_id2'];
		} else {
			$plan_id = "%";
		}
			$query = "select sum(ec.sra_pretax) as pretax, sum(ec.sra_roth) as roth, vp.plan_name, ec.vendor_id, ec.employer_id from employee_contribution as ec, vendor_plan as vp Where ec.plan_id like '".$plan_id."' and ec.employer_id like '".$employer_id."' and ec.plan_id = vp.plan_id";
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
				$query .= " AND ec.contribution_date between '".$f."' and '".$t."' GROUP BY vp.plan_id ORDER BY ec.employer_id, ec.plan_id";
				$rsPlan = mysql_query($query) or die('error in plan'.mysql_error());
		
	?>
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Search Result :: Employer Report</font></td>
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
        <td class="thcview2">Employer</td>
        <td class="thcview2">Plans</td>
        <td class="thcview2">SRA Pretax</td>
        <td class="thcview2">SRA Roth</td>
        </tr>
		<?php
		
					while($recPlan = mysql_fetch_array($rsPlan)) {
		?>
				
      <tr>
        <td class="tdcview2"><?php $TFM_nest2 = $recPlan['employer_id']; if ($lastTFM_nest2 != $TFM_nest2) { $lastTFM_nest2 = $TFM_nest2; $employerDetails = getEmployerDetails($recPlan['employer_id']); echo $employerDetails['name']; } //End of Basic-UltraDev Simulated Nested Repeat?>&nbsp;</td>
        <td class="tdcview2"><?php $TFM_nest3 = $recPlan['plan_name']; if ($lastTFM_nest3 != $TFM_nest3) { $lastTFM_nest3 = $TFM_nest3; echo $recPlan['plan_name']; } //End of Basic-UltraDev Simulated Nested Repeat?>&nbsp;</td>
        <td class="tdcview2"><?php echo $recPlan['pretax']; ?>&nbsp;</td>
        <td class="tdcview2"><?php echo $recPlan['roth']; ?>&nbsp;</td>
        </tr>
		<?php
			}
		?>
    </table>				
		<?php
			}
		?>
      </td>
    </tr>
</table>
<br />
    
<?php } ?>
    <?php if($_GET['EmployeeSubmit']) {	
			if($_GET['plan_id3']) {
				$plan_id = $_GET['plan_id3'];
			} else {
				$plan_id = "%";
			} 
			if($_GET['employee_id']) {
				$employee_id = $_GET['employee_id'];
			} else {
				$employee_id = "%";
			} 
			$query = "select sum(ec.sra_pretax) as pretax, sum(ec.sra_roth) as roth, vp.plan_name, ec.employee_id, ec.vendor_id, ec.employer_id from employee_contribution as ec, vendor_plan as vp Where ec.plan_id like '".$plan_id."' and ec.employee_id like '".$employee_id."' and ec.plan_id = vp.plan_id";
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
				$query .= " AND ec.contribution_date between '".$f."' and '".$t."' GROUP BY vp.plan_id order by ec.employee_id, ec.plan_id";
				$rsPlan = mysql_query($query) or die('error in plan'.mysql_error());
	?>
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Search Result :: Employee Report</font></td>
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
        <td class="thcview2">Employee</td>
        <td class="thcview2">Plan</td>
        <td class="thcview2">SRA Pretax</td>
        <td class="thcview2">SRA Roth</td>
      </tr>
      <?php
			
					while($recPlan = mysql_fetch_array($rsPlan)) {
					?>
					
      <tr>
        <td class="tdcview2"><?php $TFM_nest4 = $recPlan['employee_id']; if ($lastTFM_nest4 != $TFM_nest4) { $lastTFM_nest4 = $TFM_nest4; $returnEmployee = getEmployeeDetails($recPlan['employee_id']); echo $encryption->processDecrypt('ssn', $returnEmployee['ssn']); } //End of Basic-UltraDev Simulated Nested Repeat?>&nbsp;</td>
        <td class="tdcview2"><?php $TFM_nest3 = $recPlan['plan_name']; if ($lastTFM_nest3 != $TFM_nest3) { $lastTFM_nest3 = $TFM_nest3; echo $recPlan['plan_name']; } //End of Basic-UltraDev Simulated Nested Repeat?>&nbsp;</td>
        <td class="tdcview2"><?php echo $recPlan['pretax']; ?>&nbsp;</td>
        <td class="tdcview2"><?php echo $recPlan['roth']; ?>&nbsp;</td>
      </tr>
      <?php } ?>
    </table>
      <?php } ?>
      </td>
    </tr>
</table>
<br />
    
<?php } ?>
<p>&nbsp;</p>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body>
<!-- InstanceEnd --></html>