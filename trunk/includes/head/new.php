<style type="text/css">
<!--
.mainHead {color: #FFFFFF}
-->
</style>
<table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><h1 class="mainHead"><?php echo $MAINHEADING; ?></h1></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<table width="800" border="5" align="center" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
  <tr>
    <td height="432" valign="top" bgcolor="#F5F6EB">
<!-- step 1-->
<br>
<table width="800" align="right" cellpadding="0" cellspacing="0">
<tr>
	<td class="newtab" width="25"></td>
	<td class="newtab">
		<ul class="<?php if($_REQUEST['menuTopItem']==1 || !$_REQUEST['menuTopItem']) { ?>tabsx3<?php } else { ?>tabsx2<?php } ?>"> 
			<li><a href="<?php echo HTTPPATH; ?>/dw/admin_change_password.php?menuTopItem=1">Administrator</a></li> 
		</ul>
		<ul class="<?php if($_REQUEST['menuTopItem']==2) { ?>tabsx3<?php } else { ?>tabsx2<?php } ?>"> 
			<li><a href="<?php echo HTTPPATH; ?>/dw/admin_employee_list.php?menuTopItem=2">Employees</a></li>  
		</ul>
		<ul class="<?php if($_REQUEST['menuTopItem']==3) { ?>tabsx3<?php } else { ?>tabsx2<?php } ?>"> 
			<li><a href="<?php echo HTTPPATH; ?>/dw/admin_employer_list.php?menuTopItem=3">Employers</a></li> 
		</ul>
		<ul class="<?php if($_REQUEST['menuTopItem']==4) { ?>tabsx3<?php } else { ?>tabsx2<?php } ?>"> 
			<li><a href="<?php echo HTTPPATH; ?>/dw/admin_vendor_list.php?menuTopItem=4">Vendors</a></li> 
		</ul>
		<ul class="<?php if($_REQUEST['menuTopItem']==5) { ?>tabsx3<?php } else { ?>tabsx2<?php } ?>"> 
			<li><a href="<?php echo HTTPPATH; ?>/dw/admin_workflow_list.php?menuTopItem=5">Workflows</a></li> 
		</ul>
	</td>
	<td class="newtab">
		<ul class="tabsx"> <li> 
        	<a href="<?php echo HTTPPATH; ?>/dw/logout.php" title="LogOff">Log Off</a>
			</li>
		</ul>
	</td>
	<td class="newtab" width="25"></td>

</tr>
<tr><td background="<?php echo HTTPPATH; ?>/images/new/bgline.gif" height="10" width="100%" colspan="3"></td>
</tr></table><br><br>
<!-- step 2 -->

<div id="tabsE">
  <ul>
  	<?php if($_REQUEST['menuTopItem']==1 || !$_REQUEST['menuTopItem']) { ?>
    <li><a href="<?php echo HTTPPATH; ?>/dw/admin_change_password.php?menuTopItem=1"><span>Manage Admin</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/dw/admin_system_settings.php?menuTopItem=1"><span>System Settings</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/dw/admin_report.php?menuTopItem=1"><span>Reports</span></a></li>
    <?php } ?>
    <?php if($_REQUEST['menuTopItem']==2) { ?>
    <li><a href="<?php echo HTTPPATH; ?>/dw/admin_employee_import.php?menuTopItem=2"><span>Import List</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/dw/admin_employee_add.php?menuTopItem=2"><span>Create Employee</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/dw/admin_employee_search.php?menuTopItem=2"><span>Search Employee</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/dw/admin_employee_list.php?menuTopItem=2"><span>List/Modify Employees</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/dw/admin_employee_import_contribution.php?menuTopItem=2"><span>Upload Contribution list</span></a></li>
    <?php } ?>
    <?php if($_REQUEST['menuTopItem']==3) { ?>
    <li><a href="<?php echo HTTPPATH; ?>/dw/admin_employer_add.php?menuTopItem=3"><span>Create Employer</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/dw/admin_employer_list.php?menuTopItem=3"><span>List/Modify Employers</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/dw/admin_employer_search.php?menuTopItem=3"><span>Search Employer</span></a></li>
    <?php } ?>
    <?php if($_REQUEST['menuTopItem']==4) { ?>
    <li><a href="<?php echo HTTPPATH; ?>/dw/admin_vendor_new.php?menuTopItem=4"><span>Create Vendor</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/dw/admin_vendor_list.php?menuTopItem=4"><span>List/Modify Vendors</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/dw/admin_vendor_search.php?menuTopItem=4"><span>Search Vendor</span></a></li>
    <?php } ?>  
    <?php if($_REQUEST['menuTopItem']==5) { ?>
    <li><a href="<?php echo HTTPPATH; ?>/dw/admin_worklow.php?menuTopItem=5"><span>Add New Workflow</span></a></li>
	<li><a href="<?php echo HTTPPATH; ?>/dw/admin_workflow_list.php?menuTopItem=5"><span>List Workflow</span></a></li>
    <?php } ?>
  </ul>
</div>
<br /><br /><br />