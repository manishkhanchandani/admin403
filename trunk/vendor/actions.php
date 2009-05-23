<?php require_once('../Connections/dw_conn.php'); ?>
<?php
include_once('../main/functions.php');
$TYPE = 'vendor';
$TYPE2 = ucfirst($TYPE);
$TYPE3 = 'vendor_id';
$ID = $_COOKIE['vendor']['vendor_id'];
$NAME = $_COOKIE['vendor']['name'];
$EMAIL = $_COOKIE['vendor']['email'];
$STATUS = "actions.status = 'Pending'";
$UPDATE = 1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>My Actions</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">My Pending Actions</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
	  	<?php include('../main/actions.php'); ?>
	  </td>
	</tr>
</table>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body><!-- InstanceEnd --></html>