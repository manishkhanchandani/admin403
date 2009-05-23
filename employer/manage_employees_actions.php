<?php
switch($_GET['action']) {
	case 'addplan': 
		header("Location: manage_employee_vendor.php?employee_id=".$_GET['employee_id']."&menuTopItem=7"); exit;
		break;
	case 'addcontribution': 
		header("Location: employee_contribution.php?employee_id=".$_GET['employee_id']."&menuTopItem=7"); exit;
		break;
	case 'edit': 
		header("Location: manage_employee_edit.php?employee_id=".$_GET['employee_id']."&menuTopItem=7"); exit;
		break;
	case 'delete': 
		header("Location: manage_employee_delete.php?employee_id=".$_GET['employee_id']."&menuTopItem=7"); exit;
		break;
}
?>