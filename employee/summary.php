<?php require_once('../Connections/dw_conn.php'); ?>
<?php
$colname_rsPlan = "-1";
if (isset($_COOKIE['employee']['employee_id'])) {
  $colname_rsPlan = (get_magic_quotes_gpc()) ? $_COOKIE['employee']['employee_id'] : addslashes($_COOKIE['employee']['employee_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsPlan = sprintf("SELECT vendor_plan.plan_name, vendor_plan.plan_code, vendor_plan.plan_desc, vendor_plan.plan_link, vendor.name, vendor.employee_access, vendor_plan.plan_id, vendor_plan.vendor_id FROM employee_vendor INNER JOIN vendor_plan ON employee_vendor.plan_id = vendor_plan.plan_id LEFT JOIN vendor ON employee_vendor.vendor_id = vendor.vendor_id WHERE employee_vendor.employee_id = %s", $colname_rsPlan);
$rsPlan = mysql_query($query_rsPlan, $dw_conn) or die(mysql_error());
$row_rsPlan = mysql_fetch_assoc($rsPlan);
$totalRows_rsPlan = mysql_num_rows($rsPlan);

$colname_rsContribution = "-1";
if (isset($_COOKIE['employee']['employee_id'])) {
  $colname_rsContribution = (get_magic_quotes_gpc()) ? $_COOKIE['employee']['employee_id'] : addslashes($_COOKIE['employee']['employee_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsContribution = sprintf("SELECT * FROM employee_contribution WHERE employee_contribution.employee_id = %s ORDER BY employee_contribution.contribution_date DESC LIMIT 1", $colname_rsContribution);
$rsContribution = mysql_query($query_rsContribution, $dw_conn) or die(mysql_error());
$row_rsContribution = mysql_fetch_assoc($rsContribution);
$totalRows_rsContribution = mysql_num_rows($rsContribution);

$colid_rsDocuments = "-1";
if (isset($_COOKIE['employee']['employee_id'])) {
  $colid_rsDocuments = (get_magic_quotes_gpc()) ? $_COOKIE['employee']['employee_id'] : addslashes($_COOKIE['employee']['employee_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsDocuments = sprintf("SELECT d.*, em.name FROM employer_documents as d LEFT JOIN employer as em ON d.employer_id = em.employer_id LEFT JOIN employee as e ON d.employer_id = e.employer_id WHERE e.employee_id = %s", $colid_rsDocuments);
$rsDocuments = mysql_query($query_rsDocuments, $dw_conn) or die(mysql_error());
$row_rsDocuments = mysql_fetch_assoc($rsDocuments);
$totalRows_rsDocuments = mysql_num_rows($rsDocuments);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Summary</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Summary</font></td>
    </tr>
    
    <tr valign="top" >
      <td colspan="2" class="blacktd">
	  <?php if($totalRows_rsPlan>0) { ?>
        <p><strong>Vendor Details</strong></p>
	  	<table width="70%" border="6" cellpadding="5" cellspacing="0" bordercolor="#999999" class="tbl" style="border-style:solid">
			<tr align="center" valign="baseline">
			  <td nowrap="nowrap" class="thcview4">Vendor</td>
			  <td nowrap="nowrap" class="thcview4"><?php echo DISPLAYPLANNAME;?></td>
			  <td nowrap="nowrap" class="thcview4">Code</td>
			  <td nowrap="nowrap" class="thcview4">Description</td>
			  <td nowrap="nowrap" class="thcview4">Actions</td>
			</tr>
			<tr valign="baseline">
			  <td class="tdc2"><?php echo $row_rsPlan['name']; ?></td> 
			  <!-- place vendor name here -->
			  <td class="tdc2"><a href="<?php echo $row_rsPlan['plan_link']; ?>" target="_blank"><?php echo $row_rsPlan['plan_name']; ?>&nbsp;</a></td>
			  <td class="tdc2"><?php echo $row_rsPlan['plan_code']; ?>&nbsp;</td>
			  <td class="tdc2"><?php echo $row_rsPlan['plan_desc']; ?>&nbsp;</td>
			  <td class="tdc2"><a href="javascript:;" onclick="doAjaxLoadingText('../main/docs.php','GET','vendor_id=<?php echo $row_rsPlan['vendor_id']; ?>&plan_id=<?php echo $row_rsPlan['plan_id']; ?>','','divDocs_<?php echo $row_rsPlan['plan_id']; ?>','yes');">
					Docs
				</a>
				<div id="divDocs_<?php echo $row_rsPlan['plan_id']; ?>"></div></td>
			</tr>
		</table>
		<br />
		<?php } ?>
		<?php if ($totalRows_rsDocuments > 0) { // Show if recordset not empty ?>
        <p><strong><?php echo $row_rsDocuments['name']; ?> Documents </strong></p>
	  	<table width="70%" border="6" cellpadding="5" cellspacing="0" bordercolor="#999999" class="tbl" style="border-style:solid">
			<tr>
			  	<td valign="top" class="thcview4"><strong>File Name </strong></td>
				<td valign="top" class="thcview4"><strong>Comments</strong></td>
				<td valign="top" class="thcview4"><strong>Uploaded Date </strong></td>
				<td valign="top" class="thcview4"><strong>View </strong></td>
				<td valign="top" class="thcview4"><strong>Download</strong></td>
		    </tr>
			  <?php do { ?>
			  <tr>
			  	<td valign="top" class="tdc2"><?php echo $row_rsDocuments['display']; ?></td>
				<td valign="top" class="tdc2"><?php echo $row_rsDocuments['comments']; ?></td>
				<td valign="top" class="tdc2"><?php echo date('d M, Y', $row_rsDocuments['upload_dt']); ?></td>
				<td valign="top" class="tdc2"><a href="../main/files/<?php echo $row_rsDocuments['filename']; ?>" target="_blank">View</a> </td>
				<td valign="top" class="tdc2"><a href="../main/employer_document_download.php?docu_id=<?php echo $row_rsDocuments['docu_id']; ?>">Download</a></td>
		      </tr>
			  <?php } while ($row_rsDocuments = mysql_fetch_assoc($rsDocuments)); ?>
		</table>
		<br />
		<?php } // Show if recordset not empty ?>
		<?php if($totalRows_rsContribution) { ?>
	  	<table width="70%" border="6" cellpadding="5" cellspacing="0" bordercolor="#999999" class="tbl" style="border-style:solid">					
			<tr align="center" valign="baseline">
			  <td nowrap="nowrap" class="thcview3">Last Contribution Date </td>
			  <td nowrap="nowrap" class="thcview3">SRA Pretax</td>
			   <td nowrap="nowrap" class="thcview3">SRA Roth</td>

		  </tr>
		  
			<tr valign="baseline">
			  <td class="tdc2"><?php echo $row_rsContribution['contribution_date']; ?>&nbsp;</td>			  
			  <td class="tdc2">$<?php echo $row_rsContribution['sra_pretax']; ?>&nbsp;</td>
			  <td class="tdc2">$<?php echo $row_rsContribution['sra_roth']; ?>&nbsp;</td>
		  </tr>
		</table>
		<?php } ?>
	  </td>
	</tr>
</table>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsPlan);

mysql_free_result($rsContribution);

mysql_free_result($rsDocuments);
?>
