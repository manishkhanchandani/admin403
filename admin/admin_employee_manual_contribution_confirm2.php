<?php require_once('../Connections/dw_conn.php'); ?>
<?php
if($_POST['MM_Update']==1) {
	if($_POST['key']) {
		foreach($_POST['key'] as $i=>$value) {
			$query = "select * from employee_contribution where employee_id = '".$_POST['employee_id'][$i]."' and contribution_date = '".addslashes(stripslashes(trim($_POST['contribution_date'][$i])))."'";
			$rs = mysql_query($query) or die('error');
			if(mysql_num_rows($rs)==0) {
				switch($_POST['actions'][$i]) {
					case 2;
						//echo 'Accept Max Allowed Contribution';
						$arr['acceptmax'][] = $i;
						$sql = "insert into employee_contribution set employee_id = '".$_POST['employee_id'][$i]."', plan_id = '".$_POST['plan_id'][$i]."', vendor_id = '".$_POST['vendor_id'][$i]."', employer_id = '".$_POST['employer_id'][$i]."', contribution_date = '".addslashes(stripslashes(trim($_POST['contribution_date'][$i])))."', sra_pretax = '".addslashes(stripslashes(trim($_POST['max_sra_pretax_allowed'][$i])))."', sra_roth = '".$_POST['max_sra_roth_allowed'][$i]."'";
						$result = mysql_query($sql) or die(mysql_error());
						break;
					case 3;
						//echo 'Accept Contribution Even if max limit is exceeded ';
						$arr['accept'][] = $i;
						$sql = "insert into employee_contribution set employee_id = '".$_POST['employee_id'][$i]."', plan_id = '".$_POST['plan_id'][$i]."', vendor_id = '".$_POST['vendor_id'][$i]."', employer_id = '".$_POST['employer_id'][$i]."', contribution_date = '".addslashes(stripslashes(trim($_POST['contribution_date'][$i])))."', sra_pretax = '".addslashes(stripslashes(trim($_POST['max_sra_pretax_allowed'][$i])))."', sra_roth = '".$_POST['max_sra_roth_allowed'][$i]."'";
						$result = mysql_query($sql) or die(mysql_error());
												
						include_once('../main/functions.php');
						addExcessWorkflow($employee_id=$_POST['employee_id'][$i], $employer_id=$_POST['employer_id'][$i], $_POST['sra_pretax'][$i], $_POST['max_sra_pretax_allowed'][$i], $_POST['sra_roth'][$i], $_POST['max_sra_roth_allowed'][$i], $_POST['contribution_date'][$i]);
						break;
					case 4;
						//echo 'Set Manually on left Side';
						$arr['manual'][] = $i;
						$sql = "insert into employee_contribution set employee_id = '".$_POST['employee_id'][$i]."', plan_id = '".$_POST['plan_id'][$i]."', vendor_id = '".$_POST['vendor_id'][$i]."', employer_id = '".$_POST['employer_id'][$i]."', contribution_date = '".addslashes(stripslashes(trim($_POST['contribution_date'][$i])))."', sra_pretax = '".addslashes(stripslashes(trim($_POST['sra_pretax'][$i])))."', sra_roth = '".$_POST['sra_roth'][$i]."'";
					$result = mysql_query($sql) or die(mysql_error());
						break;
					case 1;
					default:
						//echo 'ignore';
						$arr['ignore'][] = $i;
						break;
				}
			} else {
				$arr['already'][] = $i;
			}
		} 
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Confirmation</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Confirmation</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">

<?php
if($arr['ignore']) {
	echo '<p>Entries of following employees were ignored:</p>';
	foreach($arr['ignore'] as $key => $value) {
		echo $_POST['ssn'][$value]."<br>";
	}
}
?>

<?php
if($arr['acceptmax']) {
	echo '<p>Following Entries were added with max contribution limit:</p>';
	foreach($arr['acceptmax'] as $key => $value) {
		echo $_POST['ssn'][$value]."<br>";
	}
}
?>

<?php
if($arr['accept']) {
	echo '<p>Following Entries were added Even if max limit is exceeded</p>';
	foreach($arr['accept'] as $key => $value) {
		echo $_POST['ssn'][$value]."<br>";
	}
}
?>

<?php
if($arr['manual']) {
	echo '<p>Following Entries were entered based on manual settings on previous page</p>';
	foreach($arr['manual'] as $key => $value) {
		echo $_POST['ssn'][$value]."<br>";
	}
}
?>
<?php
if($arr['already']) {
	echo '<p>Following Entries already exists</p>';
	foreach($arr['already'] as $key => $value) {
		echo $_POST['ssn'][$value]."<br>";
	}
}
?>
      </td>
    </tr>
</table>
<br />

<p>&nbsp;</p>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body>
<!-- InstanceEnd --></html>