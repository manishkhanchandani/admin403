<?php require_once('../Connections/dw_conn.php'); ?>
<?php
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	if($_POST['eList']) {
		$arr['employer_list'] = $_POST['eList'];
		$_POST['employer_list'] = implode('|', $_POST['eList']);
	}
	if($_POST['forward_enable']) {
		if($_POST['ft']) {
			if(in_array(1, $_POST['ft']) && in_array(2, $_POST['ft']) && in_array(3, $_POST['ft'])) {
				$_POST['forward_type'] = 7;
			} else if(in_array(1, $_POST['ft']) && in_array(2, $_POST['ft'])) {
				$_POST['forward_type'] = 4;
			} else if(in_array(1, $_POST['ft']) && in_array(3, $_POST['ft'])) {
				$_POST['forward_type'] = 5;
			} else if(in_array(2, $_POST['ft']) && in_array(3, $_POST['ft'])) {
				$_POST['forward_type'] = 6;
			} else if(in_array(1, $_POST['ft'])) {
				$_POST['forward_type'] = 1;
			} else if(in_array(2, $_POST['ft'])) {
				$_POST['forward_type'] = 2;
			} else if(in_array(3, $_POST['ft'])) {
				$_POST['forward_type'] = 3;
			}
		} else {
			$_POST["MM_insert"] = "";
			$errorMessage = "Please fill forward type. ";			
		}
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO workflow (name, description, requestor_type, approver_type, employer_list) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['description'], "text"),
                       GetSQLValueString($_POST['requestor_type'], "text"),
                       GetSQLValueString($_POST['approver_type'], "text"),
                       GetSQLValueString($_POST['employer_list'], "text"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($insertSQL, $dw_conn) or die(mysql_error());
}
?>
<?php
	
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	$uid = mysql_insert_id();
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Create New Workflow</title>
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
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
<?php echo $errorMessage; ?>
  <table border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
    <tr valign="baseline">
      <td align="right" valign="top" nowrap="nowrap" class="thc2">Name:</td>
      <td valign="top" class="tdc2"><input type="text" name="name" value="<?php echo $_POST['name']; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="top" nowrap="nowrap" class="thc2">Description:</td>
      <td valign="top" class="tdc2"><textarea name="description" cols="50" rows="5"><?php echo $_POST['description']; ?></textarea>      </td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="top" nowrap="nowrap" class="thc2">Requestor Type:</td>
      <td valign="top" class="tdc2">
          <label>
            <input <?php if (!(strcmp($_POST['requestor_type'],"Employer"))) {echo "CHECKED";} ?> name="requestor_type" type="radio" id="requestor_type_0" value="Employer" />
            Employer</label>
          <label>
            <input <?php if (!(strcmp($_POST['requestor_type'],"Employee"))) {echo "CHECKED";} ?> type="radio" name="requestor_type" value="Employee" id="requestor_type_1" />
            Employee</label>
          <label>
            <input <?php if (!(strcmp($_POST['requestor_type'],"Vendor"))) {echo "CHECKED";} ?> type="radio" name="requestor_type" value="Vendor" id="requestor_type_2" />
            Vendor</label>        </td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="top" nowrap="nowrap" class="thc2">Approver Type:</td>
      <td valign="top" class="tdc2">
          <label>
            <input <?php if (!(strcmp($_POST['approver_type'],"Employer"))) {echo "CHECKED";} ?> type="radio" name="approver_type" value="Employer" id="approver_type_0" onclick="//processEmployer(1);" />
            Employer</label>
          <label>
            <input <?php if (!(strcmp($_POST['approver_type'],"Employee"))) {echo "CHECKED";} ?> name="approver_type" type="radio" id="approver_type_1" value="Employee" onclick="//processEmployer(0);" />
            Employee</label>
          <label>
            <input <?php if (!(strcmp($_POST['approver_type'],"Vendor"))) {echo "CHECKED";} ?> type="radio" name="approver_type" value="Vendor" id="approver_type_2" onclick="//processEmployer(0);" />
            Vendor</label>
			</td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="top" nowrap="nowrap" class="thc2">Employer List: </td>
      <td valign="top" class="tdc2"><select name="eList[]" size="4" multiple="multiple" id="employer_id">
        <?php
do {  
?>
        <option value="<?php echo $row_rsEmployer['employer_id']?>"><?php echo $row_rsEmployer['name']?></option>
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
      <td valign="top" class="tdc2"><input type="submit" value="Insert record" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1" /><input name="menuTopItem" type="hidden" id="menuTopItem" value="5" />
  <input name="forward_type" type="hidden" id="forward_type" value="0" />
  <input name="employer_list" type="hidden" id="employer_list" />
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
?>