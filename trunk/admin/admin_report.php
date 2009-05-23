<?php require_once('../Connections/dw_conn.php'); ?>
<?php
include_once('start.php');
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

mysql_select_db($database_dw_conn, $dw_conn);
$query_rsVendorList = "SELECT vendor_id, name FROM vendor";
$rsVendorList = mysql_query($query_rsVendorList, $dw_conn) or die(mysql_error());
$row_rsVendorList = mysql_fetch_assoc($rsVendorList);
$totalRows_rsVendorList = mysql_num_rows($rsVendorList);

mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployer = "SELECT employer_id, name, email FROM employer";
$rsEmployer = mysql_query($query_rsEmployer, $dw_conn) or die(mysql_error());
$row_rsEmployer = mysql_fetch_assoc($rsEmployer);
$totalRows_rsEmployer = mysql_num_rows($rsEmployer);

//mysql_select_db($database_dw_conn, $dw_conn);
//$query_rsEmployee = "SELECT employee_id, email, firstname, ssn FROM employee";
//$rsEmployee = mysql_query($query_rsEmployee, $dw_conn) or die(mysql_error());
//$row_rsEmployee = mysql_fetch_assoc($rsEmployee);
//$totalRows_rsEmployee = mysql_num_rows($rsEmployee);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin Reports</title>
<!-- InstanceEndEditable -->
<?php include('css.php'); ?>
<?php include('js.php'); ?>
<!-- InstanceBeginEditable name="head" -->
<style type="text/css">
	/* CSS for the demo. CSS needed for the scripts are loaded dynamically by the scripts */

	
	#mainContainer{
		width:600px;
		margin:0 auto;
		margin-top:10px;
		border:1px double #000;
		padding:3px;

	}
	#calendarDiv,#calendarDiv2{
		width:240px;
		height:240px;
		float:left;
	}
	.clear{
		clear:both;
	}
	</style>	
    
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Admin Reports</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
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
	  <form name="searchForm" method="get" action="admin_report_result.php">
      
	    <table width="100%" border="6" cellspacing="0" cellpadding="3" class="greentbl">
          <tr>
            <td class="greenth"><font color="#ffffff" face="Tahoma" size="2">Vendor Remittance Report</font></td>
            <td class="greenth"><font color="#ffffff" face="Tahoma" size="2">Employer Remittance Report</font></td>
            <td class="greenth"><font color="#ffffff" face="Tahoma" size="2">Employee Contribution History</font></td>
          </tr>
          <tr>
            <td valign="top" class="greentd"><table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td>Vendor: </td>
                <td><select name="vendor_id" id="vendor_id" onchange="doAjaxXMLSelectBox('getEmployer_m.php','GET','dg_key='+this.value,'',document.searchForm.employer_id);">
                  <option value="0">select a vendor</option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_rsVendorList['vendor_id']?>"><?php echo $row_rsVendorList['name']?></option>
                  <?php
} while ($row_rsVendorList = mysql_fetch_assoc($rsVendorList));
  $rows = mysql_num_rows($rsVendorList);
  if($rows > 0) {
      mysql_data_seek($rsVendorList, 0);
	  $row_rsVendorList = mysql_fetch_assoc($rsVendorList);
  }
?> 
                                </select></td>
              </tr>
              <tr>
                <td>Employer:</td>
                <td><select name="employer_id" id="employer_id">
                </select>                </td>
              </tr>
            </table>            
           </td>
            <td valign="top" class="greentd"><table width="100%" border="0" cellspacing="0" cellpadding="5">

              <tr>
                <td>Employer:</td>
                <td><select name="employer_id2" id="employer_id2" onchange="doAjaxXMLSelectBox('getPlan_m.php','GET','dg_key='+this.value,'',document.searchForm.plan_id2);">
                  <option value="0">select employer</option>
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
                                                                  </select>                </td>
              </tr>
              <tr>
                <td>Vendor</td>
                <td><select name="plan_id2" id="plan_id2">
                                                </select></td>
              </tr>
            </table>
            </td>
            <td valign="top" class="greentd"><table width="100%" border="0" cellspacing="0" cellpadding="5">

              <tr>
                <td>Employer</td>
                <td><select name="employer_id3" id="employer_id3" onchange="doAjaxXMLSelectBox('getPlan_m.php','GET','dg_key='+this.value,'',document.searchForm.plan_id3);doAjaxXMLSelectBox('getEmployeeFromEmployer.php','GET','dg_key='+this.value,'',document.searchForm.employee_id);">
                  <option value="0">select employer</option>
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
              <tr>
                <td>Vendor</td>
                <td><select name="plan_id3" id="plan_id3" onchange="doAjaxXMLSelectBox('getEmployeeFromVendor.php','GET','dg_key='+this.value,'',document.searchForm.employee_id);">
                  <option value="0">select vendor</option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_rsVendorList['vendor_id']?>"><?php echo $row_rsVendorList['name']?></option>
                  <?php
} while ($row_rsVendorList = mysql_fetch_assoc($rsVendorList));
  $rows = mysql_num_rows($rsVendorList);
  if($rows > 0) {
      mysql_data_seek($rsVendorList, 0);
	  $row_rsVendorList = mysql_fetch_assoc($rsVendorList);
  }
?>
                                                </select></td>
              </tr>
              <tr>
                <td>Employee</td>
                <td><select name="employee_id" id="employee_id">
                    <option value="0">select employee</option>
                  </select>
                </td>
              </tr>
            </table>
          </td>
          </tr>
          <tr>
            <td colspan="3" align="center" valign="top" class="greentd"><strong>Period</strong>
            <table>

              <tr>
                <td>From Date:</td>
                <td><input type="text" name="fromDate" value="" onclick="" /></td>
                <td><input type="button" value="Pick date" onclick="pickDate(this,document.searchForm.fromDate);" /></td>
              </tr>
              <tr>
                <td>To Date:</td>
                <td><input type="text" name="toDate" value="" onclick="" /></td>
                <td><input type="button" value="Pick date" onclick="pickDate(this,document.searchForm.toDate);" /></td>
              </tr>
                </table>              </td>
          </tr>
          <tr>
            <td align="center" valign="top" class="greentd"><input type="submit" name="VendorSubmit" id="VendorSubmit" value="Get Vendor Report" /></td>
            <td align="center" valign="top" class="greentd"><input type="submit" name="EmployerSubmit" id="EmployerSubmit" value="Get Employer Report" />
            <input name="menuTopItem" type="hidden" id="menuTopItem" value="1" /></td>
            <td align="center" valign="top" class="greentd"><input type="submit" name="EmployeeSubmit" id="EmployeeSubmit" value="Get Employee Report" /></td>
          </tr>
        </table>
	  </form>
</div>
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
<?php
mysql_free_result($rsVendorList);

mysql_free_result($rsEmployer);

//mysql_free_result($rsEmployee);
?>