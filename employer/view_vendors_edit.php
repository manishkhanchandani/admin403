<?php require_once('../Connections/dw_conn.php'); ?>
<?php
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	if(!$_POST['contact_name']) {
		$_POST["MM_update"] = "";
		$error .= "Please fill contact name. ";
	}
	if(!$_POST['contact_email']) {
		$_POST["MM_update"] = "";
		$error .= "Please fill contact email. ";
	}
	if(!$_POST['contact_phone']) {
		$_POST["MM_update"] = "";
		$error .= "Please fill contact phone. ";
	}
}
?>
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE employer_vendors_contact SET contact_name=%s, contact_email=%s, contact_phone=%s, group_plan_number=%s WHERE contact_id=%s",
                       GetSQLValueString($_POST['contact_name'], "text"),
                       GetSQLValueString($_POST['contact_email'], "text"),
                       GetSQLValueString($_POST['contact_phone'], "text"),
                       GetSQLValueString($_POST['group_plan_number'], "text"),
                       GetSQLValueString($_POST['contact_id'], "int"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($updateSQL, $dw_conn) or die(mysql_error());

  $updateGoTo = "view_vendors.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
?>
<?php
$colname_rsVendor = "-1";
if (isset($_GET['plan_id'])) {
  $colname_rsVendor = (get_magic_quotes_gpc()) ? $_GET['plan_id'] : addslashes($_GET['plan_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsVendor = sprintf("SELECT vendor_plan.plan_name, vendor.name FROM vendor, vendor_plan WHERE vendor.vendor_id = vendor_plan.vendor_id AND vendor_plan.plan_id = %s", $colname_rsVendor);
$rsVendor = mysql_query($query_rsVendor, $dw_conn) or die(mysql_error());
$row_rsVendor = mysql_fetch_assoc($rsVendor);
$totalRows_rsVendor = mysql_num_rows($rsVendor);

$colname_rsContact = "-1";
if (isset($_GET['contact_id'])) {
  $colname_rsContact = (get_magic_quotes_gpc()) ? $_GET['contact_id'] : addslashes($_GET['contact_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsContact = sprintf("SELECT * FROM employer_vendors_contact WHERE contact_id = %s", $colname_rsContact);
$rsContact = mysql_query($query_rsContact, $dw_conn) or die(mysql_error());
$row_rsContact = mysql_fetch_assoc($rsContact);
$totalRows_rsContact = mysql_num_rows($rsContact);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Edit Vendor Details</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Request List</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
	  	<form action="<?php echo $editFormAction; ?>" name="form1" method="POST">
		<?php echo $error; ?>
	  	<table border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2">Vendor:</td>
			  <td class="tdc2"><?php echo $row_rsVendor['name']; ?></td>
		  </tr>
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2">Plan:</td>
			  <td class="tdc2"><?php echo $row_rsVendor['plan_name']; ?></td>
		  </tr>
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2">Contact Name: </td>
			  <td class="tdc2"><input name="contact_name" type="text" id="contact_name" value="<?php echo $row_rsContact['contact_name']; ?>" size="32" /></td>
			</tr>
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2"> Contact E-mail:</td>
			  <td class="tdc2"><input name="contact_email" type="text" id="contact_email" value="<?php echo $row_rsContact['contact_email']; ?>" size="32" /></td>
		  </tr>
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2">Contact Phone: </td>
			  <td class="tdc2"><input name="contact_phone" type="text" id="contact_phone" value="<?php echo $row_rsContact['contact_phone']; ?>" size="32" /></td>
		  </tr>
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2"> Group Plan Number:</td>
			  <td class="tdc2"><input name="group_plan_number" type="text" id="group_plan_number" value="<?php echo $row_rsContact['group_plan_number']; ?>" size="32" /></td>
		  </tr>
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2">&nbsp;</td>
			  <td class="tdc2"><input type="submit" name="Submit" value="Edit Vendor Details" />
		        <input name="contact_id" type="hidden" id="contact_id" value="<?php echo $row_rsContact['contact_id']; ?>" />
		      <input name="menuTopItem" type="hidden" value="<?php echo $_REQUEST['menuTopItem']; ?>" /></td>
		  </tr>
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2">&nbsp;</td>
			  <td class="tdc2"><a href="<?php echo HTTPPATH; ?>/employer/view_vendors.php?menuTopItem=<?php echo $_REQUEST['menuTopItem']; ?>">Back</a></td>
		  </tr>
		</table>
	  	<input type="hidden" name="MM_update" value="form1">
	  	</form>
	  </td>
	</tr>
</table>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body><!-- InstanceEnd -->
</html>
<?php
mysql_free_result($rsVendor);

mysql_free_result($rsContact);
?>
