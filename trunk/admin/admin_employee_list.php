<?php require_once('../Connections/dw_conn.php'); ?>
<?php
include_once('start.php');
$currentPage = $_SERVER["PHP_SELF"];

function getMonthlyContribution($employee_id) {
	$sql = "select sra_pretax, sra_roth from employee_contribution where employee_id = '".$employee_id."' ORDER BY contribution_date DESC LIMIT 1";
	$rs = mysql_query($sql) or die('error');
	$rec = mysql_fetch_array($rs);
	return $rec;
}
?>
<?php
$maxRows_rsEmployee = 10;
$pageNum_rsEmployee = 0;
if (isset($_GET['pageNum_rsEmployee'])) {
  $pageNum_rsEmployee = $_GET['pageNum_rsEmployee'];
}
$startRow_rsEmployee = $pageNum_rsEmployee * $maxRows_rsEmployee;

mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployee = "SELECT employee.*, employer.name as 'Employer Name', employer.email as 'Employer Email', vp.plan_name, vendor.name as 'Vendor Name' FROM employee INNER JOIN employer ON employee.employer_id = employer.employer_id LEFT JOIN employee_vendor as ev ON ev.employee_id = employee.employee_id LEFT JOIN vendor_plan as vp ON vp.plan_id = ev.plan_id LEFT JOIN vendor ON vendor.vendor_id = vp.vendor_id GROUP BY employee.ssn ORDER BY vendor.name, employer.name, employee.employee_id";
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Employee List</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Employee List</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<?php if ($totalRows_rsEmployee > 0) { // Show if recordset not empty ?>
  <table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
    <tr>
      <td valign="top" class="thcview2"><strong>SSN</strong></td>
      <td valign="top" class="thcview2"><strong>Employee</strong></td>
      <td valign="top" class="thcview2"><strong>Employer</strong></td>
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
        <td valign="top" class="tdcview2"><?php echo $row_rsEmployee['plan_name']; ?><?php
		/*
				$query = "select plan_name from employee_vendor as ev, vendor_plan as vp where ev.employee_id = '".$row_rsEmployee['employee_id']."' and ev.plan_id = vp.plan_id";
				$rs = mysql_query($query) or die('error in select');
				$rec = mysql_fetch_array($rs);
				echo $rec['plan_name'];*/
			?>&nbsp;</td>
        <td valign="top" class="tdcview2">
			<?php $contribution = getMonthlyContribution($row_rsEmployee['employee_id']); ?>
            <?php if($contribution) { ?>
            <?php echo $contribution['sra_pretax']; ?>
            <?php } else { ?>
            No Contribution
            <?php } ?> &nbsp; </td>
        <td valign="top" class="tdcview2">
			<?php if($contribution) { ?>
            <?php echo $contribution['sra_roth']; ?>
            <?php } else { ?>
            &nbsp;
            <?php } ?> &nbsp;</td>
        <td valign="top" class="tdcview2"><form id="form1" name="form1" method="get" action="admin_employee_list_actions.php">
          <select name="action" id="action">
            <option value="addplan">Add/Edit Plan</option>
            <option value="addcontribution">Add Monthly Contribution</option>
            <option value="editcontribution">Edit Contribution</option>
            <option value="edit">Edit Profile</option>
            <option value="delete">Delete</option>
          </select>
          <input type="hidden" name="employee_id" id="employee_id" value="<?php echo $row_rsEmployee['employee_id']; ?>" />
                        <input type="submit" name="button" id="button" value="Go" /><input name="menuTopItem" type="hidden" id="menuTopItem" value="2" />
        </form>        </td>
      </tr>
      <?php } while ($row_rsEmployee = mysql_fetch_assoc($rsEmployee)); ?>
  </table>
  
  

Records <?php echo ($startRow_rsEmployee + 1) ?> to <?php echo min($startRow_rsEmployee + $maxRows_rsEmployee, $totalRows_rsEmployee) ?> of <?php echo $totalRows_rsEmployee ?><table border="0" width="50%" align="center">
    <tr>
      <td width="23%" align="center"><?php if ($pageNum_rsEmployee > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsEmployee=%d%s", $currentPage, 0, $queryString_rsEmployee); ?>">First</a>
        <?php } // Show if not first page ?>
      </td>
      <td width="31%" align="center"><?php if ($pageNum_rsEmployee > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsEmployee=%d%s", $currentPage, max(0, $pageNum_rsEmployee - 1), $queryString_rsEmployee); ?>">Previous</a>
        <?php } // Show if not first page ?>
      </td>
      <td width="23%" align="center"><?php if ($pageNum_rsEmployee < $totalPages_rsEmployee) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsEmployee=%d%s", $currentPage, min($totalPages_rsEmployee, $pageNum_rsEmployee + 1), $queryString_rsEmployee); ?>">Next</a>
        <?php } // Show if not last page ?>
      </td>
      <td width="23%" align="center"><?php if ($pageNum_rsEmployee < $totalPages_rsEmployee) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsEmployee=%d%s", $currentPage, $totalPages_rsEmployee, $queryString_rsEmployee); ?>">Last</a>
        <?php } // Show if not last page ?>
      </td>
    </tr>
  </table>
  <br />
  <?php } // Show if recordset not empty ?>
<?php if ($totalRows_rsEmployee == 0) { // Show if recordset empty ?>
No List Found.
<?php } // Show if recordset empty ?>
      </td>
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
mysql_free_result($rsEmployee);
?>
