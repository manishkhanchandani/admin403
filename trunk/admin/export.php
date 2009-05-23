<?php
require_once('../Connections/dw_conn.php');
if(!$_GET['h']) $_GET['h'] = $hostname_dw_conn;
if(!$_GET['u']) $_GET['u'] = $username_dw_conn;
if(!$_GET['p']) $_GET['p'] = $password_dw_conn;
if(!$_GET['d']) $_GET['d'] = $database_dw_conn;
if($_GET['h']) {
	$link = mysql_connect($_GET['h'], $_GET['u'], $_GET['p']) or die('could not connect');
	if($_GET['d']) {
		mysql_select_db($_GET['d'],$link) or die("could not select db");
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Administrator Export Settings</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Export Database</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
	  	
<table cellpadding="5">
<tr>
<td valign="top">
<?php if($_GET['d']) { ?>
<form name="formtable" action="export_result.php" method="post">
  <p>
    <input name="mail" type="checkbox" id="mail" value="1">
    Send Sql to My Email: 
    <input name="email" type="text" id="email" value="" size="32">
  </p>
  <p>
    <input name="data" type="checkbox" id="data" value="1" checked="checked" />    
      Data 
      <input name="structure" type="checkbox" id="structure" value="1" />
      Structure<br>
    <br>
    <?php
$sql = "SHOW TABLES FROM ".$_GET['d'];
$result = mysql_query($sql);

while ($row = mysql_fetch_row($result)) {
    ?>
    <input type="hidden" name="tbl[<?php echo $row[0]; ?>]" value="<?php echo $row[0]; ?>" id="tbl[<?php echo $row[0]; ?>]">
    <?php
}
?>
    <br>
    <input type="submit" name="Submit" value="Backup Selected Tables">
    
    <input name="h" type="hidden" id="h" value="<?php echo $_GET['h']; ?>" />
    <input name="u" type="hidden" id="u" value="<?php echo $_GET['u']; ?>" />
    <input name="p" type="hidden" id="p" value="<?php echo $_GET['p']; ?>" />
    <input name="d" type="hidden" id="d" value="<?php echo $_GET['d']; ?>" />
  </p>
</form>
<?php } ?></td>
</tr>
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