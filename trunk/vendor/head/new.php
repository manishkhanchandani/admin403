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
			<li><a href="<?php echo HTTPPATH; ?>/vendor/actions.php?menuTopItem=1">Actions</a></li> 
		</ul>
		<ul class="<?php if($_REQUEST['menuTopItem']==2) { ?>tabsx3<?php } else { ?>tabsx2<?php } ?>"> 
			<li><a href="<?php echo HTTPPATH; ?>/vendor/report_step1.php?menuTopItem=2">Reports</a></li>  
		</ul>
		<ul class="<?php if($_REQUEST['menuTopItem']==3) { ?>tabsx3<?php } else { ?>tabsx2<?php } ?>"> 
			<li><a href="<?php echo HTTPPATH; ?>/vendor/request_list.php?menuTopItem=3">Requests</a></li> 
		</ul>	</td>
	<td class="newtab" width="20%">
			<ul class="<?php if($_REQUEST['menuTopItem']==4) { ?>tabsx3<?php } else { ?>tabsx2<?php } ?>"> 
			<li><a href="<?php echo HTTPPATH; ?>/vendor/vendor.php?menuTopItem=4">Profile</a></li> 
		</ul>
		<ul class="tabsx3"> <li> 
        	<a href="<?php echo HTTPPATH; ?>/vendor/logout.php" title="LogOff">Log Off</a>
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
    <li><a href="<?php echo HTTPPATH; ?>/vendor/actions.php?menuTopItem=1"><span>Outstanding</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/vendor/actions_approved.php?menuTopItem=1"><span>Approved</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/vendor/actions_decline.php?menuTopItem=1"><span>Declined</span></a></li>
    <?php } ?>
    <?php if($_REQUEST['menuTopItem']==2) { ?><!-- 
    <li><a href="<?php echo HTTPPATH; ?>/vendor/remittance_history.php?menuTopItem=2"><span>History per pay period</span></a></li> -->
    <li><a href="<?php echo HTTPPATH; ?>/vendor/report_remittance_by_totalremittance.php?menuTopItem=2"><span>Total Remittance </span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/vendor/reports_remittance_by_employer.php?menuTopItem=2"><span>Remittance By Employer</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/vendor/reports_remittance_by_plan.php?menuTopItem=2"><span>Remittance By <?php echo DISPLAYPLANNAME;?></span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/vendor/report_remittance_by_yeartodate.php?menuTopItem=2"><span>Year to date </span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/vendor/report_step1.php?menuTopItem=2"><span>Advance Search</span></a></li>
    <?php } ?>
    <?php if($_REQUEST['menuTopItem']==3) { ?>
	<li><a href="<?php echo HTTPPATH; ?>/vendor/request_list.php?menuTopItem=3"><span>List/Create</span></a></li> 
	<li><a href="<?php echo HTTPPATH; ?>/vendor/request_outstanding.php?menuTopItem=3"><span>Outstanding</span></a></li>
	<li><a href="<?php echo HTTPPATH; ?>/vendor/request_closed.php?menuTopItem=3"><span>Closed</span></a></li> 
    <?php } ?>
    <?php if($_REQUEST['menuTopItem']==4) { ?>
    <li><a href="<?php echo HTTPPATH; ?>/vendor/vendor_list.php?menuTopItem=4"><span>Manage Details</span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/vendor/vendor_plan.php?menuTopItem=4"><span>Manage <?php echo DISPLAYPLANNAME;?></span></a></li>
    <li><a href="<?php echo HTTPPATH; ?>/vendor/vendor.php?menuTopItem=4"><span>View Employer</span></a></li>
    <?php } ?>  
  </ul>
</div>
<div style="clear:both"></div>