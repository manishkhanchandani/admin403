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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Database Backup</title>
<style type="text/css">
<!--
body,td,th,input,select,button,textarea {
	font-family: Verdana;
	font-size: 11px;
}
-->
</style>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function flevToggleCheckboxes() { // v1.1
	// Copyright 2002, Marja Ribbers-de Vroed, FlevOOware (www.flevooware.nl/dreamweaver/)
	var sF = arguments[0], bT = arguments[1], bC = arguments[2], oF = MM_findObj(sF);
    for (var i=0; i<oF.length; i++) {
		if (oF[i].type == "checkbox") {if (bT) {oF[i].checked = !oF[i].checked;} else {oF[i].checked = bC;}}} 
}

function uncheck() {
	document.formtable.mail.checked = false;
	document.formtable.cd.checked = false;
	document.formtable.cs.checked = false;
	document.formtable.is.checked = false;
	document.formtable.id.checked = false;
	document.formtable.writetofile.checked = false;
}
//-->
</script>
</head>

<body>
<h1>MySQL Backup </h1>
<table cellpadding="5">
<tr>
<td valign="top">
<?php if($_GET['h']) { ?>
<h3>List of Databases</h3>
<?php
$db_list = mysql_list_dbs($link);

while ($row = mysql_fetch_object($db_list)) {
	if(eregi("mkhancha", $row->Database)) {
		static $srno=0; $srno++; echo $srno.". ";
		 ?>
		 <a href="<?php echo $_SERVER['PHP_SELF']; ?>?h=<?php echo $_GET['h']; ?>&u=<?php echo $_GET['u']; ?>&p=<?php echo $_GET['p']; ?>&d=<?php echo $row->Database; ?>"><?php echo $row->Database; ?></a><br>
		 <?php
	}
}
?>
<?php } ?>
</td>
<td valign="top">
<?php if($_GET['d']) { ?>
<h3>Tables in Database <?php echo $_GET['d']; ?></h3>
<form name="formtable" action="backuptables2.php" method="post">
  <p>
  <input type="submit" name="Submit" value="Backup Selected Tables">
  <input name="h" type="hidden" id="h" value="<?php echo $_GET['h']; ?>">
  <input name="u" type="hidden" id="u" value="<?php echo $_GET['u']; ?>">
  <input name="p" type="hidden" id="p" value="<?php echo $_GET['p']; ?>">
  <input name="d" type="hidden" id="d" value="<?php echo $_GET['d']; ?>">
  </p>
  <p>
    <input name="mail" type="checkbox" id="mail" value="1">
    Send Sql to My Email: 
    <input name="email" type="text" id="email" value="naveenkhanchandani@gmail.com" size="32">
  </p>
  <p>
    <input name="cd" type="checkbox" id="cd" value="1">
    Complete Data Backup  
    <input name="cs" type="checkbox" id="cs" value="1">
    Complete Structure Backup</p>
  <p>
    <input name="id" type="checkbox" id="id" value="1">
Individual Table Data Backup
<input name="is" type="checkbox" id="is" value="1">
Individual Table Structure Backup</p>
  <p>
    <input name="writetofile" type="checkbox" id="writetofile" value="1">
    Write To File <br>
      <br>
      <input name="checkbox" type="checkbox" onClick="flevToggleCheckboxes('formtable',true,false)" value="1"> 
      <strong>Toggle</strong> <a href="#" onClick="uncheck();">Uncheck Others</a> <br>
      <br>
      <br>
      <?php
$sql = "SHOW TABLES FROM ".$_GET['d'];
$result = mysql_query($sql);

while ($row = mysql_fetch_row($result)) {
    ?>
      <input type="checkbox" name="tbl[<?php echo $row[0]; ?>]" value="<?php echo $row[0]; ?>" id="tbl[<?php echo $row[0]; ?>]">
      <?php echo $row[0]; ?><br>
      <?php
}
?>
        <br>
        <input type="submit" name="Submit" value="Backup Selected Tables">
  </p>
</form>
<?php } ?>
</td>
</tr>
</table>

<p>&nbsp; </p>
</body>
</html>