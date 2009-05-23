<?php require_once('../Connections/dw_conn.php'); ?>
<?php
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsVendor = "SELECT vendor_plan.plan_name, vendor.name, vendor.vendor_id, vendor_plan.plan_link, vendor_plan.plan_code, vendor_plan.plan_desc FROM vendor, vendor_plan WHERE vendor.vendor_id = vendor_plan.vendor_id";
$rsVendor = mysql_query($query_rsVendor, $dw_conn) or die(mysql_error());
$row_rsVendor = mysql_fetch_assoc($rsVendor);
$totalRows_rsVendor = mysql_num_rows($rsVendor);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Untitled Document</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Request List</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
	  	<table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
			<tr>
			  	<td valign="top" class="thcview2"><strong>Name</strong></td>
				<td valign="top" class="thcview2"><strong>Email</strong></td>
				<td valign="top" class="thcview2"><strong>Plans</strong></td>
				<td valign="top" class="thcview2"><strong>Documents</strong></td>
				<td valign="top" class="thcview2"><strong>Actions</strong></td>
			  </tr>
			  <tr>
			  	<td valign="top" class="tdcview2">&nbsp;</td>
				<td valign="top" class="tdcview2">&nbsp;</td>
				<td valign="top" class="tdcview2">&nbsp;</td>
				<td valign="top" class="tdcview2">&nbsp;</td>
				<td valign="top" class="tdcview2">Add Vendor </td>
			  </tr>
		</table>
	  </td>
	</tr>
</table>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsVendor);
?>
