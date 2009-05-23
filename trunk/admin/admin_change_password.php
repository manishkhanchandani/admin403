<?php require_once('../Connections/dw_conn.php'); ?>
<?php
if($_POST['MM_Insert']==1) {
	if($_POST['password'] && $_POST['newpassword'] && ($_POST['newpassword']==$_POST['confirmpassword'])) {
		$rs = mysql_query("select * from admin where email = '".addslashes(stripslashes(trim($_POST['email'])))."' and password = '".md5($_POST['password'])."'") or die('error');
		if(mysql_num_rows($rs)==0) {
			$error = "Email and old password does not match.";
		} else {
			$rec = mysql_fetch_array($rs);
			$sql = "update admin set password = '".md5($_POST['newpassword'])."' where admin_id = '".$rec['admin_id']."'";
			mysql_query($sql) or die('error2');
			$sql = "update users set password = '".md5($_POST['newpassword'])."' where id = '".$rec['admin_id']."' and login_type = 'Admin'";
			mysql_query($sql) or die('error3');
			$error = 'Password Changed Successfully'; 
		}
	} else {
		$error = "Please fill old password and new password should be same as confirm password";
	}
}
if($_POST['MM_Insert']==2) {
	if($_POST['newpassword'] && ($_POST['newpassword']==$_POST['confirmpassword'])) {
		$rs = mysql_query("select * from users where email = '".addslashes(stripslashes(trim($_POST['email'])))."'") or die('error');
		if(mysql_num_rows($rs)>0) {
			$error2 = "Email already exists.";
		} else {
			$sql = "insert into admin set email = '".addslashes(stripslashes(trim($_POST['email'])))."', password = '".md5($_POST['newpassword'])."', name = '".addslashes(stripslashes(trim($_POST['name'])))."', address = '".addslashes(stripslashes(trim($_POST['address'])))."', phone = '".addslashes(stripslashes(trim($_POST['phone'])))."'";
			mysql_query($sql) or die('error2');
			
			$uid = mysql_insert_id();
			$sql = "INSERT INTO `users` (`email` , `password` , `created_dt` , `login_type` , `acting_as`, `id` ) VALUES ('".addslashes(stripslashes(trim($_POST['email'])))."', '".md5($_POST['newpassword'])."', '".date('Y-m-d H:i:s')."', 'Admin', NULL, '".$uid."')";
			$rs = mysql_query($sql) or die('could not insert in users table'.mysql_error());
		
			$error = 'Admin created Successfully'; 
		}
	} else {
		$error = "New password should be same as confirm password";
	}
}
if($_POST['MM_Insert']==3) {
	$sql = "update admin set name = '".addslashes(stripslashes(trim($_POST['name'])))."', address = '".addslashes(stripslashes(trim($_POST['address'])))."', phone = '".addslashes(stripslashes(trim($_POST['phone'])))."' where admin_id = '".$_GET['edit_id']."'";
	mysql_query($sql) or die('error2');
	$error3 = 'Details Changed Successfully'; 
}
?>
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

$colname_rsEdit = "-1";
if (isset($_GET['admin_id'])) {
  $colname_rsEdit = $_GET['admin_id'];
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEdit = sprintf("SELECT * FROM `admin` WHERE admin_id = %s", GetSQLValueString($colname_rsEdit, "int"));
$rsEdit = mysql_query($query_rsEdit, $dw_conn) or die(mysql_error());
$row_rsEdit = mysql_fetch_assoc($rsEdit);
$totalRows_rsEdit = mysql_num_rows($rsEdit);

mysql_select_db($database_dw_conn, $dw_conn);
$query_rsView = "SELECT * FROM `admin`";
$rsView = mysql_query($query_rsView, $dw_conn) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);
$totalRows_rsView = mysql_num_rows($rsView);

$colname_rsEdit2 = "-1";
if (isset($_GET['edit_id'])) {
  $colname_rsEdit2 = $_GET['edit_id'];
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEdit2 = sprintf("SELECT * FROM `admin` WHERE admin_id = %s", GetSQLValueString($colname_rsEdit2, "int"));
$rsEdit2 = mysql_query($query_rsEdit2, $dw_conn) or die(mysql_error());
$row_rsEdit2 = mysql_fetch_assoc($rsEdit2);
$totalRows_rsEdit2 = mysql_num_rows($rsEdit2);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Administrator Change Password</title>
<!-- InstanceEndEditable -->
<?php include('css.php'); ?>
<?php include('js.php'); ?>
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
</head>

<body>
<?php include('head.php'); ?>
<!-- InstanceBeginEditable name="EditRegion3" -->
<?php if ($totalRows_rsEdit > 0) { // Show if recordset not empty ?>
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Administrator Change Password</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
	 <?php if($error) { ?>
	  	<p class="error"><?php echo $error; ?></p>
        <?php } ?>
  <form id="form1" name="form1" method="post" action="">
        <table border="6" cellpadding="5" cellspacing="0" style="border-style:solid" bordercolor="#999999" class="tbl">
        <tr>
        <td align="right" class="thc2"><strong>Email:</strong></td>
        <td class="tdc2"><input name="email" type="text" id="email" value="<?php echo $row_rsEdit['email']; ?>" size="32" readonly="readonly" /></td>
        </tr>
        <tr>
        <td align="right" class="thc2"><strong>Old Password:</strong></td>
        <td class="tdc2"><input name="password" type="password" id="password" size="25" /></td>
        </tr>
        <tr>
          <td align="right" class="thc2"><strong>New Password: </strong></td>
          <td class="tdc2"><input name="newpassword" type="password" id="newpassword" size="25" /></td>
        </tr>
        <tr>
          <td align="right" class="thc2"><strong>Confirm New Password:</strong></td>
          <td class="tdc2"><input name="confirmpassword" type="password" id="confirmpassword" size="25" /></td>
        </tr>
        <tr>
          <td align="right" class="thc2">&nbsp;</td>
          <td class="tdc2"><input type="submit" name="button3" id="button3" value="Change Password" />
            <input name="MM_Insert" type="hidden" id="MM_Insert" value="1" />
            <input name="menuTopItem" type="hidden" id="menuTopItem" value="1" /></td>
        </tr>
        </table>
    </form>
      </td>
    </tr>
</table>  
<br />
  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rsEdit2 > 0) { // Show if recordset not empty ?>
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Edit Admin Details</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
      <?php if($error3) { ?>
	  	<p class="error"><?php echo $error3; ?></p>
        <?php } ?>
<form id="form3" name="form3" method="post" action="">
    <table border="6" cellpadding="5" cellspacing="0" style="border-style:solid" bordercolor="#999999" class="tbl">
      <tr>
        <td align="right" valign="top" class="thc2"><strong>Email:&nbsp;</strong></td>
        <td class="tdc2"><input name="email" type="text" id="email" value="<?php echo $row_rsEdit2['email']; ?>" size="32" readonly="readonly" />&nbsp;</td>
      </tr>
      <tr>
        <td align="right" valign="top" class="thc2"><strong>Name:&nbsp;</strong></td>
        <td class="tdc2"><input name="name" type="text" id="name" value="<?php echo $row_rsEdit2['name']; ?>" />&nbsp;</td>
      </tr>
      <tr>
        <td align="right" valign="top" class="thc2"><strong>Address:&nbsp;</strong></td>
        <td class="tdc2"><textarea name="address" id="address" cols="45" rows="5"><?php echo $row_rsEdit2['address']; ?></textarea>&nbsp;</td>
      </tr>
      <tr>
        <td align="right" valign="top" class="thc2"><strong>Phone:&nbsp;</strong></td>
        <td class="tdc2"><input name="phone" type="text" id="phone" value="<?php echo $row_rsEdit2['phone']; ?>" />&nbsp;</td>
      </tr>
      <tr>
        <td align="right" valign="top" class="thc2">&nbsp;</td>
        <td class="tdc2"><input type="submit" name="button2" id="button2" value="Update Admin" />
      <input name="MM_Insert" type="hidden" id="MM_Insert" value="3" />&nbsp;
      <input name="menuTopItem" type="hidden" id="menuTopItem" value="1" /></td>
      </tr>
    </table>
  </form>
      </td>
    </tr>
</table>
<br />  
  <?php } // Show if recordset not empty ?>
<?php if($_GET['admin_id'] || $_GET['edit_id']) {

} else {
?>
<table width="100%" border="2" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Add New Admin</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
		<?php if($error2) { ?>
	  	<p class="error"><?php echo $error2; ?></p>
        <?php } ?>
<form id="form2" name="form2" method="post" action="">
<table border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
  <tr>
    <td align="right" valign="top" class="thc2"><strong>Email:</strong></td>
    <td valign="top" class="tdc2"><input name="email" type="text" id="email" size="32" />&nbsp;</td>
  </tr>
  <tr>
    <td align="right" valign="top" class="thc2"><strong>Password:</strong></td>
    <td valign="top" class="tdc2"><input name="newpassword" type="password" id="newpassword" size="25" />&nbsp;</td>
  </tr>
  <tr>
    <td align="right" valign="top" class="thc2"><strong>Confirm Password:</strong></td>
    <td valign="top" class="tdc2"><input name="confirmpassword" type="password" id="confirmpassword" size="25" />&nbsp;</td>
  </tr>
  <tr>
    <td align="right" valign="top" class="thc2"><strong>Name:</strong></td>
    <td valign="top" class="tdc2"><input type="text" name="name" id="name" />&nbsp;</td>
  </tr>
  <tr>
    <td align="right" valign="top" class="thc2"><strong>Address:</strong></td>
    <td valign="top" class="tdc2"><textarea name="address" id="address" cols="45" rows="5"></textarea>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" valign="top" class="thc2"><strong>Phone:</strong></td>
    <td valign="top" class="tdc2"><input type="text" name="phone" id="phone" />&nbsp;</td>
  </tr>
  <tr>
    <td align="right" valign="top" class="thc2">&nbsp;</td>
    <td valign="top" class="tdc2"><input type="submit" name="button" id="button" value="Create New Admin" />
    <input name="MM_Insert" type="hidden" id="MM_Insert" value="2" />&nbsp;
    <input name="menuTopItem" type="hidden" id="menuTopItem" value="1" /></td>
  </tr>
</table>
</form>
      </td>
    </tr>
</table>
<br />
<?php } ?>
<table width="100%" border="2" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">View All Admin</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<table border="6" width="100%" cellpadding="5" cellspacing="0" style="border-style:solid" bordercolor="#999999" class="tbl">
  <tr>
    <td class="thcview2"><strong>Admin Id</strong></td>
    <td class="thcview2"><strong>Email</strong></td>
    <td class="thcview2"><strong>Actions</strong></td>
    </tr>
  <?php do { ?>
    <tr>
      <td class="tdcview2"><?php echo $row_rsView['admin_id']; ?></td>
      <td class="tdcview2"><?php echo $row_rsView['email']; ?></td>
      <td class="tdcview2"><a href="admin_change_password.php?admin_id=<?php echo $row_rsView['admin_id']; ?>&menuTopItem=1">Change Password</a> | <a href="admin_change_password.php?edit_id=<?php echo $row_rsView['admin_id']; ?>&menuTopItem=1">Change Details</a><?php if($row_rsView['admin_id']!=1) { ?> | <a href="admin_del_profile.php?admin_id=<?php echo $row_rsView['admin_id']; ?>&menuTopItem=1">Delete </a><?php } ?></td>
      </tr>
    <?php } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
</table>
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

mysql_free_result($rsView);

mysql_free_result($rsEdit2);
?>