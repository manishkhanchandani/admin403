<?php require_once('../Connections/dw_conn.php'); ?>
<?php if(!$_REQUEST['menuTopItem']) $_REQUEST['menuTopItem'] = 3; ?>
<?php
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployerList = "SELECT * FROM employer ORDER BY name ASC";
$rsEmployerList = mysql_query($query_rsEmployerList, $dw_conn) or die(mysql_error());
$row_rsEmployerList = mysql_fetch_assoc($rsEmployerList);
$totalRows_rsEmployerList = mysql_num_rows($rsEmployerList);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>List Employer</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Employer List</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<?php if ($totalRows_rsEmployerList > 0) { // Show if recordset not empty ?>
  <table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
    <tr>
      <td class="thcview2"><strong>Name</strong></td>
      <td class="thcview2"><strong>Email</strong></td>
      <td class="thcview2"><strong>Actions</strong></td>
      </tr>
    <?php do { ?>
      <tr>
        <td class="tdcview2"><?php echo $row_rsEmployerList['name']; ?>&nbsp;</td>
        <td class="tdcview2"><?php echo $row_rsEmployerList['email']; ?>&nbsp;</td>
        <td class="tdcview2"><!-- <a href="admin_employer_vendor.php?employer_id=<?php echo $row_rsEmployerList['employer_id']; ?>&menuTopItem=3">Manage Vendors</a>  --><a href="view_vendors.php?employer_id=<?php echo $row_rsEmployerList['employer_id']; ?>&menuTopItem=3">Manage Vendors</a> | <a href="admin_employer_edit.php?employer_id=<?php echo $row_rsEmployerList['employer_id']; ?>&menuTopItem=3">Edit</a> | <a href="admin_employer_delete.php?employer_id=<?php echo $row_rsEmployerList['employer_id']; ?>&menuTopItem=3">Delete</a></td>
        </tr>
      <?php } while ($row_rsEmployerList = mysql_fetch_assoc($rsEmployerList)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rsEmployerList == 0) { // Show if recordset empty ?>
  <p>No List Found.</p>
  <?php } // Show if recordset empty ?></td>
    </tr>
</table>
<br />


<p>&nbsp; </p>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsEmployerList);
?>
