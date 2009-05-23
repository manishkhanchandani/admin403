<?php require_once('../Connections/dw_conn.php'); ?>
<?php
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

// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="admin_system_settings.php";
  $loginUsername = $_POST['year'];
  $LoginRS__query = sprintf("SELECT `year` FROM admin_system_settings WHERE `year`=%s", GetSQLValueString($loginUsername, "-1"));
  mysql_select_db($database_dw_conn, $dw_conn);
  $LoginRS=mysql_query($LoginRS__query, $dw_conn) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO admin_system_settings (`year`, annual_age_limit, annual_pretax_limit, annual_roth_limit) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['year'], "int"),
                       GetSQLValueString($_POST['annual_age_limit'], "double"),
                       GetSQLValueString($_POST['annual_pretax_limit'], "double"),
                       GetSQLValueString($_POST['annual_roth_limit'], "double"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($insertSQL, $dw_conn) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE admin_system_settings SET `year`=%s, annual_age_limit=%s, annual_pretax_limit=%s, annual_roth_limit=%s WHERE setting_id=%s",
                       GetSQLValueString($_POST['year'], "int"),
                       GetSQLValueString($_POST['annual_age_limit'], "double"),
                       GetSQLValueString($_POST['annual_pretax_limit'], "double"),
                       GetSQLValueString($_POST['annual_roth_limit'], "double"),
                       GetSQLValueString($_POST['setting_id'], "int"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($updateSQL, $dw_conn) or die(mysql_error());
}

if ((isset($_GET['del_id'])) && ($_GET['del_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM admin_system_settings WHERE setting_id=%s",
                       GetSQLValueString($_GET['del_id'], "int"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($deleteSQL, $dw_conn) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
	unset($_GET['setting_id']);
}

$colname_rsEdit = "-1";
if (isset($_GET['setting_id'])) {
  $colname_rsEdit = $_GET['setting_id'];
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEdit = sprintf("SELECT * FROM admin_system_settings WHERE setting_id = %s", GetSQLValueString($colname_rsEdit, "int"));
$rsEdit = mysql_query($query_rsEdit, $dw_conn) or die(mysql_error());
$row_rsEdit = mysql_fetch_assoc($rsEdit);
$totalRows_rsEdit = mysql_num_rows($rsEdit);

mysql_select_db($database_dw_conn, $dw_conn);
$query_rsView = "SELECT * FROM admin_system_settings ORDER BY `year` DESC";
$rsView = mysql_query($query_rsView, $dw_conn) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);
$totalRows_rsView = mysql_num_rows($rsView);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>System Settings</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">System Settings</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="thc2">Year:</td>
      <td class="tdc2"><input type="text" name="year" value="" size="4" /></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="thc2">Annual Age Limit:</td>
      <td class="tdc2">$
        <input type="text" name="annual_age_limit" value="" size="12" /></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="thc2">Annual Pretax Limit:</td>
      <td class="tdc2">$
        <input type="text" name="annual_pretax_limit" value="" size="12" /></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="thc2">Annual Roth Limit:</td>
      <td class="tdc2">$
        <input type="text" name="annual_roth_limit" value="" size="12" /></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="thc2">&nbsp;</td>
      <td class="tdc2"><input type="submit" value="Insert record" />
        <input name="menuTopItem" type="hidden" id="menuTopItem" value="1" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1" />
</form>
      </td>
    </tr>
</table>
<br />


<?php if ($totalRows_rsView > 0) { // Show if recordset not empty ?>
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">View Settings</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
      
<table width="100%" border="6" cellpadding="5" cellspacing="0" style="border-style:solid" bordercolor="#999999" class="tbl">
    <tr align="center">
      <td class="thcview2"><strong>Year</strong></td>
      <td class="thcview2"><strong>Annual Age Limit</strong></td>
      <td class="thcview2"><strong>Annual Pretax Limit</strong></td>
      <td class="thcview2"><strong>Annual Roth Limit</strong></td>
      <td class="thcview2"><strong>Actions</strong></td>
      </tr>
    <?php do { ?>
      <tr>
        <td class="tdcview2"><?php echo $row_rsView['year']; ?></td>
        <td class="tdcview2">$ <?php echo $row_rsView['annual_age_limit']; ?></td>
        <td class="tdcview2">$ <?php echo $row_rsView['annual_pretax_limit']; ?></td>
        <td class="tdcview2">$ <?php echo $row_rsView['annual_roth_limit']; ?></td>
        <td align="center" class="tdcview2"><a href="admin_system_settings.php?menuTopItem=1&setting_id=<?php echo $row_rsView['setting_id']; ?>">Edit</a> | <a href="admin_system_settings.php?menuTopItem=1&del_id=<?php echo $row_rsView['setting_id']; ?>">Delete</a></td>
        </tr>
      <?php } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
  </table>
  
  </td>
    </tr>
</table>
<br />
  
  <?php } // Show if recordset not empty ?>
<?php if ($totalRows_rsEdit > 0) { // Show if recordset not empty ?>
<a name="edit" id="edit"></a>
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Edit System Settings</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<form id="form2" name="form2" method="POST" action="<?php echo $editFormAction; ?>">
    <table border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
      <tr valign="baseline">
        <td align="right" nowrap="nowrap" class="thc2">Year:</td>
        <td class="tdc2"><input name="year" type="text" id="year" value="<?php echo $row_rsEdit['year']; ?>" size="32" readonly="readonly" /></td>
      </tr>
      <tr valign="baseline">
        <td align="right" nowrap="nowrap" class="thc2">Annual Age Limit:</td>
        <td class="tdc2">$
          <input name="annual_age_limit" type="text" id="annual_age_limit" value="<?php echo $row_rsEdit['annual_age_limit']; ?>" size="12" /></td>
      </tr>
      <tr valign="baseline">
        <td align="right" nowrap="nowrap" class="thc2">Annual Pretax Limit:</td>
        <td class="tdc2">$
          <input name="annual_pretax_limit" type="text" id="annual_pretax_limit" value="<?php echo $row_rsEdit['annual_pretax_limit']; ?>" size="12" /></td>
      </tr>
      <tr valign="baseline">
        <td align="right" nowrap="nowrap" class="thc2">Annual Roth Limit:</td>
        <td class="tdc2">$
          <input name="annual_roth_limit" type="text" id="annual_roth_limit" value="<?php echo $row_rsEdit['annual_roth_limit']; ?>" size="12" /></td>
      </tr>
      <tr valign="baseline">
        <td align="right" nowrap="nowrap" class="thc2">&nbsp;</td>
        <td class="tdc2"><input type="submit" value="Update" />
        <input name="setting_id" type="hidden" id="setting_id" value="<?php echo $row_rsEdit['setting_id']; ?>" />
        <input name="menuTopItem" type="hidden" id="menuTopItem" value="1" /></td>
      </tr>
    </table>
    <input type="hidden" name="MM_update" value="form2" />
  </form>
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
mysql_free_result($rsEdit);

mysql_free_result($rsView);
?>