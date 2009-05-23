<?php
include_once('dw_conn.php');
include_once('start.php');
function getSettings() {
	$sql = "SELECT `annual_pretax_limit`, `annual_age_limit`, `year` FROM `admin_system_settings`";
	$rs = mysql_query($sql) or die('error in selecting settings');
	while($rec = mysql_fetch_array($rs)) {
		$ret[$rec['year']] = $rec;
	}
	return $ret;
}
function getEmployees() {
	global $encryption;
	$rs = mysql_query("select employee_id, ssn from employee") or die('error');
	if(mysql_num_rows($rs)) {
		while($rec = mysql_fetch_array($rs)) {
			$return[$rec['employee_id']] = $encryption->processDecrypt('ssn', $rec['ssn']);
		}
		return $return;
	} else {
		return 0;
	}
}

function inc_import_form($action, $confirmLink, $employer_id, $menuTopItem) {
	?>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
<table border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
    <tr>
      <td class="thc2">Upload File </td>
      <td class="tdc2"><input name="userfile" type="file" id="userfile" /></td>
      <td class="tdc2" align="center"><a href="../main/cfcc2.25.08.xls" target="_blank">Sample XLS File</a> | <a href="../main/cfcc2.25.08csv.csv">Sample CSV File</a> </td>
    </tr>
    <tr>
      <td class="thc2">Date (yyyy-mm-dd)</td>
      <td class="tdc2" COLSPAN=2><input name="contribution_date" type="text" id="contribution_date" /> 
        <span class="tdc2">
        <input type="button" value="Pick date" onclick="pickDate(this,document.form1.contribution_date);" />
        </span></td>
        
    </tr>
    <tr>
      <td class="thc2">&nbsp;</td>
      <td class="tdc2" COLSPAN=2><input type="button" name="Submit" value="Submit" onclick="doAjaxLoadingTextCustomImport('<?php echo $confirmLink; ?>','GET','file='+document.form1.userfile.value+'&employer_id=<?php echo $employer_id; ?>&date='+document.form1.contribution_date.value,'','divConfirm','yes');" />
      <div id="divConfirm"></div>
      <input name="MM_Insert" type="hidden" id="MM_Insert" value="1" /><input name="employer_id" type="hidden" id="employer_id" value="<?php echo $employer_id; ?>" /><input name="menuTopItem" type="hidden" id="menuTopItem" value="<?php echo $menuTopItem; ?>" /></td>
    </tr>

  </table>
</form>
	<?php
}
function getPlanId($employee_id) {
	$rs = mysql_query("select * from employee_vendor where employee_id = '".$employee_id."'") or die('error');
	if(mysql_num_rows($rs)) {
		$rec = mysql_fetch_array($rs);
		return $rec;
	} else {
		return 0;
	}
}
function getEmployerId($employee_id) {
	$rs = mysql_query("select employer_id from employee where employee_id = '".$employee_id."'") or die('error');
	if(mysql_num_rows($rs)) {
		$rec = mysql_fetch_array($rs);
		return $rec['employer_id'];
	} else {
		return 0;
	}
}

function getPeriod($Date) {
	if($Date) {
		$today = time();
		$hiretime = strtotime($Date);
		$diff = $today - $hiretime;
		return floor($diff/(60*60*24*365));
	} else {
		return 0;
	}
}
function getEmployerDetails($employer_id) {
	$rs = mysql_query("select * from employer where employer_id = '".$employer_id."'") or die('error');
	if(mysql_num_rows($rs)) {
		$rec = mysql_fetch_array($rs);
		return $rec;
	} else {
		return 0;
	}
}
function getEmployeeDetails($employee_id) {
	$rs = mysql_query("select * from employee where employee_id = '".$employee_id."'") or die('error');
	if(mysql_num_rows($rs)) {
		$rec = mysql_fetch_array($rs);
		return $rec;
	} else {
		return 0;
	}
}
// function for max roth and process roth
function maxRothContributionAvailable($employee_id, $year) {
	// read annual roth limit from settings
	$sql = "SELECT `annual_roth_limit` FROM `admin_system_settings` WHERE `year` = '".$year."'";
	$rs = mysql_query($sql) or die('error in selecting settings');
	$rec = mysql_fetch_array($rs);
	$arr['annual_limit'] = $rec['annual_roth_limit'];
		
	$sql = "SELECT sum(`sra_roth`) as curr_year_contribution FROM `employee_contribution` WHERE YEAR(`contribution_date`) = '".$year."' AND `employee_id` = '".$employee_id."'";
	$rs = mysql_query($sql) or die('error in selecting settings');
	$rec = mysql_fetch_array($rs);
	$arr['curr_year_contribution'] = $rec['curr_year_contribution']; 
	
	return ($arr['annual_limit']-$arr['curr_year_contribution']);
}

function processRothContribution($employee_id, $year, $curr_month_roth_contribution) { 
    $maxContribution = maxRothContributionAvailable($employee_id, $year);	
    if ($maxContribution >= $curr_month_roth_contribution) {
      	$arr['accepted_contribution'] = $curr_month_roth_contribution;
      	$arr['refund'] = 0;
    } else {
      $arr['accepted_contribution'] =  $maxContribution;
      $arr['refund'] = $curr_month_roth_contribution - $maxContribution;
    }	
	return $arr;  
}

function maxContributionAvailable($employee_id, $year, $hireDate, $dob) {

  // define system boundaries - nk start
  $maxAge = 50;
  $maxYrs = 15;
  $prevYrs = 5;
  $maxPastContribution = 5000;
  $maxCatchUp = 15000;
  
  
	// roth will not change
	// read annual pretax limit from settings
	$sql = "SELECT `annual_pretax_limit`, `annual_age_limit` FROM `admin_system_settings` WHERE `year` = '".$year."'";
	$rs = mysql_query($sql) or die('error in selecting settings');
	$rec = mysql_fetch_array($rs);
	$arr['annual_limit'] = $rec['annual_pretax_limit'];
	$arr['annual_age_limit'] = $rec['annual_age_limit'];
  
	
	$sql = "SELECT sum(`sra_pretax`) as curr_year_contribution FROM `employee_contribution` WHERE YEAR(`contribution_date`) = '".$year."' AND `employee_id` = '".$employee_id."'";
	$rs = mysql_query($sql) or die('error in selecting settings');
	$rec = mysql_fetch_array($rs);
	$arr['curr_year_contribution'] = $rec['curr_year_contribution'];
  
  // average contribution in last 5 years
	$prevYear = $year - $prevYrs;
	$sql = "SELECT sum(`sra_pretax`) as sp, year(contribution_date) as sd FROM `employee_contribution` WHERE Year(contribution_date) > ".$prevYear." and employee_id = ".$employee_id." group by year(contribution_date)";
	$rs = mysql_query($sql) or die('error in selecting');
	$arr['avg'] = 0;
	if(mysql_num_rows($rs)) {
		while($rec = mysql_fetch_array($rs)) {
			$total += $rec['sp'];
			$cnt += 1;
		}
		$arr['avg'] = $total/$cnt;					
	}
  
  $arr['catchup'] = 0;
  
	// current contriution greater than annual limit
	// get service period and age period
	if(!$hireDate) $hireDate = date('Y-m-d'); if(!$dob) $dob = date('Y-m-d'); 
	$arr['servicePeriod'] = getPeriod($hireDate);
	$arr['agePeriod'] = getPeriod($dob);
    
	if($arr['agePeriod']>$maxAge) { // nk- updated 50 with $maxAge
	  $arr['annual_limit'] = $arr['annual_limit'] + $arr['annual_age_limit']; // new annual limit
	} 
  
  if (($arr['servicePeriod']>$maxYrs) && ($arr['avg'] < $maxPastContribution)) {
      $sql = "select catchup from employee where employee_id = '".$employee_id."'";
			$rs = mysql_query($sql) or die('could not select catchup');
			$rec = mysql_fetch_array($rs);
			$arr['catchup'] = $rec['catchup'];      
     
			$employer_id = getEmployerId($employee_id);  // get employer idd
			$employerDetails = getEmployerDetails($employer_id); // get employer details
			$arr['servicelimitamount'] = $employerDetails['service_eligible_limit'];     
       
			if(($maxCatchUp-$arr['catchup'])>$arr['servicelimitamount']) {
				//$arr['max_catchup'] = $maxCatchUp - $arr['catchup'];
				$arr['max_catchup'] = $arr['servicelimitamount'];
			} else { 
				$arr['max_catchup'] = $maxCatchUp - $arr['catchup'];
			} // end of if((15000-$arr['catchup'])>0) 
      
      $arr['annual_limit'] = $arr['annual_limit'] + $arr['max_catchup']; // new annual limit
      
  }
  
  return ($arr['annual_limit']-$arr['curr_year_contribution']);

}
//maxContributionAvailable($employee_id, $year, $hireDate, $dob)
//processContribution($employee_id, $year, $curr_month_contribution, $hireDate, $dob)

function processContribution($employee_id, $year, $curr_month_contribution, $hireDate, $dob) {
	$arr['accept'] = 0;

  // define system boundaries - nk start
  $maxAge = 50;
  $maxYrs = 15;
  $prevYrs = 5;
  $maxPastContribution = 5000;
  $maxCatchUp = 15000;
  $arr['employee_id'] = $employee_id;
  $employer_id = getEmployerId($employee_id);  // get employer idd
  $employerDetails = getEmployerDetails($employer_id); // get employer details
  $arr['employerDetails'] = $employerDetails;
  
	// roth will not change
	// read annual pretax limit from settings
	$sql = "SELECT `annual_pretax_limit`, `annual_age_limit`, `annual_roth_limit` FROM `admin_system_settings` WHERE `year` = '".$year."'";
	$rs = mysql_query($sql) or die('error in selecting settings');
	$rec = mysql_fetch_array($rs);
	$arr['annual_limit'] = $rec['annual_pretax_limit'];
	$arr['annual_roth_limit'] = $rec['annual_roth_limit'];
	$arr['annual_age_limit'] = $rec['annual_age_limit'];
  
	
	$sql = "SELECT sum(`sra_pretax`) as curr_year_contribution FROM `employee_contribution` WHERE YEAR(`contribution_date`) = '".$year."' AND `employee_id` = '".$employee_id."'";
	$rs = mysql_query($sql) or die('error in selecting settings');
	$rec = mysql_fetch_array($rs);
	$arr['curr_year_contribution'] = $rec['curr_year_contribution'];
	$arr['curr_month_contribution'] = $curr_month_contribution;
	$arr['curr_contribution'] = $arr['curr_year_contribution']+$arr['curr_month_contribution'];
  
  // average contribution in last 5 years
	$prevYear = $year - $prevYrs;
	$sql = "SELECT sum(`sra_pretax`) as sp, year(contribution_date) as sd FROM `employee_contribution` WHERE Year(contribution_date) > ".$prevYear." and employee_id = ".$employee_id." group by year(contribution_date)";
	$rs = mysql_query($sql) or die('error in selecting');
	$arr['avg'] = 0;
	if(mysql_num_rows($rs)) {
		while($rec = mysql_fetch_array($rs)) {
			$total += $rec['sp'];
			$cnt += 1;
		}
		$arr['avg'] = $total/$cnt;					
	}
  
  $arr['catchup'] = 0;
  
	// current contriution greater than annual limit
	// get service period and age period
	if(!$hireDate) $hireDate = date('Y-m-d'); if(!$dob) $dob = date('Y-m-d'); 
	$arr['servicePeriod'] = getPeriod($hireDate);
	$arr['agePeriod'] = getPeriod($dob);
    
	if($arr['agePeriod']>$maxAge) { // nk- updated 50 with $maxAge
	  $arr['annual_limit'] = $arr['annual_limit'] + $arr['annual_age_limit']; // new annual limit
	} 
  
  if (($arr['servicePeriod']>$maxYrs) && ($arr['avg'] < $maxPastContribution)) {
      $sql = "select catchup from employee where employee_id = '".$employee_id."'";
			$rs = mysql_query($sql) or die('could not select catchup');
			$rec = mysql_fetch_array($rs);
			$arr['catchup'] = $rec['catchup'];      
     
			$arr['servicelimitamount'] = $employerDetails['service_eligible_limit'];     
      
			if(($maxCatchUp-$arr['catchup'])>$arr['servicelimitamount']) {
				//$arr['max_catchup'] = $maxCatchUp - $arr['catchup'];
				$arr['max_catchup'] = $arr['servicelimitamount'];
			} else { 
				$arr['max_catchup'] = $maxCatchUp - $arr['catchup'];
			} // end of if((15000-$arr['catchup'])>0)   
      
      $arr['annual_limit'] = $arr['annual_limit'] + $arr['max_catchup']; // new annual limit
      
  }
  
  
	if($arr['curr_contribution']>$arr['annual_limit']) {
      //$arr['accepted_contribution'] = $arr['curr_contribution'] - $arr['annual_limit'];
      $arr['accepted_contribution'] = $arr['annual_limit'] - $arr['curr_year_contribution'];
      $arr['refund'] = $arr['curr_month_contribution'] - $arr['accepted_contribution'];
      // Create refund request
  }
  else
  {
      $arr['accepted_contribution'] = $arr['curr_month_contribution'];
      $arr['refund'] = 0;
  }
  
  if ($arr['accepted_contribution']> 0)
  {
    if (($arr['max_catchup'] > 0) && (($arr['curr_year_contribution']+$arr['accepted_contribution'])>($arr['annual_limit']-$arr['max_catchup'])))
    {
		$arr['catchup'] = $arr['catchup']+($arr['curr_year_contribution']+$arr['accepted_contribution'])-($arr['annual_limit']-$arr['max_catchup']);
       	$sql = "update employee set catchup = '".$arr['catchup']."' where employee_id = '".$employee_id."'";
		$rs = mysql_query($sql) or die('could not update catchup');       
    }
  }
  
  $arr['accept'] = 1;
	return $arr;
}

function postProcess($array, $post) {
	global $encryption;
	$total = count($array['detailIds']);
	$sess['post']['total'] = $total;
	$sess['post']['records'] = $array;
	$sess['contribution_date'] = $post['contribution_date'];
	$sess['employer_id'] = $post['employer_id'];
	if(!$array['detailIds']) {
		$sess['post']['errorMessage'][] = 'No Records Uploaded. ';
	} else {
		//mysql_query("insert into employee_contribution_transaction set transaction_date = '".addslashes(stripslashes(trim($post['contribution_date'])))."', employer_id = '".$post['employer_id']."'") or die('error');
		foreach($array['detailIds'] as $i => $value) {
			$ssn = $array['detailIds'][$i][5];
			$contribution_pretax = $array['detailIds'][$i][6];
			$contribution_pretax = str_replace("$","",$contribution_pretax);
			$contribution_pretax = trim($contribution_pretax);
			$contribution_pretax = str_replace(",","",$contribution_pretax);
			if(ROTH==1) {
				$contribution_roth = $array['detailIds'][$i][7];
				$contribution_roth = str_replace("$","",$contribution_roth);
				$contribution_roth = trim($contribution_roth);
				$contribution_roth = str_replace(",","",$contribution_roth);
				if(!$contribution_roth) $contribution_roth = 0; // added
				$account = $array['detailIds'][$i][8];				
			} else {
				$contribution_roth = 0;
				$account = $array['detailIds'][$i][7];
			}
			$company = $array['detailIds'][$i][1];
			$lastname = $array['detailIds'][$i][2];
			$firstname = $array['detailIds'][$i][3];
			$middlename = $array['detailIds'][$i][4];
			$sess['post']['details'][$ssn]['ssn'] = $ssn;
			$sess['post']['details'][$ssn]['contribution_pretax'] = $contribution_pretax;
			$sess['post']['details'][$ssn]['contribution_roth'] = $contribution_roth;
			$sess['post']['details'][$ssn]['company'] = $company;
			$sess['post']['details'][$ssn]['lastname'] = $lastname;
			$sess['post']['details'][$ssn]['firstname'] = $firstname;
			$sess['post']['details'][$ssn]['middlename'] = $middlename;
			$sess['post']['details'][$ssn]['account'] = $account;
			$sql = "select * from employee where ssn = '".addslashes(stripslashes(trim($encryption->processEncrypt('ssn', $ssn))))."'";
			$result = mysql_query($sql) or die('error'.mysql_error());
			$num = mysql_num_rows($result);
			if($num==0) {
				$sess['post']['nossnfound'][$ssn][] = $ssn;
				$sess['inputPretax']['nossnfound'][$ssn] += $contribution_pretax;
				$sess['inputRoth']['nossnfound'][$ssn] += $contribution_roth;
			} else {
				$rec = mysql_fetch_array($result);
				$employee_id = $rec['employee_id'];
				$sess['employee_id'][$ssn] = $employee_id;
				$sql = "select * from employee_contribution where employee_id = '".$employee_id."' and contribution_date = '".addslashes(stripslashes(trim($post['contribution_date'])))."'";
				$result2 = mysql_query($sql) or die("error");
				$num2 = mysql_num_rows($result2);
				if($num2>0) {
					$sess['post']['contributionalreadyaddedforthisdate'][$ssn][] = $ssn;
					$sess['inputPretax']['contributionalreadyaddedforthisdate'][$ssn] += $contribution_pretax;
					$sess['inputRoth']['contributionalreadyaddedforthisdate'][$ssn] += $contribution_roth;
				} else {
					$plan = getPlanId($employee_id);
					if(!$plan || $plan==0) {
						$sess['post']['plannotselected'][$ssn][] = $ssn;
						$sess['inputPretax']['plannotselected'][$ssn] += $contribution_pretax;
						$sess['inputRoth']['plannotselected'][$ssn] += $contribution_roth;
					} else {
						
						$employeeDetails = getEmployeeDetails($employee_id);
						$max = maxContributionAvailable($employee_id, $year=date('Y'), $hireDate=$employeeDetails['hire_date'], $dob=$employeeDetails['dob']);
						$process = processContribution($employee_id, $year=date('Y'), $curr_month_contribution=$contribution_pretax, $hireDate=$employeeDetails['hire_date'], $dob=$employeeDetails['dob']);
						$process['plan'] = $plan;
						$process['contribution_date'] = $post['contribution_date'];
						$process['contribution_pretax'] = $contribution_pretax;
						$process['contribution_roth'] = $contribution_roth;
						$sess['post']['process'][$ssn][] = $process;			
						if($process['employerDetails']['roth_provision']=='Y') $roth = $process['contribution_roth']; else $roth = 0;
						if($process['refund']==0) {
							$sql = "insert into employee_contribution set employee_id = '".$process['employee_id']."', plan_id = '".$process['plan']['plan_id']."', vendor_id = '".$process['plan']['vendor_id']."', employer_id = '".$process['employerDetails']['employer_id']."', contribution_date = '".addslashes(stripslashes(trim($process['contribution_date'])))."', sra_pretax = '".addslashes(stripslashes(trim($process['contribution_pretax'])))."', sra_roth = '".$roth."'";
							$result3 = mysql_query($sql) or die(mysql_error());
						} else {						
							$sql = "insert into employee_contribution set employee_id = '".$process['employee_id']."', plan_id = '".$process['plan']['plan_id']."', vendor_id = '".$process['plan']['vendor_id']."', employer_id = '".$process['employerDetails']['employer_id']."', contribution_date = '".addslashes(stripslashes(trim($process['contribution_date'])))."', sra_pretax = '".addslashes(stripslashes(trim($process['contribution_pretax'])))."', sra_roth = '".$roth."'";				
							$result3 = mysql_query($sql) or die(mysql_error());
							addExcessWorkflowPretax($process['employee_id'], $process['employerDetails']['employer_id'], $process['refund'], $post['contribution_date']);
						}			
						$sess['refundPretax']['process'][$ssn] += $process['refund'];				
						$rothProcess = processRothContribution($employee_id, $year=date('Y'), $roth);
						if($rothProcess['refund']==0) {
						
						} else {						
							addExcessWorkflowRoth($process['employee_id'], $process['employerDetails']['employer_id'], $rothProcess['refund'], $post['contribution_date']);
						}					
						$sess['refundRoth']['process'][$ssn] += $rothProcess['refund'];
						$sess['inputPretax']['process'][$ssn] += $contribution_pretax;
						$sess['inputRoth']['process'][$ssn] += $contribution_roth;
					}
				}
			}
		}
	}		
	return $sess;
}
function updateTransaction($sess, $post, $transaction_id) {
	//echo "<pre>";
	//print_r($sess);
	//echo "</pre>";
	//$sql = "update `employee_contribution_transaction` set `totalamountprocessed` = `totalamountprocessed`+".$processPretax.", `totalamountrefunded` = `totalamountrefunded`+".$totalPretaxRefund.", `totalrecordsprocessedsuccessfully` = `totalrecordsprocessedsuccessfully`+".$processCount.", `totalrecordsrejected` = `totalrecordsrejected`-".$processCount.", `recordsalreadyuploaded` = `recordsalreadyuploaded`+".$contributionalreadyaddedforthisdateCount.", `totalrothprocessed` = `totalrothprocessed`+".$processRoth.", `totalrothrefunded` = `totalrothrefunded`+".$totalRothRefund." WHERE transaction_id = '".$transaction_id."'";
	$sql = "update `employee_contribution_transaction` set ";
	
	$totalPretaxRefund = ($sess['refundPretax']['process'])? array_sum($sess['refundPretax']['process']) : 0;
	$totalRothRefund = ($sess['refundRoth']['process']) ? array_sum($sess['refundRoth']['process']) : 0;
	
	if($sess['post']['process']) {
		$processCount = count($sess['post']['process']);
		$processPretax = array_sum($sess['inputPretax']['process']);
		$processRoth =  array_sum($sess['inputRoth']['process']);
		$processCount = ($processCount) ? $processCount : 0 ;
		
		$processPretax = ($processPretax) ? $processPretax : 0;
		$processRoth = ($processRoth) ? $processRoth : 0;
		$sql .= "`totalamountprocessed` = `totalamountprocessed`+".$processPretax.", `totalamountrefunded` = `totalamountrefunded`+".$totalPretaxRefund.", `totalrecordsprocessedsuccessfully` = `totalrecordsprocessedsuccessfully`+".$processCount.", `totalrecordsrejected` = `totalrecordsrejected`-".$processCount.", `totalrothprocessed` = `totalrothprocessed`+".$processRoth.", `totalrothrefunded` = `totalrothrefunded`+".$totalRothRefund.", "; 
	}
	if($sess['post']['contributionalreadyaddedforthisdate']) {
		$contributionalreadyaddedforthisdateCount = count($sess['post']['contributionalreadyaddedforthisdate']);
		$contributionalreadyaddedforthisdatePretax = array_sum($sess['inputPretax']['contributionalreadyaddedforthisdate']);
		$contributionalreadyaddedforthisdateRoth = array_sum($sess['inputRoth']['contributionalreadyaddedforthisdate']);
		$contributionalreadyaddedforthisdateCount = ($contributionalreadyaddedforthisdateCount) ? $contributionalreadyaddedforthisdateCount : 0;
		$sql .= "`recordsalreadyuploaded` = `recordsalreadyuploaded`+".$contributionalreadyaddedforthisdateCount.", ";
	}
	if($sess['post']['plannotselected']) {
		$plannotselectedCount = count($sess['post']['plannotselected']);
		$plannotselectedPretax = array_sum($sess['inputPretax']['plannotselected']);
		$plannotselectedRoth = array_sum($sess['inputRoth']['plannotselected']);
	}
	if($sess['post']['nossnfound']) {
		$nossnfoundCount = count($sess['post']['nossnfound']);
		$nossnfoundPretax = array_sum($sess['inputPretax']['nossnfound']);
		$nossnfoundRoth = array_sum($sess['inputRoth']['nossnfound']);
	}
	$totalrecords = $processCount + $contributionalreadyaddedforthisdateCount + $plannotselectedCount + $nossnfoundCount;
	$totalamount = $processPretax + $contributionalreadyaddedforthisdatePretax + $plannotselectedPretax + $nossnfoundPretax;
	$totalamount = ($totalamount) ? $totalamount : 0;
	$totalroth = $processRoth + $contributionalreadyaddedforthisdateRoth + $plannotselectedRoth + $nossnfoundRoth;
	$sql .= "transaction_id = '".$transaction_id."' WHERE transaction_id = '".$transaction_id."'";
	mysql_query($sql) or die('error in updating');
	return $errorMessage;
}
function saveTransaction($sess, $post) {
	//echo "<pre>";
	//print_r($sess);
	//echo "</pre>";
	if($sess['post']['process']) {
		$processCount = count($sess['post']['process']);
		$processPretax = array_sum($sess['inputPretax']['process']);
		$processRoth =  array_sum($sess['inputRoth']['process']);
	}
	if($sess['post']['contributionalreadyaddedforthisdate']) {
		$contributionalreadyaddedforthisdateCount = count($sess['post']['contributionalreadyaddedforthisdate']);
		$contributionalreadyaddedforthisdatePretax = array_sum($sess['inputPretax']['contributionalreadyaddedforthisdate']);
		$contributionalreadyaddedforthisdateRoth = array_sum($sess['inputRoth']['contributionalreadyaddedforthisdate']);
	}
	if($sess['post']['plannotselected']) {
		$plannotselectedCount = count($sess['post']['plannotselected']);
		$plannotselectedPretax = array_sum($sess['inputPretax']['plannotselected']);
		$plannotselectedRoth = array_sum($sess['inputRoth']['plannotselected']);
	}
	if($sess['post']['nossnfound']) {
		$nossnfoundCount = count($sess['post']['nossnfound']);
		$nossnfoundPretax = array_sum($sess['inputPretax']['nossnfound']);
		$nossnfoundRoth = array_sum($sess['inputRoth']['nossnfound']);
	}
	$processCount = ($processCount) ? $processCount : 0 ;
	$totalrecords = $processCount + $contributionalreadyaddedforthisdateCount + $plannotselectedCount + $nossnfoundCount;
	$totalamount = $processPretax + $contributionalreadyaddedforthisdatePretax + $plannotselectedPretax + $nossnfoundPretax;
	$totalamount = ($totalamount) ? $totalamount : 0;
	$processPretax = ($processPretax) ? $processPretax : 0;
	$processRoth = ($processRoth) ? $processRoth : 0;
	$contributionalreadyaddedforthisdateCount = ($contributionalreadyaddedforthisdateCount) ? $contributionalreadyaddedforthisdateCount : 0;
	$totalroth = $processRoth + $contributionalreadyaddedforthisdateRoth + $plannotselectedRoth + $nossnfoundRoth;
	$totalPretaxRefund = ($sess['refundPretax']['process'])? array_sum($sess['refundPretax']['process']) : 0;
	$totalRothRefund = ($sess['refundRoth']['process']) ? array_sum($sess['refundRoth']['process']) : 0;
	$sql = "INSERT INTO `employee_contribution_transaction` ( `transaction_date` , `employer_id` , `totalrecords` , `totalamount` , `totalamountprocessed` , `totalamountrefunded` , `totalrecordsprocessedsuccessfully` , `totalrecordsrejected` , `recordsalreadyuploaded` , `totalroth` , `totalrothprocessed` , `totalrothrefunded` )  VALUES ( '".$sess['contribution_date']."', '".$sess['employer_id']."', '".$totalrecords."', '".$totalamount."', '".$processPretax."', '".$totalPretaxRefund."', '".$processCount."', '".($plannotselectedCount+$nossnfoundCount)."', '".$contributionalreadyaddedforthisdateCount."', '".$totalroth."', '".$processRoth."', '".$totalRothRefund."')";
	mysql_query($sql) or die('error in inserting');
	$uid = mysql_insert_id();
	?>
<div id="transactionTable">
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Result Of Upload</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
	  	<table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
			<tr>
			  	<td valign="top" class="thcview2"><strong># of records</strong></td>
				<td valign="top" class="thcview2"><strong>Total Amount</strong></td>
				<td valign="top" class="thcview2"><strong># Successful</strong></td>
				<td valign="top" class="thcview2"><strong>#Failed</strong></td>
				<td valign="top" class="thcview2"><strong>#Duplicate</strong></td>
				<td colspan="2" valign="top" class="thcview2"><strong>Amount</strong></td>
				<td colspan="2" valign="top" class="thcview2"><strong>Refund</strong></td>
			  </tr>
			  <tr>
			    <td valign="top" class="thcview2">&nbsp;</td>
			    <td valign="top" class="thcview2">&nbsp;</td>
			    <td valign="top" class="thcview2">&nbsp;</td>
			    <td valign="top" class="thcview2">&nbsp;</td>
			    <td valign="top" class="thcview2">&nbsp;</td>
			    <td valign="top" class="thcview2"><strong>Pretax</strong></td>
			    <td valign="top" class="thcview2"><strong>Roth</strong></td>
			    <td valign="top" class="thcview2"><strong>Pretax</strong></td>
			    <td valign="top" class="thcview2"><strong>Roth</strong></td>
	      </tr>
			  <tr>
			  	<td valign="top" class="tdcview2"><?php echo $totalrecords; ?></td>
				<td valign="top" class="tdcview2"><?php echo $totalamount + $totalroth; ?></td>
				<td valign="top" class="tdcview2"><?php echo $processCount; ?></td>
				<td valign="top" class="tdcview2"><?php echo $plannotselectedCount+$nossnfoundCount; ?></td>
				<td valign="top" class="tdcview2"><?php echo $contributionalreadyaddedforthisdateCount; ?></td>
				<td valign="top" class="tdcview2"><?php echo $processPretax;?></td>
				<td valign="top" class="tdcview2"><?php echo $processRoth;?></td>
				<td valign="top" class="tdcview2"><?php echo $totalPretaxRefund;?></td>
			    <td valign="top" class="tdcview2"><?php echo $totalRothRefund;?></td>
			  </tr>
		</table>
	  </td>
	</tr>
</table>	
</div>	
	<?php
	return $uid;
}
function showTransactionTable($id) {
	$sql = "select * from employee_contribution_transaction where transaction_id = '".$id."'";
	$rs = mysql_query($sql) or die('error');
	$rec = mysql_fetch_array($rs);
	?>
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Result Of Upload</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
	  	<table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
			<tr>
			  	<td valign="top" class="thcview2"><strong># of records</strong></td>
				<td valign="top" class="thcview2"><strong>Total Amount</strong></td>
				<td valign="top" class="thcview2"><strong># Successful</strong></td>
				<td valign="top" class="thcview2"><strong>#Failed</strong></td>
				<td valign="top" class="thcview2"><strong>#Duplicate</strong></td>
				<td colspan="2" valign="top" class="thcview2"><strong>Amount</strong></td>
				<td colspan="2" valign="top" class="thcview2"><strong>Refund</strong></td>
		    </tr>
			  <tr>
			    <td valign="top" class="thcview2">&nbsp;</td>
			    <td valign="top" class="thcview2">&nbsp;</td>
			    <td valign="top" class="thcview2">&nbsp;</td>
			    <td valign="top" class="thcview2">&nbsp;</td>
			    <td valign="top" class="thcview2">&nbsp;</td>
			    <td valign="top" class="thcview2"><strong>Pretax</strong></td>
			    <td valign="top" class="thcview2"><strong>Roth</strong></td>
			    <td valign="top" class="thcview2"><strong>Pretax</strong></td>
			    <td valign="top" class="thcview2"><strong>Roth</strong></td>
	      </tr>
			  <tr>
			  	<td valign="top" class="tdcview2"><?php echo $rec['totalrecords']; ?></td>
				<td valign="top" class="tdcview2"><?php echo $rec['totalamount'] + $rec['totalroth']; ?></td>
				<td valign="top" class="tdcview2"><?php echo $rec['totalrecordsprocessedsuccessfully']; ?></td>
				<td valign="top" class="tdcview2"><?php echo $rec['totalrecordsrejected']; ?></td>
				<td valign="top" class="tdcview2"><?php echo $rec['recordsalreadyuploaded']; ?></td>
				<td valign="top" class="tdcview2"><?php echo $rec['totalamountprocessed'];?></td>
				<td valign="top" class="tdcview2"><?php echo $rec['totalrothprocessed'];?></td>
				<td valign="top" class="tdcview2"><?php echo $rec['totalamountrefunded'];?></td>
		        <td valign="top" class="tdcview2"><?php echo $rec['totalrothrefunded'];?></td>
			  </tr>
		</table>
	  </td>
	</tr>
</table>	
	<?php
}

function showTransactionTableForParticularEmployee($id, $date) {
	$sql = "select * from employee_contribution_details where employee_id = '".$id."' and cdate = '".$date."'";
	$rs = mysql_query($sql) or die('error');
	$rec = mysql_fetch_array($rs);
	?>
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Result Of Transaction</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
	  	<table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
			<tr>
			  	<td valign="top" class="thcview2"><strong>Is Duplicate</strong></td>
				<td colspan="2" valign="top" class="thcview2"><strong>Amount</strong></td>
				<td colspan="2" valign="top" class="thcview2"><strong>Refund</strong></td>
		    </tr>
			  <tr>
			    <td valign="top" class="thcview2">&nbsp;</td>
			    <td valign="top" class="thcview2"><strong>Pretax</strong></td>
			    <td valign="top" class="thcview2"><strong>Roth</strong></td>
			    <td valign="top" class="thcview2"><strong>Pretax</strong></td>
			    <td valign="top" class="thcview2"><strong>Roth</strong></td>
	      </tr>
			  <tr>
			  	<td valign="top" class="tdcview2"><?php echo $dup = ($rec['duplicate_record']>0)?'Yes':'No'; ?></td>
				<td valign="top" class="tdcview2"><?php echo $rec['pretax'];?></td>
				<td valign="top" class="tdcview2"><?php echo $rec['roth'];?></td>
				<td valign="top" class="tdcview2"><?php echo $rec['pretax_refund'];?></td>
		        <td valign="top" class="tdcview2"><?php echo $rec['roth_refund'];?></td>
			  </tr>
		</table>
	  </td>
	</tr>
</table>	
	<?php
}

function createMessage($sess) {
	if($sess['post']['process']) {
		$cnt = 0;
		$cntSSN = array();
		foreach($sess['post']['process'] as $key => $value) {
			$cnt += 1;
			$cntSSN[] = $key;
		}
		$cntSSNStr = implode(", ", $cntSSN);
		echo "Process successfull for $cnt employees. They are: $cntSSNStr";
		echo "<br>";
		echo "<br>";
	}
	if($sess['post']['contributionalreadyaddedforthisdate']) {
		$cnt = 0;
		$cntSSN = array();
		foreach($sess['post']['contributionalreadyaddedforthisdate'] as $key => $value) {
			$cnt += 1;
			$cntSSN[] = $key;
		}
		$cntSSNStr = implode(", ", $cntSSN);
		echo "Contribution for this date has already been added for $cnt employees. They are: $cntSSNStr";
		echo "<br>";
		echo "<br>";
	}
	if($sess['post']['plannotselected']) {
		$cnt = 0;
		$cntSSN = array();
		foreach($sess['post']['plannotselected'] as $key => $value) {
			$cnt += 1;
			$cntSSN[] = $key;
		}
		$cntSSNStr = implode(", ", $cntSSN);
		echo "Plan not selected for $cnt employees. They are: $cntSSNStr";
		echo "<br>";
		echo "<br>";
	}
	if($sess['post']['nossnfound']) {
		$cnt = 0;
		$cntSSN = array();
		foreach($sess['post']['nossnfound'] as $key => $value) {
			$cnt += 1;
			$cntSSN[] = $key;
		}
		$cntSSNStr = implode(", ", $cntSSN);
		echo "SSN not found for $cnt employees. They are: $cntSSNStr";
		echo "<br>";
		echo "<br>";
	}
}
function createFormattedMessage($sess) {
	if($sess['post']['process']) {
		$cnt = 0;
		$cntSSN = array();
		foreach($sess['post']['process'] as $key => $value) {
			$cnt += 1;
			$cntSSN[] = $key;
		}
		$cntSSNStr = implode(", ", $cntSSN);
		echo "Process successfull for $cnt employees. They are: $cntSSNStr";
		echo "<br>";
		echo "<br>";
	}
	if($sess['post']['contributionalreadyaddedforthisdate']) {
		$cnt = 0;
		$cntSSN = array();
		foreach($sess['post']['contributionalreadyaddedforthisdate'] as $key => $value) {
			$cnt += 1;
			$cntSSN[] = $key;
		}
		$cntSSNStr = implode(", ", $cntSSN);
		echo "Contribution for this date has already been added for $cnt employees. They are: $cntSSNStr";
		echo "<br>";
		echo "<br>";
	}
	if($sess['post']['plannotselected']) {
		$cnt = 0;
		$cntSSN = array();
		foreach($sess['post']['plannotselected'] as $key => $value) {
			$cnt += 1;
			$cntSSN[] = $key;
		}
		$cntSSNStr = implode(", ", $cntSSN);
		echo "Plan not selected for $cnt employees. They are: $cntSSNStr";
		echo "<br>";
		echo "<br>";
	}
	if($sess['post']['nossnfound']) {
		$cnt = 0;
		$cntSSN = array();
		foreach($sess['post']['nossnfound'] as $key => $value) {
			$cnt += 1;
			$cntSSN[] = $key;
		}
		$cntSSNStr = implode(", ", $cntSSN);
		echo "SSN not found for $cnt employees. They are: $cntSSNStr";
		echo "<br>";
		echo "<br>";
	}
}
function getAllEmployerList($name, $div) {
	$rs = mysql_query("select employer_id, name from employer order by name") or die('could not get employer details');	
	?>
	<select name="<?php echo $name; ?>" id="<?php echo $name; ?>" onchange="doAjaxXMLSelectBox('<?php echo HTTPPATH; ?>/main/getPlan.php','GET','employer_id='+this.value,'',<?php echo $div; ?>);">
	<option value="0">Select Employer</option>
	<?php 
	while($rec = mysql_fetch_array($rs)) {
	?>
		<option value="<?php echo $rec['employer_id']; ?>"><?php echo $rec['name']; ?></option>
	<?php
	}
	?>
	</select>
	<?php
}

function addEmployee($f,$m,$l,$s,$er,$h,$d,$a) {
	global $encryption;
	$sql = "INSERT INTO employee (firstname, middlename, lastname, ssn, employer_id, hire_date, dob, account_number, created_dt, status) VALUES ('".addslashes(stripslashes(trim($f)))."', '".addslashes(stripslashes(trim($m)))."', '".addslashes(stripslashes(trim($l)))."', '".addslashes(stripslashes(trim($encryption->processEncrypt('ssn', $s))))."', '".addslashes(stripslashes(trim($er)))."', '".addslashes(stripslashes(trim($h)))."', '".addslashes(stripslashes(trim($encryption->processEncrypt('dob', $d))))."', '".addslashes(stripslashes(trim($encryption->processEncrypt('account_number', $a))))."', '".time()."', '1')";
	$rs = mysql_query($sql) or die(__LINE__.'error2'.__FILE__.' due to '.mysql_error());
	$id = mysql_insert_id();
	return $id;
}

function addVendor($i,$pl) {
	$query = "select vendor_id from vendor_plan where plan_id = '".$pl."'";
	$rs = mysql_query($query) or die(__LINE__.'error3'.__FILE__);
	$rec = mysql_fetch_array($rs);
	$v = $rec['vendor_id'];
	$query = "insert into employee_vendor set employee_id = '".$i."', plan_id = '".$pl."', vendor_id = '".$v."'";
	mysql_query($query) or die(__LINE__.'error2'.__FILE__.' due to '.mysql_error());
	return $v;
}

function addContribution($i,$er,$pl,$v,$sp,$sr,$cd) {
	$query = "insert into employee_contribution set employee_id = '".$i."', plan_id = '".$pl."', vendor_id = '".$v."', employer_id = '".$er."', contribution_date = '".$cd."', sra_pretax = '".$sp."', sra_roth = '".$sr."'";
	mysql_query($query) or die(__LINE__.'error2'.__FILE__.' due to '.mysql_error());
}

function getAllPlans($employer_id, $name) {
	$query = "select ev.plan_id as id, p.plan_name as name, v.name as vendorname from employer_vendor as ev, vendor_plan as p, vendor as v WHERE ev.plan_id = p.plan_id and ev.vendor_id = v.vendor_id and ev.employer_id = '".$employer_id."'";
	$rs = mysql_query($query) or die('could not get employer details');	
	?>
	<select name="<?php echo $name; ?>" id="<?php echo $name; ?>">
	<option value="0">Select Plan</option>
	<?php 
	while($rec = mysql_fetch_array($rs)) {
	?>
		<option value="<?php echo $rec['id']; ?>"><?php echo $rec['vendorname']; ?> (<?php echo $rec['name']; ?>)</option>
	<?php
	}
	?>
	</select>
	<?php
}

function insertDetails($arr) {
	global $encryption;
	if($arr['e']>0) {
		$dup = 0;
	} else {
		$dup = -1;
	}
	$sql = "INSERT INTO `employee_contribution_details` (`cdate` , `ssn` , `employee_id` , `pretax` , `roth` , `pretax_refund` , `roth_refund`, `duplicate_record` ) VALUES ('".$arr['d']."', '".addslashes(stripslashes(trim($encryption->processEncrypt('ssn', $arr['s']))))."', '".$arr['e']."', '".$arr['p']."', '".$arr['r']."', '".$arr['pr']."', '".$arr['rr']."', '".$dup."' )";
	@mysql_query($sql);
	$rows = mysql_affected_rows();
	if($rows<1) {
		if($arr['e']>0) {			
			$sql = "update `employee_contribution_details` set `employee_id` = '".$arr['e']."', `pretax` = '".$arr['p']."', `roth` = '".$arr['r']."', duplicate_record = duplicate_record+1 where `cdate` = '".$arr['d']."' and `ssn` = '".addslashes(stripslashes(trim($encryption->processEncrypt('ssn', $arr['s']))))."'";
		} else {
			$sql = "update `employee_contribution_details` set `employee_id` = '".$arr['e']."', `pretax` = '".$arr['p']."', `roth` = '".$arr['r']."', duplicate_record = '".$dup."' where `cdate` = '".$arr['d']."' and `ssn` = '".addslashes(stripslashes(trim($encryption->processEncrypt('ssn', $arr['s']))))."'";
		}
		mysql_query($sql) or die('error 2');
	}
	return $rows;
}
function updateTransactionTable($date, $employer_id) {
	global $database_dw_conn, $dw_conn;
	$colname_rsfailed = "-1";
	if (isset($date)) {
	  $colname_rsfailed = (get_magic_quotes_gpc()) ? $date : addslashes($date);
	}
	mysql_select_db($database_dw_conn, $dw_conn);
	$query_rsfailed = sprintf("SELECT COUNT(employee_id) as failed, cdate FROM employee_contribution_details WHERE (employee_id = 0 or employee_id is null or employee_id = '') AND cdate = '%s' GROUP BY cdate", $colname_rsfailed);
	$rsfailed = mysql_query($query_rsfailed, $dw_conn) or die(mysql_error());
	$row_rsfailed = mysql_fetch_assoc($rsfailed);
	$totalRows_rsfailed = mysql_num_rows($rsfailed);
	
	$colname_rsSuccess = "-1";
	if (isset($date)) {
	  $colname_rsSuccess = (get_magic_quotes_gpc()) ? $date : addslashes($date);
	}
	mysql_select_db($database_dw_conn, $dw_conn);
	$query_rsSuccess = sprintf("SELECT COUNT(employee_id) as success, cdate FROM employee_contribution_details WHERE employee_id > 0 AND cdate = '%s' GROUP BY cdate", $colname_rsSuccess);
	$rsSuccess = mysql_query($query_rsSuccess, $dw_conn) or die(mysql_error());
	$row_rsSuccess = mysql_fetch_assoc($rsSuccess);
	$totalRows_rsSuccess = mysql_num_rows($rsSuccess);
	
	$colname_rsTotal = "-1";
	if (isset($date)) {
	  $colname_rsTotal = (get_magic_quotes_gpc()) ? $date : addslashes($date);
	}
	mysql_select_db($database_dw_conn, $dw_conn);
	$query_rsTotal = sprintf("SELECT SUM(pretax) as totalPretax, SUM(roth) as totalRoth, SUM(pretax_refund) as totalPretaxRefund, SUM(roth_refund) as toatlRothRefund, cdate FROM employee_contribution_details WHERE employee_id > 0 AND cdate = '%s' GROUP BY cdate", $colname_rsTotal);
	$rsTotal = mysql_query($query_rsTotal, $dw_conn) or die(mysql_error());
	$row_rsTotal = mysql_fetch_assoc($rsTotal);
	$totalRows_rsTotal = mysql_num_rows($rsTotal);
	
	$colname_rsGrandTotal = "-1";
	if (isset($date)) {
	  $colname_rsGrandTotal = (get_magic_quotes_gpc()) ? $date : addslashes($date);
	}
	mysql_select_db($database_dw_conn, $dw_conn);
	$query_rsGrandTotal = sprintf("SELECT SUM(pretax) as grandtotalPretax, SUM(roth) as grandtotalRoth, SUM(pretax_refund) as grandtotalPretaxRefund, SUM(roth_refund) as grandtoatlRothRefund, cdate FROM employee_contribution_details WHERE cdate = '%s' GROUP BY cdate", $colname_rsGrandTotal);
	$rsGrandTotal = mysql_query($query_rsGrandTotal, $dw_conn) or die(mysql_error());
	$row_rsGrandTotal = mysql_fetch_assoc($rsGrandTotal);
	$totalRows_rsGrandTotal = mysql_num_rows($rsGrandTotal);
	
	$colname_rsDuplicate = "-1";
	if (isset($date)) {
	  $colname_rsDuplicate = (get_magic_quotes_gpc()) ? $date : addslashes($date);
	}
	mysql_select_db($database_dw_conn, $dw_conn);
	$query_rsDuplicate = sprintf("SELECT COUNT(duplicate_record) as duplicate, cdate FROM employee_contribution_details WHERE duplicate_record > 0 AND employee_id > 0 AND cdate = '%s' GROUP BY cdate", $colname_rsDuplicate);
	$rsDuplicate = mysql_query($query_rsDuplicate, $dw_conn) or die(mysql_error());
	$row_rsDuplicate = mysql_fetch_assoc($rsDuplicate);
	$totalRows_rsDuplicate = mysql_num_rows($rsDuplicate);
	
	$sql = "select * from employee_contribution_transaction where transaction_date = '".$date."' and employer_id = '".$employer_id."'";
	$rs = mysql_query($sql) or die('error4');
	$num = mysql_num_rows($rs);
	if($num>0) {
		// update
		$rec = mysql_fetch_array($rs);
		$sql = "UPDATE `employee_contribution_transaction` SET `totalrecords` = '".($row_rsfailed['failed']+$row_rsSuccess['success'])."',
`totalamount` = '".$row_rsGrandTotal['grandtotalPretax']."',
`totalamountprocessed` = '".$row_rsTotal['totalPretax']."',
`totalamountrefunded` = '".$row_rsTotal['totalPretaxRefund']."',
`totalrecordsprocessedsuccessfully` = '".$row_rsSuccess['success']."',
`totalrecordsrejected` = '".$row_rsfailed['failed']."',
`recordsalreadyuploaded` = '".$row_rsDuplicate['duplicate']."',
`totalroth` = '".$row_rsGrandTotal['grandtotalRoth']."',
`totalrothprocessed` = '".$row_rsTotal['totalRoth']."',
`totalrothrefunded` = '".$row_rsTotal['toatlRothRefund']."' WHERE `employee_contribution_transaction`.`transaction_id` = '".$rec['transaction_id']."' LIMIT 1";
		mysql_query($sql) or die('error 3'.mysql_error());
		$uid = $rec['transaction_id'];
	} else {
		// insert
		$sql = "INSERT INTO `employee_contribution_transaction` SET `transaction_date` = '".$date."', `employer_id` = '".$employer_id."',
`totalrecords` = '".($row_rsfailed['failed']+$row_rsSuccess['success'])."',
`totalamount` = '".$row_rsGrandTotal['grandtotalPretax']."',
`totalamountprocessed` = '".$row_rsTotal['totalPretax']."',
`totalamountrefunded` = '".$row_rsTotal['totalPretaxRefund']."',
`totalrecordsprocessedsuccessfully` = '".$row_rsSuccess['success']."',
`totalrecordsrejected` = '".$row_rsfailed['failed']."',
`recordsalreadyuploaded` = '".$row_rsDuplicate['duplicate']."',
`totalroth` = '".$row_rsGrandTotal['grandtotalRoth']."',
`totalrothprocessed` = '".$row_rsTotal['totalRoth']."',
`totalrothrefunded` = '".$row_rsTotal['toatlRothRefund']."' ";
		mysql_query($sql) or die('error 3'.mysql_error());
		$uid = mysql_insert_id();
	}
	return $uid;
}
function vendorEmployerCheck($employer_id, $vendor_id, $plan_id) {
	$sql = "select * from employer_vendor where employer_id = '".$employer_id."' and vendor_id = '".$vendor_id."' and plan_id = '".$plan_id."'";
	$rs = mysql_query($sql) or die('error');
	return mysql_num_rows($rs);
}
function postProcessMod($array, $post) {
	global $encryption;
	$total = count($array['detailIds']);
	$sess['post']['total'] = $total;
	$sess['post']['records'] = $array;
	$sess['contribution_date'] = $post['contribution_date'];
	$sess['employer_id'] = $post['employer_id'];
	if(!$array['detailIds']) {
		$sess['post']['errorMessage'][] = 'No Records Uploaded. ';
	} else {
		//mysql_query("insert into employee_contribution_transaction set transaction_date = '".addslashes(stripslashes(trim($post['contribution_date'])))."', employer_id = '".$post['employer_id']."'") or die('error');
		foreach($array['detailIds'] as $i => $value) {
			$ssn = $array['detailIds'][$i][5];
			$contribution_pretax = $array['detailIds'][$i][6];
			$contribution_pretax = str_replace("$","",$contribution_pretax);
			$contribution_pretax = trim($contribution_pretax);
			$contribution_pretax = str_replace(",","",$contribution_pretax);
			if($contribution_pretax=="") { continue; }
			if(ROTH==1) {
				$contribution_roth = $array['detailIds'][$i][7];
				$contribution_roth = str_replace("$","",$contribution_roth);
				$contribution_roth = trim($contribution_roth);
				$contribution_roth = str_replace(",","",$contribution_roth);
				if(!$contribution_roth) $contribution_roth = 0; // added
				$account = $array['detailIds'][$i][8];				
			} else {
				$contribution_roth = 0;
				$account = $array['detailIds'][$i][7];
			}
			$company = $array['detailIds'][$i][1];
			$lastname = $array['detailIds'][$i][2];
			$firstname = $array['detailIds'][$i][3];
			$middlename = $array['detailIds'][$i][4];
			$sess['success'] = 1;
			$sess['post']['details'][$ssn]['ssn'] = $ssn;
			$sess['post']['details'][$ssn]['contribution_pretax'] = $contribution_pretax;
			$sess['post']['details'][$ssn]['contribution_roth'] = $contribution_roth;
			$sess['post']['details'][$ssn]['company'] = $company;
			$sess['post']['details'][$ssn]['lastname'] = $lastname;
			$sess['post']['details'][$ssn]['firstname'] = $firstname;
			$sess['post']['details'][$ssn]['middlename'] = $middlename;
			$sess['post']['details'][$ssn]['account'] = $account;
			$sql = "select * from employee where ssn = '".addslashes(stripslashes(trim($encryption->processEncrypt('ssn', $ssn))))."'";
			$result = mysql_query($sql) or die('error'.mysql_error());
			$num = mysql_num_rows($result);
			if($num>0) {
				$rec = mysql_fetch_array($result);
				$employee_id = $rec['employee_id'];
				$employeeDetails = getEmployeeDetails($employee_id);
				$plan = getPlanId($employee_id);
				// check if vender is active for current employer , if it is not active then dont proceed for this employee
				$vendorCheck = vendorEmployerCheck($employeeDetails['employer_id'], $plan['vendor_id'], $plan['plan_id']); 
				if($vendorCheck==0) {
					$sess['post']['vendorinactive'][$employee_id] = $plan['vendor_id'];
					$sess['post']['vendorplaninactive'][$employee_id] = $plan['plan_id'];
					$sess['post']['vendorinactivessn'][$employee_id] = $ssn;
					$arr['d'] = $post['contribution_date'];
					$arr['s'] = $ssn;
					$arr['e'] = $employee_id;
					$arr['p'] = $contribution_pretax;
					$arr['r'] = $contribution_roth;
					$arr['pr'] = $process['refund'];
					$arr['rr'] = $rothProcess['refund'];
					
					$retVal = insertDetails($arr);
					if($retVal!=1) {
						$sess['duplicate'] += 1;
					}
					continue;
				}
				$process = processContribution($employee_id, $year=date('Y'), $curr_month_contribution=$contribution_pretax, $hireDate=$employeeDetails['hire_date'], $dob=$employeeDetails['dob']);				
				$process['plan'] = $plan;
				$process['contribution_date'] = $post['contribution_date'];
				$process['contribution_pretax'] = $contribution_pretax;
				$process['contribution_roth'] = $contribution_roth;
				$sess['post']['process'][$ssn][] = $process;			
				if($process['employerDetails']['roth_provision']=='Y') $roth = $process['contribution_roth']; else $roth = 0;					
				$rothProcess = processRothContribution($employee_id, $year=date('Y'), $roth);
				
				$arr['d'] = $post['contribution_date'];
				$arr['s'] = $ssn;
				$arr['e'] = $employee_id;
				$arr['p'] = $contribution_pretax;
				$arr['r'] = $contribution_roth;
				$arr['pr'] = $process['refund'];
				$arr['rr'] = $rothProcess['refund'];
				
				$retVal = insertDetails($arr);
				$sql = "select * from employee_contribution where employee_id = '".$process['employee_id']."' and contribution_date = '".addslashes(stripslashes(trim($process['contribution_date'])))."'";
				$rs = mysql_query($sql) or die("error");
				$num2 = mysql_num_rows($rs);
				if(mysql_num_rows($rs)==0) {
					if($process['refund']==0) {
						$sql = "insert into employee_contribution set employee_id = '".$process['employee_id']."', plan_id = '".$process['plan']['plan_id']."', vendor_id = '".$process['plan']['vendor_id']."', employer_id = '".$process['employerDetails']['employer_id']."', contribution_date = '".addslashes(stripslashes(trim($process['contribution_date'])))."', sra_pretax = '".addslashes(stripslashes(trim($process['contribution_pretax'])))."', sra_roth = '".$roth."'";
						$result3 = mysql_query($sql) or die(mysql_error());
					} else {						
						$sql = "insert into employee_contribution set employee_id = '".$process['employee_id']."', plan_id = '".$process['plan']['plan_id']."', vendor_id = '".$process['plan']['vendor_id']."', employer_id = '".$process['employerDetails']['employer_id']."', contribution_date = '".addslashes(stripslashes(trim($process['contribution_date'])))."', sra_pretax = '".addslashes(stripslashes(trim($process['contribution_pretax'])))."', sra_roth = '".$roth."'";				
						$result3 = mysql_query($sql) or die(mysql_error());
						addExcessWorkflowPretax($process['employee_id'], $process['employerDetails']['employer_id'], $process['refund'], $post['contribution_date']);
					}						
					if($rothProcess['refund']==0) {
					
					} else {						
						addExcessWorkflowRoth($process['employee_id'], $process['employerDetails']['employer_id'], $rothProcess['refund'], $post['contribution_date']);
					}	
				} else { // end of if mysqlnumrows 
					$sess['post']['contributionalreadyaddedforthisdate'][] = $arr['s'];
				}
			} else {
				$sess['post']['nossnfound'][$ssn][] = $ssn;
				$employee_id = 0;
				$arr['d'] = $post['contribution_date'];
				$arr['s'] = $ssn;
				$arr['e'] = $employee_id;
				$arr['p'] = $contribution_pretax;
				$arr['r'] = $contribution_roth;
				$arr['pr'] = $process['refund'];
				$arr['rr'] = $rothProcess['refund'];
				
				$retVal = insertDetails($arr);
				if($retVal!=1) {
					$sess['duplicate'] += 1;
				}
			}	
		}
	}		
	$sess['transactionId'] = updateTransactionTable($post['contribution_date'], $sess['employer_id']);
	return $sess;
}
?>