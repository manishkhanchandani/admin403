<?php require_once('../Connections/dw_conn.php'); ?>
<?php
include_once('start.php');

$f = $_GET['f']; if(!$f) $f = '2008-01-01';
$t = $_GET['t']; if(!$t) $t = date('Y-m-d');
if(!$_GET['employee_id']) { echo 'please choose employee id'; exit; }
?>
<?php
if($_POST['MM_Insert']==1) {
	if($_POST['contribution_id']) {
		foreach($_POST['contribution_id'] as $key => $value) {
			$sql = "update employee_contribution set sra_pretax = '".$_POST['sra_pretax'][$key]."', sra_roth = '".$_POST['sra_roth'][$key]."' where contribution_id = '".$key."'";
			mysql_query($sql) or die('error in updating');
		}
	}
}
?>
<?php
$colname_rsContribution = "1";
if (isset($_GET['employee_id'])) {
  $colname_rsContribution = (get_magic_quotes_gpc()) ? $_GET['employee_id'] : addslashes($_GET['employee_id']);
}
$colfrom_rsContribution = "-1";
if (isset($f)) {
  $colfrom_rsContribution = (get_magic_quotes_gpc()) ? $f : addslashes($f);
}
$colto_rsContribution = "-1";
if (isset($t)) {
  $colto_rsContribution = (get_magic_quotes_gpc()) ? $t : addslashes($t);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsContribution = sprintf("SELECT * FROM employee_contribution, employee, vendor_plan WHERE employee_contribution.employee_id = employee.employee_id AND employee_contribution.plan_id = vendor_plan.plan_id AND employee.employee_id = %s AND employee_contribution.contribution_date BETWEEN '%s' AND '%s' ORDER BY employee_contribution.contribution_date DESC", $colname_rsContribution,$colfrom_rsContribution,$colto_rsContribution);
$rsContribution = mysql_query($query_rsContribution, $dw_conn) or die(mysql_error());
$row_rsContribution = mysql_fetch_assoc($rsContribution);
$totalRows_rsContribution = mysql_num_rows($rsContribution);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Edit Contribution: Result</title>
<!-- InstanceEndEditable -->
<?php include('css.php'); ?>
<?php include('js.php'); ?>
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>

<body>
<?php include('head.php'); ?>
<!-- InstanceBeginEditable name="EditRegion3" -->
<?php if ($totalRows_rsContribution > 0) { // Show if recordset not empty ?>
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">View Contribution Data</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<form action="" method="post" name="form1">
<table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
      <tr>
        <td class="thcview2"><strong>SSN</strong></td>
        <td class="thcview2"><strong>Account Number </strong></td>
        <td class="thcview2"><strong>Plan</strong></td>
        <td class="thcview2"><strong>Contribution Date</strong></td>
        <td class="thcview2"><strong>SRA Pretax</strong></td>
        <td class="thcview2"><strong>SRA Roth</strong></td>
        </tr>
      <?php do { ?>
      <tr>
        <td class="tdcview2"><?php echo $encryption->processDecrypt('ssn', $row_rsContribution['ssn']); ?></td>
        <td class="tdcview2"><?php echo $encryption->processDecrypt('account_number', $row_rsContribution['account_number']); ?>&nbsp;</td>
        <td class="tdcview2"><?php echo $row_rsContribution['plan_name']; ?></td>
        <td class="tdcview2"><?php echo $row_rsContribution['contribution_date']; ?></td>
        <td class="tdcview2"><input type="text" name="sra_pretax[<?php echo $row_rsContribution['contribution_id']; ?>]" value="<?php echo $row_rsContribution['sra_pretax']; ?>" /></td>
        <td class="tdcview2"><input type="text" name="sra_roth[<?php echo $row_rsContribution['contribution_id']; ?>]" value="<?php echo $row_rsContribution['sra_roth']; ?>" /><input type="hidden" name="contribution_id[<?php echo $row_rsContribution['contribution_id']; ?>]" value="<?php echo $row_rsContribution['contribution_id']; ?>" /></td>
        </tr>
      <?php } while ($row_rsContribution = mysql_fetch_assoc($rsContribution)); ?>
    </table>
<div align="center"><br />
	  <input type="submit" name="Submit" value="Update" />
      <input name="MM_Insert" type="hidden" id="MM_Insert" value="1" />
</div>
</form>
      </td>
    </tr>
</table>
<br />
<?php } // Show if recordset not empty ?>
<?php if ($totalRows_rsContribution == 0) { // Show if recordset empty ?>
<p>No Data Found.</p>
<?php } // Show if recordset empty ?>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsContribution);
?>
