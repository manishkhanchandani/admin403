<?php require_once('../Connections/dw_conn.php'); ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

function getMonthlyContribution($employee_id) {
	$sql = "select sra_pretax, sra_roth from employee_contribution where employee_id = '".$employee_id."' ORDER BY contribution_date DESC LIMIT 1";
	$rs = mysql_query($sql) or die('error');
	$rec = mysql_fetch_array($rs);
	return $rec;
}
?>
<?php
$maxRows_rsEmployees = 15;
$pageNum_rsEmployees = 0;
if (isset($_GET['pageNum_rsEmployees'])) {
  $pageNum_rsEmployees = $_GET['pageNum_rsEmployees'];
}
$startRow_rsEmployees = $pageNum_rsEmployees * $maxRows_rsEmployees;

$colname_rsEmployees = "-1";
if (isset($_COOKIE['employer']['employer_id'])) {
  $colname_rsEmployees = (get_magic_quotes_gpc()) ? $_COOKIE['employer']['employer_id'] : addslashes($_COOKIE['employer']['employer_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployees = sprintf("SELECT e.*, v.name, vp.plan_name FROM employee as e LEFT JOIN employee_vendor as ev ON e.employee_id = ev.employee_id LEFT JOIN vendor as v ON ev.vendor_id = v.vendor_id LEFT JOIN vendor_plan as vp ON ev.plan_id = vp.plan_id WHERE e.employer_id = %s GROUP BY e.ssn", $colname_rsEmployees);
$query_limit_rsEmployees = sprintf("%s LIMIT %d, %d", $query_rsEmployees, $startRow_rsEmployees, $maxRows_rsEmployees);
$rsEmployees = mysql_query($query_limit_rsEmployees, $dw_conn) or die(mysql_error());
$row_rsEmployees = mysql_fetch_assoc($rsEmployees);

if (isset($_GET['totalRows_rsEmployees'])) {
  $totalRows_rsEmployees = $_GET['totalRows_rsEmployees'];
} else {
  $all_rsEmployees = mysql_query($query_rsEmployees);
  $totalRows_rsEmployees = mysql_num_rows($all_rsEmployees);
}
$totalPages_rsEmployees = ceil($totalRows_rsEmployees/$maxRows_rsEmployees)-1;

$queryString_rsEmployees = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsEmployees") == false && 
        stristr($param, "totalRows_rsEmployees") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsEmployees = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsEmployees = sprintf("&totalRows_rsEmployees=%d%s", $totalRows_rsEmployees, $queryString_rsEmployees);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Manage Employees</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Employee List</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
	  	<?php if ($totalRows_rsEmployees > 0) { // Show if recordset not empty ?>
	  	<table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
			<tr>
			  	<td valign="top" class="thcview2"><strong>SSN</strong></td>
				<td valign="top" class="thcview2"><strong>First Name</strong></td>
				<td valign="top" class="thcview2"><strong>Last Name </strong></td>
				<td valign="top" class="thcview2"><strong>Email</strong></td>
				<td colspan="2" valign="top" class="thcview2"><strong>Plan</strong></td>
				<td valign="top" class="thcview2"><strong>Contribution</strong></td>
				<td valign="top" class="thcview2"><strong>Actions</strong></td>
          </tr>
			<tr>
			  <td valign="top" class="thcview2">&nbsp;</td>
			  <td valign="top" class="thcview2">&nbsp;</td>
			  <td valign="top" class="thcview2">&nbsp;</td>
			  <td valign="top" class="thcview2">&nbsp;</td>
			  <td valign="top" class="thcview2"><strong>Vendor</strong></td>
			  <td valign="top" class="thcview2"><strong>Product</strong></td>
			  <td valign="top" class="thcview2">&nbsp;</td>
			  <td valign="top" class="thcview2">&nbsp;</td>
		  </tr>
			  <?php do { ?>
			  <tr>
		  	    <td valign="top" class="tdcview2"><?php echo $encryption->processDecrypt('ssn', $row_rsEmployees['ssn']); ?>&nbsp;</td>
		  	    <td valign="top" class="tdcview2"><?php echo $row_rsEmployees['firstname']; ?>&nbsp;</td>
		  	    <td valign="top" class="tdcview2"><?php echo $row_rsEmployees['lastname']; ?>&nbsp;</td>
		  	    <td valign="top" class="tdcview2"><?php echo $row_rsEmployees['email']; ?>&nbsp;</td>
		  	    <td valign="top" class="tdcview2"><?php echo $row_rsEmployees['name']; ?>&nbsp;</td>
		  	    <td valign="top" class="tdcview2"><?php echo $row_rsEmployees['plan_name']; ?>&nbsp;</td>
		  	    <td valign="top" class="tdcview2"><?php $contribution = getMonthlyContribution($row_rsEmployees['employee_id']); ?>
                    <?php if($contribution) { ?>
                    <strong>SRA Pretax:</strong><br />
                    <?php echo $contribution['sra_pretax']; ?><br />
                    <strong>SRA Roth:</strong><br />
                    <?php echo $contribution['sra_roth']; ?>
                    <?php } else { ?>
                  No Contribution
                  <?php } ?>&nbsp;
                </td>
		  	    <td valign="top" class="tdcview2"><form id="form1" name="form1" method="get" action="manage_employees_actions.php">
                  <select name="action" id="action">
                    <option value="addplan">Add Plan</option>
                    <option value="addcontribution">Add Monthly Contribution</option>
                    <option value="edit">Edit</option>
                    <option value="delete">Delete</option>
                  </select>
                  <input type="hidden" name="employee_id" id="employee_id" value="<?php echo $row_rsEmployees['employee_id']; ?>" />
                  <input type="submit" name="button" id="button" value="Go" />
                  <input name="menuTopItem" type="hidden" id="menuTopItem" value="7" />
                </form></td>
		      </tr>
	  <?php } while ($row_rsEmployees = mysql_fetch_assoc($rsEmployees)); ?>
		</table>
	  	<br />
Records <?php echo ($startRow_rsEmployees + 1) ?> to <?php echo min($startRow_rsEmployees + $maxRows_rsEmployees, $totalRows_rsEmployees) ?> of <?php echo $totalRows_rsEmployees ?> <br />
<br />
        <table border="0" width="50%" align="center">
          <tr>
            <td width="23%" align="center"><?php if ($pageNum_rsEmployees > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_rsEmployees=%d%s", $currentPage, 0, $queryString_rsEmployees); ?>">First</a>
              <?php } // Show if not first page ?>
            </td>
            <td width="31%" align="center"><?php if ($pageNum_rsEmployees > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_rsEmployees=%d%s", $currentPage, max(0, $pageNum_rsEmployees - 1), $queryString_rsEmployees); ?>">Previous</a>
              <?php } // Show if not first page ?>
            </td>
            <td width="23%" align="center"><?php if ($pageNum_rsEmployees < $totalPages_rsEmployees) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_rsEmployees=%d%s", $currentPage, min($totalPages_rsEmployees, $pageNum_rsEmployees + 1), $queryString_rsEmployees); ?>">Next</a>
              <?php } // Show if not last page ?>
            </td>
            <td width="23%" align="center"><?php if ($pageNum_rsEmployees < $totalPages_rsEmployees) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_rsEmployees=%d%s", $currentPage, $totalPages_rsEmployees, $queryString_rsEmployees); ?>">Last</a>
              <?php } // Show if not last page ?>
            </td>
          </tr>
        </table>
        <br />
	  	<br />
	  	<?php } // Show if recordset not empty ?>  
  	  <?php if ($totalRows_rsEmployees == 0) { // Show if recordset empty ?>
      <p>No Employee Found. Add New Employee <a href="manage_employee_add.php?menuTopItem=7">Here</a>. </p>
      <?php } // Show if recordset empty ?></td>
	</tr>
</table>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body><!-- InstanceEnd --></html>
<?php
mysql_free_result($rsEmployees);
?>
