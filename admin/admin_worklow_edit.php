<?php require_once('../Connections/dw_conn.php'); ?>
<?php
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	if($_POST['eList']) {
		$arr['employer_list'] = $_POST['eList'];
		$_POST['employer_list'] = implode('|', $_POST['eList']);
	}
	if($_POST['name']) {
	
	} else {
		$_POST["MM_insert"] = "";
		$errorMessage .= "Please fill name. ";
	}
	if($_POST['requestor_type']) {
	
	} else {
		$_POST["MM_insert"] = "";
		$errorMessage .= "Please choose Requestor Type. ";
	}
	if($_POST['approver_type']) {
	
	} else {
		$_POST["MM_insert"] = "";
		$errorMessage .= "Please choose Approver Type. ";
	}
	if($_POST['requestor_type']==$_POST['approver_type'] && $_POST['approver_type'] && $_POST['requestor_type']) {
		$_POST["MM_insert"] = "";
		$errorMessage .= "You cannot choose same requestor and approver. ";	
	}
}
?>
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE workflow SET name=%s, description=%s, requestor_type=%s, approver_type=%s, employer_list=%s, forward_type=%s WHERE id=%s",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['description'], "text"),
                       GetSQLValueString($_POST['requestor_type'], "text"),
                       GetSQLValueString($_POST['approver_type'], "text"),
                       GetSQLValueString($_POST['employer_list'], "text"),
                       GetSQLValueString($_POST['forward_type'], "int"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($updateSQL, $dw_conn) or die(mysql_error());
}
?>
<?php	
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	$uid = $_POST['id'];
	mysql_query("delete from workflow_employer_list where id = '".$uid."'") or die("error due to ".mysql_error());
	if($arr['employer_list']) {
		$sql = "insert into workflow_employer_list (id, employer_id) VALUES "; 
		foreach($arr['employer_list'] as $key => $value) {
			$sql .= "('".$uid."', '".$value."'), ";
		}
		$sql = substr($sql, 0, -2);
		mysql_query($sql) or die('error in inserting in approver type');
	}

  $insertGoTo = "admin_workflow_list.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<?php
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployer = "SELECT employer_id, name, email FROM employer ORDER BY name ASC";
$rsEmployer = mysql_query($query_rsEmployer, $dw_conn) or die(mysql_error());
$row_rsEmployer = mysql_fetch_assoc($rsEmployer);
$totalRows_rsEmployer = mysql_num_rows($rsEmployer);

$colname_rsEdit = "-1";
if (isset($_GET['id'])) {
  $colname_rsEdit = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEdit = sprintf("SELECT * FROM workflow WHERE id = %s", $colname_rsEdit);
$rsEdit = mysql_query($query_rsEdit, $dw_conn) or die(mysql_error());
$row_rsEdit = mysql_fetch_assoc($rsEdit);
$totalRows_rsEdit = mysql_num_rows($rsEdit);

$colname_rsEditEmployerList = "-1";
if (isset($_GET['id'])) {
  $colname_rsEditEmployerList = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEditEmployerList = sprintf("SELECT * FROM workflow_employer_list WHERE id = %s", $colname_rsEditEmployerList);
$rsEditEmployerList = mysql_query($query_rsEditEmployerList, $dw_conn) or die(mysql_error());
$row_rsEditEmployerList = mysql_fetch_assoc($rsEditEmployerList);
$totalRows_rsEditEmployerList = mysql_num_rows($rsEditEmployerList);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Edit Workflow</title>
<!-- InstanceEndEditable -->
<?php include('css.php'); ?>
<?php include('js.php'); ?>
<!-- InstanceBeginEditable name="head" -->
<script language="javascript">
	function processEmployer(vari) {
		if(vari==1) {
			toggleLayer('showList', 1);
			doAjaxXMLSelectBox2('getEmployerList.php','GET','','','employer_id',0);
		} else {
			toggleLayer('showList', 0);
			pid = getDocId('employer_id');
			removeAllOptions(pid);
		}
	}
</script>
<!-- InstanceEndEditable -->
</head>

<body>
<?php include('head.php'); ?>
<!-- InstanceBeginEditable name="EditRegion3" -->
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Create New Workflow</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<form action="<?php echo $editFormAction; ?>" method="POST" name="form1" id="form1">
<?php echo $errorMessage; ?>
  <table border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
    <tr valign="baseline">
      <td align="right" valign="top" nowrap="nowrap" class="thc2">Name:</td>
      <td valign="top" class="tdc2"><input type="text" name="name" value="<?php echo $row_rsEdit['name']; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="top" nowrap="nowrap" class="thc2">Description:</td>
      <td valign="top" class="tdc2"><textarea name="description" cols="50" rows="5"><?php echo $row_rsEdit['description']; ?></textarea>      </td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="top" nowrap="nowrap" class="thc2">Requestor Type:</td>
      <td valign="top" class="tdc2">
          <label>
            <input  <?php if (!(strcmp($row_rsEdit['requestor_type'],"Employer"))) {echo "checked=\"checked\"";} ?> name="requestor_type" type="radio" id="requestor_type_0" value="Employer" />
            Employer</label>
          <label>
            <input  <?php if (!(strcmp($row_rsEdit['requestor_type'],"Employee"))) {echo "checked=\"checked\"";} ?> type="radio" name="requestor_type" value="Employee" id="requestor_type_1" />
            Employee</label>
          <label>
            <input  <?php if (!(strcmp($row_rsEdit['requestor_type'],"Vendor"))) {echo "checked=\"checked\"";} ?> type="radio" name="requestor_type" value="Vendor" id="requestor_type_2" />
            Vendor</label>        </td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="top" nowrap="nowrap" class="thc2">Approver Type:</td>
      <td valign="top" class="tdc2">
          <label>
            <input  <?php if (!(strcmp($row_rsEdit['approver_type'],"Employer"))) {echo "checked=\"checked\"";} ?> type="radio" name="approver_type" value="Employer" id="approver_type_0" onclick="//processEmployer(1);" />
            Employer</label>
          <label>
            <input  <?php if (!(strcmp($row_rsEdit['approver_type'],"Employee"))) {echo "checked=\"checked\"";} ?> name="approver_type" type="radio" id="approver_type_1" value="Employee" onclick="//processEmployer(0);" />
            Employee</label>
          <label>
            <input  <?php if (!(strcmp($row_rsEdit['approver_type'],"Vendor"))) {echo "checked=\"checked\"";} ?> type="radio" name="approver_type" value="Vendor" id="approver_type_2" onclick="//processEmployer(0);" />
            Vendor</label>
			</td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="top" nowrap="nowrap" class="thc2">Employer List: </td>
      <td valign="top" class="tdc2">
        <?php $editArray = array();
		
		if ($totalRows_rsEditEmployerList > 0) { // Show if recordset not empty ?>
          <?php do { ?>
            <?php $editArray[] = $row_rsEditEmployerList['employer_id']; ?>
            <?php } while ($row_rsEditEmployerList = mysql_fetch_assoc($rsEditEmployerList)); ?>
          <?php } // Show if recordset not empty ?><select name="eList[]" size="4" multiple="multiple" id="employer_id">
        <?php
do {  
?>
        <option value="<?php echo $row_rsEmployer['employer_id']?>"<?php if(in_array($row_rsEmployer['employer_id'],$editArray)) echo ' selected'; ?>><?php echo $row_rsEmployer['name']?></option>
        <?php
} while ($row_rsEmployer = mysql_fetch_assoc($rsEmployer));
  $rows = mysql_num_rows($rsEmployer);
  if($rows > 0) {
      mysql_data_seek($rsEmployer, 0);
	  $row_rsEmployer = mysql_fetch_assoc($rsEmployer);
  }
?>
      </select>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="top" nowrap="nowrap" class="thc2">&nbsp;</td>
      <td valign="top" class="tdc2"><input type="submit" value="Update record" /></td>
    </tr>
  </table>
  <input name="menuTopItem" type="hidden" id="menuTopItem" value="5" />
  <input name="forward_type" type="hidden" id="forward_type" value="<?php echo $row_rsEdit['forward_type']; ?>" />
  <input name="employer_list" type="hidden" id="employer_list" value="<?php echo $row_rsEdit['employer_list']; ?>" />
  <input name="id" type="hidden" id="id" value="<?php echo $row_rsEdit['id']; ?>" />
  <input type="hidden" name="MM_update" value="form1">
</form>
      </td>
    </tr>
</table>
<br />
<p>&nbsp; </p>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body><!-- InstanceEnd -->
</html>
<?php
mysql_free_result($rsEmployer);

mysql_free_result($rsEdit);

mysql_free_result($rsEditEmployerList);
?>