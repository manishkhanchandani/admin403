<?php require_once('../Connections/dw_conn.php'); ?>
<?php
$colname_rsfailed = "-1";
if (isset($date)) {
  $colname_rsfailed = (get_magic_quotes_gpc()) ? $date : addslashes($date);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsfailed = sprintf("SELECT COUNT(employee_id) as failed, cdate FROM employee_contribution_details WHERE (employee_id = 0 or employee_id is null or employee_id = '') AND cdate = '%s' GROUP BY cdate", $colname_rsfailed);
$rsfailed = mysql_query($query_rsfailed, $dw_conn) or die(mysql_error());
$row_rsfailed = mysql_fetch_assoc($rsfailed);
$totalRows_rsfailed = mysql_num_rows($rsfailed);

$colname_rsSuccess = "-1";
if (isset($date)) {
  $colname_rsSuccess = (get_magic_quotes_gpc()) ? $date : addslashes($date);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsSuccess = sprintf("SELECT COUNT(employee_id) as success, cdate FROM employee_contribution_details WHERE employee_id > 0 AND cdate = '%s' GROUP BY cdate", $colname_rsSuccess);
$rsSuccess = mysql_query($query_rsSuccess, $dw_conn) or die(mysql_error());
$row_rsSuccess = mysql_fetch_assoc($rsSuccess);
$totalRows_rsSuccess = mysql_num_rows($rsSuccess);

$colname_rsTotal = "-1";
if (isset($date)) {
  $colname_rsTotal = (get_magic_quotes_gpc()) ? $date : addslashes($date);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsTotal = sprintf("SELECT SUM(pretax) as totalPretax, SUM(roth) as totalRoth, SUM(pretax_refund) as totalPretaxRefund, SUM(roth_refund) as toatlRothRefund, cdate FROM employee_contribution_details WHERE employee_id > 0 AND cdate = '%s' GROUP BY cdate", $colname_rsTotal);
$rsTotal = mysql_query($query_rsTotal, $dw_conn) or die(mysql_error());
$row_rsTotal = mysql_fetch_assoc($rsTotal);
$totalRows_rsTotal = mysql_num_rows($rsTotal);

$colname_rsGrandTotal = "-1";
if (isset($date)) {
  $colname_rsGrandTotal = (get_magic_quotes_gpc()) ? $date : addslashes($date);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsGrandTotal = sprintf("SELECT SUM(pretax) as grandtotalPretax, SUM(roth) as grandtotalRoth, SUM(pretax_refund) as grandtotalPretaxRefund, SUM(roth_refund) as grandtoatlRothRefund, cdate FROM employee_contribution_details WHERE cdate = '%s' GROUP BY cdate", $colname_rsGrandTotal);
$rsGrandTotal = mysql_query($query_rsGrandTotal, $dw_conn) or die(mysql_error());
$row_rsGrandTotal = mysql_fetch_assoc($rsGrandTotal);
$totalRows_rsGrandTotal = mysql_num_rows($rsGrandTotal);

$colname_rsDuplicate = "-1";
if (isset($date)) {
  $colname_rsDuplicate = (get_magic_quotes_gpc()) ? $date : addslashes($date);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsDuplicate = sprintf("SELECT COUNT(duplicate_record) as duplicate, cdate FROM employee_contribution_details WHERE employee_id > 0 AND cdate = '%s' GROUP BY cdate", $colname_rsDuplicate);
$rsDuplicate = mysql_query($query_rsDuplicate, $dw_conn) or die(mysql_error());
$row_rsDuplicate = mysql_fetch_assoc($rsDuplicate);
$totalRows_rsDuplicate = mysql_num_rows($rsDuplicate);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<p>Failed: <?php echo $row_rsfailed['failed']; ?>
</p>
<p>Success: <?php echo $row_rsSuccess['success']; ?></p>
<p>Total: <?php echo $row_rsfailed['failed']+$row_rsSuccess['success']; ?></p>
<p>Amount:</p>
<p>Pretax:<?php echo $row_rsTotal['totalPretax']; ?></p>
<p>Roth:<?php echo $row_rsTotal['totalRoth']; ?></p>
<p>Refund:<?php echo $row_rsTotal['totalPretaxRefund']; ?></p>
<p>Roth Refund: <?php echo $row_rsTotal['toatlRothRefund']; ?></p>
<p>&nbsp;</p>
<p>Grand Total: </p>
<p>pretax<?php echo $row_rsGrandTotal['grandtotalPretax']; ?></p>
<p>roth<?php echo $row_rsGrandTotal['grandtotalRoth']; ?></p>
<p>pretax refund<?php echo $row_rsGrandTotal['grandtotalPretaxRefund']; ?></p>
<p>roth refund <?php echo $row_rsGrandTotal['grandtoatlRothRefund']; ?></p>
<p>&nbsp;</p>
<p>duplicate <?php echo $row_rsDuplicate['duplicate']; ?></p>
</body>
</html>
<?php
mysql_free_result($rsfailed);

mysql_free_result($rsSuccess);

mysql_free_result($rsTotal);

mysql_free_result($rsGrandTotal);

mysql_free_result($rsDuplicate);
?>
