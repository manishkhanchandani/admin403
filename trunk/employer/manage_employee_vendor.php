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

if($_POST['MM_Insert']==1 && $_GET['employee_id']) {
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
			$sql = "delete from employee_vendor where employee_id = '".$_GET['employee_id']."' and plan_id in (".$resultDeletedText.")";
			$rs = mysql_query($sql) or die('error');
		}
		$resultInserted = array_diff($plan_selected, $vendor_selected_array);
		if($resultInserted) {
			$sql = "insert into employee_vendor (employee_id, plan_id, vendor_id) values ";
			foreach($resultInserted as $value) {
				if($value!="") {
					$sql2[] = "('".$_GET['employee_id']."', '".$value."', '".$return[$value]."')";
				}
			}
			$sql3 = implode(', ', $sql2);
			$sql .= $sql3;
			$rs = mysql_query($sql) or die('error');
		}
	} else {			
		$sql = "insert into employee_vendor (employee_id, plan_id, vendor_id) values ";
		foreach($_POST['plan_id'] as $key => $value) {
			if($value!="") {
				$sql2[] = "('".$_GET['employee_id']."', '".$value."', '".$return[$value]."')";
			}
		}
		$sql3 = implode(', ', $sql2);
		$sql .= $sql3;
		$rs = mysql_query($sql) or die('error');
	}
	header("Location: manage_employees.php?".$_SERVER['QUERY_STRING']);
	exit;
}
?>
<?php
$colname_rsEmployee = "-1";
if (isset($_GET['employee_id'])) {
  $colname_rsEmployee = $_GET['employee_id'];
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployee = sprintf("SELECT employee_id, email, firstname, ssn, employer_id FROM employee WHERE employee_id = %s", GetSQLValueString($colname_rsEmployee, "int"));
$rsEmployee = mysql_query($query_rsEmployee, $dw_conn) or die(mysql_error());
$row_rsEmployee = mysql_fetch_assoc($rsEmployee);
$totalRows_rsEmployee = mysql_num_rows($rsEmployee);

$colname_rsEmployer = "-1";
if (isset($row_rsEmployee['employer_id'])) {
  $colname_rsEmployer = $row_rsEmployee['employer_id'];
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployer = sprintf("SELECT employer_id, name, email FROM employer WHERE employer_id = %s", GetSQLValueString($colname_rsEmployer, "int"));
$rsEmployer = mysql_query($query_rsEmployer, $dw_conn) or die(mysql_error());
$row_rsEmployer = mysql_fetch_assoc($rsEmployer);
$totalRows_rsEmployer = mysql_num_rows($rsEmployer);

$colname_rsVendor = "2";
if (isset($row_rsEmployee['employer_id'])) {
  $colname_rsVendor = $row_rsEmployee['employer_id'];
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsVendor = sprintf("SELECT * FROM vendor, vendor_plan, employer_vendor WHERE vendor.vendor_id = vendor_plan.vendor_id AND vendor_plan.plan_id AND employer_vendor.plan_id = vendor_plan.plan_id AND employer_vendor.employer_id = %s ORDER BY vendor.name, vendor_plan.plan_name", GetSQLValueString($colname_rsVendor, "int"));
$rsVendor = mysql_query($query_rsVendor, $dw_conn) or die(mysql_error());
$row_rsVendor = mysql_fetch_assoc($rsVendor);
$totalRows_rsVendor = mysql_num_rows($rsVendor);

$colname_rsVendorEmployee = "-1";
if (isset($_GET['employee_id'])) {
  $colname_rsVendorEmployee = $_GET['employee_id'];
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsVendorEmployee = sprintf("SELECT * FROM employee_vendor WHERE employee_id = %s", GetSQLValueString($colname_rsVendorEmployee, "int"));
$rsVendorEmployee = mysql_query($query_rsVendorEmployee, $dw_conn) or die(mysql_error());
$row_rsVendorEmployee = mysql_fetch_assoc($rsVendorEmployee);
$totalRows_rsVendorEmployee = mysql_num_rows($rsVendorEmployee);
?>
<?php
$vendor_selected = array();
if($totalRows_rsVendorEmployee>0) {
	do { 
		$vendor_selected[] = $row_rsVendorEmployee['plan_id'];
		$employerVendorList[] = $row_rsVendorEmployee;
	} while ($row_rsVendorEmployee = mysql_fetch_assoc($rsVendorEmployee));
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
<title>Add Vendor</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Choose Vendor For Employee &quot;<?php echo $row_rsEmployee['firstname']; ?>&quot; and Employer &quot;<?php echo $row_rsEmployer['employer_id']; ?>&quot;</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<form name="form1" id="form1" method="post" action="">
  <p>Vendor and Its <?php echo DISPLAYPLANNAME;?><br />
    <select name="plan_id[]" size="5" id="plan_id[]">
      <?php
do {  
?>
      <option value="<?php echo $row_rsVendor['plan_id']?>"<?php if(in_array($row_rsVendor['plan_id'], $vendor_selected)) echo ' selected'; ?>><?php echo $row_rsVendor['plan_name']?> (<?php echo $row_rsVendor['name']; ?>)</option>
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
    <input name="employee_id" type="hidden" id="employee_id" value="<?php echo $row_rsEmployee['employee_id']; ?>" />
    <input name="menuTopItem" type="hidden" id="menuTopItem" value="7" />
    <input name="MM_Insert" type="hidden" id="MM_Insert" value="1" />
  </p>
</form>
      </td>
    </tr>
</table>
<br />

<p>&nbsp;</p>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body><!-- InstanceEnd -->
</html>
<?php
mysql_free_result($rsEmployee);

mysql_free_result($rsEmployer);

mysql_free_result($rsVendor);

mysql_free_result($rsVendorEmployee);
?>
