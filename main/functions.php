<?php
include_once('dw_conn.php');
function getVendorIdByName($name, $employer_id) {
	$sql = "select vendor.vendor_id from vendor LEFT JOIN employer_vendor as ev ON vendor.vendor_id = ev.vendor_id where ev.employer_id = '".$employer_id."' AND vendor.name like '".addslashes(stripslashes(trim($name)))."'";
	$rs = mysql_query($sql) or die('error in sel');
	if(mysql_num_rows($rs)) {
		$rec = mysql_fetch_array($rs);
		return $rec['vendor_id'];
	} else {
		return 0;
	}
}
function getPlanIdByName($name, $vid) {
	$sql = "select plan_id from vendor_plan where plan_name like '".addslashes(stripslashes(trim($name)))."' and vendor_id = '".$vid."'";
	$rs = mysql_query($sql) or die('error in sel');
	if(mysql_num_rows($rs)) {
		$rec = mysql_fetch_array($rs);
		return $rec['plan_id'];
	} else {
		$sql = "select plan_id from vendor_plan where vendor_id = '".$vid."'";
		$rs = mysql_query($sql) or die('error in sel');
		if(mysql_num_rows($rs)) {
			$rec = mysql_fetch_array($rs);
			return $rec['plan_id'];
		}
	}
	return false;
}
/*
$vid = getVendorIdByName('',$employer_id=1);
echo "<hr>";
$pid = getPlanIdByName('Lifepath 2020', $vid);
exit;
*/

function setEmployeeVendor($e, $p, $v) {
	$sql = "INSERT INTO employee_vendor (employee_id, plan_id, vendor_id) VALUES ('".$e."', '".$p."', '".$v."')";
	$rs = mysql_query($sql) or die('error in ins');
	return mysql_affected_rows();
}
function getEmployeeNameEmail($id) {
	$sql = "select CONCAT_WS(' ',firstname,middlename,lastname) as name, email from employee where employee_id = $id";
	$rs = mysql_query($sql) or die('e');
	$rec = mysql_fetch_array($rs);
	return $rec;
}
function getEmployerNameEmail($id) {
	$sql = "select name, email from employer where employer_id = $id";
	$rs = mysql_query($sql) or die('e');
	$rec = mysql_fetch_array($rs);
	return $rec;
}
function getVendorNameEmail($id) {
	$sql = "select name, email from vendor where vendor_id = $id";
	$rs = mysql_query($sql) or die('e');
	$rec = mysql_fetch_array($rs);
	return $rec;
}
function getDocument($id) {
	$sql = "select filename, filetype from workflow_documents where id = '".$id."'";
	$rs = mysql_query($sql) or die('error');
	while($rec = mysql_fetch_array($rs)) {
		$attachment["../workflow/files/".$rec['filename']] = $rec['filetype'];
	}
	return $attachment;
}
function getDocumentDetails($id) {
	$sql = "select * from workflow_documents where id = '".$id."'";
	$rs = mysql_query($sql) or die('error');
	while($rec = mysql_fetch_array($rs)) {
		$return[] = $rec;
	}
	return $return;
}
function getEmployerName($id) {
	$sql = "select name from employer where employer_id = $id";
	$rs = mysql_query($sql) or die('e');
	$rec = mysql_fetch_array($rs);
	return $rec['name'];
}
function getEmployeeName($id) {
	$sql = "select CONCAT_WS(' ',firstname,middlename,lastname) as name from employee where employee_id = $id";
	$rs = mysql_query($sql) or die('e');
	$rec = mysql_fetch_array($rs);
	return $rec['name'];
}
function getEmployeeEmail($id) {
	$sql = "select email from employee where employee_id = $id";
	$rs = mysql_query($sql) or die('e');
	$rec = mysql_fetch_array($rs);
	return $rec['email'];
}
function getVendorName($id) {
	$sql = "select name from vendor where vendor_id = $id";
	$rs = mysql_query($sql) or die('e');
	$rec = mysql_fetch_array($rs);
	return $rec['name'];
}
function hasChild($id) {
	$sql = "select * from actions where pid = '".$id."'";
	$rs = mysql_query($sql) or die('e');
	return mysql_num_rows($rs);
}
function changeTense($string) {
	$array = array('Pending'=>'Outstanding', 'Approve'=>'Approved', 'Decline'=>'Declined','Cancel'=>'Cancelled');
	$return = $array[$string];
	return $return;
}
function isValidSSN($ssn) {
	$ssn = trim($ssn);
	if(ereg("^[0-9]{3}-[0-9]{2}-[0-9]{4}$", $ssn)) {
		return 1;
	} else {
		return 0;
	}
}
function validateEmail($email)
{
	if(ereg('^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$', $email)) {
		return 1;
	} else {
		return 0;
	}
} 
function getEmployersVendors($employer_id) {
	$return = array();
	$query = "select * from employer_vendor WHERE employer_id = '".$employer_id."'";
	$rs = mysql_query($query) or die('error in line '.__LINE__.' of file '.__FILE__.' due to mysql error '.mysql_error());
	while($rec = mysql_fetch_array($rs)) {
		$return[] = $rec['plan_id'];
	}
	return $return;
}
function getDelEmployersVendors($employer_id) {
	$return = array();
	$query = "select * from employer_vendor_deleted WHERE employer_id = '".$employer_id."'";
	$rs = mysql_query($query) or die('error in line '.__LINE__.' of file '.__FILE__.' due to mysql error '.mysql_error());
	while($rec = mysql_fetch_array($rs)) {
		$return[] = $rec['plan_id'];
	}
	return $return;
}

function setEmployeeInactive($employer_id, $vendor_id, $plan_id) {
	$sql = "select e.employee_id, ev.emp_plan_id from employee as e INNER JOIN employee_vendor as ev ON e.employee_id = ev.employee_id where e.employer_id = '".$employer_id."' and ev.vendor_id = '".$vendor_id."' and ev.plan_id = '".$plan_id."'";
	$rs = mysql_query($sql) or die('error in line '.__LINE__.' of file '.__FILE__.' due to mysql error '.mysql_error());
	while($rec = mysql_fetch_array($rs)) {
		$sql2 = "insert into employee_vendor_deleted (employee_id, vendor_id, plan_id, del_date) VALUES ('".$rec['employee_id']."', '".$vendor_id."', '".$plan_id."', '".time()."')";
		mysql_query($sql2) or die('error in line '.__LINE__.' of file '.__FILE__.' due to mysql error '.mysql_error());
		$sql3 = "delete from employee_vendor where emp_plan_id = '".$rec['emp_plan_id']."'";
		mysql_query($sql3) or die('error in line '.__LINE__.' of file '.__FILE__.' due to mysql error '.mysql_error());
	}
	return true;
}

function setEmployeeActive($employer_id, $vendor_id, $plan_id) {
	$sql = "select e.employee_id, ev.vendor_del_id from employee as e INNER JOIN employee_vendor_deleted as ev ON e.employee_id = ev.employee_id where e.employer_id = '".$employer_id."' and ev.vendor_id = '".$vendor_id."' and ev.plan_id = '".$plan_id."'";
	$rs = mysql_query($sql) or die('error in line '.__LINE__.' of file '.__FILE__.' due to mysql error '.mysql_error());
	while($rec = mysql_fetch_array($rs)) {
		$sql2 = "insert into employee_vendor (employee_id, vendor_id, plan_id) VALUES ('".$rec['employee_id']."', '".$vendor_id."', '".$plan_id."')";
		mysql_query($sql2) or die('error in line '.__LINE__.' of file '.__FILE__.' due to mysql error '.mysql_error());
		$sql3 = "delete from employee_vendor_deleted where vendor_del_id = '".$rec['vendor_del_id']."'";
		mysql_query($sql3) or die('error in line '.__LINE__.' of file '.__FILE__.' due to mysql error '.mysql_error());
	}
	return true;
}

function addExcessWorkflow($employee_id, $employer_id, $pretax, $maxpretax, $roth, $maxroth, $date) {
	// create workflow for employee as requestor and employer as approver
	$title = "Refund Pretax: ".($pretax-$maxpretax).", Roth: ".($roth-$maxroth)." ".$date;
	$requestor_type = 'Employee';
	$action_type = 'Employer';
	$status = 'Pending';
	$rs = mysql_query("select id from workflow where name = 'Excess Contribution' ORDER BY id ASC LIMIT 1") or die('error');
	if(mysql_num_rows($rs)) {
		$rec = mysql_fetch_array($rs);
		$wf_id = $rec['id'];
	} else {
		$wf_id = 2;
	}
	$insertSQL = "INSERT INTO actions (title, id, requestor_id, requestor_type, action_type, wf_id, status) VALUES ('".$title."', '".$employer_id."', '".$employee_id."', '".$requestor_type."', '".$action_type."', '".$wf_id."', '".$status."')";

  $Result1 = mysql_query($insertSQL) or die(mysql_error());
}

function addExcessWorkflowNew($employee_id, $employer_id, $refund, $date) {
	// create workflow for employee as requestor and employer as approver
	$title = "Refund ". $refund.", On ".$date;
	$requestor_type = 'Employee';
	$action_type = 'Employer';
	$status = 'Pending';
	$sql = "select id from workflow where name = 'Excess Contribution' ORDER BY id ASC LIMIT 1";
	$rs = mysql_query($sql) or die('error');
	if(mysql_num_rows($rs)) {
		$rec = mysql_fetch_array($rs);
		$wf_id = $rec['id'];
	} else {
		$wf_id = 2;
	}
	$insertSQL = "INSERT INTO actions (title, id, requestor_id, requestor_type, action_type, wf_id, status) VALUES ('".$title."', '".$employer_id."', '".$employee_id."', '".$requestor_type."', '".$action_type."', '".$wf_id."', '".$status."')";

  $Result1 = mysql_query($insertSQL) or die(mysql_error());
}

function addExcessWorkflowPretax($employee_id, $employer_id, $refund, $date) {
	// create workflow for employee as requestor and employer as approver
	$title = "Pretax Refund ". $refund.", On ".$date;
	$requestor_type = 'Employee';
	$action_type = 'Employer';
	$status = 'Pending';
	$sql = "select id from workflow where name = 'Excess Contribution' ORDER BY id ASC LIMIT 1";
	$rs = mysql_query($sql) or die('error');
	if(mysql_num_rows($rs)) {
		$rec = mysql_fetch_array($rs);
		$wf_id = $rec['id'];
	} else {
		$wf_id = 2;
	}
	$insertSQL = "INSERT INTO actions (title, id, requestor_id, requestor_type, action_type, wf_id, status) VALUES ('".$title."', '".$employer_id."', '".$employee_id."', '".$requestor_type."', '".$action_type."', '".$wf_id."', '".$status."')";

  $Result1 = mysql_query($insertSQL) or die(mysql_error());
}
function addExcessWorkflowRoth($employee_id, $employer_id, $refund, $date) {
	// create workflow for employee as requestor and employer as approver
	$title = "Roth Refund ". $refund.", On ".$date;
	$requestor_type = 'Employee';
	$action_type = 'Employer';
	$status = 'Pending';
	$sql = "select id from workflow where name = 'Excess Contribution' ORDER BY id ASC LIMIT 1";
	$rs = mysql_query($sql) or die('error');
	if(mysql_num_rows($rs)) {
		$rec = mysql_fetch_array($rs);
		$wf_id = $rec['id'];
	} else {
		$wf_id = 2;
	}
	$insertSQL = "INSERT INTO actions (title, id, requestor_id, requestor_type, action_type, wf_id, status) VALUES ('".$title."', '".$employer_id."', '".$employee_id."', '".$requestor_type."', '".$action_type."', '".$wf_id."', '".$status."')";

  $Result1 = mysql_query($insertSQL) or die(mysql_error());
}
function getMailIds($action_id) {
	$rs = mysql_query("select * from actions where action_id = '".$action_id."'") or die("error");
	$rec = mysql_fetch_array($rs);
	$requesterId = $rec['requestor_id'];
	$requestorType = $rec['requestor_type'];
	$approverId = $rec['id'];
	$approverType = $rec['action_type'];
	
	// get requestors details
	switch($requestorType) {
		case 'Employer':
			break;
		case 'Employee':
			break;
		case 'Vendor':
			break;
	}
	
	// get approvers details
	switch($approverType) {
		case 'Employer':
			break;
		case 'Employee':
			break;
		case 'Vendor':
			break;
	}
	
	// get employee acting as employer and get compliance based on employer_id
	
	// create a mail using email table
	
	// save the mail in queue
}
?>