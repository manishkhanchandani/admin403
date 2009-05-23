<?php require_once('../Connections/dw_conn.php'); ?>
<?php
ob_start();
if(!$_GET['date']) {
	echo 'Please choose date to continue.';
	exit;
}
if(!$_GET['file']) {
	echo 'Please choose file to continue.';
	exit;
}
if(!$_GET['employer_id']) {
	$_GET['employer_id'] = 0;
}
?>
<?php
$colname2_rsConfirm = "0";
if (isset($_GET['employer_id'])) {
  $colname2_rsConfirm = (get_magic_quotes_gpc()) ? $_GET['employer_id'] : addslashes($_GET['employer_id']);
}
$colname_rsConfirm = "-1";
if (isset($_GET['date'])) {
  $colname_rsConfirm = (get_magic_quotes_gpc()) ? $_GET['date'] : addslashes($_GET['date']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsConfirm = sprintf("SELECT * FROM employee_contribution_transaction WHERE transaction_date = '%s' AND employee_contribution_transaction.employer_id = %s", $colname_rsConfirm,$colname2_rsConfirm);
$rsConfirm = mysql_query($query_rsConfirm, $dw_conn) or die(mysql_error());
$row_rsConfirm = mysql_fetch_assoc($rsConfirm);
$totalRows_rsConfirm = mysql_num_rows($rsConfirm);
?>
<?php if ($totalRows_rsConfirm > 0) { // Show if recordset not empty ?>
You have already uploaded the data with date <?php echo $_GET['date']; ?>. 
<input type="submit" name="Submit" value="Upload Data With Same Date"> 
<input type="hidden" name="transaction_id" value="<?php echo $row_rsConfirm['transaction_id']; ?>">
<?php } // Show if recordset not empty ?>
<?php if ($totalRows_rsConfirm == 0) { // Show if recordset empty ?>
<?php
ob_end_clean();
echo trim(1);
exit;
?>
<?php } // Show if recordset empty ?>
<?php
mysql_free_result($rsConfirm);
?>