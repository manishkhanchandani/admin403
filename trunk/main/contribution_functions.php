<?php
include('dw_conn.php');
include_once('start.php');
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
function getSystemSettings($year) {
	$rs = mysql_query("select * from admin_system_settings where year = '".$year."'") or die('error');
	if(mysql_num_rows($rs)) {
		$rec = mysql_fetch_array($rs);
		return $rec;
	} else {
		return 0;
	}
}
function getEmployees() {
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
function getAnnualContribution($contribution_sra_pretax, $contribution_sra_roth, $employee_id, $year) {
	$sql = "select sum(sra_pretax) as contribution_sra_pretax, sum(sra_roth) as contribution_sra_roth from employee_contribution where employee_id = '".$employee_id."' and YEAR(contribution_date) = '".$year."'";
	$rs = mysql_query($sql) or die('error');
	$rec = mysql_fetch_array($rs);
	$rec['contribution_sra_pretax'] += $contribution_sra_pretax;
	$rec['contribution_sra_roth'] += $contribution_sra_roth;
	return $rec;
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

function checkcontribution($contribution_sra_pretax, $contribution_sra_roth, $limit_sra_pretax, $limit_sra_roth, $servicelimitamt, $agelimitamt, $hireDate, $dob) {
	$status['data'] = "Data is contribution_sra_pretax is $contribution_sra_pretax, contribution_sra_roth is $contribution_sra_roth, limit_sra_pretax is $limit_sra_pretax, limit_sra_roth is $limit_sra_roth, servicelimitamt is $servicelimitamt, agelimitamt is $agelimitamt, hireDate is $hireDate, dob is $dob<br>";
	// get service period and age period
	if(!$hireDate) $hireDate = date('Y-m-d'); if(!$dob) $dob = date('Y-m-d'); 
	$servicePeriod = getPeriod($hireDate);
	$agePeriod = getPeriod($dob);
	//echo "Service Period is: $servicePeriod and Age period is: $agePeriod <br>"; // one
	$SERVICE_LIMIT = 15;
	$AGE_LIMIT = 50;
		
	// check if contribution sra pretax is greater than limit sra pretax
	$doAge = 0;
	//echo "test 1: contribution pre tax is: $contribution_sra_pretax and limit pretax is: $limit_sra_pretax<br>";
	if($contribution_sra_pretax>$limit_sra_pretax) {
		//echo "test 1 pass<br>";
		//echo "test 1: servicePeriod is: $servicePeriod and SERVICE_LIMIT is: $SERVICE_LIMIT<br>";
		if($servicePeriod>=$SERVICE_LIMIT) {
			//echo 'service period is greater than limit';
			if($contribution_sra_pretax>$servicelimitamt) {
				$doAge = 1;
				//echo 'contribution pretax is greater than service limit amount so check age<br>';
			} else {
				//echo 'contribution pretax is less than service limit amount<br>';
				$contribution_sra_pretax_status = 1;
			}
		} else {
			//echo 'service period is less than limit<br>';
			$contribution_sra_pretax_status = 0;
		}
		if($agePeriod>=$AGE_LIMIT && $contribution_sra_pretax_status==0) {
			//echo 'age period is greater than age limit and contribution_sra_pretax_status is 0<br>';
			if($contribution_sra_pretax>$agelimitamt) {
				$contribution_sra_pretax_status = 0;
			} else {
				$contribution_sra_pretax_status = 1;
			}
		} else {
			//echo 'age period not considered<br>';		
		}
	} else {
		$contribution_sra_pretax_status = 1;
	}
	
	// check if contribution sra pretax is greater than limit sra pretax
	$doAge = 0;
	//echo "test 1: contribution sra_roth is: $contribution_sra_roth and limit sra_roth is: $limit_sra_roth<br>";
	if($contribution_sra_roth>$limit_sra_roth) {
		// echo "test 1 pass<br>";
		// echo "test 1: servicePeriod is: $servicePeriod and SERVICE_LIMIT is: $SERVICE_LIMIT<br>";
		if($servicePeriod>=$SERVICE_LIMIT) {
			// echo 'service period is greater than limit';
			if($contribution_sra_roth>$servicelimitamt) {
				$doAge = 1;
				// echo 'contribution pretax is greater than service limit amount so check age<br>';
			} else {
				// echo 'contribution pretax is less than service limit amount<br>';
				$contribution_sra_roth_status = 1;
			}
		} else {
			// echo 'service period is less than limit<br>';
			$contribution_sra_roth_status = 0;
		}
		if($agePeriod>=$AGE_LIMIT && $contribution_sra_roth_status==0) {
			// echo 'age period is greater than age limit and contribution_sra_roth_status is 0<br>';
			if($contribution_sra_roth>$agelimitamt) {
				$contribution_sra_roth_status = 0;
			} else {
				$contribution_sra_roth_status = 1;
			}
		} else {
			// echo 'age period not considered<br>';		
		}
	} else {
		$contribution_sra_roth_status = 1;
	}
	
	// getting the final status
	// echo "SRA Roth Status is: $contribution_sra_roth_status and SRA Pretax Status : $contribution_sra_pretax_status <br>"; // two
	$status['sra_roth'] = $contribution_sra_roth_status;
	$status['sra_pretax'] = $contribution_sra_pretax_status;
	$status['servicelimitamt'] = $servicelimitamt;
	$status['agelimitamt'] = $agelimitamt;
	
	return $status;
}
function getMaxAmountNew($employee_id, $year, $curr_month_contribution, $hireDate, $dob) {
	$arr['accept'] = 0;

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
	$arr['curr_month_contribution'] = $curr_month_contribution;
	$arr['curr_contribution'] = $arr['curr_year_contribution']+$arr['curr_month_contribution'];
	if($arr['curr_contribution']>$arr['annual_limit']) {
		// current contriution greater than annual limit
		// get service period and age period
		if(!$hireDate) $hireDate = date('Y-m-d'); if(!$dob) $dob = date('Y-m-d'); 
		$arr['servicePeriod'] = getPeriod($hireDate);
		$arr['agePeriod'] = getPeriod($dob);
		
		if($arr['agePeriod']>50) {
			$arr['annual_limit'] = $arr['annual_limit'] + $arr['annual_age_limit']; // new annual limit
			if($arr['curr_contribution']<$arr['new_annual_limit']) {
				$arr['accept'] = 1;
				return $arr;
			} else { // else of if($arr['curr_contribution']<$arr['new_annual_limit']) {
				$arr['gotoservice'] = 1;
			} // end of if($arr['curr_contribution']<$arr['new_annual_limit']) {
		} else { // else of if($arr['agePeriod']>50) {
			$arr['gotoservice'] = 1;
		} // end of if($arr['agePeriod']>50) {
		if($arr['gotoservice']==1) {
			if($arr['servicePeriod']>15) {
				// perform sql query to find annual contribution for last 5 years
				$prevYear = $year - 5;
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
				
				if($arr['avg']>5000) {
					$catchup = 0;
					$arr['catchup'] = $catchup;
					$arr['accepted_contribution'] = $arr['annual_limit']-$arr['curr_year_contribution'];
					// create a request for refund
					$arr['refund'] = $arr['curr_month_contribution'] - $arr['accepted_contribution'];
					// update catchup
					$arr['catchup'] = $arr['catchup'] + $arr['curr_year_contribution'] + $arr['accepted_contribution'] - ($arr['annual_limit'] - $arr['max_catchup']);
					// update catchup in database
					$sql = "update employee set catchup = '".$arr['catchup']."' where employee_id = '".$employee_id."'";
					$rs = mysql_query($sql) or die('could not update catchup');
					return $arr;
				} else { // else of if($arr['avg']>5000) {
					// read catchup contribution
					$sql = "select catchup from employee where employee_id = '".$employee_id."'";
					$rs = mysql_query($sql) or die('could not select catchup');
					$rec = mysql_fetch_array($rs);
					$arr['catchup'] = $rec['catchup'];
					
					$employer_id = getEmployerId($employee_id);  // get employer idd
					$employerDetails = getEmployerDetails($employer_id); // get employer details
					$arr['servicelimitamount'] = $employerDetails['service_eligible_limit'];
					if((15000-$arr['catchup'])>$arr['servicelimitamount']) {
						$arr['max_catchup'] = $arr['annual_limit'] + 15000 - $arr['catchup'];
					} else { // else of if((15000-$arr['catchup'])>$arr['servicelimitamount']) 
						$arr['max_catchup'] = $arr['servicelimitamount'];
					} // end of if((15000-$arr['catchup'])>$arr['servicelimitamount']) 
					
					$arr['annual_limit'] = $arr['annual_limit'] + $arr['max_catchup']; // new annual limit
					
					if(($arr['curr_month_contribution']+$arr['curr_year_contribution'])>$arr['annual_limit']) {
						$arr['accepted_contribution'] = $arr['annual_limit']-$arr['curr_year_contribution'];
						// create a request for refund
						$arr['refund'] = $arr['curr_month_contribution'] - $arr['accepted_contribution'];
					} else { // else of if(($arr['curr_month_contribution']+$arr['curr_year_contribution'])>$arr['annual_limit']) {
						$arr['accepted_contribution'] = $arr['curr_month_contribution'];
					} // end of if(($arr['curr_month_contribution']+$arr['curr_year_contribution'])>$arr['annual_limit']) {
					$arr['catchup'] = $arr['catchup'] + $arr['curr_year_contribution'] + $arr['accepted_contribution'] - ($arr['annual_limit'] - $arr['max_catchup']);
					return $arr;
				} // end of if($arr['avg']>5000) {	
			} else { // else of if($arr['servicePeriod']>15) {
				$catchup = 0;
				$arr['catchup'] = $catchup;
				
				$arr['accepted_contribution'] = $arr['annual_limit']-$arr['curr_year_contribution'];
				// create a request for refund
				$arr['refund'] = $arr['curr_month_contribution'] - $arr['accepted_contribution'];
				// update catchup
				$arr['catchup'] = $arr['catchup'] + $arr['curr_year_contribution'] + $arr['accepted_contribution'] - ($arr['annual_limit'] - $arr['max_catchup']);
				// update catchup in database
				$sql = "update employee set catchup = '".$arr['catchup']."' where employee_id = '".$employee_id."'";
				$rs = mysql_query($sql) or die('could not update catchup');
				return $arr;
			} // end of if($arr['servicePeriod']>15) {
		} else { // else if($arr['gotoservice']==1) {
		
		} // end of if($arr['gotoservice']==1) {
	} else { // else of if($arr['curr_contribution']>$arr['annual_limit']) {
		// current contribution is less than annual limit
		// return the accepted contribution
		$arr['accept'] = 1;
		return $arr;
	} // end of if($arr['curr_contribution']>$arr['annual_limit']) {	
}

function getMaxAmount($employee_id) {
	// read annual pretax limit from settings
	//$employee_id = $_GET['employee_id'];
	$employer_id = getEmployerId($employee_id);  // get employer idd
	$employerDetails = getEmployerDetails($employer_id); // get employer details
	$contribution_sra_pretax=0;
	$contribution_sra_roth=0;
	$year=date('Y');
	$annual = getAnnualContribution($contribution_sra_pretax, $contribution_sra_roth, $employee_id, $year);
	//print_r($annual);
	$systemSetting = getSystemSettings($year);
	//print_r($systemSetting);
	//echo "<br>";
	if($systemSetting) {
		$limit_sra_pretax = $systemSetting['annual_pretax_limit'];
		$limit_sra_roth = $systemSetting['annual_roth_limit'];
		$agelimitamt = $systemSetting['annual_age_limit'];
	} else {
		$limit_sra_pretax = 0;
		$limit_sra_roth = 0;
		$agelimitamt = 0;								
	}
	//echo "$limit_sra_pretax<br>$limit_sra_roth<br>$agelimitamt<br>"; 
	//$max_sra_pretax_allowed = $limit_sra_pretax-($contribution_sra_pretax-$array['detailIds'][$i][2]);
	//$max_sra_roth_allowed = $limit_sra_roth-($contribution_sra_roth-$sraRoth);
	//echo "$max_sra_pretax_allowed<br>$max_sra_roth_allowed<br>"; 
	// echo $servicelimitamt = $employerDetails['service_eligible_limit'];
	// get hire date and dob from employee details
	$hireDate = $employeeDetails['hire_date'];
	$dob = $employeeDetails['dob'];
	// check status if roth or contribution is allowed
	$status = checkcontribution($contribution_sra_pretax, $contribution_sra_roth, $limit_sra_pretax, $limit_sra_roth, $servicelimitamt, $agelimitamt, $hireDate, $dob);
	//print_r($status);
	// set the status of recors to be inserted
	if($employerDetails['roth_provision']=='Y') {
		if($status['sra_roth']==1 && $status['sra_pretax']==1) {
			$go = 1;		
			if($status['servicelimitamt']>=$status['agelimitamt']) {
				$maxAllowed_pretax = $status['servicelimitamt'];
				$maxAllowed_roth = $status['servicelimitamt'];
				$maxAllowed_pretax = $maxAllowed_pretax-$annual['contribution_sra_pretax'];
				$maxAllowed_roth = $maxAllowed_roth-$annual['contribution_sra_roth'];
			} else if($status['agelimitamt']>=$status['servicelimitamt']) {
				$maxAllowed_pretax = $status['agelimitamt'];
				$maxAllowed_roth = $status['agelimitamt'];
				$maxAllowed_pretax = $maxAllowed_pretax-$annual['contribution_sra_pretax'];
				$maxAllowed_roth = $maxAllowed_roth-$annual['contribution_sra_roth'];
			} else {
				$maxAllowed_pretax = 0;
				$maxAllowed_roth = 0;
			}
		} else {
			$go = 0;
			$maxAllowed_pretax = 0;
			$maxAllowed_roth = 0;
		}
	} else {
		if($status['sra_pretax']==1) {
			$go = 1;
			if($status['servicelimitamt']>=$status['agelimitamt']) {
				$maxAllowed_pretax = $status['servicelimitamt'];
				$maxAllowed_pretax = $maxAllowed_pretax-$annual['contribution_sra_pretax'];
				$maxAllowed_roth = -1;
			} else if($status['agelimitamt']>=$status['servicelimitamt']) {
				$maxAllowed_pretax = $status['agelimitamt'];
				$maxAllowed_pretax = $maxAllowed_pretax-$annual['contribution_sra_pretax'];
				$maxAllowed_roth = -1;
			} else {
				$maxAllowed_pretax = 0;
				$maxAllowed_roth = -1;
			}
		} else {
			$go = 0;
			$maxAllowed = 0;
			$maxAllowed_roth = -1;
		}								
	}
	$arr['maxAllowed_pretax'] = $maxAllowed_pretax;
	$arr['maxAllowed_roth'] = $maxAllowed_roth;
	$arr['go'] = $go;
	$arr['status'] = $status;
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
				$arr['max_catchup'] = $arr['annual_limit'] + $maxCatchUp - $arr['catchup'];
			} else { 
				$arr['max_catchup'] = $arr['servicelimitamount'];
			} // end of if((15000-$arr['catchup'])>$arr['servicelimitamount']) 
      
      $arr['annual_limit'] = $arr['annual_limit'] + $arr['max_catchup']; // new annual limit
      
  }
  
  return $arr['annual_limit'];

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
     
			$employer_id = getEmployerId($employee_id);  // get employer idd
			$employerDetails = getEmployerDetails($employer_id); // get employer details
			$arr['servicelimitamount'] = $employerDetails['service_eligible_limit'];     
      
			if(($maxCatchUp-$arr['catchup'])>$arr['servicelimitamount']) {
				$arr['max_catchup'] = $arr['annual_limit'] + $maxCatchUp - $arr['catchup'];
			} else { 
				$arr['max_catchup'] = $arr['servicelimitamount'];
			} // end of if((15000-$arr['catchup'])>$arr['servicelimitamount']) 
      
      $arr['annual_limit'] = $arr['annual_limit'] + $arr['max_catchup']; // new annual limit
      
  }
  
  
	if($arr['curr_contribution']>$arr['annual_limit']) {
      $arr['accepted_contribution'] = $arr['curr_contribution'] - $arr['annual_limit'];
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
?>