<?php
switch($_GET['action']) {
	case 'addplan': 
		header("Location: admin_vendor_plan.php?vendor_id=".$_GET['vendor_id']."&menuTopItem=4"); exit;
		break;
	case 'edit': 
		header("Location: admin_vendor_edit.php?vendor_id=".$_GET['vendor_id']."&menuTopItem=4"); exit;
		break;
	case 'delete': 
		header("Location: admin_vendor_delete.php?vendor_id=".$_GET['vendor_id']."&menuTopItem=4"); exit;
		break;
	case 'upload': 
		header("Location: admin_vendor_upload.php?vendor_id=".$_GET['vendor_id']."&menuTopItem=4"); exit;
		break;
}
?>