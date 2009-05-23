<?php require_once('../Connections/dw_conn.php'); ?>
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
  $insertSQL = sprintf("INSERT INTO vendor_plan (vendor_id, plan_name, plan_code, plan_desc, plan_link) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['vendor_id'], "int"),
                       GetSQLValueString($_POST['plan_name'], "text"),
                       GetSQLValueString($_POST['plan_code'], "text"),
                       GetSQLValueString($_POST['plan_desc'], "text"),
                       GetSQLValueString($_POST['plan_link'], "text"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($insertSQL, $dw_conn) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE vendor_plan SET plan_name=%s, plan_code=%s, plan_desc=%s, plan_link=%s WHERE plan_id=%s",
                       GetSQLValueString($_POST['plan_name'], "text"),
                       GetSQLValueString($_POST['plan_code'], "text"),
                       GetSQLValueString($_POST['plan_desc'], "text"),
                       GetSQLValueString($_POST['plan_link'], "text"),
                       GetSQLValueString($_POST['plan_id'], "int"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($updateSQL, $dw_conn) or die(mysql_error());
}

$colname_rsVendor = "-1";
if (isset($_GET['vendor_id'])) {
  $colname_rsVendor = (get_magic_quotes_gpc()) ? $_GET['vendor_id'] : addslashes($_GET['vendor_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsVendor = sprintf("SELECT * FROM vendor WHERE vendor_id = %s", $colname_rsVendor);
$rsVendor = mysql_query($query_rsVendor, $dw_conn) or die(mysql_error());
$row_rsVendor = mysql_fetch_assoc($rsVendor);
$totalRows_rsVendor = mysql_num_rows($rsVendor);

$colname_rsEditPlan = "-1";
if (isset($_GET['plan_id'])) {
  $colname_rsEditPlan = (get_magic_quotes_gpc()) ? $_GET['plan_id'] : addslashes($_GET['plan_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEditPlan = sprintf("SELECT * FROM vendor_plan WHERE plan_id = %s", $colname_rsEditPlan);
$rsEditPlan = mysql_query($query_rsEditPlan, $dw_conn) or die(mysql_error());
$row_rsEditPlan = mysql_fetch_assoc($rsEditPlan);
$totalRows_rsEditPlan = mysql_num_rows($rsEditPlan);

$colname_rsVendorPlans = "-1";
if (isset($_GET['vendor_id'])) {
  $colname_rsVendorPlans = (get_magic_quotes_gpc()) ? $_GET['vendor_id'] : addslashes($_GET['vendor_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsVendorPlans = sprintf("SELECT * FROM vendor_plan WHERE vendor_id = %s", $colname_rsVendorPlans);
$rsVendorPlans = mysql_query($query_rsVendorPlans, $dw_conn) or die(mysql_error());
$row_rsVendorPlans = mysql_fetch_assoc($rsVendorPlans);
$totalRows_rsVendorPlans = mysql_num_rows($rsVendorPlans);

$MM_paramName = ""; 

// *** Go To Record and Move To Record: create strings for maintaining URL and Form parameters
// create the list of parameters which should not be maintained
$MM_removeList = "&index=";
if ($MM_paramName != "") $MM_removeList .= "&".strtolower($MM_paramName)."=";
$MM_keepURL="";
$MM_keepForm="";
$MM_keepBoth="";
$MM_keepNone="";
// add the URL parameters to the MM_keepURL string
reset ($HTTP_GET_VARS);
while (list ($key, $val) = each ($HTTP_GET_VARS)) {
	$nextItem = "&".strtolower($key)."=";
	if (!stristr($MM_removeList, $nextItem)) {
		$MM_keepURL .= "&".$key."=".urlencode($val);
	}
}
// add the Form parameters to the MM_keepURL string
if(isset($HTTP_POST_VARS)){
	reset ($HTTP_POST_VARS);
	while (list ($key, $val) = each ($HTTP_POST_VARS)) {
		$nextItem = "&".strtolower($key)."=";
		if (!stristr($MM_removeList, $nextItem)) {
			$MM_keepForm .= "&".$key."=".urlencode($val);
		}
	}
}
// create the Form + URL string and remove the intial '&' from each of the strings
$MM_keepBoth = $MM_keepURL."&".$MM_keepForm;
if (strlen($MM_keepBoth) > 0) $MM_keepBoth = substr($MM_keepBoth, 1);
if (strlen($MM_keepURL) > 0)  $MM_keepURL = substr($MM_keepURL, 1);
if (strlen($MM_keepForm) > 0) $MM_keepForm = substr($MM_keepForm, 1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Manage Plans</title>
<!-- InstanceEndEditable -->
<?php include('css.php'); ?>
<?php include('js.php'); ?>
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>

<body>
<?php include('head.php'); ?>
<!-- InstanceBeginEditable name="EditRegion3" -->
<?php if ($totalRows_rsVendor > 0) { // Show if recordset not empty ?>
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Add New Plan For Vendor <?php echo $row_rsVendor['name']; ?></font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <table border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
    <tr valign="baseline">
      <td nowrap align="right" class="thc2">Name:</td>
      <td class="tdc2"><input type="text" name="plan_name" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right" class="thc2">Code:</td>
      <td class="tdc2"><input type="text" name="plan_code" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right" valign="top" class="thc2">Description:</td>
      <td class="tdc2"><textarea name="plan_desc" cols="50" rows="5"></textarea>
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right" class="thc2">Link:</td>
      <td class="tdc2"><input type="text" name="plan_link" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right" class="thc2">&nbsp;</td>
      <td class="tdc2"><input type="submit" value="Add <?php echo DISPLAYPLANNAME;?>"></td>
    </tr>
  </table>
  <input type="hidden" name="vendor_id" value="<?php echo $row_rsVendor['vendor_id']; ?>">
  <input type="hidden" name="MM_insert" value="form1"><input name="menuTopItem" type="hidden" id="menuTopItem" value="4" />
</form>
      </td>
    </tr>
</table>
<br />

<?php if ($totalRows_rsEditPlan > 0) { // Show if recordset not empty ?>
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Edit Plan For Vendor <?php echo $row_rsVendor['name']; ?><a name="edit" id="edit"></a></font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<form name="form2" id="form2" method="POST" action="<?php echo $editFormAction; ?>">
  <table border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="thc2">Name:</td>
      <td class="tdc2"><input type="text" name="plan_name" value="<?php echo $row_rsEditPlan['plan_name']; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="thc2">Code:</td>
      <td class="tdc2"><input type="text" name="plan_code" value="<?php echo $row_rsEditPlan['plan_code']; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="top" nowrap="nowrap" class="thc2">Description:</td>
      <td class="tdc2"><textarea name="plan_desc" cols="50" rows="5"><?php echo $row_rsEditPlan['plan_desc']; ?></textarea>
      </td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="thc2">Link:</td>
      <td class="tdc2"><input type="text" name="plan_link" value="<?php echo $row_rsEditPlan['plan_link']; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" class="thc2">&nbsp;</td>
      <td class="tdc2"><input type="submit" value="Update Plan" />
          <input name="plan_id" type="hidden" id="plan_id" value="<?php echo $row_rsEditPlan['plan_id']; ?>" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form2"><input name="menuTopItem" type="hidden" id="menuTopItem" value="4" />
</form>
      </td>
    </tr>
</table>
<br />
<?php } // Show if recordset not empty ?>
<?php if ($totalRows_rsVendorPlans > 0) { // Show if recordset not empty ?>
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">View Plans For Vendor <?php echo $row_rsVendor['name']; ?></font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<table border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl" width="100%">
  <tr>
    <td class="thcview2"><strong>Name </strong></td>
    <td class="thcview2"><strong>Code </strong></td>
    <td class="thcview2"><strong>Description </strong></td>
    <td class="thcview2"><strong>Link </strong></td>
    <td class="thcview2"><strong>Actions</strong></td>
    </tr>
  <?php do { ?>
  <tr>
    <td class="tdcview2"><?php echo $row_rsVendorPlans['plan_name']; ?></td>
    <td class="tdcview2"><?php echo $row_rsVendorPlans['plan_code']; ?></td>
    <td class="tdcview2"><?php echo $row_rsVendorPlans['plan_desc']; ?></td>
    <td class="tdcview2"><a href="<?php echo $row_rsVendorPlans['plan_link']; ?>" target="_blank"><?php echo $row_rsVendorPlans['plan_link']; ?></a></td>
    <td class="tdcview2"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?plan_id=<?php echo $row_rsVendorPlans['plan_id']; ?>&vendor_id=<?php echo $row_rsVendor['vendor_id']; ?>&menuTopItem=4#edit">Edit</a> | <a href="admin_vendor_plan_delete.php?<?php echo $MM_keepNone.(($MM_keepNone!="")?"&":"")."plan_id=".urlencode($row_rsVendorPlans['plan_id']) ?>">Delete</a></td>
    </tr>
  <?php } while ($row_rsVendorPlans = mysql_fetch_assoc($rsVendorPlans)); ?>
</table>
      </td>
    </tr>
</table>
<br />

<?php } // Show if recordset not empty ?>


<?php } // Show if recordset not empty ?>

<?php if ($totalRows_rsVendor == 0) { // Show if recordset not empty ?>
<p>You are not allowed to view this page.</p>
<?php } // Show if recordset empty ?>
<p>&nbsp;</p>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body><!-- InstanceEnd -->
</html>
<?php
mysql_free_result($rsVendor);

mysql_free_result($rsEditPlan);

mysql_free_result($rsVendorPlans);
?>
