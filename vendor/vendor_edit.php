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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE vendor SET name=%s, email=%s, phone=%s, fax=%s, url=%s, remittance_address=%s, modified_dt=%s WHERE vendor_id=%s",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['phone'], "text"),
                       GetSQLValueString($_POST['fax'], "text"),
                       GetSQLValueString($_POST['url'], "text"),
                       GetSQLValueString($_POST['remittance_address'], "text"),
                       GetSQLValueString($_POST['modified_dt'], "int"),
                       GetSQLValueString($_POST['vendor_id'], "int"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($updateSQL, $dw_conn) or die(mysql_error());

  $updateGoTo = "vendor_list.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsEdit = "-1";
if (isset($_COOKIE['vendor']['vendor_id'])) {
  $colname_rsEdit = $_COOKIE['vendor']['vendor_id'];
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEdit = sprintf("SELECT * FROM vendor WHERE vendor_id = %s", GetSQLValueString($colname_rsEdit, "int"));
$rsEdit = mysql_query($query_rsEdit, $dw_conn) or die(mysql_error());
$row_rsEdit = mysql_fetch_assoc($rsEdit);
$totalRows_rsEdit = mysql_num_rows($rsEdit);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Edit Vendor</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Edit Vendor</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" class="thc2">Email:</td>
      <td class="tdc2"><input type="text" name="email" value="<?php echo $row_rsEdit['email']; ?>" size="32" readonly="readonly" /></td>
    </tr>

    <tr valign="baseline">
      <td nowrap="nowrap" align="right" class="thc2">Name:</td>
      <td class="tdc2"><input type="text" name="name" value="<?php echo $row_rsEdit['name']; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" class="thc2">Phone:</td>
      <td class="tdc2"><input type="text" name="phone" value="<?php echo $row_rsEdit['phone']; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" class="thc2">Fax:</td>
      <td class="tdc2"><input type="text" name="fax" value="<?php echo $row_rsEdit['fax']; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" class="thc2">Vender URL:</td>
      <td class="tdc2"><input name="url" type="text" id="url" size="35" value="<?php echo $row_rsEdit['url']; ?>" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" class="thc2">Remittance Address:</td>
      <td class="tdc2"><input type="text" name="remittance_address" value="<?php echo $row_rsEdit['remittance_address']; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" class="thc2">&nbsp;</td>
      <td class="tdc2"><input type="submit" value="Update Vendor" />
      <input name="vendor_id" type="hidden" id="vendor_id" value="<?php echo $row_rsEdit['vendor_id']; ?>" />
      <input name="modified_dt" type="hidden" id="modified_dt" value="<?php echo time(); ?>" /><input name="menuTopItem" type="hidden" id="menuTopItem" value="4" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
</form>
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
mysql_free_result($rsEdit);
?>