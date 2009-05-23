<?php 
include_once('start.php');
?>
<?php
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsdocu = "SELECT * FROM workflow_documents";
$rsdocu = mysql_query($query_rsdocu, $dw_conn) or die(mysql_error());
$row_rsdocu = mysql_fetch_assoc($rsdocu);
$totalRows_rsdocu = mysql_num_rows($rsdocu);
?>
<?php if(TYPE=='Employee' && PAGE=='Outstanding') { ?>
<?php
$colname_rsDetails = "-1";
if (isset($_COOKIE['employee']['employee_id'])) {
  $colname_rsDetails = (get_magic_quotes_gpc()) ? $_COOKIE['employee']['employee_id'] : addslashes($_COOKIE['employee']['employee_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsDetails = sprintf("SELECT workflow.name, workflow.description, actions.action_id, actions.id, actions.title, actions.requestor_id, actions.requestor_type, actions.action_type, actions.wf_id, actions.status, actions.reasons, actions.date_created FROM actions, workflow WHERE actions.wf_id = workflow.id AND actions.requestor_type = 'Employee' AND actions.requestor_id = %s AND (actions.status = 'Pending') ORDER BY actions.date_created DESC", $colname_rsDetails);
$rsDetails = mysql_query($query_rsDetails, $dw_conn) or die(mysql_error());
$row_rsDetails = mysql_fetch_assoc($rsDetails);
$totalRows_rsDetails = mysql_num_rows($rsDetails);
?>
<?php } ?>
<?php if(TYPE=='Employer' && PAGE=='Outstanding') { ?>
<?php
$colname_rsDetails = "-1";
if (isset($_COOKIE['employer']['employer_id'])) {
  $colname_rsDetails = (get_magic_quotes_gpc()) ? $_COOKIE['employer']['employer_id'] : addslashes($_COOKIE['employer']['employer_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsDetails = sprintf("SELECT workflow.name, workflow.description, actions.action_id, actions.id, actions.title, actions.requestor_id, actions.requestor_type, actions.action_type, actions.wf_id, actions.status, actions.reasons, actions.date_created FROM actions, workflow WHERE actions.wf_id = workflow.id AND actions.requestor_type = 'Employer' AND actions.requestor_id = %s AND (actions.status = 'Pending') ORDER BY actions.date_created DESC", $colname_rsDetails);
$rsDetails = mysql_query($query_rsDetails, $dw_conn) or die(mysql_error());
$row_rsDetails = mysql_fetch_assoc($rsDetails);
$totalRows_rsDetails = mysql_num_rows($rsDetails);
?>
<?php } ?>
<?php if(TYPE=='Vendor' && PAGE=='Outstanding') { ?>
<?php
$colname_rsDetails = "-1";
if (isset($_COOKIE['vendor']['vendor_id'])) {
  $colname_rsDetails = (get_magic_quotes_gpc()) ? $_COOKIE['vendor']['vendor_id'] : addslashes($_COOKIE['vendor']['vendor_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsDetails = sprintf("SELECT workflow.name, workflow.description, actions.action_id, actions.id, actions.title, actions.requestor_id, actions.requestor_type, actions.action_type, actions.wf_id, actions.status, actions.reasons, actions.date_created FROM actions, workflow WHERE actions.wf_id = workflow.id AND actions.requestor_type = 'Vendor' AND actions.requestor_id = %s AND (actions.status = 'Pending') ORDER BY actions.date_created DESC", $colname_rsDetails);
$rsDetails = mysql_query($query_rsDetails, $dw_conn) or die(mysql_error());
$row_rsDetails = mysql_fetch_assoc($rsDetails);
$totalRows_rsDetails = mysql_num_rows($rsDetails);
?>
<?php } ?>
<?php if(TYPE=='Employee' && PAGE=='Closed') { ?>
<?php
$colname_rsDetails = "-1";
if (isset($_COOKIE['employee']['employee_id'])) {
  $colname_rsDetails = (get_magic_quotes_gpc()) ? $_COOKIE['employee']['employee_id'] : addslashes($_COOKIE['employee']['employee_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsDetails = sprintf("SELECT workflow.name, workflow.description, actions.action_id, actions.id, actions.title, actions.requestor_id, actions.requestor_type, actions.action_type, actions.wf_id, actions.status, actions.reasons, actions.date_created FROM actions, workflow WHERE actions.wf_id = workflow.id AND actions.requestor_type = 'Employee' AND actions.requestor_id = %s AND (actions.status = 'Approve' OR actions.status = 'Decline' OR actions.status = 'Cancel') ORDER BY actions.date_created DESC", $colname_rsDetails);
$rsDetails = mysql_query($query_rsDetails, $dw_conn) or die(mysql_error());
$row_rsDetails = mysql_fetch_assoc($rsDetails);
$totalRows_rsDetails = mysql_num_rows($rsDetails);
?>
<?php } ?>
<?php if(TYPE=='Employer' && PAGE=='Closed') { ?>
<?php
$colname_rsDetails = "-1";
if (isset($_COOKIE['employer']['employer_id'])) {
  $colname_rsDetails = (get_magic_quotes_gpc()) ? $_COOKIE['employer']['employer_id'] : addslashes($_COOKIE['employer']['employer_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsDetails = sprintf("SELECT workflow.name, workflow.description, actions.action_id, actions.id, actions.title, actions.requestor_id, actions.requestor_type, actions.action_type, actions.wf_id, actions.status, actions.reasons, actions.date_created FROM actions, workflow WHERE actions.wf_id = workflow.id AND actions.requestor_type = 'Employer' AND actions.requestor_id = %s AND (actions.status = 'Approve' OR actions.status = 'Decline' OR actions.status = 'Cancel') ORDER BY actions.date_created DESC", $colname_rsDetails);
$rsDetails = mysql_query($query_rsDetails, $dw_conn) or die(mysql_error());
$row_rsDetails = mysql_fetch_assoc($rsDetails);
$totalRows_rsDetails = mysql_num_rows($rsDetails);
?>
<?php } ?>
<?php if(TYPE=='Vendor' && PAGE=='Closed') { ?>
<?php
$colname_rsDetails = "-1";
if (isset($_COOKIE['vendor']['vendor_id'])) {
  $colname_rsDetails = (get_magic_quotes_gpc()) ? $_COOKIE['vendor']['vendor_id'] : addslashes($_COOKIE['vendor']['vendor_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsDetails = sprintf("SELECT workflow.name, workflow.description, actions.action_id, actions.id, actions.title, actions.requestor_id, actions.requestor_type, actions.action_type, actions.wf_id, actions.status, actions.reasons, actions.date_created FROM actions, workflow WHERE actions.wf_id = workflow.id AND actions.requestor_type = 'Vendor' AND actions.requestor_id = %s AND (actions.status = 'Approve' OR actions.status = 'Decline' OR actions.status = 'Cancel') ORDER BY actions.date_created DESC", $colname_rsDetails);
$rsDetails = mysql_query($query_rsDetails, $dw_conn) or die(mysql_error());
$row_rsDetails = mysql_fetch_assoc($rsDetails);
$totalRows_rsDetails = mysql_num_rows($rsDetails);
?>
<?php } ?>
<?php 
if ($totalRows_rsdocu > 0) { // Show if recordset not empty 
	do { 
    	$documentList[$row_rsdocu['id']][$row_rsdocu['filename']] = $row_rsdocu['display'];
	} while ($row_rsdocu = mysql_fetch_assoc($rsdocu));
} // Show if recordset not empty  
?>
<?php if ($totalRows_rsDetails > 0) { // Show if recordset not empty ?>
<table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
	<tr>
	  <td valign="top" class="thcview2"><strong>Date</strong></td>
	  <td valign="top" class="thcview2"><strong>Title</strong></td>
		<td valign="top" class="thcview2"><strong>Name</strong></td>
		<td valign="top" class="thcview2"><strong>Type</strong></td>
		<td valign="top" class="thcview2"><strong>Documents</strong></td>
		<td valign="top" class="thcview2"><strong>Approver</strong></td>
		<td valign="top" class="thcview2"><strong>Requestor</strong></td>
		<td valign="top" class="thcview2"><strong>Status</strong></td>
		<?php if(PAGE=='Outstanding') { ?>
        <td valign="top" class="thcview2"><strong>Actions</strong></td>
		<?php } ?>
  </tr>
	  <?php do { ?>
	  <tr>
	    <td valign="top" class="tdcview2"><?php echo date('d M, Y', strtotime($row_rsDetails['date_created'])); ?></td>
	    <td valign="top" class="tdcview2"><?php echo $row_rsDetails['title']; ?>&nbsp;</td>
		<td valign="top" class="tdcview2"><?php echo $row_rsDetails['name']; ?></td>
		<td valign="top" class="tdcview2"><?php echo $row_rsDetails['action_type']; ?></td>
		<td valign="top" class="tdcview2"><?php 		
            if($documentList[$row_rsDetails['wf_id']]) {
                foreach($documentList[$row_rsDetails['wf_id']] as $key => $value) {
            ?>
            <a href="../workflow/files/<?php echo $key; ?>" target="_blank">
            <?php if($value) echo $value; else echo $key; ?>
            </a><br />
            <?php
                }
            }
            ?>
&nbsp;</td>
		<td valign="top" class="tdcview2"><?php if($row_rsDetails['action_type']=="Employee") echo getEmployeeName($row_rsDetails['id']).' (Employee)'; else if($row_rsDetails['action_type']=="Employer") echo getEmployerName($row_rsDetails['id']).' (Employer)'; else if($row_rsDetails['action_type']=="Vendor") echo getVendorName($row_rsDetails['id']).' (Vendor)';?></td>
		<td valign="top" class="tdcview2"><?php if($row_rsDetails['requestor_type']=="Employee") echo getEmployeeName($row_rsDetails['requestor_id']).' (Employee)'; else if($row_rsDetails['requestor_type']=="Employer") echo getEmployerName($row_rsDetails['requestor_id']).' (Employer)'; else if($row_rsDetails['requestor_type']=="Vendor") echo getVendorName($row_rsDetails['requestor_id']).' (Vendor)';?></td>
		<td valign="top" class="tdcview2"><?php echo changeTense($row_rsDetails['status']); ?><br>
	    Reasons: <?php echo $row_rsDetails['reasons']; ?></td>
		<?php if(PAGE=='Outstanding') { ?>
	    <td valign="top" class="tdcview2"><a href="javascript:;" onclick="doAjaxLoadingText('request_cancel.php','GET','action_id=<?php echo $row_rsDetails['action_id']; ?>','','div<?php echo $row_rsDetails['action_id']; ?>','yes');">Cancel</a><br />
            <div id="div<?php echo $row_rsDetails['action_id']; ?>"></div></td>
		<?php } ?>
	  </tr>
	  <?php } while ($row_rsDetails = mysql_fetch_assoc($rsDetails)); ?>
</table>
<?php } // Show if recordset not empty ?>  
<?php if ($totalRows_rsDetails == 0) { // Show if recordset empty ?>
<p>No Request Found. </p>
<?php } // Show if recordset empty ?>
<?php
mysql_free_result($rsdocu);

mysql_free_result($rsDetails);
?>