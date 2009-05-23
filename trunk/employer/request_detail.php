<?php require_once('../Connections/dw_conn.php'); ?>
<?php
include_once('../main/functions.php');
?>
<?php
$colid_rsDetails = "-1";
if (isset($_COOKIE['employer']['employer_id'])) {
  $colid_rsDetails = (get_magic_quotes_gpc()) ? $_COOKIE['employer']['employer_id'] : addslashes($_COOKIE['employer']['employer_id']);
}
$colname_rsDetails = "-1";
if (isset($_GET['id'])) {
  $colname_rsDetails = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsDetails = sprintf("SELECT workflow.name, workflow.description, actions.*  FROM actions, workflow WHERE actions.wf_id = workflow.id AND actions.wf_id = %s AND actions.requestor_type = 'Employer' AND actions.requestor_id = %s", $colname_rsDetails,$colid_rsDetails);
$rsDetails = mysql_query($query_rsDetails, $dw_conn) or die(mysql_error());
$row_rsDetails = mysql_fetch_assoc($rsDetails);
$totalRows_rsDetails = mysql_num_rows($rsDetails);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Request Details</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Request Created</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
	  	<?php if ($totalRows_rsDetails > 0) { // Show if recordset not empty ?>
	  	<table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
		    <tr>
		  	    <td valign="top" class="thcview2"><strong>Name</strong></td>
			    <td valign="top" class="thcview2"><strong>Type</strong></td>
			    <td valign="top" class="thcview2"><strong>Description</strong></td>
			    <td valign="top" class="thcview2"><strong>Approver</strong></td>
			    <td valign="top" class="thcview2"><strong>Requestor</strong></td>
			    <td valign="top" class="thcview2"><strong>Status</strong></td>
			    <td valign="top" class="thcview2"><strong>Reasons</strong></td>
          </tr>
		      <?php do { ?>
		      <tr>
	  	        <td valign="top" class="tdcview2"><?php echo $row_rsDetails['name']; ?></td>
	  	        <td valign="top" class="tdcview2"><?php echo $row_rsDetails['action_type']; ?></td>
	  	        <td valign="top" class="tdcview2"><?php echo $row_rsDetails['description']; ?></td>
	  	        <td valign="top" class="tdcview2"><?php if($row_rsDetails['action_type']=="Employee") echo getEmployeeName($row_rsDetails['id']).' (Employee)'; else if($row_rsDetails['action_type']=="Employer") echo getEmployerName($row_rsDetails['id']).' (Employer)'; else if($row_rsDetails['action_type']=="Vendor") echo getVendorName($row_rsDetails['id']).' (Vendor)';?></td>
	  	        <td valign="top" class="tdcview2"><?php if($row_rsDetails['requestor_type']=="Employee") echo getEmployeeName($row_rsDetails['requestor_id']).' (Employee)'; else if($row_rsDetails['requestor_type']=="Employer") echo getEmployerName($row_rsDetails['requestor_id']).' (Employer)'; else if($row_rsDetails['requestor_type']=="Vendor") echo getVendorName($row_rsDetails['requestor_id']).' (Vendor)';?></td>
	  	        <td valign="top" class="tdcview2"><?php echo changeTense($row_rsDetails['status']); ?></td>
	  	        <td valign="top" class="tdcview2"><?php echo $row_rsDetails['reasons']; ?></td>
	          </tr>
	          <?php } while ($row_rsDetails = mysql_fetch_assoc($rsDetails)); ?>
	    </table>
	  	<?php } // Show if recordset not empty ?>  
  	  <?php if ($totalRows_rsDetails == 0) { // Show if recordset empty ?>
      <p>No Request Found. </p>
      <?php } // Show if recordset empty ?></td>
	</tr>
</table>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body><!-- InstanceEnd --></html>
<?php
mysql_free_result($rsDetails);
?>
