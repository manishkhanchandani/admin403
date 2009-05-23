<?php require_once('../Connections/dw_conn.php'); ?>
<?php
function getVendorId($plan_id) {
	$rs = mysql_query("select * from vendor_plan where plan_id = '".$plan_id."'");
	$rec = mysql_fetch_array($rs);
	return $rec['vendor_id'];
}
function getAllVendorId() {
	$rs = mysql_query("select * from vendor_plan");
	while($rec = mysql_fetch_array($rs)) {
		$return[$rec['plan_id']] = $rec['vendor_id'];
	}
	return $return;
}
if($_POST['MM_Insert']==2) {
	if($_POST['vendor_active_string']) {
		$vendor_active_array = explode(",",$_POST['vendor_active_string']);
	} else {
		$vendor_active_array = array();
	}
	if($_POST['plan_id']) {
		$plan_selected = $_POST['plan_id'];
	} else {
		$plan_selected = array();
	}
	$resultDeleted = array_diff($vendor_active_array, $plan_selected);
	if($resultDeleted) {
		$resultDeletedText = implode(',',$resultDeleted);
		$sql = "update employer_vendor set active = 0 where employer_id = '".$_GET['employer_id']."' and plan_id in (".$resultDeletedText.")";
		$rs = mysql_query($sql) or die('error');
	}
	$resultInserted = array_diff($plan_selected, $vendor_active_array);
	if($resultInserted) {
		$resultInsertedString = implode(',',$resultInserted);
		$sql = "update employer_vendor set active = 1 where employer_id = '".$_GET['employer_id']."' and plan_id in (".$resultInsertedString.")";
		$rs = mysql_query($sql) or die('error');
	}
}
if($_POST['MM_Insert']==1) {
	$return = getAllVendorId();
	if($_POST['vendor_selected_text']) {
		$vendor_selected_array = explode(",",$_POST['vendor_selected_text']);
		if($_POST['plan_id']) {
			$plan_selected = $_POST['plan_id'];
		} else {
			$plan_selected = array();
		}
		$resultDeleted = array_diff($vendor_selected_array, $plan_selected);
		if($resultDeleted) {
			$resultDeletedText = implode(',',$resultDeleted);
			$sql = "delete from employer_vendor where employer_id = '".$_GET['employer_id']."' and plan_id in (".$resultDeletedText.")";
			$rs = mysql_query($sql) or die('error');
		}
		$resultInserted = array_diff($plan_selected, $vendor_selected_array);
		if($resultInserted) {
			$sql = "insert into employer_vendor (employer_id, plan_id, vendor_id) values ";
			foreach($resultInserted as $value) {
				if($value!="") {
					$sql2[] = "('".$_GET['employer_id']."', '".$value."', '".$return[$value]."')";
				}
			}
			$sql3 = implode(', ', $sql2);
			$sql .= $sql3;
			$rs = mysql_query($sql) or die('error');
		}
	} else {			
		$sql = "insert into employer_vendor (employer_id, plan_id, vendor_id) values ";
		foreach($_POST['plan_id'] as $key => $value) {
			if($value!="") {
				$sql2[] = "('".$_GET['employer_id']."', '".$value."', '".$return[$value]."')";
			}
		}
		$sql3 = implode(', ', $sql2);
		$sql .= $sql3;
		$rs = mysql_query($sql) or die('error');
	}
	header("Location: admin_employer_list.php?menuTopItem=3");
	exit;
}
?>
<?php
$colname_rsEmployer = "-1";
if (isset($_GET['employer_id'])) {
  $colname_rsEmployer = (get_magic_quotes_gpc()) ? $_GET['employer_id'] : addslashes($_GET['employer_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployer = sprintf("SELECT employer_id, name, email FROM employer WHERE employer_id = %s", $colname_rsEmployer);
$rsEmployer = mysql_query($query_rsEmployer, $dw_conn) or die(mysql_error());
$row_rsEmployer = mysql_fetch_assoc($rsEmployer);
$totalRows_rsEmployer = mysql_num_rows($rsEmployer);

mysql_select_db($database_dw_conn, $dw_conn);
$query_rsVendor = "SELECT * FROM vendor, vendor_plan  WHERE vendor.vendor_id = vendor_plan.vendor_id  ORDER BY vendor.name, vendor_plan.plan_name";
$rsVendor = mysql_query($query_rsVendor, $dw_conn) or die(mysql_error());
$row_rsVendor = mysql_fetch_assoc($rsVendor);
$totalRows_rsVendor = mysql_num_rows($rsVendor);

$colname_rsVendorEmployer = "-1";
if (isset($_GET['employer_id'])) {
  $colname_rsVendorEmployer = (get_magic_quotes_gpc()) ? $_GET['employer_id'] : addslashes($_GET['employer_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsVendorEmployer = sprintf("SELECT * FROM employer_vendor WHERE employer_id = %s", $colname_rsVendorEmployer);
$rsVendorEmployer = mysql_query($query_rsVendorEmployer, $dw_conn) or die(mysql_error());
$row_rsVendorEmployer = mysql_fetch_assoc($rsVendorEmployer);
$totalRows_rsVendorEmployer = mysql_num_rows($rsVendorEmployer);
?>
<?php
$vendor_selected = array();
if($totalRows_rsVendorEmployer>0) {
	do { 
		$vendor_selected[] = $row_rsVendorEmployer['plan_id'];
		$employerVendorList[] = $row_rsVendorEmployer;
	} while ($row_rsVendorEmployer = mysql_fetch_assoc($rsVendorEmployer));
}
if($vendor_selected) {
	$vendor_selected_text = implode(',',$vendor_selected);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Manage Vendors</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Choose Vendor For Employer &quot;<?php echo $row_rsEmployer['name']; ?>&quot;</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<form name="form1" id="form1" method="post" action="">
  <p>Vendor and Its Plans<br />
    <select name="plan_id[]" size="5" multiple="multiple" id="plan_id[]">
      <?php
do {  
?>
      <option value="<?php echo $row_rsVendor['plan_id']?>"<?php if(in_array($row_rsVendor['plan_id'], $vendor_selected)) echo ' selected'; ?>><?php echo $row_rsVendor['plan_name']?> (<?php echo $row_rsVendor['name']; ?>)</option>
	  <?php $vends[$row_rsVendor['vendor_id']]= $row_rsVendor; ?>
      <?php
} while ($row_rsVendor = mysql_fetch_assoc($rsVendor));
  $rows = mysql_num_rows($rsVendor);
  if($rows > 0) {
      mysql_data_seek($rsVendor, 0);
	  $row_rsVendor = mysql_fetch_assoc($rsVendor);
  }
?>
        </select> 
  </p>
  <p>
    <input type="submit" name="Submit" value="Update" />
    <input name="vendor_selected_text" type="hidden" id="vendor_selected_text" value="<?php echo $vendor_selected_text; ?>" />
    <input name="employer_id" type="hidden" id="employer_id" value="<?php echo $row_rsEmployer['employer_id']; ?>" />
    <input name="MM_Insert" type="hidden" id="MM_Insert" value="1" /><input name="menuTopItem" type="hidden" id="menuTopItem" value="3" />
  </p>
</form>
      </td>
    </tr>
</table>
<br />
<?php if($employerVendorList) { ?>
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Update Settings</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
        <form id="form2" name="form2" method="post" action="">
<table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
  <tr>
    <td valign="top" class="thcview2"><strong>Vendor</strong></td>
    <td valign="top" class="thcview2"><strong>Plan</strong></td>
    <td valign="top" class="thcview2"><strong>Active</strong></td>
    </tr>
<?php $vendor_active = array();
foreach($employerVendorList as $key => $value) {  
if($value['active']==1) $vendor_active[] = $value['plan_id'];
?>
  <tr>
    <td valign="top" class="tdcview2"><?php echo $vends[$value['vendor_id']]['name']; ?>&nbsp;</td>
    <td valign="top" class="tdcview2"><?php echo $vends[$value['vendor_id']]['plan_name']; ?>&nbsp;</td>
    <td valign="top" class="tdcview2"><input name="plan_id[]" type="checkbox" id="plan_id_<?php echo $value['plan_id']; ?>" value="<?php echo $value['plan_id']; ?>" <?php if(in_array($value['plan_id'], $vendor_active)) echo "checked"; ?> /></td>
    </tr>
<?php } ?>

</table>
<br />
<?php if($vendor_active) {
	$vendor_active_string = implode(',', $vendor_active);
}
?>
<input type="hidden" name="vendor_active_string" value="<?php echo $vendor_active_string; ?>" />
<input name="MM_Insert" type="hidden" id="MM_Insert" value="2" /><input name="menuTopItem" type="hidden" id="menuTopItem" value="3" />
<input type="submit" value="Update" name="submit" />
        </form>
</td>
    </tr>
</table>
<br />
<?php } ?>
<p>&nbsp;</p>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body><!-- InstanceEnd -->
</html>
<?php
mysql_free_result($rsEmployer);

mysql_free_result($rsVendor);

mysql_free_result($rsVendorEmployer);
?>
