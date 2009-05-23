<?php require_once('../Connections/dw_conn.php'); ?>
<?php
$colname_rsRequest = "-1";
if (isset($_COOKIE['employer']['employer_id'])) {
  $colname_rsRequest = (get_magic_quotes_gpc()) ? $_COOKIE['employer']['employer_id'] : addslashes($_COOKIE['employer']['employer_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsRequest = sprintf("SELECT workflow.*, count(workflow_documents.id) as cnt FROM workflow LEFT JOIN workflow_documents ON workflow.id = workflow_documents.id LEFT JOIN workflow_employer_list ON workflow.id = workflow_employer_list.id WHERE workflow.requestor_type = 'Employer' AND (workflow_employer_list.employer_id = %s OR workflow.employer_list IS NULL OR workflow.employer_list='' OR workflow.employer_list='0') GROUP BY workflow.id ORDER BY workflow.name ASC", $colname_rsRequest);
$rsRequest = mysql_query($query_rsRequest, $dw_conn) or die(mysql_error());
$row_rsRequest = mysql_fetch_assoc($rsRequest);
$totalRows_rsRequest = mysql_num_rows($rsRequest);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Create Request</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Workflow List</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
	  	<?php if ($totalRows_rsRequest > 0) { // Show if recordset not empty ?>
	  	<table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
		    <tr>
		      <td valign="top" class="thcview2"><strong>Name</strong></td>
			    <td valign="top" class="thcview2"><strong>Description</strong></td>
			    <td valign="top" class="thcview2"><strong>Approver Type</strong></td>
			    <td valign="top" class="thcview2"><strong>Documents</strong></td>
			    <td valign="top" class="thcview2"><strong>Actions</strong></td>
	        </tr>
		      <?php do { ?>
		      <tr>
		        <td valign="top" class="tdcview2"><?php echo $row_rsRequest['name']; ?>&nbsp;</td>
	  	        <td valign="top" class="tdcview2"><?php echo $row_rsRequest['description']; ?>&nbsp;</td>
	  	        <td valign="top" class="tdcview2"><?php echo $row_rsRequest['approver_type']; ?>&nbsp;</td>
	  	        <td valign="top" class="tdcview2"><?php if($row_rsRequest['cnt']>0) { ?><a href="javascript:;" onclick="doAjaxLoadingText('<?php echo HTTPPATH; ?>/main/view_documents.php','GET','id=<?php echo $row_rsRequest['id']; ?>','','divDocu<?php echo $row_rsRequest['id']; ?>','yes');">View Documents</a><?php } else { ?>No Document Found.<?php  } ?><br />
				<div id="divDocu<?php echo $row_rsRequest['id']; ?>"></div>
				</td>
	  	        <td valign="top" class="tdcview2"><a href="create_request.php?id=<?php echo $row_rsRequest['id']; ?>&menuTopItem=6">Create Request</a> <br />
		          <a href="request_detail.php?id=<?php echo $row_rsRequest['id']; ?>&menuTopItem=6">View Requests</a> </td>
	          </tr>
              <?php } while ($row_rsRequest = mysql_fetch_assoc($rsRequest)); ?>
	    </table>
	  	<?php } // Show if recordset not empty ?>  
  	  <?php if ($totalRows_rsRequest == 0) { // Show if recordset empty ?>
      <p>No Request List Found. </p>
      <?php } // Show if recordset empty ?></td>
	</tr>
</table>
<p>&nbsp; </p>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsRequest);
?>
