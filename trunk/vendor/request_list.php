<?php require_once('../Connections/dw_conn.php'); ?>
<?php
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsRequest = "SELECT * FROM workflow WHERE requestor_type = 'Vendor' ORDER BY name ASC";
$rsRequest = mysql_query($query_rsRequest, $dw_conn) or die(mysql_error());
$row_rsRequest = mysql_fetch_assoc($rsRequest);
$totalRows_rsRequest = mysql_num_rows($rsRequest);

mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployer = "SELECT employer_id, name, email FROM employer";
$rsEmployer = mysql_query($query_rsEmployer, $dw_conn) or die(mysql_error());
$row_rsEmployer = mysql_fetch_assoc($rsEmployer);
$totalRows_rsEmployer = mysql_num_rows($rsEmployer);

mysql_select_db($database_dw_conn, $dw_conn);
$query_rsdocu = "SELECT * FROM workflow_documents";
$rsdocu = mysql_query($query_rsdocu, $dw_conn) or die(mysql_error());
$row_rsdocu = mysql_fetch_assoc($rsdocu);
$totalRows_rsdocu = mysql_num_rows($rsdocu);
?>
<?php 
if ($totalRows_rsEmployer > 0) { // Show if recordset not empty 
	do { 
		$employerList[$row_rsEmployer['employer_id']] = $row_rsEmployer['name'];
	} while ($row_rsEmployer = mysql_fetch_assoc($rsEmployer)); 
} // Show if recordset not empty 
?>
<?php 
if ($totalRows_rsdocu > 0) { // Show if recordset not empty 
	do { 
    	$documentList[$row_rsdocu['id']][$row_rsdocu['filename']] = $row_rsdocu['display'];
	} while ($row_rsdocu = mysql_fetch_assoc($rsdocu));
} // Show if recordset not empty  
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
			    <td valign="top" class="thcview2"><strong>Employer List </strong></td>
			    <td valign="top" class="thcview2"><strong>Documents</strong></td>
			    <td valign="top" class="thcview2"><strong>Actions</strong></td>
	        </tr>
		      <?php do { ?>
		      <tr>
		        <td valign="top" class="tdcview2"><?php echo $row_rsRequest['name']; ?></td>
	  	        <td valign="top" class="tdcview2"><?php echo $row_rsRequest['description']; ?></td>
	  	        <td valign="top" class="tdcview2"><?php echo $row_rsRequest['approver_type']; ?></td>
	  	        <td valign="top" class="tdcview2"><?php if($row_rsRequest['employer_list']){
													$tmp = explode("|",$row_rsRequest['employer_list']);
													foreach($tmp as $value) {
														echo $employerList[trim($value)];
														echo "<br>";
													}
												  } ?></td>
	  	        <td valign="top" class="tdcview2"><?php 
            if($documentList[$row_rsRequest['id']]) {
                foreach($documentList[$row_rsRequest['id']] as $key => $value) {
            ?>
                    <a href="../workflow/files/<?php echo $key; ?>" target="_blank">
                    <?php if($value) echo $value; else echo $key; ?>
                    </a><br />
                    <?php
                }
            }
            ?>
&nbsp;</td>
	  	        <td valign="top" class="tdcview2"><a href="create_request.php?id=<?php echo $row_rsRequest['id']; ?>&menuTopItem=3">Create Request</a> <br />
		          <a href="request_detail.php?id=<?php echo $row_rsRequest['id']; ?>&menuTopItem=3">View Requests</a> </td>
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

mysql_free_result($rsEmployer);

mysql_free_result($rsdocu);
?>
