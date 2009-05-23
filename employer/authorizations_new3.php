<?php require_once('../Connections/dw_conn.php'); ?>
<?php
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	if(!$_POST['compliance_designee_email']) {
		unset($_POST["MM_insert"]);
		$error .= 'Please enter email. ';
	}
	if(!$_POST['compliance_designee_name']) {
		unset($_POST["MM_insert"]);
		$error .= 'Please enter name. ';
	}
	if(!$_POST['compliance_designee_password']) {
		unset($_POST["MM_insert"]);
		$error .= 'Please enter password. ';
	}
	if(!$_POST['compliance_designee_confirm_password']) {
		unset($_POST["MM_insert"]);
		$error .= 'Please enter confirm password. ';
	}
	if($_POST['compliance_designee_password']!=$_POST['compliance_designee_confirm_password']) {
		unset($_POST["MM_insert"]);
		$error .= 'Password should be same as confirm password. ';
	}
	$_POST['compliance_designee_password'] = md5($_POST['compliance_designee_password']);
}
?>
<?php
// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="authorizations_new3.php";
  $loginUsername = $_POST['compliance_designee_email'];
  $LoginRS__query = "SELECT email FROM users WHERE email='" . $loginUsername . "'";
  mysql_select_db($database_dw_conn, $dw_conn);
  $LoginRS=mysql_query($LoginRS__query, $dw_conn) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    //header ("Location: $MM_dupKeyRedirect");
    //exit;
	unset($_POST["MM_insert"]);
	$error .= 'Email already exist. Please choose another email. ';
  }
}

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
  $insertSQL = sprintf("INSERT INTO employer_access (employer_id, compliance_designee_name, compliance_designee_email, compliance_designee_phone, compliance_designee_password) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['employer_id'], "int"),
                       GetSQLValueString($_POST['compliance_designee_name'], "text"),
                       GetSQLValueString($_POST['compliance_designee_email'], "text"),
                       GetSQLValueString($_POST['compliance_designee_phone'], "text"),
                       GetSQLValueString($_POST['compliance_designee_password'], "text"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($insertSQL, $dw_conn) or die(mysql_error());
}
?>
<?php
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	$uid = mysql_insert_id();
	$sql = "INSERT INTO `users` (`email` , `password` , `created_dt` , `login_type` , `acting_as`, `id` ) VALUES ('".addslashes(stripslashes(trim($_POST['compliance_designee_email'])))."', '".$_POST['compliance_designee_password']."', '".date('Y-m-d H:i:s')."', 'Designee', 'Employer', '".$uid."')";
	$rs = mysql_query($sql) or die('could not insert in users table');
	$success = 1;
	$error = 'Designee Created Successfully. ';
	unset($_POST);
}
?>
<?php
$colname_rsAuth = "-1";
if (isset($_COOKIE['employer']['employer_id'])) {
  $colname_rsAuth = (get_magic_quotes_gpc()) ? $_COOKIE['employer']['employer_id'] : addslashes($_COOKIE['employer']['employer_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsAuth = sprintf("SELECT * FROM employer_access WHERE employer_id = %s", $colname_rsAuth);
$rsAuth = mysql_query($query_rsAuth, $dw_conn) or die(mysql_error());
$row_rsAuth = mysql_fetch_assoc($rsAuth);
$totalRows_rsAuth = mysql_num_rows($rsAuth);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Other Authorizations</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">New Authorizations</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
	  	<form action="<?php echo $editFormAction; ?>" name="form1" method="POST">
		<?php echo $error; ?>
	  	<table border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2">Compliance Designee Email:</td>
			  <td class="tdc2"><input name="compliance_designee_email" type="text" id="compliance_designee_email" value="<?php echo $_POST['compliance_designee_email']; ?>" size="32" /></td>
			</tr>
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2">Compliance Designee Password:</td>
			  <td class="tdc2"><input name="compliance_designee_password" type="password" id="compliance_designee_password" value="" size="15" /></td>
		  </tr>
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2">Compliance Designee Confirm Password:</td>
			  <td class="tdc2"><input name="compliance_designee_confirm_password" type="password" id="compliance_designee_confirm_password" value="" size="15" /></td>
		  </tr>
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2">Compliance Designee Name:</td>
			  <td class="tdc2"><input name="compliance_designee_name" type="text" id="compliance_designee_name" value="<?php echo $_POST['compliance_designee_name']; ?>" size="32" /></td>
		  </tr>
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2">Compliance Designee Phone Number:</td>
			  <td class="tdc2"><input name="compliance_designee_phone" type="text" id="compliance_designee_phone" value="<?php echo $_POST['compliance_designee_phone']; ?>" size="32" /></td>
		  </tr>
			<tr valign="baseline">
			  <td nowrap="nowrap" align="right" class="thc2">&nbsp;</td>
			  <td class="tdc2"><input type="submit" name="Submit" value="Add New Authorizations" />
		      <input type="hidden" name="menuTopItem" value="<?php echo $_REQUEST['menuTopItem']; ?>" />
		      <input name="employer_id" type="hidden" id="employer_id" value="<?php echo $_COOKIE['employer']['employer_id']; ?>" /></td>
		  </tr>
		</table>
		<input type="hidden" name="MM_insert" value="form1">
	  	</form>
	  </td>
	</tr>
</table>
<br />
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Authorizations</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
	  	<table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
			<tr>
			  	<td valign="top" class="thcview2"><strong>Name</strong></td>
				<td valign="top" class="thcview2"><strong>Email</strong></td>
				<td valign="top" class="thcview2"><strong>Phone</strong></td>
				<td valign="top" class="thcview2"><strong>Actions</strong></td>
			  </tr>
			  <?php do { ?>
			  <tr>
		  	    <td valign="top" class="tdcview2"><?php echo $row_rsAuth['compliance_designee_name']; ?>&nbsp;</td>
		  	    <td valign="top" class="tdcview2"><?php echo $row_rsAuth['compliance_designee_email']; ?>&nbsp;</td>
		  	    <td valign="top" class="tdcview2"><?php echo $row_rsAuth['compliance_designee_phone']; ?>&nbsp;</td>
		  	    <td valign="top" class="tdcview2"><a href="authorizations_delete.php?compliance_id=<?php echo $row_rsAuth['compliance_id']; ?>&menuTopItem=<?php echo $_REQUEST['menuTopItem']; ?>">Delete</a> </td>
		      </tr>
		  <?php } while ($row_rsAuth = mysql_fetch_assoc($rsAuth)); ?>
		</table>
	  </td>
	</tr>
</table>
<p>&nbsp;</p>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsAuth);
?>
