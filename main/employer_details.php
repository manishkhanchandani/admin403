<?php require_once('../Connections/dw_conn.php'); ?>
<?php
include('start.php');
?>
<?php
$colname_rsRecord = "-1";
if (isset($_GET['employer_id'])) {
  $colname_rsRecord = (get_magic_quotes_gpc()) ? $_GET['employer_id'] : addslashes($_GET['employer_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsRecord = sprintf("SELECT * FROM employer WHERE employer_id = %s", $colname_rsRecord);
$rsRecord = mysql_query($query_rsRecord, $dw_conn) or die(mysql_error());
$row_rsRecord = mysql_fetch_assoc($rsRecord);
$totalRows_rsRecord = mysql_num_rows($rsRecord);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Vendor Details</title>
<?php include('css.php'); ?>
<?php include('js.php'); ?>
</head>

<body>
<table border="6" align="center" cellpadding="3" cellspacing="0" class="blacktbl" width="30%">
  <tr valign="bottom" >
    <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Employer Details</font></td>
  </tr>
  <tr valign="top" >
    <td colspan="2" class="blacktd">
          <table width="100%" border="6" cellpadding="5" cellspacing="0" bordercolor="#999999" class="tbl" style="border-style:solid">
            <tr valign="baseline">
              <td width="20%" align="right" nowrap class="thc2">Email:</td>
              <td class="tdc2"><?php echo $row_rsRecord['email']; ?></td>
            </tr>
            <tr valign="baseline">
              <td align="right" nowrap class="thc2">Name:</td>
              <td class="tdc2"><?php echo $row_rsRecord['name']; ?></td>
            </tr>
            <tr valign="baseline">
              <td align="right" nowrap class="thc2">Phone:</td>
              <td class="tdc2"><?php echo $row_rsRecord['phone']; ?></td>
            </tr>
            <tr valign="baseline">
              <td align="right" valign="top" nowrap class="thc2">Address:</td>
              <td class="tdc2"><?php echo $row_rsRecord['address']; ?></td>
            </tr>
            <tr valign="baseline">
              <td align="right" nowrap class="thc2">Loan Provision:</td>
              <td class="tdc2"><?php echo $row_rsRecord['loan_provision']; ?></td>
            </tr>
            <tr valign="baseline">
              <td align="right" nowrap class="thc2">Service Provision:</td>
              <td class="tdc2"><?php echo $row_rsRecord['service_provision']; ?></td>
            </tr>
            <tr valign="baseline">
              <td align="right" nowrap class="thc2">&nbsp;</td>
              <td class="tdc2"><?php echo $row_rsRecord['service_eligible_limit']; ?></td>
            </tr>
            <tr valign="baseline">
              <td align="right" nowrap class="thc2">Hardship Provision:</td>
              <td class="tdc2"><?php echo $row_rsRecord['hardship_provision']; ?></td>
            </tr>
            <tr valign="baseline">
              <td align="right" nowrap class="thc2">Exchanges:</td>
              <td class="tdc2"><?php echo $row_rsRecord['exchanges']; ?></td>
            </tr>
            <tr valign="baseline">
              <td align="right" nowrap class="thc2">Transfers In:</td>
              <td class="tdc2"><?php echo $row_rsRecord['transfers_in']; ?></td>
            </tr>
            <tr valign="baseline">
              <td align="right" nowrap class="thc2">Transfers Out:</td>
              <td class="tdc2"><?php echo $row_rsRecord['transfers_out']; ?></td>
            </tr>
            <tr valign="baseline">
              <td align="right" nowrap class="thc2">Roth Provision:</td>
              <td class="tdc2"><?php echo $row_rsRecord['roth_provision']; ?></td>
            </tr>
          </table>
      </td>
  </tr>
</table>
<?php include('end.php'); ?>
</body>
</html>
<?php
mysql_free_result($rsRecord);
?>
