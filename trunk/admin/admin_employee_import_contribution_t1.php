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

$currentPage = $_SERVER["PHP_SELF"];

//include_once('../main/contribution_functions.php');
?>
<?php
$maxRows_rsContribution = 25;
$pageNum_rsContribution = 0;
if (isset($_GET['pageNum_rsContribution'])) {
  $pageNum_rsContribution = $_GET['pageNum_rsContribution'];
}
$startRow_rsContribution = $pageNum_rsContribution * $maxRows_rsContribution;

mysql_select_db($database_dw_conn, $dw_conn);
$query_rsContribution = "SELECT * FROM employee_contribution_transaction ORDER BY transaction_date DESC";
$query_limit_rsContribution = sprintf("%s LIMIT %d, %d", $query_rsContribution, $startRow_rsContribution, $maxRows_rsContribution);
$rsContribution = mysql_query($query_limit_rsContribution, $dw_conn) or die(mysql_error());
$row_rsContribution = mysql_fetch_assoc($rsContribution);

if (isset($_GET['totalRows_rsContribution'])) {
  $totalRows_rsContribution = $_GET['totalRows_rsContribution'];
} else {
  $all_rsContribution = mysql_query($query_rsContribution);
  $totalRows_rsContribution = mysql_num_rows($all_rsContribution);
}
$totalPages_rsContribution = ceil($totalRows_rsContribution/$maxRows_rsContribution)-1;

$queryString_rsContribution = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsContribution") == false && 
        stristr($param, "totalRows_rsContribution") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsContribution = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsContribution = sprintf("&totalRows_rsContribution=%d%s", $totalRows_rsContribution, $queryString_rsContribution);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Upload Contribution</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Upload Employee Contribution</font></td>
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
<?php
include('../main/import_functions.php'); ?>
<?php inc_import_form($action='admin_employee_import_contribution_confirmation_t1.php', $confirmLink=HTTPPATH."/main/confirm_upload_date.php", $employer_id=0, $menuTopItem=2); ?>

</div>
      </td>
    </tr>
</table>
<br />
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">View Contribution Data</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">

        <table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
          <tr>
            <td valign="top" class="thcview2"><strong>Date</strong></td>
            <td valign="top" class="thcview2"><strong># of records</strong></td>
            <td colspan="2" valign="top" class="thcview2"><strong>Total Amount</strong></td>
            <td valign="top" class="thcview2"><strong># Successful</strong></td>
            <td valign="top" class="thcview2"><strong>#Failed</strong></td>
            <td valign="top" class="thcview2"><strong>#Duplicate</strong></td>
            <td colspan="2" valign="top" class="thcview2"><strong>Amount</strong></td>
            <td colspan="2" valign="top" class="thcview2"><strong>Refund</strong></td>
          </tr>
          <?php do { ?>
          <tr>
            <td valign="top" class="thcview2">&nbsp;</td>
            <td valign="top" class="thcview2">&nbsp;</td>
            <td valign="top" class="thcview2"><strong>Pretax</strong></td>
            <td valign="top" class="thcview2"><strong>Roth</strong></td>
            <td valign="top" class="thcview2">&nbsp;</td>
            <td valign="top" class="thcview2">&nbsp;</td>
            <td valign="top" class="thcview2">&nbsp;</td>
            <td valign="top" class="thcview2"><strong>Pretax</strong></td>
            <td valign="top" class="thcview2"><strong>Roth</strong></td>
            <td valign="top" class="thcview2"><strong>Pretax</strong></td>
            <td valign="top" class="thcview2"><strong>Roth</strong></td>
          </tr>
          <tr>
            <td valign="top" class="tdcview2"><?php echo $row_rsContribution['transaction_date']; ?></td>
            <td valign="top" class="tdcview2"><?php echo $row_rsContribution['totalrecords']; ?></td>
            <td valign="top" class="tdcview2"><?php echo $row_rsContribution['totalamount']; ?></td>
            <td valign="top" class="tdcview2"><?php echo $row_rsContribution['totalroth']; ?></td>
            <td valign="top" class="tdcview2"><?php echo $row_rsContribution['totalrecordsprocessedsuccessfully']; ?></td>
            <td valign="top" class="tdcview2"><?php echo $row_rsContribution['totalrecordsrejected']; ?></td>
            <td valign="top" class="tdcview2"><?php echo $row_rsContribution['recordsalreadyuploaded']; ?></td>
            <td valign="top" class="tdcview2"><?php echo $row_rsContribution['totalamountprocessed']; ?></td>
            <td valign="top" class="tdcview2"><?php echo $row_rsContribution['totalrothprocessed']; ?></td>
            <td valign="top" class="tdcview2"><?php echo $row_rsContribution['totalamountrefunded']; ?></td>
            <td valign="top" class="tdcview2"><?php echo $row_rsContribution['totalrothrefunded']; ?></td>
          </tr>
          <?php } while ($row_rsContribution = mysql_fetch_assoc($rsContribution)); ?>
        </table>
        <p> Records <?php echo ($startRow_rsContribution + 1) ?> to <?php echo min($startRow_rsContribution + $maxRows_rsContribution, $totalRows_rsContribution) ?> of <?php echo $totalRows_rsContribution ?>
        <table border="0" width="50%" align="center">
          <tr>
            <td width="23%" align="center"><?php if ($pageNum_rsContribution > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_rsContribution=%d%s", $currentPage, 0, $queryString_rsContribution); ?>">First</a>
              <?php } // Show if not first page ?>
            </td>
            <td width="31%" align="center"><?php if ($pageNum_rsContribution > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_rsContribution=%d%s", $currentPage, max(0, $pageNum_rsContribution - 1), $queryString_rsContribution); ?>">Previous</a>
              <?php } // Show if not first page ?>
            </td>
            <td width="23%" align="center"><?php if ($pageNum_rsContribution < $totalPages_rsContribution) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_rsContribution=%d%s", $currentPage, min($totalPages_rsContribution, $pageNum_rsContribution + 1), $queryString_rsContribution); ?>">Next</a>
              <?php } // Show if not last page ?>
            </td>
            <td width="23%" align="center"><?php if ($pageNum_rsContribution < $totalPages_rsContribution) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_rsContribution=%d%s", $currentPage, $totalPages_rsContribution, $queryString_rsContribution); ?>">Last</a>
              <?php } // Show if not last page ?>
            </td>
          </tr>
        </table>
        </p></td>
    </tr>
</table>
<br />
<p>&nbsp; </p>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsContribution);
?>
