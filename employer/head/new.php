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
			<li><a href="<?php echo HTTPPATH; ?>/employer/actions.php?menuTopItem=1">Actions</a></li> 
		</ul>
		<ul class="<?php if($_REQUEST['menuTopItem']==2) { ?>tabsx3<?php } else { ?>tabsx2<?php } ?>"> 
			<li><a href="<?php echo HTTPPATH; ?>/employer/reports_vendor.php?menuTopItem=2">Reports</a></li>  
		</ul>
		<ul class="<?php if($_REQUEST['menuTopItem']==6) { ?>tabsx3<?php } else { ?>tabsx2<?php } ?>"> 
			<li><a href="<?php echo HTTPPATH; ?>/employer/request_list.php?menuTopItem=6">Requests</a></li> 
		</ul>
		<ul class="<?php if($_REQUEST['menuTopItem']==7) { ?>tabsx3<?php } else { ?>tabsx2<?php } ?>"> 
			<li><a href="<?php echo HTTPPATH; ?>/employer/manage_employees.php?menuTopItem=7">Manage Employees </a></li> 
		</ul>
		<ul class="<?php if($_REQUEST['menuTopItem']==8) { ?>tabsx3<?php } else { ?>tabsx2<?php } ?>"> 
			<li><a href="<?php echo HTTPPATH; ?>/employer/view_vendors.php?menuTopItem=8#">Manage Vendors </a></li> 
		</ul>	</td>
	<td class="newtab" width="20%">		
		<ul class="<?php if($_REQUEST['menuTopItem']==3) { ?>tabsx3<?php } else { ?>tabsx2<?php } ?>"> 
			<li><a href="<?php echo HTTPPATH; ?>/employer/edit_details.php?menuTopItem=3">Profile</a></li> 
		</ul>
		<ul class="tabsx3"> <li> 
        	<a href="<?php echo HTTPPATH; ?>/admin/logout.php" title="LogOff">Log Off</a>
			</li>
		</ul>	</td>
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
    <li><a href="<?php echo HTTPPATH; ?>/employer/actions.php?menuTopItem=1"><span>Outstanding</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/employer/actions_approved.php?menuTopItem=1"><span>Approved</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/employer/actions_decline.php?menuTopItem=1"><span>Declined</span></a></li>
    <!-- <li><a href="<?php echo HTTPPATH; ?>/employer/actions_cancel.php?menuTopItem=1"><span>Cancelled</span></a></li> -->
    <?php } ?>
    <?php if($_REQUEST['menuTopItem']==2) { ?>
    <li><a href="<?php echo HTTPPATH; ?>/employer/reports_vendor.php?menuTopItem=2"><span>Per Vendor</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/employer/reports_period.php?menuTopItem=2"><span>Per Period</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/employer/report_remittance_by_totalremittance.php?menuTopItem=2"><span>Total Remittance</span></a>
    <li><a href="<?php echo HTTPPATH; ?>/employer/report_step1.php?menuTopItem=2"><span>Advance Search</span></a></li>
    <?php } ?>
    <?php if($_REQUEST['menuTopItem']==3) { ?><!-- 
    <li><a href="<?php echo HTTPPATH; ?>/employer/manage_vendors.php?menuTopItem=3"><span>Manage Vendors</span></a></li> -->
    <li><a href="<?php echo HTTPPATH; ?>/employer/authorizations_new2.php?menuTopItem=3"><span>Employee Authorizations</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/employer/authorizations_new3.php?menuTopItem=3"><span>Other Authorizations</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/employer/edit_details.php?menuTopItem=3"><span>Edit Details</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/employer/change_password.php?menuTopItem=3"><span>Change Password</span></a></li>
    <?php } ?>
    <?php if($_REQUEST['menuTopItem']==6) { ?>
	<li><a href="<?php echo HTTPPATH; ?>/employer/request_list.php?menuTopItem=6"><span>List/Create</span></a></li>  
	<li><a href="<?php echo HTTPPATH; ?>/employer/request_outstanding.php?menuTopItem=6"><span>Outstanding</span></a></li>
	<li><a href="<?php echo HTTPPATH; ?>/employer/request_closed.php?menuTopItem=6"><span>Closed</span></a></li> 
    <?php } ?>
    <?php if($_REQUEST['menuTopItem']==7) { ?>
	<li><a href="<?php echo HTTPPATH; ?>/employer/manage_employees.php?menuTopItem=7"><span>Edit Details </span></a></li>  
	<li><a href="<?php echo HTTPPATH; ?>/employer/manage_employee_add.php?menuTopItem=7"><span>Create Employee </span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/employer/employee_import.php?menuTopItem=7"><span>Upload Employees</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/employer/employee_import_contribution.php?menuTopItem=7"><span>Contributions</span></a></li>
    <?php } ?>
	<?php if($_REQUEST['menuTopItem']==8) { ?>
	<li><a href="<?php echo HTTPPATH; ?>/employer/view_vendors.php?menuTopItem=8"><span>List/Edit Vendors</span></a></li>  
    <?php } ?>
  </ul>
</div>
<div style="clear:both"></div>