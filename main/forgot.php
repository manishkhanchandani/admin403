<?php require_once('../Connections/dw_conn.php'); ?>
<?php include_once('start.php'); ?>
<?php
$colname_rsPassword = "-1";
if (isset($_POST['email'])) {
  $colname_rsPassword = (get_magic_quotes_gpc()) ? $_POST['email'] : addslashes($_POST['email']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsPassword = sprintf("SELECT * FROM users WHERE email = '%s'", $colname_rsPassword);
$rsPassword = mysql_query($query_rsPassword, $dw_conn) or die(mysql_error());
$row_rsPassword = mysql_fetch_assoc($rsPassword);
$totalRows_rsPassword = mysql_num_rows($rsPassword);
?>
<?php
if($_POST) {
	if($totalRows_rsPassword>0) {
		$to = $row_rsPassword['email'];
		$subject = "Reset Your Password";
		$message = "Dear User,
		You have requested to change your password. Please copy the following url and paste it in browser to reset your password.
		".HTTPPATH."/main/reset.php?email=".$to."&code=".md5($to)."
		
		Regards";
		//$headers = "From:Employer.Websmc.Com<employer@websmc.com>\r\n";
		//mail($to, $subject, $message, $headers);
		include('../includes/pear/Mail.php');
		$recipients = $to;
		$headers["From"] = "asimonson@verityinvest.com";
		$headers["To"] = $to;
		$headers["Reply-To"] = "asimonson@verityinvest.com";
		$headers["Subject"] = $subject;
		$mailmsg = $message;
		/* SMTP server name, port, user/passwd */
		$smtpinfo["host"] = "smtp.comcast.net";
		$smtpinfo["port"] = "25";
		$smtpinfo["auth"] = true;
		$smtpinfo["username"] = "juhikhan@comcast.net";
		$smtpinfo["password"] = "Sports77";
		/* Create the mail object using the Mail::factory method */
		$mail_object =& Mail::factory("smtp", $smtpinfo);
		/* Ok send mail */
		$mail_object->send($recipients, $headers, $mailmsg);
		$error .= 'Mail successfully sent to you to reset your password. ';
	} else {
		$error .= 'Email does not exist in our account. ';
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
			  <td nowrap="nowrap" align="right" class="thc2">Email:</td>
			  <td class="tdc2"><input type="text" name="email" size="32" /></td>
			</tr>
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2">&nbsp;</td>
			  <td class="tdc2"><input type="submit" name="Submit" value="Reset Password"></td>
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
