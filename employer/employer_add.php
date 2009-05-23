<?php require_once('../Connections/dw_conn.php'); ?>
<?php include_once('start.php'); ?>
<?php
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frmEmployer")) {
	if($_POST['name'] && $_POST['email'] && ($_POST['password']==$_POST['password'])) {
		$_POST['password'] = md5($_POST['password']);
	} else {
		$error = "Please fill all fields and password should match confirm password";
		$_POST["MM_insert"] = "";
	}
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frmEmployer")) {
	function checkNewUser($e) {
		$rs = mysql_query("select * from users where email = '".addslashes(stripslashes(trim($e)))."'") or die('error');
		return mysql_num_rows($rs);
	}
	$checkValue = checkNewUser($_POST['email']);
	if($checkValue==0) {
		$sql = "INSERT INTO `users` (`email` , `password` , `created_dt` , `login_type` , `acting_as` ) VALUES ('".addslashes(stripslashes(trim($_POST['email'])))."', '".$_POST['password']."', '".date('Y-m-d H:i:s')."', 'Employer', NULL)";
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
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frmEmployer")) {
  $insertSQL = sprintf("INSERT INTO employer (name, phone, email, address, loan_provision, service_provision, hardship_provision, exchanges, transfers_in, transfers_out, roth_provision, service_eligible_limit, password, created_dt, status) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['phone'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['address'], "text"),
                       GetSQLValueString(isset($_POST['loan_provision']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['service_provision']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['hardship_provision']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['exchanges']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['transfers_in']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['transfers_out']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['roth_provision']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString($_POST['service_eligible_limit'], "double"),
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['created_dt'], "int"),
                       GetSQLValueString($_POST['status'], "int"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($insertSQL, $dw_conn) or die(mysql_error());
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frmEmployer")) {
	$uid = mysql_insert_id();
	$sql = "update `users` set id = '".$uid."' where user_id = '".$user_id."'";
	$rs = mysql_query($sql) or die('could not insert in users table');
		
	$insertGoTo = "../main/login.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Add New Employer</title>

<?php include('css.php'); ?>
<?php include('js.php'); ?>

</head>

<body>
<table border="6" align="center" cellpadding="3" cellspacing="0" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Add New Employer</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<?php echo $error; ?>
<form method="post" name="frmEmployer" action="<?php echo $editFormAction; ?>">
  <table border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
    <tr valign="baseline">
      <td align="right" nowrap class="thc2">Email:</td>
      <td class="tdc2"><input type="text" name="email" value="<?php echo $_POST['email']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap class="thc2">Password:</td>
      <td class="tdc2"><input type="password" name="password" value="<?php echo $_POST['password']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap class="thc2">Confirm Password: </td>
      <td class="tdc2"><input name="cpassword" type="password" id="cpassword" value="<?php echo $_POST['cpassword']; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap class="thc2">Name:</td>
      <td class="tdc2"><input type="text" name="name" value="<?php echo $_POST['name']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap class="thc2">Phone:</td>
      <td class="tdc2"><input type="text" name="phone" value="<?php echo $_POST['phone']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="top" nowrap class="thc2">Address:</td>
      <td class="tdc2"><textarea name="address" cols="50" rows="5"><?php echo $_POST['address']; ?></textarea>      </td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap class="thc2">Loan_provision:</td>
      <td class="tdc2"><input type="checkbox" name="loan_provision" value="Y" ></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap class="thc2">Service_provision:</td>
      <td class="tdc2"><input type="checkbox" name="service_provision" value="Y" onclick="if(document.frmEmployer.service_provision.checked==true) toggleLayer('divServiceEligibleLimit',1); else toggleLayer('divServiceEligibleLimit',0);" ></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap class="thc2">&nbsp;</td>
      <td class="tdc2"><div id="divServiceEligibleLimit" style="display:none;">Service Eligible Limit: 
      $ <input name="service_eligible_limit" type="text" id="service_eligible_limit" size="5" /></div>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="thc2">Hardship Provision:</td>
      <td class="tdc2"><input type="checkbox" name="hardship_provision" value="Y" ></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="thc2">Exchanges:</td>
      <td class="tdc2"><input type="checkbox" name="exchanges" value="Y" ></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="thc2">Transfers In:</td>
      <td class="tdc2"><input type="checkbox" name="transfers_in" value="Y" ></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="thc2">Transfers Out:</td>
      <td class="tdc2"><input type="checkbox" name="transfers_out" value="Y" ></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="thc2">Roth Provision:</td>
      <td class="tdc2"><input type="checkbox" name="roth_provision" value="Y" ></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap class="thc2">&nbsp;</td>
      <td class="tdc2"><input type="submit" value="Insert record"></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap class="thc2">&nbsp;</td>
      <td class="tdc2"><a href="../main/login.php">Back</a></td>
    </tr>
  </table>
  <input type="hidden" name="created_dt" value="<?php echo time(); ?>">
  <input type="hidden" name="status" value="1">
  <input type="hidden" name="MM_insert" value="frmEmployer" /><input name="menuTopItem" type="hidden" id="menuTopItem" value="3" />
</form>
      </td>
    </tr>
</table>
<br />

<p>&nbsp; </p>
<?php include('end.php'); ?>
</body>
</html>