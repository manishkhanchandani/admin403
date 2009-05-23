<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Vendor :: Total Remittance</title>
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
    
    
    
    
<script type="text/javascript" src="../includes/dhtml/js/ajax.js"></script>    
<script type="text/javascript" src="../includes/dhtml/js/separateFiles/dhtmlSuite-common.js"></script> 
<script tpe="text/javascript">
	DHTMLSuite.include("chainedSelect");
</script>




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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Reports</font></td>
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
	  <form name="searchForm" method="get" action="report_period_result.php">
      
	    <table width="100%" border="6" cellspacing="0" cellpadding="3" class="greentbl">
          <tr>
            <td class="greenth"><font color="#ffffff" face="Tahoma" size="2">Total Remittance</font></td>
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
            <td align="center" valign="top" class="greentd"><input type="submit" name="EmployerSubmit" id="EmployerSubmit" value="Get Total Remittance" />
            <input name="menuTopItem" type="hidden" id="menuTopItem" value="3" /></td>
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