<?php require_once('../Connections/dw_conn.php'); ?>
<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "1")) {
  $updateSQL = sprintf("UPDATE actions SET status=%s, reasons=%s WHERE action_id=%s",
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['reasons'], "text"),
                       GetSQLValueString($_POST['action_id'], "int"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($updateSQL, $dw_conn) or die(mysql_error());
}

$colname_rsEdit = "-1";
if (isset($_GET['action_id'])) {
  $colname_rsEdit = (get_magic_quotes_gpc()) ? $_GET['action_id'] : addslashes($_GET['action_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEdit = sprintf("SELECT * FROM actions WHERE action_id = %s", $colname_rsEdit);
$rsEdit = mysql_query($query_rsEdit, $dw_conn) or die(mysql_error());
$row_rsEdit = mysql_fetch_assoc($rsEdit);
$totalRows_rsEdit = mysql_num_rows($rsEdit);
?>
<?php if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "1")) {
echo 'Request Status Changed Successfully';
} else {
?>
<form method="post" name="form<?php echo $row_rsEdit['action_id']; ?>" action="<?php echo $editFormAction; ?>">
  <table>
    <tr valign="baseline">
      <td nowrap align="right">Reasons:</td>
      <td><input type="text" name="reasons" value="<?php echo $row_rsEdit['reasons']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input name="Button" type="button" value="Change Status" onClick="str=getFormElements(document.form<?php echo $row_rsEdit['action_id']; ?>); doAjaxLoadingText('request_cancel.php','POST','action_id=<?php echo $row_rsEdit['action_id']; ?>',str,'div<?php echo $row_rsEdit['action_id']; ?>','yes');"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="1">
  <input type="hidden" name="action_id" value="<?php echo $row_rsEdit['action_id']; ?>">
  <input type="hidden" name="status" value="Cancel" size="32">
</form>
<?php } ?>
<?php
mysql_free_result($rsEdit);
?>
