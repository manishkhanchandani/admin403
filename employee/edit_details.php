<?php require_once('../Connections/dw_conn.php'); ?>
<?php
include_once('start.php');
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {	
	foreach($_POST as $key => $value) {
		$_POST[$key] = $encryption->processEncrypt($key, $value);
	}
}

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

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
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE employee SET firstname=%s, middlename=%s, lastname=%s, employer_id=%s, address=%s, married=%s, sex=%s, hire_date=%s, termination_date=%s, dob=%s, account_number=%s, phone=%s, fax=%s, modified_dt=%s WHERE employee_id=%s",
                       GetSQLValueString($_POST['firstname'], "text"),
                       GetSQLValueString($_POST['middlename'], "text"),
                       GetSQLValueString($_POST['lastname'], "text"),
                       GetSQLValueString($_POST['employer_id'], "int"),
                       GetSQLValueString($_POST['address'], "text"),
                       GetSQLValueString($_POST['married'], "text"),
                       GetSQLValueString($_POST['sex'], "text"),
                       GetSQLValueString($_POST['hire_date'], "date"),
                       GetSQLValueString($_POST['termination_date'], "date"),
                       GetSQLValueString($_POST['dob'], "date"),
                       GetSQLValueString($_POST['account_number'], "text"),
                       GetSQLValueString($_POST['phone'], "text"),
                       GetSQLValueString($_POST['fax'], "text"),
                       GetSQLValueString($_POST['modified_dt'], "int"),
                       GetSQLValueString($_POST['employee_id'], "int"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($updateSQL, $dw_conn) or die(mysql_error());

  $updateGoTo = "edit_details.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployer = "SELECT employer_id, name, email FROM employer ORDER BY name ASC";
$rsEmployer = mysql_query($query_rsEmployer, $dw_conn) or die(mysql_error());
$row_rsEmployer = mysql_fetch_assoc($rsEmployer);
$totalRows_rsEmployer = mysql_num_rows($rsEmployer);

$colname_rsEdit = "-1";
if (isset($_COOKIE['employee']['employee_id'])) {
  $colname_rsEdit = $_COOKIE['employee']['employee_id'];
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEdit = sprintf("SELECT * FROM employee WHERE employee_id = %s", GetSQLValueString($colname_rsEdit, "int"));
$rsEdit = mysql_query($query_rsEdit, $dw_conn) or die(mysql_error());
$row_rsEdit = mysql_fetch_assoc($rsEdit);
$totalRows_rsEdit = mysql_num_rows($rsEdit);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Edit Employee</title>
<!-- InstanceEndEditable -->
<?php include('css.php'); ?>
<?php include('js.php'); ?>
<!-- InstanceBeginEditable name="head" -->
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
<!-- InstanceEndEditable -->
</head>

<body>
<?php include('head.php'); ?>
<!-- InstanceBeginEditable name="EditRegion3" -->
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Edit Employee</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<?php echo $error; ?>
<br />
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
	<div id="calendarForForm">
<?php
foreach($row_rsEdit as $key => $value) {
	$row_rsEdit[$key] = $encryption->processDecrypt($key, $value);
}
?>
<form action="<?php echo $editFormAction; ?>" method="POST" name="form1">
      <table border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
        <tr valign="baseline">
          <td align="right" nowrap class="thc2">Email:</td>
          <td class="tdc2"><?php echo $row_rsEdit['email']; ?></td>
        </tr>
        <tr valign="baseline">
          <td align="right" nowrap="nowrap" class="thc2">SSN:</td>
          <td class="tdc2"><?php echo $row_rsEdit['ssn']; ?></td>
        </tr>
        <tr valign="baseline">
          <td align="right" nowrap class="thc2">First name:</td>
          <td class="tdc2"><input type="text" name="firstname" value="<?php echo $row_rsEdit['firstname']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" nowrap class="thc2">Middle name:</td>
          <td class="tdc2"><input type="text" name="middlename" value="<?php echo $row_rsEdit['middlename']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" nowrap class="thc2">Last name:</td>
          <td class="tdc2"><input type="text" name="lastname" value="<?php echo $row_rsEdit['lastname']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" nowrap class="thc2">Employer:</td>
          <td class="tdc2"><select name="employer_id">
            <?php
do {  
?>
            <option value="<?php echo $row_rsEmployer['employer_id']?>"<?php if (!(strcmp($row_rsEmployer['employer_id'], $row_rsEdit['employer_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsEmployer['name']?></option>
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
          <td align="right" nowrap class="thc2">Hire date:</td>
          <td class="tdc2"><input type="text" name="hire_date" value="<?php echo $row_rsEdit['hire_date']; ?>" size="32" readonly="readonly"><!-- 
            <input type="button" value="Pick date" onclick="pickDate(this,document.form1.hire_date);" /> --></td>
        </tr>
        <tr valign="baseline">
          <td align="right" nowrap class="thc2">Termination date:</td>
          <td class="tdc2"><input type="text" name="termination_date" value="<?php echo $row_rsEdit['termination_date']; ?>" size="32" readonly="readonly"><!-- 
            <input type="button" value="Pick date" onclick="pickDate(this,document.form1.termination_date);" /> --></td>
        </tr>
        <tr valign="baseline">
          <td align="right" nowrap class="thc2">Date of Birth:</td>
          <td class="tdc2"><input type="text" name="dob" value="<?php echo $row_rsEdit['dob']; ?>" size="32" readonly="readonly"><!-- 
            <input type="button" value="Pick date" onclick="pickDate(this,document.form1.dob);" /> --></td>
        </tr>
        <tr valign="baseline">
          <td align="right" nowrap class="thc2">Account Number:</td>
          <td class="tdc2"><input type="text" name="account_number" value="<?php echo $row_rsEdit['account_number']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" nowrap class="thc2">Phone:</td>
          <td class="tdc2"><input type="text" name="phone" value="<?php echo $row_rsEdit['phone']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" nowrap class="thc2">Fax:</td>
          <td class="tdc2"><input type="text" name="fax" value="<?php echo $row_rsEdit['fax']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap="nowrap" class="thc2">Address:</td>
          <td valign="top" class="tdc2"><textarea name="address" id="address" cols="45" rows="5"><?php echo $row_rsEdit['address']; ?></textarea></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap="nowrap" class="thc2">Married:</td>
    <td valign="top" class="tdc2"><p>
              <label>
              <input <?php if (!(strcmp($row_rsEdit['married'],"Yes"))) {echo "checked=\"checked\"";} ?> type="radio" name="married" value="Yes" id="married_0" />
                Yes</label>
              <label>
              <input <?php if (!(strcmp($row_rsEdit['married'],"No"))) {echo "checked=\"checked\"";} ?> type="radio" name="married" value="No" id="married_1" />
                No</label>
              <br />
          </p></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap="nowrap" class="thc2">Gender:</td>
    <td valign="top" class="tdc2"><p>
              <label>
              <input <?php if (!(strcmp($row_rsEdit['sex'],"M"))) {echo "checked=\"checked\"";} ?> type="radio" name="sex" value="M" id="sex_0" />
                Male</label>
              <label>
              <input <?php if (!(strcmp($row_rsEdit['sex'],"F"))) {echo "checked=\"checked\"";} ?> type="radio" name="sex" value="F" id="sex_1" />
                Female</label>
              <br />
          </p></td>
        </tr>
        
        <tr valign="baseline">
          <td align="right" nowrap class="thc2">&nbsp;</td>
          <td class="tdc2"><input type="submit" value="Update"></td>
        </tr>
  </table>
<input name="modified_dt" type="hidden" id="modified_dt" value="<?php echo time(); ?>">
<input name="employee_id" type="hidden" id="employee_id" value="<?php echo $row_rsEdit['employee_id']; ?>" />
<input type="hidden" name="MM_update" value="form1" />

<input name="menuTopItem" type="hidden" id="menuTopItem" value="4" />

</form></div>
      </td>
    </tr>
</table>
<br />

    <p>&nbsp;</p>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body><!-- InstanceEnd -->
</html>
<?php
mysql_free_result($rsEmployer);

mysql_free_result($rsEdit);
?>
