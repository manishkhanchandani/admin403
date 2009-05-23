<?php require_once('../Connections/dw_conn.php'); ?>
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

 if(!$_REQUEST['menuTopItem']) $_REQUEST['menuTopItem'] = 3; 
?>
<?php
$colname_rsEmployerList = "%";
if (isset($_GET['kw'])) {
  $colname_rsEmployerList = (get_magic_quotes_gpc()) ? $_GET['kw'] : addslashes($_GET['kw']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployerList = sprintf("SELECT employer.employer_id, employer.email, employer.name, vendor.name as 'vname', vendor_plan.plan_name FROM employer LEFT JOIN employer_vendor ON employer.employer_id = employer_vendor.employer_id LEFT JOIN vendor ON employer_vendor.vendor_id = vendor.vendor_id LEFT JOIN vendor_plan ON vendor.vendor_id = vendor_plan.vendor_id WHERE (employer.name LIKE '%%%s%%' OR employer.phone LIKE '%%%s%%' OR employer.email LIKE  '%%%s%%' OR employer.address LIKE  '%%%s%%' OR vendor.name LIKE '%%%s%%' OR vendor_plan.plan_name LIKE '%%%s%%') GROUP BY employer.employer_id, vendor.vendor_id, vendor_plan.plan_id ORDER BY employer.name ASC, vendor.name ASC, vendor_plan.plan_name ASC", $colname_rsEmployerList,$colname_rsEmployerList,$colname_rsEmployerList,$colname_rsEmployerList,$colname_rsEmployerList,$colname_rsEmployerList);
$rsEmployerList = mysql_query($query_rsEmployerList, $dw_conn) or die(mysql_error());
$row_rsEmployerList = mysql_fetch_assoc($rsEmployerList);
$totalRows_rsEmployerList = mysql_num_rows($rsEmployerList);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>List Employer</title>
<!-- InstanceEndEditable -->
<?php include('css.php'); ?>
<?php include('js.php'); ?>
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>

<body>
<?php include('head.php'); ?>
<!-- InstanceBeginEditable name="EditRegion3" -->
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Search Employer</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd"><form id="form1" name="form1" method="get" action="">
        Keyword: 
        <input name="kw" type="text" id="kw" value="<?php echo $_GET['kw']; ?>" />
        <input type="submit" name="button2" id="button2" value="Search" />
        <input name="menuTopItem" type="hidden" id="menuTopItem" value="3" />
       <br />
       <br />
      Note: Keyword must contain one of the following fields: Employer Name, Employer Email, Employer details etc.
      </form>
      </td>
    </tr>
</table>
<br />
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Employer List</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<?php if ($totalRows_rsEmployerList > 0) { // Show if recordset not empty ?>
  <table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
    <tr>
      <td class="thcview2"><strong>Name</strong></td>
      <td class="thcview2"><strong>Email</strong></td>
      <td class="thcview2"><strong>Vendor</strong></td>
      <td class="thcview2"><strong><?php echo DISPLAYPLANNAME;?></strong></td>
      <td class="thcview2"><strong>Actions</strong></td>
      </tr>
    <?php do { ?>
      <tr>
        <td class="tdcview2"><?php $TFM_nest1 = $row_rsEmployerList['name']; if ($lastTFM_nest1 != $TFM_nest1) { $lastTFM_nest1 = $TFM_nest1; echo $row_rsEmployerList['name']; } //End of Basic-UltraDev Simulated Nested Repeat?>&nbsp;</td>
        <td class="tdcview2"><?php $TFM_nest2 = $row_rsEmployerList['email']; if ($lastTFM_nest2 != $TFM_nest2) { $lastTFM_nest2 = $TFM_nest2; echo $row_rsEmployerList['email']; } //End of Basic-UltraDev Simulated Nested Repeat?>&nbsp;</td>
        <td class="tdcview2"><?php $TFM_nest4 = $row_rsEmployerList['vname']; if ($lastTFM_nest4 != $TFM_nest4) { $lastTFM_nest4 = $TFM_nest4; echo $row_rsEmployerList['vname']; } //End of Basic-UltraDev Simulated Nested Repeat?>&nbsp;</td>
        <td class="tdcview2"><?php echo $row_rsEmployerList['plan_name']; ?>&nbsp;</td>
        <td class="tdcview2"><!-- <a href="admin_employer_vendor.php?employer_id=<?php echo $row_rsEmployerList['employer_id']; ?>&menuTopItem=3">Manage Vendors</a>  --><a href="view_vendors.php?employer_id=<?php echo $row_rsEmployerList['employer_id']; ?>&menuTopItem=3">Manage Vendors</a> | <a href="admin_employer_edit.php?employer_id=<?php echo $row_rsEmployerList['employer_id']; ?>&menuTopItem=3">Edit</a> | <a href="admin_employer_delete.php?employer_id=<?php echo $row_rsEmployerList['employer_id']; ?>&menuTopItem=3">Delete</a></td>
        </tr>
      <?php } while ($row_rsEmployerList = mysql_fetch_assoc($rsEmployerList)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rsEmployerList == 0) { // Show if recordset empty ?>
  <p>No List Found.</p>
  <?php } // Show if recordset empty ?></td>
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
mysql_free_result($rsEmployerList);
?>
