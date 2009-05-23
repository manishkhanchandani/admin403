<?php require_once('../Connections/dw_conn.php'); ?>
<?php
include_once('start.php');

if($_POST['MM_Insert']==1) {
	$sql = "update users set acting_as = 'Employer' where user_id = '".$_POST['user_id']."'";
	mysql_query($sql) or die('error in mysql');
}
if($_GET['remove']==1 && $_POST['MM_Insert']!=1) {
	$sql = "update users set acting_as = NULL where user_id = '".$_GET['user_id']."'";
	mysql_query($sql) or die('error in mysql');
}
?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

$colname_rsEmployees = "-1";
if (isset($_COOKIE['employer']['employer_id'])) {
  $colname_rsEmployees = (get_magic_quotes_gpc()) ? $_COOKIE['employer']['employer_id'] : addslashes($_COOKIE['employer']['employer_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployees = sprintf("SELECT employee.email, concat_ws(' ', employee.firstname, employee.middlename, employee.lastname) as name, employee.ssn, employee.employee_id, users.user_id FROM employee, users WHERE employee.employee_id = users.id AND users.login_type = 'Employee' AND employee.employer_id = %s AND (users.acting_as IS NULL OR users.acting_as = '' OR users.acting_as = 'Employee')", $colname_rsEmployees);
$rsEmployees = mysql_query($query_rsEmployees, $dw_conn) or die(mysql_error());
$row_rsEmployees = mysql_fetch_assoc($rsEmployees);
$totalRows_rsEmployees = mysql_num_rows($rsEmployees);

$maxRows_rsView = 100;
$pageNum_rsView = 0;
if (isset($_GET['pageNum_rsView'])) {
  $pageNum_rsView = $_GET['pageNum_rsView'];
}
$startRow_rsView = $pageNum_rsView * $maxRows_rsView;

$colname_rsView = "-1";
if (isset($_COOKIE['employer']['employer_id'])) {
  $colname_rsView = (get_magic_quotes_gpc()) ? $_COOKIE['employer']['employer_id'] : addslashes($_COOKIE['employer']['employer_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsView = sprintf("SELECT employee.email, concat_ws(' ', employee.firstname, employee.middlename, employee.lastname) as name, employee.ssn, employee.employee_id, users.user_id FROM employee, users WHERE employee.employee_id = users.id AND users.login_type = 'Employee' AND employee.employer_id = %s AND users.acting_as = 'Employer'", $colname_rsView);
$query_limit_rsView = sprintf("%s LIMIT %d, %d", $query_rsView, $startRow_rsView, $maxRows_rsView);
$rsView = mysql_query($query_limit_rsView, $dw_conn) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);

if (isset($_GET['totalRows_rsView'])) {
  $totalRows_rsView = $_GET['totalRows_rsView'];
} else {
  $all_rsView = mysql_query($query_rsView);
  $totalRows_rsView = mysql_num_rows($all_rsView);
}
$totalPages_rsView = ceil($totalRows_rsView/$maxRows_rsView)-1;

$queryString_rsView = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsView") == false && 
        stristr($param, "totalRows_rsView") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsView = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsView = sprintf("&totalRows_rsView=%d%s", $totalRows_rsView, $queryString_rsView);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Authorizations</title>
<!-- InstanceEndEditable -->
<?php include('css.php'); ?>
<?php include('js.php'); ?>
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>

<body>
<?php include('head.php'); ?>
<!-- InstanceBeginEditable name="EditRegion3" -->
<?php if ($totalRows_rsEmployees > 0) { // Show if recordset not empty ?>
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
  <tr valign="bottom" >
    <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Add Authorization</font></td>
  </tr>
  <tr valign="top" >
    <td colspan="2" class="blacktd"><form action="" name="form1" method="post">
        <table border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
          <tr valign="baseline">
            <td nowrap="nowrap" align="right" class="thc2">Employee List: </td>
            <td class="tdc2"><select name="user_id" id="user_id">
                <?php
do {  
?>
                <option value="<?php echo $row_rsEmployees['user_id']?>"><?php echo $row_rsEmployees['name']?> / <?php echo $encryption->processDecrypt('ssn', $row_rsEmployees['ssn']); ?></option>
                <?php
} while ($row_rsEmployees = mysql_fetch_assoc($rsEmployees));
  $rows = mysql_num_rows($rsEmployees);
  if($rows > 0) {
      mysql_data_seek($rsEmployees, 0);
	  $row_rsEmployees = mysql_fetch_assoc($rsEmployees);
  }
?>
            </select></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right" class="thc2">&nbsp;</td>
            <td class="tdc2"><input type="submit" name="Submit" value="Add Employee As Employer" />
            <input name="MM_Insert" type="hidden" id="MM_Insert" value="1" /></td>
          </tr>
        </table>
    </form></td>
  </tr>
</table>
<?php } // Show if recordset not empty ?>
<?php if ($totalRows_rsView > 0) { // Show if recordset not empty ?>
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
  <tr valign="bottom" >
    <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">View Authorizations</font></td>
  </tr>
  <tr valign="top" >
    <td colspan="2" class="blacktd"><p>Records <?php echo ($startRow_rsView + 1) ?> to <?php echo min($startRow_rsView + $maxRows_rsView, $totalRows_rsView) ?> of <?php echo $totalRows_rsView ?></p>
        <table border="0" width="50%" align="center">
          <tr>
            <td width="23%" align="center"><?php if ($pageNum_rsView > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_rsView=%d%s", $currentPage, 0, $queryString_rsView); ?>">First</a>
                <?php } // Show if not first page ?>
            </td>
            <td width="31%" align="center"><?php if ($pageNum_rsView > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_rsView=%d%s", $currentPage, max(0, $pageNum_rsView - 1), $queryString_rsView); ?>">Previous</a>
                <?php } // Show if not first page ?>
            </td>
            <td width="23%" align="center"><?php if ($pageNum_rsView < $totalPages_rsView) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_rsView=%d%s", $currentPage, min($totalPages_rsView, $pageNum_rsView + 1), $queryString_rsView); ?>">Next</a>
                <?php } // Show if not last page ?>
            </td>
            <td width="23%" align="center"><?php if ($pageNum_rsView < $totalPages_rsView) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_rsView=%d%s", $currentPage, $totalPages_rsView, $queryString_rsView); ?>">Last</a>
                <?php } // Show if not last page ?>
            </td>
          </tr>
        </table>
        <br />
        <table border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
          <tr>
            <td valign="top" class="thcview2"><strong>Employee</strong></td>
            <td valign="top" class="thcview2"><strong>Email</strong></td>
            <td valign="top" class="thcview2"><strong>Actions</strong></td>
          </tr>
          <?php do { ?>
          <tr>
            <td valign="top" class="tdcview2"><?php echo $row_rsView['name']; ?></td>
            <td valign="top" class="tdcview2"><?php echo $row_rsView['email']; ?></td>
            <td valign="top" class="tdcview2"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?menuTopItem=3&remove=1&user_id=<?php echo $row_rsView['user_id']; ?>">Remove From List </a></td>
          </tr>
          <?php } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
        </table>
        <p> Records <?php echo ($startRow_rsView + 1) ?> to <?php echo min($startRow_rsView + $maxRows_rsView, $totalRows_rsView) ?> of <?php echo $totalRows_rsView ?>
        <table border="0" width="50%" align="center">
          <tr>
            <td width="23%" align="center"><?php if ($pageNum_rsView > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_rsView=%d%s", $currentPage, 0, $queryString_rsView); ?>">First</a>
                <?php } // Show if not first page ?>
            </td>
            <td width="31%" align="center"><?php if ($pageNum_rsView > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_rsView=%d%s", $currentPage, max(0, $pageNum_rsView - 1), $queryString_rsView); ?>">Previous</a>
                <?php } // Show if not first page ?>
            </td>
            <td width="23%" align="center"><?php if ($pageNum_rsView < $totalPages_rsView) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_rsView=%d%s", $currentPage, min($totalPages_rsView, $pageNum_rsView + 1), $queryString_rsView); ?>">Next</a>
                <?php } // Show if not last page ?>
            </td>
            <td width="23%" align="center"><?php if ($pageNum_rsView < $totalPages_rsView) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_rsView=%d%s", $currentPage, $totalPages_rsView, $queryString_rsView); ?>">Last</a>
                <?php } // Show if not last page ?>
            </td>
          </tr>
        </table>
        </p></td>
  </tr>
</table>
<?php } // Show if recordset not empty ?>
<p>&nbsp;</p>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body><!-- InstanceEnd --></html>
<?php
mysql_free_result($rsEmployees);

mysql_free_result($rsView);
?>
