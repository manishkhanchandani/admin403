<?php require_once('../Connections/dw_conn.php'); ?>
<?php
$colname_rsVendorList = "-1";
if (isset($_COOKIE['employer']['employer_id'])) {
  $colname_rsVendorList = (get_magic_quotes_gpc()) ? $_COOKIE['employer']['employer_id'] : addslashes($_COOKIE['employer']['employer_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsVendorList = sprintf("SELECT vendor.vendor_id, vendor.name, employer_vendor.employer_id FROM vendor, employer_vendor WHERE employer_vendor.vendor_id = vendor.vendor_id AND employer_vendor.employer_id = %s GROUP BY employer_vendor.vendor_id", $colname_rsVendorList);
$rsVendorList = mysql_query($query_rsVendorList, $dw_conn) or die(mysql_error());
$row_rsVendorList = mysql_fetch_assoc($rsVendorList);
$totalRows_rsVendorList = mysql_num_rows($rsVendorList);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Report Per Vendor</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Reports</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
	  <form name="form1" method="get" action="report_vendor_result.php">
      
	    <table width="100%" border="6" cellspacing="0" cellpadding="3" class="greentbl">
          <tr>
            <td class="greenth"><font color="#ffffff" face="Tahoma" size="2">Reports Per Vendor</font></td>
          </tr>
          <tr>
            <td valign="top" class="greentd"><table border="0" align="center" cellpadding="5" cellspacing="0">
              <tr>
                <td>Vendor: </td>
                <td><select name="vendor_id" id="vendor_id" onchange="doAjaxXMLSelectBox('getPlan.php','GET','vendor_id='+this.value,'',document.form1.plan_id);">
                  <option value="0">select a vendor</option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_rsVendorList['vendor_id']?>"><?php echo $row_rsVendorList['name']?></option>
                  <?php
} while ($row_rsVendorList = mysql_fetch_assoc($rsVendorList));
  $rows = mysql_num_rows($rsVendorList);
  if($rows > 0) {
      mysql_data_seek($rsVendorList, 0);
	  $row_rsVendorList = mysql_fetch_assoc($rsVendorList);
  }
?> 
                                </select></td>
              </tr>
              <tr>
                <td><?php echo DISPLAYPLANNAME;?>:</td>
                <td><select name="plan_id" id="plan_id">
                                </select></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td align="center" valign="top" class="greentd"><input type="submit" name="VendorSubmit" id="VendorSubmit" value="Get Vendor Report" />
              <input name="menuTopItem" type="hidden" id="menuTopItem" value="2" /></td>
          </tr>
        </table>
	  </form>
      </td>
    </tr>
</table>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsVendorList);
?>
