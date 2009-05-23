<?php require_once('../Connections/dw_conn.php'); ?>
<?php
include('start.php');
?>
<?php
$colname_rsRecord = "-1";
if (isset($_GET['vendor_id'])) {
  $colname_rsRecord = (get_magic_quotes_gpc()) ? $_GET['vendor_id'] : addslashes($_GET['vendor_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsRecord = sprintf("SELECT * FROM vendor WHERE vendor_id = %s", $colname_rsRecord);
$rsRecord = mysql_query($query_rsRecord, $dw_conn) or die(mysql_error());
$row_rsRecord = mysql_fetch_assoc($rsRecord);
$totalRows_rsRecord = mysql_num_rows($rsRecord);

$colname_rsPlan = "-1";
if (isset($_GET['vendor_id'])) {
  $colname_rsPlan = (get_magic_quotes_gpc()) ? $_GET['vendor_id'] : addslashes($_GET['vendor_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsPlan = sprintf("SELECT * FROM vendor_plan WHERE vendor_id = %s", $colname_rsPlan);
$rsPlan = mysql_query($query_rsPlan, $dw_conn) or die(mysql_error());
$row_rsPlan = mysql_fetch_assoc($rsPlan);
$totalRows_rsPlan = mysql_num_rows($rsPlan);
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
<table width="40%" border="6" align="center" cellpadding="3" cellspacing="0" class="blacktbl">
  <tr valign="bottom" >
    <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Vendor Details</font></td>
  </tr>
  <tr valign="top" >
    <td colspan="2" class="blacktd">
          <table width="100%" border="6" cellpadding="5" cellspacing="0" bordercolor="#999999" class="tbl" style="border-style:solid">
            <tr valign="baseline">
              <td width="25%" align="right" nowrap class="thc2">Name:</td>
              <td class="tdc2"><?php echo $row_rsRecord['name']; ?></td>
            </tr>
            <tr valign="baseline">
              <td nowrap align="right" class="thc2">Phone:</td>
              <td class="tdc2"><?php echo $row_rsRecord['phone']; ?></td>
            </tr>
            <tr valign="baseline">
              <td nowrap align="right" class="thc2">Fax:</td>
              <td class="tdc2"><?php echo $row_rsRecord['fax']; ?></td>
            </tr>
            <tr valign="baseline">
              <td nowrap align="right" class="thc2">Vender URL:</td>
              <td class="tdc2"><?php echo $row_rsRecord['url']; ?></td>
            </tr>
            <tr valign="baseline">
              <td align="right" valign="top" nowrap class="thc2">Remittance Address:</td>
              <td class="tdc2"><?php echo $row_rsRecord['remittance_address']; ?></td>
            </tr>
          </table>
    </td>
  </tr>
</table>
<br>
<table width="70%" border="6" align="center" cellpadding="3" cellspacing="0" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Vendor Plans</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
	  	<table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
			<tr>
			  	<td valign="top" class="thcview2"><strong><?php echo DISPLAYPLANNAME; ?> Name </strong></td>
				<td valign="top" class="thcview2"><strong><?php echo DISPLAYPLANNAME; ?> Code </strong></td>
				<td valign="top" class="thcview2"><strong><?php echo DISPLAYPLANNAME; ?> Description </strong></td>
		  </tr>
			  <?php do { ?>
			  <tr>
                <td valign="top" class="tdcview2"><a href="<?php echo $row_rsPlan['plan_link']; ?>" target="_blank"><?php echo $row_rsPlan['plan_name']; ?></a></td>
                <td valign="top" class="tdcview2"><?php echo $row_rsPlan['plan_code']; ?></td>
                <td valign="top" class="tdcview2"><?php echo $row_rsPlan['plan_desc']; ?></td>
          </tr>
			  <?php } while ($row_rsPlan = mysql_fetch_assoc($rsPlan)); ?>
		</table>
	  </td>
	</tr>
</table>
<?php include('end.php'); ?>
</body>
</html>
<?php
mysql_free_result($rsRecord);

mysql_free_result($rsPlan);
?>
