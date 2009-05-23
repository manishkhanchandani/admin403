<?php require_once('../Connections/dw_conn.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Administrator Database Management</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
	  	<ul>
			<li><a href="<?php echo HTTPPATH; ?>/admin/export.php?menuTopItem=1">Export Database</a></li>
			<li><a href="../sql/bigdump.php" target="_blank">Import Database</a> </li>
			<li><a href="<?php echo HTTPPATH; ?>/admin/truncatetables.php?menuTopItem=1" onClick="sc=confirm('are u sure you want to delete all table records. you cannot undo this change. click ok to delete all tables'); if(sc) return true; else return false;"><span>Truncate Tables</span></a> (Delete Data but keep Important Records)</li>
	  	    <li><a href="<?php echo HTTPPATH; ?>/admin/truncatetables_all.php?menuTopItem=1" onclick="sc=confirm('are u sure you want to delete all table records. you cannot undo this change. click ok to delete all tables. Admin login and password will be lost. So use this link if you want to import the old data immediately without logout.'); if(sc) {sc2=confirm('Are you sure?'); if (sc2) return true; else return false; } else return false;">Truncate Tables</a> (Delete Complete Data, Admin login and password will be lost. So use this link if you want to import the old data immediately without logout.) </li>
	  	</ul>
      </td>
    </tr>
</table>  

<p>&nbsp;</p>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body>
<!-- InstanceEnd --></html>