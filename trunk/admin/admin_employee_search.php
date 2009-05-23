<?php require_once('../Connections/dw_conn.php'); ?>
<?php
include_once('start.php');
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
function getMonthlyContribution($employee_id) {
	$sql = "select sra_pretax, sra_roth from employee_contribution where employee_id = '".$employee_id."' ORDER BY contribution_date DESC LIMIT 1";
	$rs = mysql_query($sql) or die('error');
	$rec = mysql_fetch_array($rs);
	return $rec;
}
$_GET['menuTopItem'] = 2;
if(!eregi("menuTopItem", $_SERVER['QUERY_STRING'])) {
	$_SERVER['QUERY_STRING'] .= "&menuTopItem=2";
}
?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsEmployee = 10;
$pageNum_rsEmployee = 0;
if (isset($_GET['pageNum_rsEmployee'])) {
  $pageNum_rsEmployee = $_GET['pageNum_rsEmployee'];
}
$startRow_rsEmployee = $pageNum_rsEmployee * $maxRows_rsEmployee;

$colname_rsEmployee = "%";
if (isset($_GET['kw'])) {
  $colname_rsEmployee = $_GET['kw'];
}
$colemp_rsEmployee = "%";
if (isset($_GET['employer_id'])) {
  $colemp_rsEmployee = $_GET['employer_id'];
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployee = sprintf("SELECT employee.*, employer.name as 'Employer Name', employer.email as 'Employer Email', vp.plan_name, vendor.name as 'Vendor Name' FROM employee INNER JOIN employer ON employee.employer_id = employer.employer_id LEFT JOIN employee_vendor as ev ON ev.employee_id = employee.employee_id LEFT JOIN vendor_plan as vp ON vp.plan_id = ev.plan_id LEFT JOIN vendor ON vendor.vendor_id = vp.vendor_id WHERE (employee.firstname LIKE %s OR employee.ssn LIKE %s OR employee.middlename LIKE %s OR employee.lastname LIKE %s OR employee.email LIKE %s) AND employee.employer_id LIKE %s ORDER BY vendor.name, employer.name, employee.employee_id", GetSQLValueString("%" . $colname_rsEmployee . "%", "text"),GetSQLValueString("%" . $encryption->processEncrypt('ssn', $colname_rsEmployee) . "%", "text"),GetSQLValueString("%" . $colname_rsEmployee . "%", "text"),GetSQLValueString("%" . $colname_rsEmployee . "%", "text"),GetSQLValueString("%" . $colname_rsEmployee . "%", "text"),GetSQLValueString($colemp_rsEmployee, "text"));
$query_limit_rsEmployee = sprintf("%s LIMIT %d, %d", $query_rsEmployee, $startRow_rsEmployee, $maxRows_rsEmployee);
$rsEmployee = mysql_query($query_limit_rsEmployee, $dw_conn) or die(mysql_error());
$row_rsEmployee = mysql_fetch_assoc($rsEmployee);

if (isset($_GET['totalRows_rsEmployee'])) {
  $totalRows_rsEmployee = $_GET['totalRows_rsEmployee'];
} else {
  $all_rsEmployee = mysql_query($query_rsEmployee);
  $totalRows_rsEmployee = mysql_num_rows($all_rsEmployee);
}
$totalPages_rsEmployee = ceil($totalRows_rsEmployee/$maxRows_rsEmployee)-1;

mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployer = "SELECT employer_id, name FROM employer ORDER BY name ASC";
$rsEmployer = mysql_query($query_rsEmployer, $dw_conn) or die(mysql_error());
$row_rsEmployer = mysql_fetch_assoc($rsEmployer);
$totalRows_rsEmployer = mysql_num_rows($rsEmployer);

$queryString_rsEmployee = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsEmployee") == false && 
        stristr($param, "totalRows_rsEmployee") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsEmployee = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsEmployee = sprintf("&totalRows_rsEmployee=%d%s", $totalRows_rsEmployee, $queryString_rsEmployee);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Search Employee</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Search Employee</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<form id="form1" name="form1" method="get" action="">
  <input name="kw" type="text" id="kw" value="<?php echo $_GET['kw']; ?>" size="32" />
  Employer:
  <select name="employer_id" id="employer_id">
    <option value="%" <?php if (!(strcmp("%", $_GET['employer_id']))) {echo "selected=\"selected\"";} ?>>All</option>
    <?php
do {  
?>
    <option value="<?php echo $row_rsEmployer['employer_id']?>"<?php if (!(strcmp($row_rsEmployer['employer_id'], $_GET['employer_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsEmployer['name']?></option>
    <?php
} while ($row_rsEmployer = mysql_fetch_assoc($rsEmployer));
  $rows = mysql_num_rows($rsEmployer);
  if($rows > 0) {
      mysql_data_seek($rsEmployer, 0);
	  $row_rsEmployer = mysql_fetch_assoc($rsEmployer);
  }
?>
  </select>
  <input type="submit" name="button" id="button" value="Go" /><input name="menuTopItem" type="hidden" id="menuTopItem" value="2" />
  <br />
  <br />
Note: Search with employee SSN, Name or Email.
<br />
<br />
</form>
      </td>
    </tr>
</table>
<br />

<?php if ($totalRows_rsEmployee > 0) { // Show if recordset not empty ?>
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Employee List</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
    <tr>
      <td valign="top" class="thcview2"><strong>SSN</strong></td>
      <td valign="top" class="thcview2"><strong>Name</strong></td>
      <td valign="top" class="thcview2"><strong>Employer Details</strong></td>
      <td valign="top" class="thcview2"><strong>Vendor</strong></td>
      <td valign="top" class="thcview2"><strong><?php echo DISPLAYPLANNAME;?></strong></td>
      <td colspan="2" valign="top" class="thcview2" align="center"><strong>Contribution</strong></td>
      <td valign="top" class="thcview2"><strong>Actions</strong></td>
    </tr>
    <tr>
      <td valign="top" class="thcview2">&nbsp;</td>
      <td valign="top" class="thcview2">&nbsp;</td>
      <td valign="top" class="thcview2">&nbsp;</td>
      <td valign="top" class="thcview2">&nbsp;</td>
      <td valign="top" class="thcview2">&nbsp;</td>
      <td valign="top" class="thcview2"><strong>SRA Pretax</strong></td>
      <td valign="top" class="thcview2"><strong>SRA Roth</strong></td>
      <td valign="top" class="thcview2">&nbsp;</td>
    </tr>
    <?php do { ?>
      <tr>
        <td valign="top" class="tdcview2"><?php echo $encryption->processDecrypt('ssn', $row_rsEmployee['ssn']); ?>&nbsp;</td>
        <td valign="top" class="tdcview2"><?php echo $row_rsEmployee['firstname']; ?> <?php echo $row_rsEmployee['lastname']; ?>&nbsp;</td>
        <td valign="top" class="tdcview2">
            <?php echo $row_rsEmployee['Employer Name']; ?>&nbsp;</td>
        <td valign="top" class="tdcview2"><?php echo $row_rsEmployee['Vendor Name']; ?>&nbsp;</td>
        <td valign="top" class="tdcview2"><?php echo $row_rsEmployee['plan_name']; ?>
            <?php
		/*
				$query = "select plan_name from employee_vendor as ev, vendor_plan as vp where ev.employee_id = '".$row_rsEmployee['employee_id']."' and ev.plan_id = vp.plan_id";
				$rs = mysql_query($query) or die('error in select');
				$rec = mysql_fetch_array($rs);
				echo $rec['plan_name'];*/
			?>
          &nbsp;</td>
        <td valign="top" class="tdcview2"><?php $contribution = getMonthlyContribution($row_rsEmployee['employee_id']); ?>
            <?php if($contribution) { ?>
            <?php echo $contribution['sra_pretax']; ?>
            <?php } else { ?>
  No Contribution
  <?php } ?>
        </td>
        <td valign="top" class="tdcview2"><?php if($contribution) { ?>
            <?php echo $contribution['sra_roth']; ?>
            <?php } else { ?>
&nbsp;
  <?php } ?>&nbsp;
        </td>
        <td valign="top" class="tdcview2"><form id="form1" name="form1" method="get" action="admin_employee_list_actions.php">
            <select name="action" id="action">
              <option value="addplan">Add/Edit Plan</option>
              <option value="addcontribution">Add Monthly Contribution</option>
              <option value="editcontribution">Edit Contribution</option>
              <option value="edit">Edit</option>
              <option value="delete">Delete</option>
            </select>
            <input type="hidden" name="employee_id" id="employee_id" value="<?php echo $row_rsEmployee['employee_id']; ?>" />
            <input type="submit" name="button" id="button" value="Go" /><input name="menuTopItem" type="hidden" id="menuTopItem" value="2" />
        </form></td>
      </tr>
      <?php } while ($row_rsEmployee = mysql_fetch_assoc($rsEmployee)); ?>
  </table>
  <p> Records <?php echo ($startRow_rsEmployee + 1) ?> to <?php echo min($startRow_rsEmployee + $maxRows_rsEmployee, $totalRows_rsEmployee) ?> of <?php echo $totalRows_rsEmployee ?> </p>
  <table border="0">
    <tr>
      <td><?php if ($pageNum_rsEmployee > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_rsEmployee=%d%s", $currentPage, 0, $queryString_rsEmployee); ?>">First</a>
          <?php } // Show if not first page ?>      </td>
      <td><?php if ($pageNum_rsEmployee > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_rsEmployee=%d%s", $currentPage, max(0, $pageNum_rsEmployee - 1), $queryString_rsEmployee); ?>">Previous</a>
          <?php } // Show if not first page ?>      </td>
      <td><?php if ($pageNum_rsEmployee < $totalPages_rsEmployee) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_rsEmployee=%d%s", $currentPage, min($totalPages_rsEmployee, $pageNum_rsEmployee + 1), $queryString_rsEmployee); ?>">Next</a>
          <?php } // Show if not last page ?>      </td>
      <td><?php if ($pageNum_rsEmployee < $totalPages_rsEmployee) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_rsEmployee=%d%s", $currentPage, $totalPages_rsEmployee, $queryString_rsEmployee); ?>">Last</a>
          <?php } // Show if not last page ?>      </td>
    </tr>
  </table>
      </td>
    </tr>
</table>
<br />
<?php } // Show if recordset not empty ?>

<?php if ($totalRows_rsEmployee == 0) { // Show if recordset empty ?>
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Employee List</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">No Employee Found.</td>
    </tr>
</table>
<br />
<?php } // Show if recordset empty ?>
<p>&nbsp;</p>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsEmployee);

mysql_free_result($rsEmployer);
?>