<?php require_once('../Connections/dw_conn.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

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
}

$colname_rsVendors = "%";
if (isset($_GET['kw'])) {
  $colname_rsVendors = (get_magic_quotes_gpc()) ? $_GET['kw'] : addslashes($_GET['kw']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsVendors = sprintf("SELECT vendor.vendor_id, vendor.name, vendor.email, vendor.url, vendor_plan.plan_name FROM vendor LEFT JOIN vendor_plan ON vendor.vendor_id = vendor_plan.vendor_id WHERE (vendor.name LIKE '%%%s%%' OR vendor.email LIKE  '%%%s%%' OR vendor.url LIKE  '%%%s%%' OR vendor_plan.plan_name LIKE   '%%%s%%' OR vendor_plan.plan_code LIKE  '%%%s%%' OR vendor_plan.plan_desc LIKE  '%%%s%%' OR vendor_plan.plan_link LIKE  '%%%s%%') ORDER BY vendor.name ASC", $colname_rsVendors,$colname_rsVendors,$colname_rsVendors,$colname_rsVendors,$colname_rsVendors,$colname_rsVendors,$colname_rsVendors);
$rsVendors = mysql_query($query_rsVendors, $dw_conn) or die(mysql_error());
$row_rsVendors = mysql_fetch_assoc($rsVendors);
$totalRows_rsVendors = mysql_num_rows($rsVendors);

mysql_select_db($database_dw_conn, $dw_conn);
$query_rsdocu = "SELECT * FROM vendor_documents";
$rsdocu = mysql_query($query_rsdocu, $dw_conn) or die(mysql_error());
$row_rsdocu = mysql_fetch_assoc($rsdocu);
$totalRows_rsdocu = mysql_num_rows($rsdocu);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Vendor's List</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Search Vendor</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd"><form id="form1" name="form1" method="get" action="">
        Keyword: 
        <input name="kw" type="text" id="kw" value="<?php echo $_GET['kw']; ?>" />
        <input type="submit" name="button2" id="button2" value="Search" />
        <input name="menuTopItem" type="hidden" id="menuTopItem" value="4" />
       <br />
       <br />
      Note: Keyword must contain one of the following fields: Vendor Name, Vendor Email, <?php echo DISPLAYPLANNAME;?> Name, <?php echo DISPLAYPLANNAME;?> Details, <?php echo DISPLAYPLANNAME;?> Link etc.
      </form>
      </td>
    </tr>
</table>
<br />
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">List of Vendors</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
        <?php 
if ($totalRows_rsdocu > 0) { // Show if recordset not empty 
	do { 
    	$documentList[$row_rsdocu['vendor_id']][$row_rsdocu['filename']] = $row_rsdocu['display'];
	} while ($row_rsdocu = mysql_fetch_assoc($rsdocu));
} // Show if recordset not empty  
?>
        <?php if ($totalRows_rsVendors > 0) { // Show if recordset not empty ?>
  <table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
    <tr>
      <td valign="top" class="thcview2"><strong>Name</strong></td>
          <td valign="top" class="thcview2"><strong>Email</strong></td>
          <td valign="top" class="thcview2"><strong>Product</strong></td>
          <td valign="top" class="thcview2"><strong>Documents</strong></td>
          <td valign="top" class="thcview2"><strong>Actions</strong></td>
      </tr>
    <?php do { ?>
      <tr>
        <td valign="top" class="tdcview2"><?php $TFM_nest3 = $row_rsVendors['name']; if ($lastTFM_nest3 != $TFM_nest3) { $lastTFM_nest3 = $TFM_nest3; ?><a href="<?php echo $row_rsVendors['url']; ?>" target="_blank"><?php echo $row_rsVendors['name']; ?></a><?php } ?>
          &nbsp;</td>
        <td valign="top" class="tdcview2"><?php $TFM_nest2 = $row_rsVendors['email']; if ($lastTFM_nest2 != $TFM_nest2) { $lastTFM_nest2 = $TFM_nest2; echo $row_rsVendors['email']; } ?>
          &nbsp;</td>
        <td valign="top" class="tdcview2"><?php echo $row_rsVendors['plan_name']; ?>&nbsp;</td>
        <td valign="top" class="tdcview2">
                <?php 
		if($documentList[$row_rsVendors['vendor_id']]) {
			foreach($documentList[$row_rsVendors['vendor_id']] as $key => $value) {
		?>
          <a href="../vendor/files/<?php echo $key; ?>" target="_blank"><?php echo $value;?></a><br />
                <?php
			}
		}
		?>
          &nbsp; &nbsp;</td>
        <td valign="top" class="tdcview2"><form id="form<?php echo $row_rsVendors['vendor_id']; ?>" name="form<?php echo $row_rsVendors['vendor_id']; ?>" method="get" action="admin_vendor_actions.php">
          <select name="action" id="action">
            <option value="addplan">Add/Remove Plan</option>
            <option value="edit">Edit Vendor</option>
            <option value="delete">Delete Vendor</option>
            <option value="upload">Upload Plan Documents</option>
            </select>
          <input name="vendor_id" type="hidden" id="vendor_id" value="<?php echo $row_rsVendors['vendor_id']; ?>" />
          <input type="submit" name="button" id="button" value="Go" />
          <input name="menuTopItem" type="hidden" id="menuTopItem" value="4" />
&nbsp;        
        </form></td>
      </tr>
      <?php } while ($row_rsVendors = mysql_fetch_assoc($rsVendors)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
        <?php if ($totalRows_rsVendors == 0) { // Show if recordset empty ?>
          <p>No List Found.</p>
          <?php } // Show if recordset empty ?></td>
    </tr>
</table>
<br />

<p>&nbsp;</p>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsVendors);

mysql_free_result($rsdocu);
?>
