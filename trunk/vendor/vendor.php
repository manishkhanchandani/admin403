<?php require_once('../Connections/dw_conn.php'); ?>
<?php
include_once('start.php');

$colname_rsDetail = "-1";
if (isset($_COOKIE['vendor']['vendor_id'])) {
  $colname_rsDetail = (get_magic_quotes_gpc()) ? $_COOKIE['vendor']['vendor_id'] : addslashes($_COOKIE['vendor']['vendor_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsDetail = sprintf("SELECT employer.name, employee.firstname, employee.lastname, vp2.plan_name as plan_name2, employee.employee_id, employer.employer_id, employee_vendor.vendor_id FROM employer_vendor LEFT JOIN employer ON employer_vendor.employer_id = employer.employer_id LEFT JOIN employee ON employer.employer_id = employee.employer_id LEFT JOIN employee_vendor ON employee.employee_id = employee_vendor.employee_id LEFT JOIN vendor_plan as vp2 ON employee_vendor.plan_id = vp2.plan_id WHERE employer_vendor.vendor_id = %s GROUP BY employer.employer_id, employee.employee_id ORDER BY employer.name, employee.firstname", $colname_rsDetail);
$rsDetail = mysql_query($query_rsDetail, $dw_conn) or die(mysql_error());
$row_rsDetail = mysql_fetch_assoc($rsDetail);
$totalRows_rsDetail = mysql_num_rows($rsDetail);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Vendor Details</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Request List</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
	  	<table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
			<tr>
			  	<td valign="top" class="thcview2"><strong>Employer</strong></td>
				<td valign="top" class="thcview2"><strong>Employee</strong></td>
		        <td valign="top" class="thcview2"><strong><?php echo DISPLAYPLANNAME;?> Choosen </strong></td>
		  </tr>
			  <?php do { ?>
			  <tr>
			  	<td valign="top" class="tdcview2"><a href="../main/employer_details.php?employer_id=<?php echo $row_rsDetail['employer_id']; ?>"  target="_blank"><?php $TFM_nest1 = $row_rsDetail['name']; if ($lastTFM_nest1 != $TFM_nest1) { $lastTFM_nest1 = $TFM_nest1; echo $row_rsDetail['name']; } //End of Basic-UltraDev Simulated Nested Repeat?></a>&nbsp;</td>
				<td valign="top" class="tdcview2"><a href="../main/employee_details.php?employee_id=<?php echo $row_rsDetail['employee_id']; ?>" target="_blank"><?php $TFM_nest2 = $row_rsDetail['firstname']." ".$row_rsDetail['lastname']; if ($lastTFM_nest2 != $TFM_nest2) { $lastTFM_nest2 = $TFM_nest2; echo $row_rsDetail['firstname']." ".$row_rsDetail['lastname']; } //End of Basic-UltraDev Simulated Nested Repeat?></a>&nbsp;</td>
			    <td valign="top" class="tdcview2"><a href="../main/vendor_details.php?vendor_id=<?php echo $row_rsDetail['vendor_id']; ?>" target="_blank"><?php echo $row_rsDetail['plan_name2']; ?></a>&nbsp;</td>
			  </tr>
			  <?php } while ($row_rsDetail = mysql_fetch_assoc($rsDetail)); ?>
		</table>
	  </td>
	</tr>
</table>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsDetail);
?>
