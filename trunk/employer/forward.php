<?php require_once('../Connections/dw_conn.php'); ?>
<?php
$colname_rsRequestNew = "-1";
if (isset($_GET['wid'])) {
  $colname_rsRequestNew = (get_magic_quotes_gpc()) ? $_GET['wid'] : addslashes($_GET['wid']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsRequestNew = sprintf("SELECT * FROM workflow WHERE id = %s", $colname_rsRequestNew);
$rsRequestNew = mysql_query($query_rsRequestNew, $dw_conn) or die(mysql_error());
$row_rsRequestNew = mysql_fetch_assoc($rsRequestNew);
$totalRows_rsRequestNew = mysql_num_rows($rsRequestNew);

$colname_rsAction = "-1";
if (isset($_GET['id'])) {
  $colname_rsAction = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsAction = sprintf("SELECT * FROM actions WHERE id = %s", $colname_rsAction);
$rsAction = mysql_query($query_rsAction, $dw_conn) or die(mysql_error());
$row_rsAction = mysql_fetch_assoc($rsAction);
$totalRows_rsAction = mysql_num_rows($rsAction);

echo $string= stripslashes(urldecode($_GET['string']));
$arr = unserialize($string);
print_r($arr);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Untitled Document</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Create New Request</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
	    <form name="form1" id="form1" method="POST" action="<?php echo $editFormAction; ?>">
			<table border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
				<tr valign="top">
				  <td align="right" nowrap="nowrap" class="thc2">Name:</td>
				  <td class="tdc2"><input name="action_name" type="text" id="action_name" value="<?php echo $row_rsAction['action_name']; ?>" /></td>
			  </tr>
				<tr valign="top">
				  <td align="right" nowrap="nowrap" class="thc2">Description:</td>
				  <td class="tdc2"><textarea name="action_description" cols="35" rows="5" id="action_description"><?php echo $row_rsAction['action_description']; ?></textarea></td>
			  </tr>
				<tr valign="top">
				  <td align="right" nowrap="nowrap" class="thc2">Approver Type: </td>
				  <td class="tdc2"><?php echo $row_rsRequestNew['approver_type']; ?>
			      <input name="action_type" type="hidden" id="action_type" value="<?php echo $row_rsRequestNew['approver_type']; ?>" /></td>
				</tr>
				<tr valign="top">
				  <td align="right" nowrap="nowrap" class="thc2">Approver's Employer: </td>
				  <td class="tdc2">&nbsp;</td>
				</tr>
			  <?php if($row_rsRequestNew['approver_type']=="Employee") { ?> 
				<tr valign="top">
				  <td align="right" nowrap="nowrap" class="thc2">Approver's Employees: </td>
				  <td class="tdc2">&nbsp;</td>
				</tr>
			  <?php } ?>
				<tr valign="top">
				  <td align="right" nowrap="nowrap" class="thc2">Approver's Vendors:</td>
				  <td class="tdc2">&nbsp;</td>
			  </tr>
				<tr valign="top">
				  <td align="right" nowrap="nowrap" class="thc2">Approver's Employees:</td>
				  <td class="tdc2">&nbsp;</td>
			  </tr>
				<tr valign="top">
				  <td align="right" nowrap="nowrap" class="thc2">Approver's Employers:</td>
				  <td class="tdc2">&nbsp;</td>
			  </tr>
				<tr valign="top">
				  <td align="right" nowrap="nowrap" class="thc2">&nbsp;</td>
				  <td class="tdc2"><input type="submit" name="Submit" value="Forward Request" />
			      <input name="wf_id" type="hidden" id="wf_id" value="<?php echo $row_rsRequestNew['id']; ?>" />
                  <input name="status" type="hidden" id="status" value="Pending" />			      
                  <input name="menuTopItem" type="hidden" id="menuTopItem" value="3" />
			      <input name="id" type="hidden" id="id" />
			      <input name="pid" type="hidden" id="pid" value="<?php echo $_GET['id']; ?>" /></td>
			  </tr>
		  </table>
            <input type="hidden" name="MM_insert" value="form1">
	    </form>
	  </td>
	</tr>
</table>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body><!-- InstanceEnd -->
</html>
<?php
mysql_free_result($rsRequestNew);

mysql_free_result($rsAction);
?>
