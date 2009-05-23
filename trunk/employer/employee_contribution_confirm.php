<?php require_once('../Connections/dw_conn.php'); ?>
<?php
include_once('start.php');

include_once('../main/contribution_functions.php');
if($_POST['MM_insert']==1) {
	if($_POST['contribution_date']) {
		$postarray = $_POST;
		$array['header'][1] = 'ssn';
		$array['header'][2] = 'sra_pretax';
		$array['header'][3] = 'sra_roth';
		$array['detailIds'][1][1] = $_POST['ssn'];
		$array['detailIds'][1][2] = $_POST['sra_pretax'];
		$array['detailIds'][1][3] = $_POST['sra_roth'];
		$array['details'][1][$array['header'][1]] = $_POST['ssn'];
		$array['details'][1][$array['header'][2]] = $_POST['sra_pretax'];
		$array['details'][1][$array['header'][3]] = $_POST['sra_roth'];
		$total = count($array['detailIds']);	
		if($array['detailIds']) {
			foreach($array['detailIds'] as $i => $value) {
				$ssn = $array['detailIds'][$i][1];
				$sql = "select * from employee where ssn = '".addslashes(stripslashes(trim($encryption->processEncrypt('ssn', $array['detailIds'][$i][1]))))."'";
				$result = mysql_query($sql) or die('error'.mysql_error());
				$num = mysql_num_rows($result);
				if($num>0) {
					$rec = mysql_fetch_array($result);
					$employee_id = $rec['employee_id'];
					$sql = "select * from employee_contribution where employee_id = '".$employee_id."' and contribution_date = '".addslashes(stripslashes(trim($_POST['contribution_date'])))."'";
					$result2 = mysql_query($sql) or die("error");
					$num2 = mysql_num_rows($result2);
					if($num2>0) {
						$arr['showResultNotInserted'][$i] = $array['detailIds'][$i][1];
					} else { // else of if($num2>0) {
						$plan = getPlanId($employee_id);
						if($plan['plan_id']>0) {	
							$employer_id = getEmployerId($employee_id);  // get employer idd
							$employerDetails = getEmployerDetails($employer_id); // get employer details
							$employeeDetails = getEmployeeDetails($employee_id); // get employee details
							// check if roth permission is yes else dont set roth	
							if($employerDetails['roth_provision']=='Y') {
								$sraRoth = addslashes(stripslashes(trim($array['detailIds'][$i][3])));
							} else {
								$sraRoth = NULL;
							}	// end of if($employerDetails['roth_provision']=='Y') {	

							// getting the status
							// get annual contribution amount
							$year = date('Y',strtotime($_POST['contribution_date']));
							$contribution = getAnnualContribution($contribution_sra_pretax=$array['detailIds'][$i][2], $contribution_sra_roth=$sraRoth, $employee_id, $year);
							$contribution_sra_pretax = $contribution['contribution_sra_pretax'];
							$contribution_sra_roth = $contribution['contribution_sra_roth'];
							// get system settings for current year
							$systemSetting = getSystemSettings($year);
							if($systemSetting) {
								$limit_sra_pretax = $systemSetting['annual_pretax_limit'];
								$limit_sra_roth = $systemSetting['annual_roth_limit'];
								$agelimitamt = $systemSetting['annual_age_limit'];
							} else { // else of if($systemSetting) {
								$limit_sra_pretax = 0;
								$limit_sra_roth = 0;
								$agelimitamt = 0;								
							} // end of if($systemSetting) {
							$max_sra_pretax_allowed = $limit_sra_pretax-($contribution_sra_pretax-$array['detailIds'][$i][2]);
							$max_sra_roth_allowed = $limit_sra_roth-($contribution_sra_roth-$sraRoth);
	
							// get service limit amount
							$servicelimitamt = $employerDetails['service_eligible_limit'];
							// get hire date and dob from employee details
							$hireDate = $employeeDetails['hire_date'];
							$dob = $employeeDetails['dob'];
							// check status if roth or contribution is allowed
							$status = checkcontribution($contribution_sra_pretax, $contribution_sra_roth, $limit_sra_pretax, $limit_sra_roth, $servicelimitamt, $agelimitamt, $hireDate, $dob);
							// set the status of recors to be inserted
							if($employerDetails['roth_provision']=='Y') {
								if($status['sra_roth']==1 && $status['sra_pretax']==1) {
									$go = 1;
								} else { // else of if($status['sra_roth']==1 && $status['sra_pretax']==1) {
									$go = 0;
								} // end of if($status['sra_roth']==1 && $status['sra_pretax']==1) {
							} else { // else of if($employerDetails['roth_provision']=='Y')
								if($status['sra_pretax']==1) {
									$go = 1;
								} else {
									$go = 0;
								}								
							} // end of if($employerDetails['roth_provision']=='Y')
							// if allowed insert the record else set its status in pending
							if($go==1) {
								$arr['showResultInserted'][$i] = $array['detailIds'][$i][1];
								$sql = "insert into employee_contribution set employee_id = '".$employee_id."', plan_id = '".$plan['plan_id']."', vendor_id = '".$plan['vendor_id']."', employer_id = '".$employer_id."', contribution_date = '".addslashes(stripslashes(trim($_POST['contribution_date'])))."', sra_pretax = '".addslashes(stripslashes(trim($array['detailIds'][$i][2])))."', sra_roth = '".$sraRoth."'";
								$result3 = mysql_query($sql) or die(mysql_error());
							} else { // else of if($go==1) {
								$pending[$i]['employee_id'] = $employee_id;
								$pending[$i]['ssn'] = $ssn;
								$pending[$i]['data'] = $status['data'];
								$pending[$i]['plan_id'] = $plan['plan_id'];
								$pending[$i]['vendor_id'] = $plan['vendor_id'];
								$pending[$i]['employer_id'] = $employer_id;
								$pending[$i]['contribution_date'] = $_POST['contribution_date'];
								$pending[$i]['sra_pretax'] = $array['detailIds'][$i][2];
								$pending[$i]['sra_roth'] = $sraRoth;
								$pending[$i]['max_sra_pretax_allowed'] = $max_sra_pretax_allowed;
								$pending[$i]['max_sra_roth_allowed'] = $max_sra_roth_allowed;									
							} // end of if($go==1) {
						} else { // else of if($plan['plan_id']>0) {
							$noplanid[] = $employee_id;
						} // end of if($plan['plan_id']>0) {
					}	// end of if($num2>0) {				
				} else { // else of if($num>0) {
					$arr['showResult'][$i] = $array['detailIds'][$i][1];
				} // end of if($num>0) {
			} // end of foreach($array['detailIds'] as $i => $value) {
			if($noplanid) {
				$noplanidstring = implode(', ', $noplanid);
				mysql_select_db($database_dw_conn, $dw_conn);
				$query_rsNoPlanId = "SELECT * FROM employee WHERE employee_id IN (".$noplanidstring.")";
				$rsNoPlanId = mysql_query($query_rsNoPlanId, $dw_conn) or die(mysql_error().__LINE__);
				$row_rsNoPlanId = mysql_fetch_assoc($rsNoPlanId);
				$totalRows_rsNoPlanId = mysql_num_rows($rsNoPlanId);
			} else { // else of if($noplanid) {
				$noplanidstring = 0;
			} // end of if($noplanid) {
		}	// end of if($array['detailIds']) {
	} else { // else of if($_POST['contribution_date']) {
		$error = 'Please enter date. ';
	} // end of if($_POST['contribution_date']) {
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Confirmation</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Confirmation</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<?php
if($arr['showResult']) {
	echo 'Following SSN were not found in employer database. Please click each SSN to add it to the employee records<br>';
	echo '<ul>
	';
	foreach($arr['showResult'] as $key => $value) {
		echo "<li><a href='admin_employee_add.php?ssn=".$value."&menuTopItem=2' target='_blank'>".$value."</a></li>";
	}
	echo '</ul>';
}
if($arr['showResultInserted']) {
	echo count($arr['showResultInserted']).' of '.count($total).' records were successfully imported.<br>';
	//echo 'Record Inserted for employees: '.implode(',', $arr['showResultInserted']).'<br>';
	echo 'Record Inserted for employees: <br>';
	echo '<ul>
	';
	foreach($arr['showResultInserted'] as $key => $value) {
		echo "<li>".$value."</li>";
	}
	echo '</ul>';
}
if($arr['showResultNotInserted']) {
	//echo 'Record not inserted (as record already uploaded for particular selected month) for employees: '.implode(', ', $arr['showResultNotInserted']).'<br>';
	echo 'Record not inserted (as record already uploaded for particular selected month) for employees: <br>';
	echo '<ul>
	';
	foreach($arr['showResultNotInserted'] as $key => $value) {
		echo "<li>".$value."</li>";
	}
	echo '</ul>';
}
echo $error; echo "<br>";
if($totalRows_rsNoPlanId) {
	echo 'Following employee were not added as their plan is not selected: <br>';
	do { 
		$tmpEmployee .= $row_rsNoPlanId['email'].' (SSN: '.$row_rsNoPlanId['ssn'].')<br>';
	} while ($row_rsNoPlanId = mysql_fetch_assoc($rsNoPlanId)); 
	echo substr($tmpEmployee, 0, -4);
}
?>
<?php 
// start pending part
if($pending) {
?>
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Update Following Records.</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<form id="form2" name="form2" method="post" action="employee_contribution_confirm2.php">
  <table width="100%" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td valign="top"><strong>Employee</strong></td>
      <td valign="top"><strong>Contribution Date</strong></td>
      <td valign="top"><strong>SRA PreTax</strong></td>
      <td valign="top"><strong>SRA Roth</strong></td>
      <td valign="top"><strong>Actions</strong></td>
    </tr>
    <?php foreach($pending as $key => $value) { ?>
    <tr>
      <td valign="top"><?php echo $value['ssn']; ?>&nbsp;</td>
      <td valign="top"><input name="contribution_date[<?php echo $key; ?>]" type="text" id="contribution_date_<?php echo $key; ?>" size="12" value="<?php echo $value['contribution_date']; ?>" /></td>
      <td valign="top"><input name="sra_pretax[<?php echo $key; ?>]" type="text" id="sra_pretax_<?php echo $key; ?>" size="8" value="<?php echo $value['sra_pretax']; ?>" />
      <?php if($value['max_sra_pretax_allowed']>0) { $max_sra_pretax_allowed = $value['max_sra_pretax_allowed']; } else { $max_sra_pretax_allowed = 0; } ?>
      <br />
      Max Allowed:<br /><?php echo $max_sra_pretax_allowed; ?><input type="hidden" name="max_sra_pretax_allowed[<?php echo $key; ?>]" id="max_sra_pretax_allowed_<?php echo $key; ?>" value="<?php echo $max_sra_pretax_allowed; ?>" /></td>
      <td valign="top"><input name="sra_roth[<?php echo $key; ?>]" type="text" id="sra_roth_<?php echo $key; ?>" size="8" value="<?php echo $value['sra_roth']; ?>" />
      <?php if($value['max_sra_roth_allowed']>0) { $max_sra_roth_allowed = $value['max_sra_roth_allowed']; } else { $max_sra_roth_allowed = 0; } ?>
      <br />
      Max Allowed:<br /><?php echo $max_sra_roth_allowed; ?><input type="hidden" name="max_sra_roth_allowed[<?php echo $key; ?>]" id="max_sra_roth_allowed_<?php echo $key; ?>" value="<?php echo $max_sra_roth_allowed; ?>" /></td>
      <td valign="top"><input name="actions[<?php echo $key; ?>]" type="radio" id="action_<?php echo $key; ?>_1" value="1" checked="checked" />
        Ignore 
          <br />
          <input type="radio" name="actions[<?php echo $key; ?>]" id="action_<?php echo $key; ?>_2" value="2" />
        Accept Max Allowed Contribution 
        <br />
        <input type="radio" name="actions[<?php echo $key; ?>]" id="action_<?php echo $key; ?>_3" value="3" />
        Accept maximum and create action for refund<br />
        <input name="actions[<?php echo $key; ?>]" type="radio" id="action_<?php echo $key; ?>_4" value="4" />
        Set Manually on left Side
        <input name="employee_id[<?php echo $key; ?>]" type="hidden" id="employee_id_[<?php echo $key; ?>]" value="<?php echo $value['employee_id']; ?>" />
        <input name="plan_id[<?php echo $key; ?>]" type="hidden" id="plan_id_[<?php echo $key; ?>]" value="<?php echo $value['plan_id']; ?>" />
        <input name="vendor_id[<?php echo $key; ?>]" type="hidden" id="vendor_id_[<?php echo $key; ?>]" value="<?php echo $value['vendor_id']; ?>" />
        <input type="hidden" name="employer_id[<?php echo $key; ?>]" id="employer_id_[<?php echo $key; ?>]" value="<?php echo $value['employer_id']; ?>" />
        <input type="hidden" name="ssn[<?php echo $key; ?>]" id="ssn_[<?php echo $key; ?>]" value="<?php echo $value['ssn']; ?>" />
        <input name="key[<?php echo $key; ?>]" type="hidden" id="key[<?php echo $key; ?>]" value="<?php echo $key; ?>" /></td>
    </tr>
    <?php } ?>
  </table>
  <p>
    <input type="submit" name="button" id="button" value="Update" />
    <input name="MM_Update" type="hidden" id="MM_Update" value="1" /><input name="menuTopItem" type="hidden" id="menuTopItem" value="2" />
  </p>
</form>
      </td>
    </tr>
</table>
<br />

<?php } 
// ends pending part
?>
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