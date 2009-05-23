<?php require_once('../Connections/dw_conn.php'); ?>
<?php
include_once('start.php');

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	if($_POST['employee_id']) {
		$_POST['id'] = $_POST['employee_id'];
		$sql = "select email, firstname as name from employee where employee_id = '".$_POST['id']."'";
		$rs = mysql_query($sql) or die('error1');
		$rec = mysql_fetch_array($rs);
		$email = $rec['email'];
		$name = $rec['name'];
		$APPROVERTYPE = 'employee';
	} else if($_POST['employer_id']) {
		$_POST['id'] = $_POST['employer_id'];
		$sql = "select email, name from employer where employer_id = '".$_POST['id']."'";
		$rs = mysql_query($sql) or die('error1');
		$rec = mysql_fetch_array($rs);
		$email = $rec['email'];
		$name = $rec['name'];
		$APPROVERTYPE = 'employer';
	} else if($_POST['vendor_id']) {
		$_POST['id'] = $_POST['vendor_id'];
		$sql = "select email, name from vendor where vendor_id = '".$_POST['id']."'";
		$rs = mysql_query($sql) or die('error1');
		$rec = mysql_fetch_array($rs);
		$email = $rec['email'];	
		$name = $rec['name'];
		$APPROVERTYPE = 'vendor';
	}
	if(!$_POST['title']) {
		$_POST["MM_insert"] = "";
		$errorMessage = "Please enter title. ";
	}
	if(!$_POST['id']) {
		$_POST["MM_insert"] = "";
		$errorMessage = "Please select Approver. ";
	}
}
?>
<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO actions (title, id, requestor_id, requestor_type, action_type, wf_id, status) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($_POST['id'], "int"),
                       GetSQLValueString($_POST['requestor_id'], "int"),
                       GetSQLValueString($_POST['requestor_type'], "text"),
                       GetSQLValueString($_POST['action_type'], "text"),
                       GetSQLValueString($_POST['wf_id'], "int"),
                       GetSQLValueString($_POST['status'], "text"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($insertSQL, $dw_conn) or die(mysql_error());
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	$uid = mysql_insert_id();
  $errorMessage = "Request Created Successfully. You will be now redirected to pending request list.";
  $success=1;
	if(REQUESTOR_TYPE=="Employer") {
		$requestor = $_COOKIE['employer']['email'];
		$url = HTTPPATH."/employer/request_outstanding.php?menuTopItem=1";
  	} 
	if(REQUESTOR_TYPE=="Employee") {
		$requestor = $_COOKIE['employee']['email'];
		$url = HTTPPATH."/employee/request_outstanding.php?menuTopItem=3";
  	} 
	if(REQUESTOR_TYPE=="Vendor") {
		$requestor = $_COOKIE['vendor']['email'];
		$url = HTTPPATH."/vendor/request_outstanding.php?menuTopItem=1";
  	}
	if($APPROVERTYPE=="employer") {
		$requestor = $_COOKIE['employer']['email'];
		$url2 = HTTPPATH."/employer/actions.php?menuTopItem=1";
  	} 
	if($APPROVERTYPE=="employee") {
		$requestor = $_COOKIE['employee']['email'];
		$url2 = HTTPPATH."/employee/actions.php?menuTopItem=1";
  	} 
	if($APPROVERTYPE=="vendor") {
		$requestor = $_COOKIE['vendor']['email'];
		$url2 = HTTPPATH."/vendor/actions.php?menuTopItem=1";
  	}
	$approver = $email;
	$messageRequestor = "
This is an automated notification from the 403b system<br><br>
 
You have created a new request and details of this request is located at: <a href='".$url."'>".$url."</a><br><br>
 
	"; 
	$messageApprover = "
This is an automated notification from the 403b system<br><br>
 
There is an request waiting for your approval. To view/approve this request please go to <a href='".$url2."'>".$url2."</a><br><br>
 
	"; 
	$sql = "select filename, filetype from workflow_documents where id = '".$_GET['id']."'";
	$rs = mysql_query($sql) or die('error');
	while($rec = mysql_fetch_array($rs)) {
		$attachment["../workflow/files/".$rec['filename']] = $rec['filetype'];
	}
	$m = new mymail;
	$m->attachment = $attachment;
	$m->to = $approver;
	$m->from = "Verity Investments<asimonson@verityinvest.com>";
	$m->subject = 'ACTION REQUIRED: "'.$_POST['title'].'" requested by '.$name;
	$m->txt = strip_tags($messageApprover);
	$m->html = $messageApprover;
	$m->emailAttachment();
	
	$m2 = new mymail;
	$m2->attachment = $attachment;
	$m2->to = $requestor;
	$m2->from = "Verity Investments<asimonson@verityinvest.com>";
	$m2->subject = 'REQUEST CREATED: "'.$_POST['title'].'" by '.$name;
	$m2->txt = strip_tags($messageRequestor);
	$m2->html = $messageRequestor;
	$m2->emailAttachment();
}

$colname_rsRequestNew = "-1";
if (isset($_GET['id'])) {
  $colname_rsRequestNew = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsRequestNew = sprintf("SELECT * FROM workflow WHERE id = %s", $colname_rsRequestNew);
$rsRequestNew = mysql_query($query_rsRequestNew, $dw_conn) or die(mysql_error());
$row_rsRequestNew = mysql_fetch_assoc($rsRequestNew);
$totalRows_rsRequestNew = mysql_num_rows($rsRequestNew);

if(REQUESTOR_TYPE=="Employer") {
$colname_rsVendor = "-1";
if (REQUESTOR_ID!="") {
  $colname_rsVendor = (get_magic_quotes_gpc()) ? REQUESTOR_ID : addslashes(REQUESTOR_ID);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsVendor = sprintf("SELECT vendor.name, vendor.vendor_id FROM employer_vendor, vendor WHERE employer_vendor.employer_id = %s AND employer_vendor.vendor_id = vendor.vendor_id GROUP BY employer_vendor.vendor_id", $colname_rsVendor);
$rsVendor = mysql_query($query_rsVendor, $dw_conn) or die(mysql_error());
$row_rsVendor = mysql_fetch_assoc($rsVendor);
$totalRows_rsVendor = mysql_num_rows($rsVendor);

$colname_rsEmployee = "-1";
if (REQUESTOR_ID!="") {
  $colname_rsEmployee = (get_magic_quotes_gpc()) ? REQUESTOR_ID : addslashes(REQUESTOR_ID);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployee = sprintf("SELECT employee_id, firstname FROM employee WHERE employer_id = %s", $colname_rsEmployee);
$rsEmployee = mysql_query($query_rsEmployee, $dw_conn) or die(mysql_error());
$row_rsEmployee = mysql_fetch_assoc($rsEmployee);
$totalRows_rsEmployee = mysql_num_rows($rsEmployee);

}

if(REQUESTOR_TYPE=="Employee") {

$colname_rsEmployee = "-1";
if (REQUESTOR_ID!="") {
  $colname_rsEmployee = (get_magic_quotes_gpc()) ? REQUESTOR_ID : addslashes(REQUESTOR_ID);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployee = sprintf("SELECT employer.name, employer.employer_id FROM employee, employer WHERE employee.employer_id = employer.employer_id AND employee.employee_id = %s", $colname_rsEmployee);
$rsEmployee = mysql_query($query_rsEmployee, $dw_conn) or die(mysql_error());
$row_rsEmployee = mysql_fetch_assoc($rsEmployee);
$totalRows_rsEmployee = mysql_num_rows($rsEmployee);

$colname_rsVendor = "-1";
if (REQUESTOR_ID!="") {
  $colname_rsVendor = (get_magic_quotes_gpc()) ? REQUESTOR_ID : addslashes(REQUESTOR_ID);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsVendor = sprintf("SELECT vendor.name, vendor.vendor_id FROM employee_vendor, vendor WHERE employee_vendor.employee_id = %s AND employee_vendor.vendor_id = vendor.vendor_id GROUP BY employee_vendor.vendor_id", $colname_rsVendor);
$rsVendor = mysql_query($query_rsVendor, $dw_conn) or die(mysql_error());
$row_rsVendor = mysql_fetch_assoc($rsVendor);
$totalRows_rsVendor = mysql_num_rows($rsVendor);

}

if(REQUESTOR_TYPE=="Vendor") {
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployer = "SELECT * FROM employer";
$rsEmployer = mysql_query($query_rsEmployer, $dw_conn) or die(mysql_error());
$row_rsEmployer = mysql_fetch_assoc($rsEmployer);
$totalRows_rsEmployer = mysql_num_rows($rsEmployer);
}
?>
<?php echo $errorMessage; ?>
<?php
if($success==1) {
	?>
	<meta http-equiv="refresh" content="5;URL=request_outstanding.php?menuTopItem=<?php echo $_REQUEST['menuTopItem']; ?>">
	<?php
}
?>
<?php if($success!=1) { ?>
<form name="form1" id="form1" method="POST" action="<?php echo $editFormAction; ?>">
	<table border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
		<tr valign="top">
		  <td align="right" nowrap="nowrap" class="thc2">Title:</td>
		  <td class="tdc2"><input name="title" type="text" id="title" size="32"></td>
	  </tr>
		<tr valign="top">
		  <td align="right" nowrap="nowrap" class="thc2">Name:</td>
		  <td class="tdc2"><input name="action_name" type="hidden" id="action_name" value="<?php echo $row_rsRequestNew['name']; ?>" /><?php echo $row_rsRequestNew['name']; ?></td>
	  </tr>
		<tr valign="top">
		  <td align="right" nowrap="nowrap" class="thc2">Description:</td>
		  <td class="tdc2"><input name="action_description" type="hidden" id="action_description" value="<?php echo htmlspecialchars($row_rsRequestNew['description']); ?>" /><?php echo $row_rsRequestNew['description']; ?>&nbsp;</td>
	  </tr>
		<tr valign="top">
		  <td align="right" nowrap="nowrap" class="thc2">Approver Type: </td>
		  <td class="tdc2"><?php echo $row_rsRequestNew['approver_type']; ?>
		  <input name="action_type" type="hidden" id="action_type" value="<?php echo $row_rsRequestNew['approver_type']; ?>" /></td>
		</tr>
	  <?php if($row_rsRequestNew['approver_type']=="Vendor" && REQUESTOR_TYPE=="Employer") { ?> 
		<tr valign="top">
		  <td align="right" nowrap="nowrap" class="thc2">Approver's Vendor: </td>
		  <td class="tdc2"><select name="vendor_id" id="vendor_id">
			<?php
			do {  
			?>
			<option value="<?php echo $row_rsVendor['vendor_id']?>"><?php echo $row_rsVendor['name']?></option>
			<?php
			} while ($row_rsVendor = mysql_fetch_assoc($rsVendor));
			$rows = mysql_num_rows($rsVendor);
			if($rows > 0) {
			mysql_data_seek($rsVendor, 0);
			$row_rsVendor = mysql_fetch_assoc($rsVendor);
			}
			?>
		  </select>
		  </td>
	  </tr>
	  <?php } ?> 			  
	  <?php if($row_rsRequestNew['approver_type']=="Employee" && REQUESTOR_TYPE=="Employer") { ?> 
		<tr valign="top">
		  <td align="right" nowrap="nowrap" class="thc2">Approver's Employee: </td>
		  <td class="tdc2"><select name="employee_id" id="employee_id">
			<?php
			do {  
			?>
			<option value="<?php echo $row_rsEmployee['employee_id']?>"><?php echo $row_rsEmployee['firstname']?></option>
			<?php
			} while ($row_rsEmployee = mysql_fetch_assoc($rsEmployee));
			$rows = mysql_num_rows($rsEmployee);
			if($rows > 0) {
			mysql_data_seek($rsEmployee, 0);
			$row_rsEmployee = mysql_fetch_assoc($rsEmployee);
			}
			?>
		  </select>
		  </td>
	  </tr>
	  <?php } ?> 		  
	  <?php if($row_rsRequestNew['approver_type']=="Vendor" && REQUESTOR_TYPE=="Employee") { ?> 
		<tr valign="top">
		  <td align="right" nowrap="nowrap" class="thc2">Approver's Vendors: </td>
		  <td class="tdc2"><select name="vendor_id" id="vendor_id">
			  <?php
			do {  
			?>
		  <option value="<?php echo $row_rsVendor['vendor_id']?>"><?php echo $row_rsVendor['name']?></option>
		  <?php
			} while ($row_rsVendor = mysql_fetch_assoc($rsVendor));
			  $rows = mysql_num_rows($rsVendor);
			  if($rows > 0) {
				  mysql_data_seek($rsVendor, 0);
				  $row_rsVendor = mysql_fetch_assoc($rsVendor);
			  }
			?>
		  </select></td>
	  </tr>
	  <?php } ?> 		  
	  <?php if($row_rsRequestNew['approver_type']=="Employer" && REQUESTOR_TYPE=="Employee") { ?> 
		<tr valign="top">
		  <td align="right" nowrap="nowrap" class="thc2">Approver's Employers: </td>
		  <td class="tdc2"><input name="employer_id" type="hidden" id="employer_id" value="<?php echo $row_rsEmployee['employer_id']; ?>" />
		  <?php echo $row_rsEmployee['name']; ?></td>
		</tr>				
	  <?php } ?>
	   <?php if($row_rsRequestNew['approver_type']=="Employer" && REQUESTOR_TYPE=="Vendor") { ?> 
	  <tr valign="top">
				  <td align="right" nowrap="nowrap" class="thc2">Approver's Employer: </td>
	    <td class="tdc2"><select name="employer_id" id="employer_id"<?php if($row_rsRequestNew['approver_type']=="Employee") { ?> onchange="doAjaxXMLSelectBox('../main/getEmployeeList.php','GET','employer_id='+this.value,'',document.form1.employee_id);"<?php } ?>>
				      <option value="0">Select Employer</option>
			        <?php
					do {  
					?>
										  <option value="<?php echo $row_rsEmployer['employer_id']?>"><?php echo $row_rsEmployer['name']?></option>
										<?php
					} while ($row_rsEmployer = mysql_fetch_assoc($rsEmployer));
					  $rows = mysql_num_rows($rsEmployer);
					  if($rows > 0) {
						  mysql_data_seek($rsEmployer, 0);
						  $row_rsEmployer = mysql_fetch_assoc($rsEmployer);
					  }
					?>
			      </select></td>
			  </tr>
			  <?php } ?>
			  <?php if($row_rsRequestNew['approver_type']=="Employee" && REQUESTOR_TYPE=="Vendor") { ?> 
				<tr valign="top">
				  <td align="right" nowrap="nowrap" class="thc2">Approver's Employees: </td>
				  <td class="tdc2"><select name="employee_id" id="employee_id">
			      </select></td>
			  </tr>
			  <?php } ?>
		<tr valign="top">
		  <td align="right" nowrap="nowrap" class="thc2">&nbsp;</td>
		  <td class="tdc2"><input type="submit" name="Submit" value="Create New Request" />
		  <input name="wf_id" type="hidden" id="wf_id" value="<?php echo $row_rsRequestNew['id']; ?>" />
		  <input name="status" type="hidden" id="status" value="Pending" />
		  <input name="menuTopItem" type="hidden" id="menuTopItem" value="<?php echo $_REQUEST['menuTopItem']; ?>" />
		  <input name="id" type="hidden" id="id" />
		  <input name="requestor_id" type="hidden" id="requestor_id" value="<?php echo REQUESTOR_ID; ?>" />
		  <input name="requestor_type" type="hidden" id="requestor_type" value="<?php echo REQUESTOR_TYPE; ?>" /></td>
	  </tr>
  </table>
	<input type="hidden" name="MM_insert" value="form1">
</form>
<?php } ?>
<?php
mysql_free_result($rsRequestNew);
?>