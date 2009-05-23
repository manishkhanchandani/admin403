<?php require_once('../Connections/dw_conn.php'); ?>
<?php
include_once('start.php');

$colname_rsEdit = "-1";
if (isset($_COOKIE['employee']['employee_id'])) {
  $colname_rsEdit = (get_magic_quotes_gpc()) ? $_COOKIE['employee']['employee_id'] : addslashes($_COOKIE['employee']['employee_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEdit = sprintf("SELECT employee_id, password, email, firstname FROM employee WHERE employee_id = %s", $colname_rsEdit);
$rsEdit = mysql_query($query_rsEdit, $dw_conn) or die(mysql_error());
$row_rsEdit = mysql_fetch_assoc($rsEdit);
$totalRows_rsEdit = mysql_num_rows($rsEdit);
?>
<?php
if($_POST['MM_Insert']==1) {
	if($_POST['oldpassword'] && $_POST['newpassword'] && $_POST['confirmpassword']) {
		if(md5($_POST['oldpassword'])==$row_rsEdit['password']) {
			if($_POST['newpassword']==$_POST['confirmpassword']) {
				$sql = "update employee set password = '".md5($_POST['newpassword'])."' where employee_id = '".$_COOKIE['employee']['employee_id']."'";
				mysql_query($sql) or die('error');
				$errorMessage = 'Password updated successfully.';
				//header("Location: change_password.php?errorMessage=".urlencode($errorMessage)."&menuTopItem=4");
				//exit;
			} else {
				$errorMessage .= "New Password does not match with confirm password";
			}
		} else {
			$errorMessage .= "Password does not match with our record.";
		}
	} else {
		$errorMessage .= 'Please add all fields.';
	}
} else {
	//$errorMessage = $_GET['errorMessage'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Change Password</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Change Password</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
	  	<?php echo $errorMessage; ?>
	  	<form action="" name="form1" method="post">
	  	<table border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2">Email:</td>
			  <td class="tdc2"><?php echo $row_rsEdit['email']; ?></td>
			</tr>
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2">First Name: </td>
			  <td class="tdc2"><?php echo $row_rsEdit['firstname']; ?></td>
		  </tr>
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2">Old Password:</td>
			  <td class="tdc2"><input name="oldpassword" type="password" id="oldpassword" /></td>
		  </tr>
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2">New Password: </td>
			  <td class="tdc2"><input name="newpassword" type="password" id="newpassword" /></td>
		  </tr>
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2">Confirm New Password: </td>
			  <td class="tdc2"><input name="confirmpassword" type="password" id="confirmpassword" /></td>
		  </tr>
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2">&nbsp;</td>
			  <td class="tdc2"><input type="submit" name="Submit" value="Change Password" />
		      <input name="MM_Insert" type="hidden" id="MM_Insert" value="1" />
		      <input name="menuTopItem" type="hidden" id="menuTopItem" value="4" /></td>
		  </tr>
		</table>
		</form>
	  </td>
	</tr>
</table>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body><!-- InstanceEnd --></html>
<?php
mysql_free_result($rsEdit);
?>
