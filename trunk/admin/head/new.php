<style type="text/css">
<!--
.mainHead {color: #FFFFFF}
-->
</style>
<!-- #BeginLibraryItem "/Library/headerframe.lbi" --><iframe src="<?php echo HTTPPATH; ?>/Templates/BlankTemplateAM/index.php" name="headerverityfrm" width="100%" height="125px" scrolling="No" frameborder="0" id="headerverityfrm"></iframe><!-- #EndLibraryItem --><!--<table width="900" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><h1 class="mainHead"><?php echo $MAINHEADING; ?></h1></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table> -->
<table width="1000" border="5" align="center" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
  <tr>
    <td height="432" valign="top" bgcolor="#F5F6EB">
<!-- step 1-->
<br>
<table width="100%" align="right" cellpadding="0" cellspacing="0">
<tr>
	<td class="newtab" width="10%"></td>
	<td class="newtab" width="70%">
		<ul class="<?php if($_REQUEST['menuTopItem']==1 || !$_REQUEST['menuTopItem']) { ?>tabsx3<?php } else { ?>tabsx2<?php } ?>"> 
			<li><a href="<?php echo HTTPPATH; ?>/admin/admin_report.php?menuTopItem=1">Administrator Settings</a></li> 
		</ul>
		<ul class="<?php if($_REQUEST['menuTopItem']==2) { ?>tabsx3<?php } else { ?>tabsx2<?php } ?>"> 
			<li><a href="<?php echo HTTPPATH; ?>/admin/admin_employee_list.php?menuTopItem=2">Manage Employees</a></li>  
		</ul>
		<ul class="<?php if($_REQUEST['menuTopItem']==3) { ?>tabsx3<?php } else { ?>tabsx2<?php } ?>"> 
			<li><a href="<?php echo HTTPPATH; ?>/admin/admin_employer_list.php?menuTopItem=3">Manage Employers</a></li> 
		</ul>
		<ul class="<?php if($_REQUEST['menuTopItem']==4) { ?>tabsx3<?php } else { ?>tabsx2<?php } ?>"> 
			<li><a href="<?php echo HTTPPATH; ?>/admin/admin_vendor_list.php?menuTopItem=4">Manage Vendors</a></li> 
		</ul>
		<ul class="<?php if($_REQUEST['menuTopItem']==5) { ?>tabsx3<?php } else { ?>tabsx2<?php } ?>"> 
			<li><a href="<?php echo HTTPPATH; ?>/admin/admin_workflow_list.php?menuTopItem=5">Manage Workflows</a></li> 
		</ul>
	</td>
	<td class="newtab" width="10%">
		<ul class="tabsx3"> <li> 
        	<a href="<?php echo HTTPPATH; ?>/admin/logout.php" title="LogOff">Log Off</a>
			</li>
		</ul>
	</td>
	<td class="newtab" width="10%"></td>

</tr>
<tr><td background="<?php echo HTTPPATH; ?>/images/new/bgline_blue2.gif" height="10" width="100%" colspan="4"></td>
</tr></table>

<br><br>
<div style="clear:both"></div>
<!-- step 2 -->

<div id="tabsE">
  <ul>
  	<?php if($_REQUEST['menuTopItem']==1 || !$_REQUEST['menuTopItem']) { ?>
    <li><a href="<?php echo HTTPPATH; ?>/admin/admin_change_password.php?menuTopItem=1"><span>Manage Admin</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/admin/admin_system_settings.php?menuTopItem=1"><span>System Settings</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/admin/admin_report.php?menuTopItem=1"><span>Reports</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/admin/database.php?menuTopItem=1"><span>Database</span></a></li>
    <?php } ?>
    <?php if($_REQUEST['menuTopItem']==2) { ?>
    <li><a href="<?php echo HTTPPATH; ?>/admin/admin_employee_import.php?menuTopItem=2"><span>Upload Employee List</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/admin/admin_employee_add.php?menuTopItem=2"><span>Create</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/admin/admin_employee_search.php?menuTopItem=2"><span>Search</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/admin/admin_employee_list.php?menuTopItem=2"><span>Edit Records</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/admin/admin_employee_import_contribution.php?menuTopItem=2"><span>Upload Contribution List</span></a></li>
    <?php } ?>
    <?php if($_REQUEST['menuTopItem']==3) { ?>
    <li><a href="<?php echo HTTPPATH; ?>/admin/admin_employer_add.php?menuTopItem=3"><span>Create</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/admin/admin_employer_list.php?menuTopItem=3"><span>Edit Records</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/admin/admin_employer_search.php?menuTopItem=3"><span>Search</span></a></li>
    <?php } ?>
    <?php if($_REQUEST['menuTopItem']==4) { ?>
    <li><a href="<?php echo HTTPPATH; ?>/admin/admin_vendor_new.php?menuTopItem=4"><span>Create</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/admin/admin_vendor_list.php?menuTopItem=4"><span>Edit Records</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/admin/admin_vendor_search.php?menuTopItem=4"><span>Search</span></a></li>
    <?php } ?>  
    <?php if($_REQUEST['menuTopItem']==5) { ?>
    <li><a href="<?php echo HTTPPATH; ?>/admin/admin_worklow.php?menuTopItem=5"><span>Add New</span></a></li>
	<li><a href="<?php echo HTTPPATH; ?>/admin/admin_workflow_list.php?menuTopItem=5"><span>List</span></a></li>
    <?php } ?>
  </ul>
</div>
<div style="clear:both"></div>
