<?php require_once('../Connections/dw_conn.php'); ?>
<?php
include('start.php');
?>
<?php
$colname_rsRecord = "-1";
if (isset($_GET['employee_id'])) {
  $colname_rsRecord = (get_magic_quotes_gpc()) ? $_GET['employee_id'] : addslashes($_GET['employee_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsRecord = sprintf("SELECT * FROM employee WHERE employee_id = %s", $colname_rsRecord);
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
    <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Employee Details</font></td>
  </tr>
  <tr valign="top" >
    <td colspan="2" class="blacktd">
          <table width="100%" border="6" cellpadding="5" cellspacing="0" bordercolor="#999999" class="tbl" style="border-style:solid">
            <tr valign="baseline">
              <td width="20%" align="right" nowrap class="thc2">Email:</td>
              <td class="tdc2"><?php echo $row_rsRecord['email']; ?></td>
            </tr>
            <tr valign="baseline">
              <td align="right" nowrap="nowrap" class="thc2">SSN:</td>
              <td class="tdc2"><?php echo $encryption->processDecrypt('ssn', $row_rsRecord['ssn']); ?></td>
            </tr>
            <tr valign="baseline">
              <td align="right" nowrap class="thc2">First name:</td>
              <td class="tdc2"><?php echo $row_rsRecord['firstname']; ?></td>
            </tr>
            <tr valign="baseline">
              <td align="right" nowrap class="thc2">Middle name:</td>
              <td class="tdc2"><?php echo $row_rsRecord['middlename']; ?></td>
            </tr>
            <tr valign="baseline">
              <td align="right" nowrap class="thc2">Last name:</td>
              <td class="tdc2"><?php echo $row_rsRecord['lastname']; ?></td>
            </tr>
            <tr valign="baseline">
              <td align="right" nowrap class="thc2">Hire date:</td>
              <td class="tdc2"><?php echo $row_rsRecord['hire_date']; ?></td>
            </tr>
            <tr valign="baseline">
              <td align="right" nowrap class="thc2">Termination date:</td>
              <td class="tdc2"><?php echo $row_rsRecord['termination_date']; ?></td>
            </tr>
            <tr valign="baseline">
              <td align="right" nowrap class="thc2">Date of Birth:</td>
              <td class="tdc2"><?php echo $row_rsRecord['dob']; ?></td>
            </tr>
            <tr valign="baseline">
              <td align="right" nowrap class="thc2">Account Number:</td>
              <td class="tdc2"><?php echo $row_rsRecord['account_number']; ?></td>
            </tr>
            <tr valign="baseline">
              <td align="right" nowrap class="thc2">Phone:</td>
              <td class="tdc2"><?php echo $row_rsRecord['phone']; ?></td>
            </tr>
            <tr valign="baseline">
              <td align="right" nowrap class="thc2">Fax:</td>
              <td class="tdc2"><?php echo $row_rsRecord['fax']; ?></td>
            </tr>
            <tr valign="baseline">
              <td align="right" valign="top" nowrap="nowrap" class="thc2">Address:</td>
              <td class="tdc2"><?php echo $row_rsRecord['address']; ?></td>
            </tr>
            <tr valign="baseline">
              <td align="right" valign="top" nowrap="nowrap" class="thc2">Married:</td>
              <td class="tdc2"><?php echo $row_rsRecord['married']; ?></td>
            </tr>
            <tr valign="baseline">
              <td align="right" valign="top" nowrap="nowrap" class="thc2">Gender:</td>
              <td class="tdc2"><?php if($row_rsRecord['sex']=='M') echo 'Male'; else if($row_rsRecord['sex']=='F') echo 'Female'; ?></td>
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
