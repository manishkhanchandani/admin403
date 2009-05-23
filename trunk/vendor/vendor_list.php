<?php require_once('../Connections/dw_conn.php'); ?>
<?php
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

$colname_rsVendors = "-1";
if (isset($_COOKIE['vendor']['vendor_id'])) {
  $colname_rsVendors = (get_magic_quotes_gpc()) ? $_COOKIE['vendor']['vendor_id'] : addslashes($_COOKIE['vendor']['vendor_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsVendors = sprintf("SELECT vendor_id, name, email, url FROM vendor WHERE vendor_id = %s ORDER BY name ASC", $colname_rsVendors);
$rsVendors = mysql_query($query_rsVendors, $dw_conn) or die(mysql_error());
$row_rsVendors = mysql_fetch_assoc($rsVendors);
$totalRows_rsVendors = mysql_num_rows($rsVendors);

$colname_rsdocu = "-1";
if (isset($_COOKIE['vendor']['vendor_id'])) {
  $colname_rsdocu = (get_magic_quotes_gpc()) ? $_COOKIE['vendor']['vendor_id'] : addslashes($_COOKIE['vendor']['vendor_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsdocu = sprintf("SELECT * FROM vendor_documents WHERE vendor_id = %s", $colname_rsdocu);
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">My Details</font></td>
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
          <td valign="top" class="thcview2"><strong><?php echo DISPLAYPLANNAME;?></strong></td>
          <td valign="top" class="thcview2"><strong>Documents</strong></td>
          <td valign="top" class="thcview2"><strong>Actions</strong></td>
      </tr>
    <?php do { ?>
      <tr>
        <td valign="top" class="tdcview2"><a href="<?php echo $row_rsVendors['url']; ?>" target="_blank"><?php echo $row_rsVendors['name']; ?></a></td>
        <td valign="top" class="tdcview2"><?php echo $row_rsVendors['email']; ?></td>
        <td valign="top" class="tdcview2"><?php 
			$colname_rsPlan = "-1";
			if (isset($row_rsVendors['vendor_id'])) {
			  $colname_rsPlan = $row_rsVendors['vendor_id'];
			}
			mysql_select_db($database_dw_conn, $dw_conn);
			$query_rsPlan = sprintf("SELECT * FROM vendor_plan WHERE vendor_id = %s", GetSQLValueString($colname_rsPlan, "int"));
			$rsPlan = mysql_query($query_rsPlan, $dw_conn) or die(mysql_error());
			$row_rsPlan = mysql_fetch_assoc($rsPlan);
			$totalRows_rsPlan = mysql_num_rows($rsPlan);	
			do { 
				echo $row_rsPlan['plan_name']; 
				echo "<br />";
			} while ($row_rsPlan = mysql_fetch_assoc($rsPlan)); 
			mysql_free_result($rsPlan);
		?>    </td>
        <td valign="top" class="tdcview2">
                <?php 
		if($documentList[$row_rsVendors['vendor_id']]) {
			foreach($documentList[$row_rsVendors['vendor_id']] as $key => $value) {
		?>
          <a href="../vendor/files/<?php echo $key; ?>" target="_blank"><?php if($value) echo $value; else echo $key; ?></a><br />
                <?php
			}
		}
		?>
          &nbsp; </td>
        <td valign="top" class="tdcview2"><form id="form<?php echo $row_rsVendors['vendor_id']; ?>" name="form<?php echo $row_rsVendors['vendor_id']; ?>" method="get" action="vendor_actions.php">
          <select name="action" id="action">
            <option value="addplan">Add/Remove <?php echo DISPLAYPLANNAME;?></option>
            <option value="edit">Edit Vendor</option>
            <option value="upload">Upload Plan Documents</option>
            </select>
          <input name="vendor_id" type="hidden" id="vendor_id" value="<?php echo $row_rsVendors['vendor_id']; ?>" />
          <input type="submit" name="button" id="button" value="Go" />
          <input name="menuTopItem" type="hidden" id="menuTopItem" value="4" />
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
