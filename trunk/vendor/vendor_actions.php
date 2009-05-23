<?php
switch($_GET['action']) {
	case 'addplan': 
		header("Location: vendor_plan.php?menuTopItem=4"); exit;
		break;
	case 'edit': 
		header("Location: vendor_edit.php?menuTopItem=4"); exit;
		break;
	case 'upload': 
		header("Location: vendor_upload.php?menuTopItem=4"); exit;
		break;
}
?>