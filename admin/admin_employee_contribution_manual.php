<?php require_once('../Connections/dw_conn.php'); ?>
<?php 
include_once('start.php');

include_once('../main/functions.php'); 
include_once('../main/import_functions.php'); 
?>
<?php
$colname_rsPlan = "-1";
if (isset($_GET['employee_id'])) {
  $colname_rsPlan = (get_magic_quotes_gpc()) ? $_GET['employee_id'] : addslashes($_GET['employee_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsPlan = sprintf("SELECT * FROM employee_vendor WHERE employee_id = %s", $colname_rsPlan);
$rsPlan = mysql_query($query_rsPlan, $dw_conn) or die(mysql_error());
$row_rsPlan = mysql_fetch_assoc($rsPlan);
$totalRows_rsPlan = mysql_num_rows($rsPlan);


$colname_rsEmployee = "-1";
if (isset($_GET['employee_id'])) {
  $colname_rsEmployee = (get_magic_quotes_gpc()) ? $_GET['employee_id'] : addslashes($_GET['employee_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployee = sprintf("SELECT employee_id, email, firstname, employee.ssn, employee.employer_id, employee.hire_date, employee.dob FROM employee WHERE employee_id = %s", $colname_rsEmployee);
$rsEmployee = mysql_query($query_rsEmployee, $dw_conn) or die(mysql_error());
$row_rsEmployee = mysql_fetch_assoc($rsEmployee);
$totalRows_rsEmployee = mysql_num_rows($rsEmployee);


$colname_rsContribution = "-1";
if (isset($_GET['employee_id'])) {
  $colname_rsContribution = (get_magic_quotes_gpc()) ? $_GET['employee_id'] : addslashes($_GET['employee_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsContribution = sprintf("SELECT * FROM employee_contribution, employee, vendor_plan WHERE employee_contribution.employee_id = employee.employee_id AND employee_contribution.plan_id = vendor_plan.plan_id AND employee.employee_id = %s ORDER BY employee_contribution.contribution_date DESC", $colname_rsContribution);
$rsContribution = mysql_query($query_rsContribution, $dw_conn) or die(mysql_error());
$row_rsContribution = mysql_fetch_assoc($rsContribution);
$totalRows_rsContribution = mysql_num_rows($rsContribution);

$employer_id = $row_rsEmployee['employer_id'];

$colname_rsEmployer = "-1";
if (isset($employer_id)) {
  $colname_rsEmployer = (get_magic_quotes_gpc()) ? $employer_id : addslashes($employer_id);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployer = sprintf("SELECT employer.roth_provision FROM employer WHERE employer_id = %s", $colname_rsEmployer);
$rsEmployer = mysql_query($query_rsEmployer, $dw_conn) or die(mysql_error());
$row_rsEmployer = mysql_fetch_assoc($rsEmployer);
$totalRows_rsEmployer = mysql_num_rows($rsEmployer);
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
<style type="text/css">
.concentrate {
font-weight:bold;
}
</style>
<script language="javascript">
	var DHTML_SUITE_THEME = 'zune';
	var DHTML_SUITE_THEME_FOLDER = '../includes/dhtml/themes/';
	var DHTML_SUITE_JS_FOLDER = '../includes/dhtml/js/separateFiles/';
	</script>
<script type="text/javascript" src="../includes/dhtml/js/dhtml-suite-for-applications-without-comments.js"></script>
<script type="text/javascript">
<!--
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

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
  } if (errors) alert('The following error(s) occurred:\n'+errors);
  document.MM_returnValue = (errors == '');
}
//-->
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

<?php
if($row_rsEmployee) {
	foreach($row_rsEmployee as $key => $value) {
		$row_rsEmployee[$key] = $encryption->processDecrypt($key, $value);
	}
}
?>
<form action="admin_employee_manual_contribution_confirmation.php" method="post" name="form1" id="form1" onsubmit="MM_validateForm('contribution_date','','R','sra_pretax','','RisNum');return document.MM_returnValue">
  <table border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="thc2">Date:</td>
      <td class="tdc2"><input type="text" name="contribution_date" value="" size="32" /> <input type="button" value="Pick date" onclick="pickDate(this,document.form1.contribution_date);" /></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="thc2">SRA Pretax:</td>
      <td class="tdc2"><input type="text" name="sra_pretax" value="" size="32" /><?php $cont = maxContributionAvailable($row_rsEmployee['employee_id'], date('Y'), $row_rsEmployee['hire_date'], $row_rsEmployee['dob']); echo " <span class='concentrate'>Max Allowed:</span> $".$cont; ?></td>
    </tr>
	<?php if($row_rsEmployer['roth_provision']!='Y') { ?>
		<input type="hidden" name="sra_roth" value="0"  />
	<?php } else { ?>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="thc2">SRA Roth:</td>
      <td class="tdc2"><input type="text" name="sra_roth" value="" size="32" /> <?php $cont2 = maxRothContributionAvailable($row_rsEmployee['employee_id'], date('Y')); echo " <span class='concentrate'>Max Allowed:</span> $".$cont2; ?></td>
    </tr>
	<?php } ?>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="thc2">&nbsp;</td>
      <td class="tdc2"><input type="submit" value="Insert record" /></td>
    </tr>
  </table>
  
  
<input type="hidden" name="employee_id" value="<?php echo $_GET['employee_id']; ?>" />
  <input type="hidden" name="plan_id" value="<?php echo $row_rsPlan['plan_id']; ?>" />
  <input name="vendor_id" type="hidden" id="vendor_id" value="<?php echo $row_rsPlan['vendor_id']; ?>" />
  <input type="hidden" name="MM_insert" value="1" />
  <input name="employer_id" type="hidden" id="employer_id" value="<?php echo $row_rsEmployee['employer_id']; ?>" />
  <span class="tdc2">
  <input name="menuTopItem" type="hidden" id="menuTopItem" value="2" />
  <input name="ssn" type="hidden" id="ssn" value="<?php echo $row_rsEmployee['ssn']; ?>" />
</span>
</form>
</div>
<?php } // Show if recordset not empty ?>
<?php if ($totalRows_rsPlan == 0) { // Show if recordset empty ?>
  <p>Employee has not yet choosen any <?php echo DISPLAYPLANNAME;?>. Please first select <?php echo DISPLAYPLANNAME;?> for employee.</p>
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
        <td class="thcview2"><strong><?php echo DISPLAYPLANNAME;?></strong></td>
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

mysql_free_result($rsEmployer);
?>