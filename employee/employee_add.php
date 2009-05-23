<?php require_once('../Connections/dw_conn.php'); ?>
<?php
include_once('start.php');
include_once('../main/functions.php');
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	if($_POST['ssn']) {
	
	} else {
		$error .= "Please fill ssn. ";
		$_POST["MM_insert"] = "";
	}
	if(isValidSSN($_POST['ssn'])) {
	
	} else {
		$error .= "Please fill valid ssn. ";
		$_POST["MM_insert"] = "";
	}
	if($_POST['email']) {
	
	} else {
		$error .= "Please fill email. ";
		$_POST["MM_insert"] = "";
	}
	if(validateEmail($_POST['email'])) {
	
	} else {
		$error .= "Please fill valid email. ";
		$_POST["MM_insert"] = "";
	}
	if($_POST['password']) {
	
	} else {
		$error .= "Please fill password. ";
		$_POST["MM_insert"] = "";
	}
	if($_POST['cpassword']) {
	
	} else {
		$error .= "Please fill confirm password. ";
		$_POST["MM_insert"] = "";
	}
	if($_POST['password']==$_POST['cpassword']) {
	
	} else {
		$error .= "Password Should be same as Confirm Password. ";
		$_POST["MM_insert"] = "";
	}
	if($_POST['hire_date']) {
	
	} else {
		$error .= "Please fill hire date. ";
		$_POST["MM_insert"] = "";
	}
	if($_POST['dob']) {
	
	} else {
		$error .= "Please fill date of birth. ";
		$_POST["MM_insert"] = "";
	}
	if($_POST['firstname']) {
	
	} else {
		$error .= "Please fill first name. ";
		$_POST["MM_insert"] = "";
	}	
	$_POST['password'] = md5($_POST['password']);
	
	foreach($_POST as $key => $value) {
		$_POST[$key] = $encryption->processEncrypt($key, $value);
	}
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	function checkNewUser($e) {
		$rs = mysql_query("select * from users where email = '".addslashes(stripslashes(trim($e)))."'") or die('error');
		return mysql_num_rows($rs);
	}
	$checkValue = checkNewUser($_POST['email']);
	if($checkValue==0) {
		$sql = "INSERT INTO `users` (`email` , `password` , `created_dt` , `login_type` , `acting_as` ) VALUES ('".addslashes(stripslashes(trim($_POST['email'])))."', '".$_POST['password']."', '".date('Y-m-d H:i:s')."', 'Employee', NULL)";
		$rs = mysql_query($sql) or die('could not insert in users table');
		$user_id = mysql_insert_id();
	} else {
		$error = "Email already exits, please use another email address.";
		$_POST["MM_insert"] = "";
	}
}
?>
<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

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
  $insertSQL = sprintf("INSERT INTO employee (password, email, firstname, middlename, lastname, ssn, employer_id, address, married, sex, hire_date, termination_date, dob, account_number, phone, fax, created_dt, status) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['firstname'], "text"),
                       GetSQLValueString($_POST['middlename'], "text"),
                       GetSQLValueString($_POST['lastname'], "text"),
                       GetSQLValueString($_POST['ssn'], "text"),
                       GetSQLValueString($_POST['employer_id'], "int"),
                       GetSQLValueString($_POST['address'], "text"),
                       GetSQLValueString($_POST['married'], "text"),
                       GetSQLValueString($_POST['sex'], "text"),
                       GetSQLValueString($_POST['hire_date'], "int"),
                       GetSQLValueString($_POST['termination_date'], "int"),
                       GetSQLValueString($_POST['dob'], "date"),
                       GetSQLValueString($_POST['account_number'], "text"),
                       GetSQLValueString($_POST['phone'], "text"),
                       GetSQLValueString($_POST['fax'], "text"),
                       GetSQLValueString($_POST['created_dt'], "int"),
                       GetSQLValueString($_POST['status'], "int"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($insertSQL, $dw_conn) or die(mysql_error());
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	$uid = mysql_insert_id();
	$sql = "update `users` set id = '".$uid."' where user_id = '".$user_id."'";
	$rs = mysql_query($sql) or die('could not insert in users table');
		
	$insertGoTo = "../main/login.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployer = "SELECT employer_id, name, email FROM employer ORDER BY name ASC";
$rsEmployer = mysql_query($query_rsEmployer, $dw_conn) or die(mysql_error());
$row_rsEmployer = mysql_fetch_assoc($rsEmployer);
$totalRows_rsEmployer = mysql_num_rows($rsEmployer);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Create New Employee</title>

<?php include('css.php'); ?>
<?php include('js.php'); ?>
<script language="javascript">
	var DHTML_SUITE_THEME = 'zune';
	var DHTML_SUITE_THEME_FOLDER = '../includes/dhtml/themes/';
	var DHTML_SUITE_JS_FOLDER = '../includes/dhtml/js/separateFiles/';
	</script>
<script type="text/javascript" src="../includes/dhtml/js/dhtml-suite-for-applications-without-comments.js"></script>
<script type="text/javascript">
	function calendarMonthChange(inputArray)
	{
		var calendarRef = inputArray.calendarRef;
		
		var month = inputArray.month;
		var year = inputArray.year;
		month++;
		if(month>12){
			month=1;
			year++;
		}
		
		var objectToChange = false;
		switch(calendarRef.id)
		{
			case "calendar1":
				objectToChange = myCalendar2;
				break;
			case "calendar2":	
				objectToChange = myCalendar3;
				break;
			case "calendar3":
				month-=3;
				if(month<1){
					month=12 + month;
					year--;	
				}
				objectToChange = myCalendar;
				break;
		}
		objectToChange.setDisplayedMonth(month);
		objectToChange.setDisplayedYear(year);
	}
	</script>

</head>

<body>
<table border="6" cellspacing="0" cellpadding="3" class="blacktbl" align="center">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Add New Employee</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<?php if($error) { ?>
<p class="error"><?php echo $error; ?></p>
<?php } ?>
<script type="text/javascript">
	var calendarObjForForm = new DHTMLSuite.calendar({minuteDropDownInterval:10,numberOfRowsInHourDropDown:5,callbackFunctionOnDayClick:'getDateFromCalendar',isDragable:true,displayTimeBar:true}); 
	calendarObjForForm.setCallbackFunctionOnClose('myOtherFunction');
	
	function myOtherFunction()
	{
		
		
	}
	function pickDate(buttonObj,inputObject)
	{
		calendarObjForForm.setCalendarPositionByHTMLElement(inputObject,0,inputObject.offsetHeight+2);	// Position the calendar right below the form input
		calendarObjForForm.setInitialDateFromInput(inputObject,'yyyy-mm-dd hh:ii');	// Specify that the calendar should set it's initial date from the value of the input field.
		calendarObjForForm.addHtmlElementReference('myDate',inputObject);	// Adding a reference to this element so that I can pick it up in the getDateFromCalendar below(myInput is a unique key)
		if(calendarObjForForm.isVisible()){
			calendarObjForForm.hide();
		}else{
			calendarObjForForm.resetViewDisplayedMonth();	// This line resets the view back to the inital display, i.e. it displays the inital month and not the month it displayed the last time it was open.
			calendarObjForForm.display();
		}		
	}	
	/* inputArray is an associative array with the properties
	year
	month
	day
	hour
	minute
	calendarRef - Reference to the DHTMLSuite.calendar object.
	*/
	function getDateFromCalendar(inputArray)
	{
		var references = calendarObjForForm.getHtmlElementReferences(); // Get back reference to form field.
		//references.myDate.value = inputArray.year + '-' + inputArray.month + '-' + inputArray.day + ' ' + inputArray.hour + ':' + inputArray.minute;
		references.myDate.value = inputArray.year + '-' + inputArray.month + '-' + inputArray.day;
		calendarObjForForm.hide();	
		
	}	
	</script>
	<div id="calendarForForm"><form method="post" name="form1" action="<?php echo $editFormAction; ?>">
      <table border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
        <tr valign="baseline">
          <td align="right" valign="top" nowrap class="thc2">Email:</td>
          <td valign="top" class="tdc2"><input type="text" name="email" value="<?php echo $_POST['email']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap class="thc2">Password:</td>
          <td valign="top" class="tdc2"><input type="password" name="password" value="" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap class="thc2">Confirm Password: </td>
          <td valign="top" class="tdc2"><input name="cpassword" type="password" id="cpassword" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap class="thc2">First name:</td>
          <td valign="top" class="tdc2"><input type="text" name="firstname" value="<?php echo $_POST['firstname']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap class="thc2">Middle name:</td>
          <td valign="top" class="tdcview2"><input type="text" name="middlename" value="<?php echo $_POST['middlename']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap class="thc2">Last name:</td>
          <td valign="top" class="tdc"><input type="text" name="lastname" value="<?php echo $_POST['lastname']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap class="thc2">SSN:</td>
          <td valign="top" class="tdc2"><input type="text" name="ssn" value="" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap class="thc2">Employer:</td>
          <td valign="top" class="tdc2"><select name="employer_id">
            <?php
do {  
?>
            <option value="<?php echo $row_rsEmployer['employer_id']?>"<?php if (!(strcmp($row_rsEmployer['employer_id'], $_POST['employer_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsEmployer['name']?></option>
            <?php
} while ($row_rsEmployer = mysql_fetch_assoc($rsEmployer));
  $rows = mysql_num_rows($rsEmployer);
  if($rows > 0) {
      mysql_data_seek($rsEmployer, 0);
	  $row_rsEmployer = mysql_fetch_assoc($rsEmployer);
  }
?>
          </select>          </td>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap class="thc2">Hire date:</td>
          <td valign="top" class="tdc2"><input type="text" name="hire_date" value="<?php echo $_POST['hire_date']; ?>" size="32"> <input type="button" value="Pick date" onclick="pickDate(this,document.form1.hire_date);" /></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap class="thc2">Termination date:</td>
          <td valign="top" class="tdc2"><input type="text" name="termination_date" value="<?php echo $_POST['termination_date']; ?>" size="32">
          <input type="button" value="Pick date" onclick="pickDate(this,document.form1.termination_date);" /></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap class="thc2">Date of Birth:</td>
          <td valign="top" class="tdc2"><input type="text" name="dob" value="<?php if($_POST['dob']) echo $encryption->processDecrypt('dob', $_POST['dob']); ?>" size="32">
          <input type="button" value="Pick date" onclick="pickDate(this,document.form1.dob);" /></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap class="thc2">Account Number:</td>
          <td valign="top" class="tdc2"><input type="text" name="account_number" value="<?php if($_POST['account_number']) echo $encryption->processDecrypt('account_number', $_POST['account_number']); ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap class="thc2">Phone:</td>
          <td valign="top" class="tdc2"><input type="text" name="phone" value="<?php echo $_POST['phone']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap class="thc2">Fax:</td>
          <td valign="top" class="tdc2"><input type="text" name="fax" value="<?php echo $_POST['fax']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap class="thc2">Address:</td>
          <td valign="top" class="tdc2"><textarea name="address" id="address" cols="45" rows="5"><?php echo $_POST['address']; ?></textarea></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap class="thc2">Married:</td>
          <td valign="top" class="tdc2">
          <?php $married = $_POST['married']; if(!$married) $married = "Yes"; ?>
          <label>
              <input <?php if (!(strcmp($married,"Yes"))) {echo "checked=\"checked\"";} ?> name="married" type="radio" id="married_0" value="Yes" />
              Yes</label>
<label>
              <input <?php if (!(strcmp($married,"No"))) {echo "checked=\"checked\"";} ?> type="radio" name="married" value="No" id="married_1" />
          No</label></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap class="thc2">Gender:</td>
          <td valign="top" class="tdc2">
          <?php $sex = $_POST['sex']; if(!$sex) $sex = "M"; ?>
          
          <label>
              <input <?php if (!(strcmp($sex,"M"))) {echo "checked=\"checked\"";} ?> name="sex" type="radio" id="sex_0" value="M" />
              Male</label>
            <label>
              <input <?php if (!(strcmp($sex,"F"))) {echo "checked=\"checked\"";} ?> type="radio" name="sex" value="F" id="sex_1" />
              Female</label>          </td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap class="thc2">&nbsp;</td>
          <td valign="top" class="tdc2"><input type="submit" value="Insert record"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap class="thc2">&nbsp;</td>
          <td valign="top" class="tdc2"><a href="../main/login.php">Back</a></td>
        </tr>
  </table>
<input type="hidden" name="created_dt" value="<?php echo time(); ?>">
      <input type="hidden" name="status" value="1">
      <input type="hidden" name="MM_insert" value="form1">
      <input name="menuTopItem" type="hidden" id="menuTopItem" value="2" />
	</form></div>
      </td>
    </tr>
</table>
<br />

    <p>&nbsp;</p>
<?php include('end.php'); ?>
</body>
</html>
<?php
mysql_free_result($rsEmployer);
?>
