<?php require_once('../Connections/dw_conn.php'); ?>
<?php
$colname_rsEmployee = "-1";
if (isset($_COOKIE['employer']['employer_id'])) {
  $colname_rsEmployee = (get_magic_quotes_gpc()) ? $_COOKIE['employer']['employer_id'] : addslashes($_COOKIE['employer']['employer_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployee = sprintf("SELECT employee.employee_id, employee.firstname FROM employee WHERE employer_id = %s", $colname_rsEmployee);
$rsEmployee = mysql_query($query_rsEmployee, $dw_conn) or die(mysql_error());
$row_rsEmployee = mysql_fetch_assoc($rsEmployee);
$totalRows_rsEmployee = mysql_num_rows($rsEmployee);

$colname_rsVendorList = "-1";
if (isset($_COOKIE['employer']['employer_id'])) {
  $colname_rsVendorList = (get_magic_quotes_gpc()) ? $_COOKIE['employer']['employer_id'] : addslashes($_COOKIE['employer']['employer_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsVendorList = sprintf("SELECT vendor.vendor_id, vendor.name, employer_vendor.employer_id FROM vendor, employer_vendor WHERE employer_vendor.vendor_id = vendor.vendor_id AND employer_vendor.employer_id = %s GROUP BY employer_vendor.vendor_id", $colname_rsVendorList);
$rsVendorList = mysql_query($query_rsVendorList, $dw_conn) or die(mysql_error());
$row_rsVendorList = mysql_fetch_assoc($rsVendorList);
$totalRows_rsVendorList = mysql_num_rows($rsVendorList);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Report Step 1</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Advance Search</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" align="center" class="blacktd">
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
	  <form name="form1" id="form1" method="get" action="report_step2.php">
	  <table border="6" cellspacing="0" cellpadding="5" style="border-style:solid; padding:0px; margin:0px;" bordercolor="#999999" class="tbl">
			<tr>
				<td valign="top" class="thcview2"><strong>Period</strong></td>
				<td valign="top" class="tdcview2">

	    <table>
          <tr>
            <td>From Date:</td>
            <td><input type="text" name="fromDate" value="" onclick="" /></td>
            <td><input type="button" value="Pick date" onclick="pickDate(this,document.form1.fromDate);" /></td>
          </tr>
          <tr>
            <td>To Date:</td>
            <td><input type="text" name="toDate" value="" onclick="" /></td>
            <td><input type="button" value="Pick date" onclick="pickDate(this,document.form1.toDate);" /></td>
          </tr>
        </table>
		<br />
		<strong>(Note: </strong>if no date is selected, date will be taken from 1st day of year to current date. ) </td>
		</tr>
			<tr>
              <td valign="top" class="thcview2"><strong>Report View </strong></td>
              <td valign="top" class="tdcview2"><input name="report_view" type="radio" value="1" checked="checked" />
    Summary of Contribution<br />
    <input name="report_view" type="radio" value="2" />
    Detailed Transactions </td>
		    </tr>
			<tr>
			  <td valign="top" class="thcview2"><strong>Report Type </strong></td>
			  <td valign="top" class="tdcview2"><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                <tr align="center">
                  <td colspan="2"><strong>
                    <input name="report_type" type="radio" value="1" checked="checked" />
                    Choose <?php echo DISPLAYPLANNAME;?> </strong></td>
                  <td colspan="2"><strong>
                    <input name="report_type" type="radio" value="2" />
                    Choose Employee </strong></td>
                  </tr>
                <tr>
                  <td align="right"><strong>Vendor:</strong></td>
                  <td><select name="vendor_id" id="vendor_id" onchange="doAjaxXMLSelectBox('getPlan.php','GET','vendor_id='+this.value,'',document.form1.plan_id);">
                  <option value="%">select a vendor</option>
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
                  <td align="right"><strong>Employee:</strong></td>
                  <td><select name="employee_id" id="employee_id" onchange="doAjaxXMLSelectBox('getPlan2.php','GET','employee_id='+this.value,'',document.form1.plan_id2);">
                    <option value="%">Select</option>
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
                  </select></td>
                </tr>
                <tr>
                  <td align="right"><strong><?php echo DISPLAYPLANNAME;?>:</strong></td>
                  <td><select name="plan_id" id="plan_id">
                  </select></td>
                  <td align="right"><strong><?php echo DISPLAYPLANNAME;?>:</strong></td>
                  <td><select name="plan_id2" id="plan_id2">
                  </select></td>
                </tr>
              </table></td>
		    </tr>
			<tr>
			  <td valign="top" class="thcview2">&nbsp;</td>
			  <td valign="top" class="tdcview2"><input type="submit" name="Submit" value="Get Report" />
		      <input name="menuTopItem" type="hidden" id="menuTopItem" value="2" /></td>
	    </tr>
		</table>
	  </form>
	</div>
	  </td>
	</tr>
</table>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsEmployee);

mysql_free_result($rsVendorList);
?>
