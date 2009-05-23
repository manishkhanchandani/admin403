<?php require_once('../Connections/dw_conn.php'); ?>
<?php 
include_once('start.php');

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	$sql = "select * from employee_contribution where employee_id = '".$_GET['employee_id']."' and contribution_date = '".addslashes(stripslashes(trim($_POST['contribution_date'])))."'";
	$result2 = mysql_query($sql) or die("error");
	$num = mysql_num_rows($result2);
	if($num>0) {
		$_POST["MM_insert"] == "";
		$errorMessage = '<p class=errorMessage>Contribution already added for this date.</p>';
	} else {
		$errorMessage = '<p class=errorMessage>Contribution Added Successfully</p>';
	}
}
?>
<?php
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO employee_contribution (employee_id, plan_id, vendor_id, employer_id, contribution_date, sra_pretax, sra_roth) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['employee_id'], "int"),
                       GetSQLValueString($_POST['plan_id'], "int"),
                       GetSQLValueString($_POST['vendor_id'], "int"),
                       GetSQLValueString($_POST['employer_id'], "int"),
                       GetSQLValueString($_POST['contribution_date'], "date"),
                       GetSQLValueString($_POST['sra_pretax'], "double"),
                       GetSQLValueString($_POST['sra_roth'], "double"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($insertSQL, $dw_conn) or die(mysql_error());
}

$colname_rsPlan = "-1";
if (isset($_GET['employee_id'])) {
  $colname_rsPlan = $_GET['employee_id'];
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsPlan = sprintf("SELECT * FROM employee_vendor WHERE employee_id = %s", GetSQLValueString($colname_rsPlan, "int"));
$rsPlan = mysql_query($query_rsPlan, $dw_conn) or die(mysql_error());
$row_rsPlan = mysql_fetch_assoc($rsPlan);
$totalRows_rsPlan = mysql_num_rows($rsPlan);

$colname_rsEmployee = "-1";
if (isset($_GET['employee_id'])) {
  $colname_rsEmployee = $_GET['employee_id'];
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployee = sprintf("SELECT employee_id, email, firstname, employee.ssn, employee.employer_id FROM employee WHERE employee_id = %s", GetSQLValueString($colname_rsEmployee, "int"));
$rsEmployee = mysql_query($query_rsEmployee, $dw_conn) or die(mysql_error());
$row_rsEmployee = mysql_fetch_assoc($rsEmployee);
$totalRows_rsEmployee = mysql_num_rows($rsEmployee);

$colname_rsContribution = "-1";
if (isset($_GET['employee_id'])) {
  $colname_rsContribution = $_GET['employee_id'];
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsContribution = sprintf("SELECT * FROM employee_contribution, employee, vendor_plan WHERE employee_contribution.employee_id = employee.employee_id AND employee_contribution.plan_id = vendor_plan.plan_id AND employee.employee_id = %s ORDER BY employee_contribution.contribution_date DESC", GetSQLValueString($colname_rsContribution, "int"));
$rsContribution = mysql_query($query_rsContribution, $dw_conn) or die(mysql_error());
$row_rsContribution = mysql_fetch_assoc($rsContribution);
$totalRows_rsContribution = mysql_num_rows($rsContribution);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Add Monthly Employee Contribution</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Add Employee Monthly Contribution For Employee &quot;<?php echo $row_rsEmployee['firstname']; ?>&quot;</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<?php echo $errorMessage; ?>
<?php if ($totalRows_rsPlan > 0) { // Show if recordset not empty ?>
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
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="thc2">Date:</td>
      <td class="tdc2"><input type="text" name="contribution_date" value="" size="32" /> <input type="button" value="Pick date" onclick="pickDate(this,document.form1.contribution_date);" /></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="thc2">SRA Pretax:</td>
      <td class="tdc2"><input type="text" name="sra_pretax" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="thc2">SRA Roth:</td>
      <td class="tdc2"><input type="text" name="sra_roth" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="thc2">&nbsp;</td>
      <td class="tdc2"><input type="submit" value="Insert record" /></td>
    </tr>
  </table>
  
  
<input type="hidden" name="employee_id" value="<?php echo $_GET['employee_id']; ?>" />
  <input type="hidden" name="plan_id" value="<?php echo $row_rsPlan['plan_id']; ?>" />
  <input name="vendor_id" type="hidden" id="vendor_id" value="<?php echo $row_rsPlan['vendor_id']; ?>" />
  <input type="hidden" name="MM_insert" value="form1" />
  <input name="employer_id" type="hidden" id="employer_id" value="<?php echo $row_rsEmployee['employer_id']; ?>" />
  <span class="tdc2">
  <input name="menuTopItem" type="hidden" id="menuTopItem" value="2" />
  </span>
</form>
</div>
<?php } // Show if recordset not empty ?>
<?php if ($totalRows_rsPlan == 0) { // Show if recordset empty ?>
  <p>Employee has not yet choosen any plan. Please first select plan for employee.</p>
  <?php } // Show if recordset empty ?>
      </td>
    </tr>
</table>
<br />

<?php if ($totalRows_rsContribution > 0) { // Show if recordset not empty ?>
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">View Contribution Data</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
      <tr>
        <td class="thcview2"><strong>SSN</strong></td>
        <td class="thcview2"><strong>Account Number </strong></td>
        <td class="thcview2"><strong>Plan</strong></td>
        <td class="thcview2"><strong>Contribution Date</strong></td>
        <td class="thcview2"><strong>SRA Pretax</strong></td>
        <td class="thcview2"><strong>SRA Roth</strong></td>
        </tr>
      <?php do { ?>
<?php
foreach($row_rsContribution as $key => $value) {
	$row_rsContribution[$key] = $encryption->processDecrypt($key, $value);
}
?>
      <tr>
        <td class="tdcview2"><?php echo $row_rsContribution['ssn']; ?></td>
        <td class="tdcview2"><?php echo $row_rsContribution['account_number']; ?>&nbsp;</td>
        <td class="tdcview2"><?php echo $row_rsContribution['plan_name']; ?></td>
        <td class="tdcview2"><?php echo $row_rsContribution['contribution_date']; ?></td>
        <td class="tdcview2"><?php echo $row_rsContribution['sra_pretax']; ?></td>
        <td class="tdcview2"><?php echo $row_rsContribution['sra_roth']; ?></td>
        </tr>
      <?php } while ($row_rsContribution = mysql_fetch_assoc($rsContribution)); ?>
    </table>
      
      </td>
    </tr>
</table>
<br />
<?php } // Show if recordset not empty ?>
<p>&nbsp;</p>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsPlan);

mysql_free_result($rsEmployee);

mysql_free_result($rsContribution);
?>