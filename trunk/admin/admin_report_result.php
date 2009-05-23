<?php require_once('../Connections/dw_conn.php'); ?>
<?php
session_start();
include_once('start.php');
unset($_SESSION['print']);
$i=0;
$j=0;
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
		
				
				$query = "select sum(ec.sra_pretax) as pretax, sum(ec.sra_roth) as roth, v.name, ec.vendor_id, ec.employer_id, ec.contribution_date from employee_contribution as ec, vendor as v Where ec.employer_id like '".$employer_id."' and ec.vendor_id like '".$vendor_id."' and ec.vendor_id = v.vendor_id";
					$grp = " ec.vendor_id";
					$ord = "ec.vendor_id, ec.employer_id";
				if($_GET['fromDate']) {
					$f = $_GET['fromDate'];
					$grp = " ec.contribution_date, ec.vendor_id";
					$ord = " ec.contribution_date, ec.vendor_id, ec.employer_id";
				} else {
					$f = date('Y')."-01-01";
					$grp = " ec.vendor_id";
					$ord = "ec.vendor_id, ec.employer_id";
				}
				if($_GET['toDate']) {
					$t = $_GET['toDate'];
					$grp = " ec.contribution_date, ec.vendor_id";
					$ord = " ec.contribution_date, ec.vendor_id, ec.employer_id";
				} else {
					$t = date('Y-m-d');
				}				
				$query .= " AND ec.contribution_date between '".$f."' and '".$t."' GROUP BY ".$grp." ORDER BY ".$ord;
				$rsPlan = mysql_query($query) or die('error in plan'.mysql_error());
				if(mysql_num_rows($rsPlan)) {
	?>
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Search Result :: Vendor Report</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
      <tr>
        <td class="thcview2">Vendor<?php $_SESSION['print'][$i][0] = "Vendor"; $j++; ?></td>
        <td class="thcview2">Employer<?php $_SESSION['print'][$i][1] = "Employer"; $j++; ?></td>
        <?php if($_GET['fromDate'] || $_GET['toDate']) { ?>
        <td class="thcview2">Date<?php $_SESSION['print'][$i][2] = "Date"; $j++;  ?></td>
		<?php } ?>
        <td class="thcview2">SRA Pretax<?php $_SESSION['print'][$i][3] = "SRA Pretax"; $j++; ?></td>
        <td class="thcview2">SRA Roth<?php $_SESSION['print'][$i][4] = "SRA Roth"; $j++; ?></td>
        </tr>
		<?php
					while($recPlan = mysql_fetch_array($rsPlan)) {
					$j=0;
					$i++;
	  ?>
      <tr>
        <td class="tdcview2"><?php $TFM_nest1 = $recPlan['vendor_id']; if ($lastTFM_nest1 != $TFM_nest1) { $lastTFM_nest1 = $TFM_nest1; $vendorDetails = getVendorDetails($recPlan['vendor_id']); echo $vendorDetails['name']; $_SESSION['print'][$i][0] = $vendorDetails['name']; $j++; } else {$_SESSION['print'][$i][0] = "&nbsp;"; $j++; }//End of Basic-UltraDev Simulated Nested Repeat?>&nbsp;</td>
        <td class="tdcview2"><?php $TFM_nest2 = $recPlan['employer_id']; if ($lastTFM_nest2 != $TFM_nest2) { $lastTFM_nest2 = $TFM_nest2; $employerDetails = getEmployerDetails($recPlan['employer_id']); echo $employerDetails['name']; $_SESSION['print'][$i][1] = $employerDetails['name']; $j++;  } else {$_SESSION['print'][$i][1] = "&nbsp;"; $j++; }//End of Basic-UltraDev Simulated Nested Repeat?>&nbsp;</td>
        <?php if($_GET['fromDate'] || $_GET['toDate']) { ?>
        <td class="tdcview2"><?php echo $recPlan['contribution_date']; ?><?php $_SESSION['print'][$i][2] = $recPlan['contribution_date']; $j++; ?>&nbsp;</td>
		<?php } ?>
        <td class="tdcview2"><?php echo $recPlan['pretax']; ?><?php $_SESSION['print'][$i][3] = $recPlan['pretax']; $j++; ?>&nbsp;</td>
        <td class="tdcview2"><?php echo $recPlan['roth']; ?><?php $_SESSION['print'][$i][4] = $recPlan['roth']; $j++; ?>&nbsp;</td>
        </tr>
      <?php }  ?>
    </table>
      </td>
    </tr>
</table><p>
<a href="admin_report_print.php" target="_blank">Print This Page</a>&nbsp;</p>
<br />
    	<?php } else {
			echo 'No Record found.';
		}
		?>
<?php } ?>
    <?php if($_GET['EmployerSubmit']) { 
		if($_GET['employer_id2']) {
			$employer_id = $_GET['employer_id2'];
		} else {
			$employer_id = "%";
		}
		
		if($_GET['plan_id2']) {
			$vendor_id = $_GET['plan_id2'];
		} else {
			$vendor_id = "%";
		}
			$query = "select sum(ec.sra_pretax) as pretax, sum(ec.sra_roth) as roth, vp.name, ec.vendor_id, ec.employer_id, ec.contribution_date from employee_contribution as ec, vendor as vp Where ec.vendor_id like '".$vendor_id."' and ec.employer_id like '".$employer_id."' and ec.vendor_id = vp.vendor_id";
					$grp = " ec.employer_id, ec.vendor_id";
					$ord = "ec.employer_id";
				if($_GET['fromDate']) {
					$f = $_GET['fromDate'];
					$grp = " ec.contribution_date, ec.employer_id, ec.vendor_id";
					$ord = " ec.contribution_date, ec.employer_id";
				} else {
					$f = date('Y')."-01-01";
				}
				if($_GET['toDate']) {
					$t = $_GET['toDate'];
					$grp = " ec.contribution_date, ec.employer_id, ec.vendor_id";
					$ord = " ec.contribution_date, ec.employer_id";
				} else {
					$t = date('Y-m-d');
				}				
				$query .= " AND ec.contribution_date between '".$f."' and '".$t."' GROUP BY $grp ORDER BY $ord";
				$rsPlan = mysql_query($query) or die('error in plan'.mysql_error());
				if(mysql_num_rows($rsPlan)) {
	
	?>
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Search Result :: Employer Report</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
      <tr>
        <td class="thcview2">Employer<?php $_SESSION['print'][$i][0] = "Employer"; $j++; ?></td>
        <td class="thcview2">Vendor<?php $_SESSION['print'][$i][1] = "Vendor"; $j++; ?></td>
		<?php if($_GET['fromDate'] || $_GET['toDate']) { ?>
        <td class="thcview2">Date<?php $_SESSION['print'][$i][2] = "Date"; $j++; ?></td>
		<?php } ?>
        <td class="thcview2">SRA Pretax<?php $_SESSION['print'][$i][3] = "SRA Pretax"; $j++; ?></td>
        <td class="thcview2">SRA Roth<?php $_SESSION['print'][$i][4] = "SRA Roth"; $j++; ?></td>
        </tr>
		<?php
		
					while($recPlan = mysql_fetch_array($rsPlan)) {
					$j=0;
					$i++;
		?>
      <tr>
        <td class="tdcview2"><?php $TFM_nest2 = $recPlan['employer_id']; if ($lastTFM_nest2 != $TFM_nest2) { $lastTFM_nest2 = $TFM_nest2; $employerDetails = getEmployerDetails($recPlan['employer_id']); echo $employerDetails['name']; $_SESSION['print'][$i][0] = $employerDetails['name']; $j++; } else {$_SESSION['print'][$i][0] = "&nbsp;"; $j++; }//End of Basic-UltraDev Simulated Nested Repeat?>&nbsp;</td>
        <td class="tdcview2"><?php $TFM_nest3 = $recPlan['name']; if ($lastTFM_nest3 != $TFM_nest3) { $lastTFM_nest3 = $TFM_nest3; echo $recPlan['name']; $_SESSION['print'][$i][1] = $recPlan['name']; $j++; } else { $_SESSION['print'][$i][1] = "&nbsp;"; $j++; } //End of Basic-UltraDev Simulated Nested Repeat?>&nbsp;</td>
		<?php if($_GET['fromDate'] || $_GET['toDate']) { ?>
        <td class="tdcview2"><?php echo $recPlan['contribution_date']; ?><?php $_SESSION['print'][$i][2] = $recPlan['contribution_date']; $j++; ?>&nbsp;</td>
		<?php } ?>
        <td class="tdcview2"><?php echo $recPlan['pretax']; ?><?php $_SESSION['print'][$i][3] = $recPlan['pretax']; $j++; ?>&nbsp;</td>
        <td class="tdcview2"><?php echo $recPlan['roth']; ?><?php $_SESSION['print'][$i][4] = $recPlan['roth']; $j++; ?>&nbsp;</td>
        </tr>
		<?php
			}
		?>
    </table>
      </td>
    </tr>
</table><p>
<a href="admin_report_print.php" target="_blank">Print This Page</a>&nbsp;</p>
<br />
    <?php } else {
			echo 'No Record found.';
		}
		?>
<?php } ?>
    <?php if($_GET['EmployeeSubmit']) {	
				if($_GET['plan_id3']) {
				$vendor_id = $_GET['plan_id3'];
			} else {
				$vendor_id = "%";
			} 
			if($_GET['employee_id']) {
				$employee_id = $_GET['employee_id'];
			} else {
				$employee_id = "%";
			} 
			
			if($_GET['employer_id3']) {
				$employer_id3 = $_GET['employer_id3'];
			} else {
				$employer_id3 = "%";
			} 
			$query = "select sum(ec.sra_pretax) as pretax, sum(ec.sra_roth) as roth, v.name, ec.employee_id, ec.vendor_id, ec.employer_id, ec.contribution_date, emp.name as 'EmployerName', e.account_number, e.ssn from employee_contribution as ec, vendor as v, employer as emp, employee as e Where ec.employer_id like '".$employer_id3."' and ec.vendor_id like '".$vendor_id."' and ec.employee_id like '".$employee_id."' and ec.vendor_id = v.vendor_id and ec.employer_id = emp.employer_id and ec.employee_id = e.employee_id";
					$grp = " ec.employer_id, ec.vendor_id, ec.employee_id";
					$ord = "emp.name, v.name, e.lastname";
				if($_GET['fromDate']) {
					$f = $_GET['fromDate'];
					$grp = " ec.contribution_date, e.employee_id"; //,ec.employer_id, ec.vendor_id";
					$ord = " ec.contribution_date, emp.name, v.name, e.lastname";
					$_SESSION['date'] = 1;
				} else {
					$f = date('Y')."-01-01";
				}
				if($_GET['toDate']) {
					$t = $_GET['toDate'];
					$grp = " ec.contribution_date, e.employee_id"; //,ec.employer_id, ec.vendor_id";
					$ord = " ec.contribution_date, emp.name, v.name, e.lastname";
					$_SESSION['date'] = 1;
				} else {
					$t = date('Y-m-d');
				}				
				$query .= " AND ec.contribution_date between '".$f."' and '".$t."' GROUP BY $grp order by $ord";
				
				$rsPlan = mysql_query($query) or die('error in plan'.mysql_error());
				if(mysql_num_rows($rsPlan)) {
	?>
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Search Result :: Employee Report</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
      <tr>
        <td class="thcview2">Employer<?php //$_SESSION['print'][$i][0] = "Employer"; $j++; ?></td>
        <td class="thcview2">Vendor<?php //$_SESSION['print'][$i][1] = "Vendor"; $j++; ?></td>
        <td class="thcview2">Employee<?php //$_SESSION['print'][$i][2] = "Employee"; $j++; ?></td>
        <td class="thcview2">SSN<?php //$_SESSION['print'][$i][3] = "SSN"; $j++; ?></td>
        <td class="thcview2">Account Number<?php //$_SESSION['print'][$i][4] = "Account Number"; $j++; ?></td>
		<?php if($_GET['fromDate'] || $_GET['toDate']) { ?>
        <td class="thcview2">Date<?php //$_SESSION['print'][$i][5] = "Date"; $j++; ?></td>
		<?php } ?>
        <td class="thcview2">SRA Pretax<?php //$_SESSION['print'][$i][6] = "SRA Pretax"; $j++; ?></td>
        <td class="thcview2">SRA Roth<?php //$_SESSION['print'][$i][7] = "SRA Roth"; $j++; ?></td>
      </tr>
      <?php
			
					while($recPlan = mysql_fetch_array($rsPlan)) {
					$j=0;
					$i++;
					?>
      <tr>
        <td class="tdcview2"><?php $TFM_nest1 = $recPlan['employer_id']; if ($lastTFM_nest1 != $TFM_nest1) { $lastTFM_nest1 = $TFM_nest1; echo $recPlan['EmployerName']; $_SESSION['print'][$i][0] = $recPlan['EmployerName']; $j++; } else {$_SESSION['print'][$i][0] = $recPlan['EmployerName']; $j++; }  //End of Basic-UltraDev Simulated Nested Repeat?>&nbsp;</td>
        <td class="tdcview2"><?php $TFM_nest3 = $recPlan['name']; if ($lastTFM_nest3 != $TFM_nest3) { $lastTFM_nest3 = $TFM_nest3; echo $recPlan['name']; $_SESSION['print'][$i][1] = $recPlan['name']; $j++; } else {$_SESSION['print'][$i][1] = $recPlan['name']; $j++; } //End of Basic-UltraDev Simulated Nested Repeat?>&nbsp;</td>
        <td class="tdcview2"><?php $TFM_nest4 = $recPlan['employee_id']; if ($lastTFM_nest4 != $TFM_nest4) { $lastTFM_nest4 = $TFM_nest4; $returnEmployee = getEmployeeDetails($recPlan['employee_id']); echo $returnEmployee['lastname']." ".$returnEmployee['firstname']; $_SESSION['print'][$i][2] = $returnEmployee['lastname']." ".$returnEmployee['firstname']; $j++; } else {$_SESSION['print'][$i][2] = "&nbsp;"; $j++; } //End of Basic-UltraDev Simulated Nested Repeat?>&nbsp;</td>
        <td class="tdcview2"><?php $TFM_nest5 = $encryption->processDecrypt('ssn', $recPlan['ssn']); if ($lastTFM_nest5 != $TFM_nest5) { $lastTFM_nest5 = $TFM_nest5; $_SESSION['print'][$i][3] = $encryption->processDecrypt('ssn', $recPlan['ssn']); echo $encryption->processDecrypt('ssn', $recPlan['ssn']); $j++; } else {$_SESSION['print'][$i][3] = "&nbsp;"; $j++; } //End of Basic-UltraDev Simulated Nested Repeat?></td>
        <td class="tdcview2"><?php $TFM_nest6 = $recPlan['account_number']; if ($lastTFM_nest6 != $TFM_nest6) { $lastTFM_nest6 = $TFM_nest6; $_SESSION['print'][$i][4] = $recPlan['account_number']; echo $recPlan['account_number']; $j++; } else {$_SESSION['print'][$i][4] = "&nbsp;"; $j++; } //End of Basic-UltraDev Simulated Nested Repeat?></td>
		<?php if($_GET['fromDate'] || $_GET['toDate']) { ?>
        <td class="tdcview2"><?php echo $recPlan['contribution_date']; ?><?php $_SESSION['print'][$i][5] = $recPlan['contribution_date']; $j++; ?>&nbsp;</td>
		<?php } ?>
        <td class="tdcview2"><?php echo $recPlan['pretax']; ?><?php $_SESSION['print'][$i][6] = $recPlan['pretax']; $j++; ?>&nbsp;</td>
        <td class="tdcview2"><?php echo $recPlan['roth']; ?><?php $_SESSION['print'][$i][7] = $recPlan['roth']; $j++; ?>&nbsp;</td>
      </tr>
      <?php } ?>
    </table>
      </td>
    </tr>
</table>

<br /><p>
<a href="admin_report_print.php?change=1" target="_blank">Print This Page</a>&nbsp;</p>
    <?php } else {
			echo 'No Record found.';
		}
		?>
<?php } ?>

<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body>
<!-- InstanceEnd --></html>