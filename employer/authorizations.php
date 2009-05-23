<?php require_once('../Connections/dw_conn.php'); ?>
<?php
if($_POST['MM_Insert']==1) {
	if($_POST['employee_selected_text']) {
		$employee_selected_array = explode(",",$_POST['employee_selected_text']);
		if($_POST['employee_id']) {
			$employee_selected = $_POST['employee_id'];
		} else {
			$employee_selected = array();
		}
		$resultDeleted = array_diff($employee_selected_array, $employee_selected);
		if($resultDeleted) {
			$resultDeletedText = implode(',',$resultDeleted);
			$sql = "delete from employer_auth where employer_id = '".$_COOKIE['employer']['employer_id']."' and employee_id in (".$resultDeletedText.")";
			$rs = mysql_query($sql) or die('error');
		}
		$resultInserted = array_diff($employee_selected, $employee_selected_array);
		if($resultInserted) {
			$sql = "insert into employer_auth (employer_id, employee_id) values ";
			foreach($resultInserted as $value) {
				if($value!="") {
					$sql2[] = "('".$_COOKIE['employer']['employer_id']."', '".$value."')";
				}
			}
			$sql3 = implode(', ', $sql2);
			$sql .= $sql3;
			$rs = mysql_query($sql) or die('error');
		}
	} else {			
		$sql = "insert into employer_auth (employer_id, employee_id) values ";
		foreach($_POST['employee_id'] as $key => $value) {
			if($value!="") {
				$sql2[] = "('".$_COOKIE['employer']['employer_id']."', '".$value."')";
			}
		}
		$sql3 = implode(', ', $sql2);
		$sql .= $sql3;
		$rs = mysql_query($sql) or die('error');
	}
}
?>
<?php
$colname_rsEmployee = "-1";
if (isset($_COOKIE['employer']['employer_id'])) {
  $colname_rsEmployee = (get_magic_quotes_gpc()) ? $_COOKIE['employer']['employer_id'] : addslashes($_COOKIE['employer']['employer_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployee = sprintf("SELECT employee_id, firstname FROM employee WHERE employer_id = %s", $colname_rsEmployee);
$rsEmployee = mysql_query($query_rsEmployee, $dw_conn) or die(mysql_error());
$row_rsEmployee = mysql_fetch_assoc($rsEmployee);
$totalRows_rsEmployee = mysql_num_rows($rsEmployee);

$colname_rsAuth = "-1";
if (isset($_COOKIE['employer']['employer_id'])) {
  $colname_rsAuth = (get_magic_quotes_gpc()) ? $_COOKIE['employer']['employer_id'] : addslashes($_COOKIE['employer']['employer_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsAuth = sprintf("SELECT * FROM employer_auth WHERE employer_id = %s", $colname_rsAuth);
$rsAuth = mysql_query($query_rsAuth, $dw_conn) or die(mysql_error());
$row_rsAuth = mysql_fetch_assoc($rsAuth);
$totalRows_rsAuth = mysql_num_rows($rsAuth);
?>
<?php
$employee_selected = array();
if($totalRows_rsAuth>0) {
	do { 
		$employee_selected[] = $row_rsAuth['employee_id'];
		$employeeList[] = $row_rsAuth;
	} while ($row_rsAuth = mysql_fetch_assoc($rsAuth));
}
if($employee_selected) {
	$employee_selected_text = implode(',',$employee_selected);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Manage Authorizations</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Employee Permissions :: Set Following Employees As Employer</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
	  	<form name="form1" id="form1" method="post" action="">
	  	<table border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
			<tr valign="top">
			  <td align="right" nowrap="nowrap" class="thc2">Employees:</td>
			  <td class="tdc2"><select name="employee_id[]" size="5" multiple="multiple" id="employee_id[]">
			    <?php
do {  
?>
			    <option value="<?php echo $row_rsEmployee['employee_id']?>"<?php if(in_array($row_rsEmployee['employee_id'], $employee_selected)) echo ' selected'; ?>><?php echo $row_rsEmployee['firstname']?></option>
			    <?php
} while ($row_rsEmployee = mysql_fetch_assoc($rsEmployee));
  $rows = mysql_num_rows($rsEmployee);
  if($rows > 0) {
      mysql_data_seek($rsEmployee, 0);
	  $row_rsEmployee = mysql_fetch_assoc($rsEmployee);
  }
?>
		      </select></td>
			</tr>
			<tr valign="top">
			  <td align="right" nowrap="nowrap" class="thc2">&nbsp;</td>
			  <td class="tdc2"><input type="submit" name="Submit" value="Update" />
		        <input name="employee_selected_text" type="hidden" id="employee_selected_text" value="<?php echo $employee_selected_text; ?>" />
                <input name="menuTopItem" type="hidden" id="menuTopItem" value="3" />
                <input name="MM_Insert" type="hidden" id="MM_Insert" value="1" />
</td>
		  </tr>
		</table>
		</form>
	  </td>
	</tr>
</table>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body><!-- InstanceEnd --></html>
<?php
mysql_free_result($rsEmployee);

mysql_free_result($rsAuth);
?>
