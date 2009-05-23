<?php require_once('../Connections/dw_conn.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_dw_conn, $dw_conn);
$query_rsWF = "SELECT * FROM workflow";
$rsWF = mysql_query($query_rsWF, $dw_conn) or die(mysql_error());
$row_rsWF = mysql_fetch_assoc($rsWF);
$totalRows_rsWF = mysql_num_rows($rsWF);

mysql_select_db($database_dw_conn, $dw_conn);
$query_rsdocu = "SELECT * FROM workflow_documents";
$rsdocu = mysql_query($query_rsdocu, $dw_conn) or die(mysql_error());
$row_rsdocu = mysql_fetch_assoc($rsdocu);
$totalRows_rsdocu = mysql_num_rows($rsdocu);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>List Workflow</title>
<!-- InstanceEndEditable -->
<?php include('css.php'); ?>
<?php include('js.php'); ?>
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
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
      <?php 
if ($totalRows_rsdocu > 0) { // Show if recordset not empty 
	do { 
    	$documentList[$row_rsdocu['id']][$row_rsdocu['filename']] = $row_rsdocu['display'];
	} while ($row_rsdocu = mysql_fetch_assoc($rsdocu));
} // Show if recordset not empty  
?>
<?php if ($totalRows_rsWF > 0) { // Show if recordset not empty ?>
  <table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
    <tr>
      <td valign="top" class="thcview2"><strong>Name</strong></td>
      <td valign="top" class="thcview2"><strong>Description</strong></td>
      <td valign="top" class="thcview2"><strong>Requestor Type</strong></td>
      <td valign="top" class="thcview2"><strong>Approver Type</strong></td>
      <td valign="top" class="thcview2"><strong>Documents</strong></td>
      <td valign="top" class="thcview2"><strong>Actions</strong></td>
      </tr>
    <?php do { ?>
      <tr>
        <td valign="top" class="tdcview2"><?php echo $row_rsWF['name']; ?>&nbsp;</td>
        <td valign="top" class="tdcview2"><?php echo $row_rsWF['description']; ?>&nbsp;</td>
        <td valign="top" class="tdcview2"><?php echo $row_rsWF['requestor_type']; ?><strong></strong></td>
        <td valign="top" class="tdcview2"><?php echo $row_rsWF['approver_type']; ?><strong></strong></td>
        <td valign="top" class="tdcview2">
			<?php 
            if($documentList[$row_rsWF['id']]) {
                foreach($documentList[$row_rsWF['id']] as $key => $value) {
            ?>
                    <a href="../workflow/files/<?php echo $key; ?>" target="_blank"><?php if($value) echo $value; else echo $key; ?></a><br />
            <?php
                }
            }
            ?>
&nbsp;        </td>
        <td valign="top" class="tdcview2"><a href="admin_worklow_edit.php?id=<?php echo $row_rsWF['id']; ?>&menuTopItem=5">Edit</a> | <a href="admin_workflow_upload.php?id=<?php echo $row_rsWF['id']; ?>&menuTopItem=5">Add Documents</a> | <a href="admin_workflow_delete.php?id=<?php echo $row_rsWF['id']; ?>&menuTopItem=5">Delete</a></td>
        </tr>
      <?php } while ($row_rsWF = mysql_fetch_assoc($rsWF)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rsWF == 0) { // Show if recordset empty ?>
  <br />
  No Workflow found.<br />
  <?php } // Show if recordset empty ?>
      </td>
    </tr>
</table>
<br />

<p>&nbsp;</p>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body><!-- InstanceEnd --></html>
<?php
mysql_free_result($rsWF);

mysql_free_result($rsdocu);
?>