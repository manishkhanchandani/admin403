<?php
switch($_GET['action']) {
	case 'addplan': 
		header("Location: admin_employee_vendor.php?employee_id=".$_GET['employee_id']."&menuTopItem=2"); exit;
		break;
	case 'addcontribution': 
		header("Location: admin_employee_contribution_manual.php?employee_id=".$_GET['employee_id']."&menuTopItem=2"); exit;
		break;
	case 'editcontribution': 
		header("Location: admin_employee_editcontribution.php?employee_id=".$_GET['employee_id']."&menuTopItem=2"); exit;
		break;
	case 'edit': 
		header("Location: admin_employee_edit.php?employee_id=".$_GET['employee_id']."&menuTopItem=2"); exit;
		break;
	case 'delete': 
		header("Location: admin_employee_delete.php?employee_id=".$_GET['employee_id']."&menuTopItem=2"); exit;
		break;
}
?>