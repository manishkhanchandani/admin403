<?php require_once('../Connections/dw_conn.php'); ?>
<?php include_once('start.php'); ?>
<?php
$colname_rsPassword = "-1";
if (isset($_GET['email'])) {
  $colname_rsPassword = (get_magic_quotes_gpc()) ? $_GET['email'] : addslashes($_GET['email']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsPassword = sprintf("SELECT * FROM users WHERE email = '%s'", $colname_rsPassword);
$rsPassword = mysql_query($query_rsPassword, $dw_conn) or die(mysql_error());
$row_rsPassword = mysql_fetch_assoc($rsPassword);
$totalRows_rsPassword = mysql_num_rows($rsPassword);
?>
<?php
if($_POST) {
	if(md5($_POST['email'])==$_GET['code']) {
		if($_POST['password'] && $_POST['password']==$_POST['cpassword']) {
			switch($row_rsPassword['login_type']) {
				case 'Admin':
					$sql = "update admin set password = '".md5($_POST['password'])."' where admin_id = '".$row_rsPassword['id']."'";
					break;
				case 'Employer':
					$sql = "update employer set password = '".md5($_POST['password'])."' where employer_id = '".$row_rsPassword['id']."'";
					break;
				case 'Employee':
					$sql = "update employee set password = '".md5($_POST['password'])."' where employee_id = '".$row_rsPassword['id']."'";
					break;
				case 'Vendor':
					$sql = "update vendor set password = '".md5($_POST['password'])."' where vendor_id = '".$row_rsPassword['id']."'";
					break;
				case 'Designee':
					$sql = "update employer_access set compliance_designee_password = '".md5($_POST['password'])."' where compliance_id = '".$row_rsPassword['id']."'";
					break;
			}
			mysql_query($sql) or die('error in line '.__LINE__.' of file '.__FILE__.' due to mysql error '.mysql_error());
			$sql = "update users set password = '".md5($_POST['password'])."' where user_id = '".$row_rsPassword['user_id']."'";
			mysql_query($sql) or die('error in line '.__LINE__.' of file '.__FILE__.' due to mysql error '.mysql_error());
			$error .= 'Password Changed Successfully. ';
		} else {
			$error .= 'Password should match confirm password';
		}
	} else {
		$error .= 'Code is incorrect. Please check the link and try again. ';
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Forgot Password</title>
<?php include('css.php'); ?>
<?php include('js.php'); ?>
</head>

<body>
<table width="30%" border="6" align="center" cellpadding="3" cellspacing="0" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Forgot Password</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
	  	<form action="" name="form1" method="post">
			<?php echo $error; ?>
	  	<table width="100%" border="6" cellpadding="5" cellspacing="0" bordercolor="#999999" class="tbl" style="border-style:solid">
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2">New Password:</td>
			  <td class="tdc2"><input name="password" type="password" id="password" size="32" /></td>
			</tr>
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2">Confirm New Password: </td>
			  <td class="tdc2"><input name="cpassword" type="password" id="cpassword" size="32"></td>
		  </tr>
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2">&nbsp;</td>
			  <td class="tdc2"><input type="submit" name="Submit" value="Reset Password">
		      <input name="email" type="hidden" id="email" value="<?php echo $row_rsPassword['email']; ?>"></td>
		  </tr>
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2">&nbsp;</td>
			  <td class="tdc2"><a href="login.php">Back</a></td>
		  </tr>
		</table>
		</form>
	  </td>
	</tr>
</table>

<?php include('end.php'); ?>
</body>
</html>
<?php
mysql_free_result($rsPassword);
?>
