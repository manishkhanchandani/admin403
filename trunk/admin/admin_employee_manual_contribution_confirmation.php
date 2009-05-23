<?php require_once('../Connections/dw_conn.php'); ?>
<?php
include_once('start.php');
include_once('../main/import_functions.php');
include_once('../main/functions.php');
if($_POST['MM_insert']==1) {
	if($_POST['contribution_date']) {
		$postarray = $_POST;
		$array['header'][1] = 'company';
		$array['header'][2] = 'lastname';
		$array['header'][3] = 'firstname';
		$array['header'][4] = 'middlename';
		$array['header'][5] = 'ssn';
		$array['header'][6] = 'sra_pretax';
		$array['header'][7] = 'sra_roth';
		$array['header'][8] = 'account';
		define('ROTH', 1);
		$array['detailIds'][1][1] = "";
		$array['detailIds'][1][2] = "";
		$array['detailIds'][1][3] = "";
		$array['detailIds'][1][4] = "";
		$array['detailIds'][1][5] = $_POST['ssn'];
		$array['detailIds'][1][6] = $_POST['sra_pretax'];
		$array['detailIds'][1][7] = $_POST['sra_roth'];
		$array['detailIds'][1][8] = "";
		$array['details'][1][1] = "";
		$array['details'][1][2] = "";
		$array['details'][1][3] = "";
		$array['details'][1][4] = "";
		$array['details'][1][5] = $_POST['ssn'];
		$array['details'][1][6] = $_POST['sra_pretax'];
		$array['details'][1][7] = $_POST['sra_roth'];
		$array['details'][1][8] = "";
		//$sess = postProcess($array, $_POST);
		$post = $_POST;
		$sess = postProcessMod($array, $post);
		//$transactionId = updateTransactionTable($post['contribution_date'], $employer_id=0);
	} else {
		?>
		<script language="javascript">
			alert('Please enter contribution date');
			location.href = "<?php echo $_SERVER['HTTP_REFERER']; ?>";
		</script>
		<?php
		exit;
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
<div id="transactionTable"><?php echo showTransactionTableForParticularEmployee($_POST['employee_id'], $post['contribution_date']); ?></div>	
<p>&nbsp;</p>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body>
<!-- InstanceEnd --></html>