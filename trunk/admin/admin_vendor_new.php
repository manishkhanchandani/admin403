<?php require_once('../Connections/dw_conn.php'); ?>
<?php
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	if($_POST['name'] && $_POST['email'] && ($_POST['password']==$_POST['password'])) {
		$_POST['password'] = md5($_POST['password']);		
	} else {
		$error = "Please fill all fields and password should match confirm password";
		$_POST["MM_insert"] = "";
	}
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	function checkNewUser($e) {
		$rs = mysql_query("select * from users where email = '".addslashes(stripslashes(trim($e)))."'") or die('error');
		return mysql_num_rows($rs);
	}
	$checkValue = checkNewUser($_POST['email']);
	if($checkValue==0) {
		$sql = "INSERT INTO `users` (`email` , `password` , `created_dt` , `login_type` , `acting_as` ) VALUES ('".addslashes(stripslashes(trim($_POST['email'])))."', '".$_POST['password']."', '".date('Y-m-d H:i:s')."', 'Vendor', NULL)";
		$rs = mysql_query($sql) or die('could not insert in users table');
		$user_id = mysql_insert_id();
	} else {
		$error = "Email already exits, please use another email address.";
		$_POST["MM_insert"] = "";
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO vendor (name, password, email, phone, fax, url, remittance_address, created_dt, status, employer_access, employee_access) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['phone'], "text"),
                       GetSQLValueString($_POST['fax'], "text"),
                       GetSQLValueString($_POST['url'], "text"),
                       GetSQLValueString($_POST['remittance_address'], "text"),
                       GetSQLValueString($_POST['created_dt'], "int"),
                       GetSQLValueString($_POST['status'], "int"),
                       GetSQLValueString($_POST['employer_access'], "text"),
                       GetSQLValueString($_POST['employee_access'], "text"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($insertSQL, $dw_conn) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	$uid = mysql_insert_id();
	$sql = "update `users` set id = '".$uid."' where user_id = '".$user_id."'";
	$rs = mysql_query($sql) or die('could not insert in users table');
		
	$insertGoTo = "admin_vendor_list.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Add New Vendor</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Add New Vendor</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<?php echo $error; ?>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <table border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
        <tr valign="baseline">
          <td nowrap align="right" class="thc2">Email:</td>
          <td class="tdc2"><input type="text" name="email" value="<?php echo $_POST['email']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right" class="thc2">Password:</td>
          <td class="tdc2"><input type="password" name="password" value="<?php echo $_POST['password']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right" class="thc2">Confirm Password: </td>
          <td class="tdc2"><input name="cpassword" type="password" id="cpassword" value="<?php echo $_POST['cpassword']; ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right" class="thc2">Name:</td>
          <td class="tdc2"><input type="text" name="name" value="<?php echo $_POST['name']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right" class="thc2">Phone:</td>
          <td class="tdc2"><input type="text" name="phone" value="<?php echo $_POST['phone']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right" class="thc2">Fax:</td>
          <td class="tdc2"><input type="text" name="fax" value="<?php echo $_POST['fax']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right" class="thc2">Vender URL:</td>
          <td class="tdc2"><input name="url" type="text" id="url" size="35" value="<?php echo $_POST['url']; ?>" /></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap class="thc2">Remittance Address:</td>
          <td class="tdc2"><textarea name="remittance_address" cols="32" rows="4"><?php echo $_POST['remittance_address']; ?></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right" class="thc2"> Employer Account Access URL: </td>
          <td class="tdc2"><input name="employer_access" type="text" id="employer_access" size="32" value="<?php echo $_POST['employer_access']; ?>" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right" class="thc2"> Employee Account Access URL:</td>
          <td class="tdc2"><input name="employee_access" type="text" id="employee_access" size="32" value="<?php echo $_POST['employee_access']; ?>" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right" class="thc2">&nbsp;</td>
          <td class="tdc2"><input type="submit" value="Add New Vendor"></td>
        </tr>
      </table>
<input type="hidden" name="created_dt" value="<?php echo time(); ?>">
      <input type="hidden" name="status" value="1">
      <input type="hidden" name="MM_insert" value="form1"><input name="menuTopItem" type="hidden" id="menuTopItem" value="4" />
    </form>
      </td>
    </tr>
</table>
<br />

    <p>&nbsp; </p>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body><!-- InstanceEnd -->
</html>