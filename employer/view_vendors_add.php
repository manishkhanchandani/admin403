<?php require_once('../Connections/dw_conn.php'); ?>
<?php
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	if(!$_POST['contact_name']) {
		$_POST["MM_insert"] = "";
		$error .= "Please fill contact name. ";
	}
	if(!$_POST['contact_email']) {
		$_POST["MM_insert"] = "";
		$error .= "Please fill contact email. ";
	}
	if(!$_POST['contact_phone']) {
		$_POST["MM_insert"] = "";
		$error .= "Please fill contact phone. ";
	}
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	$rs = mysql_query("insert into employer_vendor (employer_id, plan_id, vendor_id) VALUES ('".$_POST['employer_id']."', '".$_POST['plan_id']."', '".$_POST['vendor_id']."')") or die('error in line '.__LINE__.' of file '.__FILE__.' due to mysql error '.mysql_error());
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO employer_vendors_contact (employer_id, vendor_id, plan_id, contact_name, contact_email, contact_phone, group_plan_number) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['employer_id'], "int"),
                       GetSQLValueString($_POST['vendor_id'], "int"),
                       GetSQLValueString($_POST['plan_id'], "int"),
                       GetSQLValueString($_POST['contact_name'], "text"),
                       GetSQLValueString($_POST['contact_email'], "text"),
                       GetSQLValueString($_POST['contact_phone'], "text"),
                       GetSQLValueString($_POST['group_plan_number'], "text"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($insertSQL, $dw_conn) or die(mysql_error());

  $insertGoTo = "view_vendors.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_rsVendor = "-1";
if (isset($_GET['plan_id'])) {
  $colname_rsVendor = (get_magic_quotes_gpc()) ? $_GET['plan_id'] : addslashes($_GET['plan_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsVendor = sprintf("SELECT vendor_plan.plan_name, vendor.name FROM vendor, vendor_plan WHERE vendor.vendor_id = vendor_plan.vendor_id AND vendor_plan.plan_id = %s", $colname_rsVendor);
$rsVendor = mysql_query($query_rsVendor, $dw_conn) or die(mysql_error());
$row_rsVendor = mysql_fetch_assoc($rsVendor);
$totalRows_rsVendor = mysql_num_rows($rsVendor);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Add Vendor Details</title>
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
			  <td class="tdc2"><input name="contact_name" type="text" id="contact_name" value="<?php echo $_POST['contact_name']; ?>" size="32" /></td>
			</tr>
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2"> Contact E-mail:</td>
			  <td class="tdc2"><input name="contact_email" type="text" id="contact_email" value="<?php echo $_POST['contact_email']; ?>" size="32" /></td>
		  </tr>
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2">Contact Phone: </td>
			  <td class="tdc2"><input name="contact_phone" type="text" id="contact_phone" value="<?php echo $_POST['contact_phone']; ?>" size="32" /></td>
		  </tr>
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2"> Group Plan Number:</td>
			  <td class="tdc2"><input name="group_plan_number" type="text" id="group_plan_number" value="<?php echo $_POST['group_plan_number']; ?>" size="32" /></td>
		  </tr>
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2">&nbsp;</td>
			  <td class="tdc2"><input type="submit" name="Submit" value="Add Vendor Details" />
		      <input name="employer_id" type="hidden" id="employer_id" value="<?php echo $_COOKIE['employer']['employer_id']; ?>" />
		      <input name="vendor_id" type="hidden" id="vendor_id" value="<?php echo $_GET['vendor_id']; ?>" />
		      <input name="plan_id" type="hidden" id="plan_id" value="<?php echo $_GET['plan_id']; ?>" />
		      <input name="menuTopItem" type="hidden" value="<?php echo $_REQUEST['menuTopItem']; ?>" /></td>
		  </tr>
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2">&nbsp;</td>
			  <td class="tdc2"><a href="<?php echo HTTPPATH; ?>/employer/view_vendors.php?menuTopItem=<?php echo $_REQUEST['menuTopItem']; ?>">Back</a></td>
		  </tr>
		</table>
		<input type="hidden" name="MM_insert" value="form1">
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
?>
