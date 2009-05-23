<?php require_once('../Connections/dw_conn.php'); ?>
<?php
include_once('start.php');
include('auth.php');
if($_GET['from']) $from = $_GET['from']; else $from = date('Y-m-01');
if($_GET['to']) $to = $_GET['to']; else $to = date('Y-m-t');

$sql = "select COUNT(*) as cnt from monitor as m INNER JOIN monitor_sites as ms ON m.site_id = ms.site_id WHERE m.checktime BETWEEN '$from' AND '$to'";
$totalRows = $Common->selectCacheCount($sql);

$sql = "select m.*, ms.site from monitor as m INNER JOIN monitor_sites as ms ON m.site_id = ms.site_id WHERE m.checktime BETWEEN '$from' AND '$to'";
$sql .= " ORDER BY checktime DESC";

if($_GET['export']) {
	$record = $Common->selectCacheRecord($sql);
	$heading = "Site\tStatus\tCheckdate\r\n";
	if($record) {
		foreach($record as $detail) {
			$export .= $detail['site']."\t".$detail['status']."\t".$detail['checktime']."\r\n";
		}
	}
	$filename = "$from-$to-monitor.xls";
	$excel = $heading.$export;
	header("Content-type: application/x-msdownload");
	header("Content-Disposition: attachment; filename=$filename");
	header("Pragma: no-cache");
	header("Expires: 0");
	print "$excel"; 
	exit;
} else {
	if($_GET['max']) $max = $_GET['max']; else $max = 100;
	if($_GET['page']) $page = $_GET['page']; else $page = 1;
	$paginateDetails = $Common->getpaginatedetails($max, $page, $totalRows);
	
	$record = $Common->selectCacheLimitRecord($sql, $max, $paginateDetails['start']);
	if($paginateDetails['totalPages']>0) {
		$PaginateIt = new PaginateIt();
		$PaginateIt->SetItemsPerPage($max);
		$PaginateIt->SetItemCount($totalRows);
		$pagination = $PaginateIt->GetPageLinks();
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Administrator Site Monitor Report</title>
<!-- InstanceEndEditable -->
<?php include('css.php'); ?>
<?php include('js.php'); ?>
<!-- InstanceBeginEditable name="head" -->
<link rel="stylesheet" href="<?php echo HTTPPATH; ?>/libs/ui.datepicker/ui.datepicker.css" type="text/css" media="screen" title="core css file" charset="utf-8" />
<script src="<?php echo HTTPPATH; ?>/libs/jquery/jquery.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTPPATH; ?>/libs/ui.datepicker/ui.datepicker.js" type="text/javascript" charset="utf-8"></script>
<!-- InstanceEndEditable -->
</head>

<body>
<?php include('head.php'); ?>
<!-- InstanceBeginEditable name="EditRegion3" -->
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Administrator Site Report</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" align="center" class="blacktd">
	  
	  	<p><a href="superadmin.php?menuTopItem=1">Back To Superadmin</a></p>
		<form action="" method="get" name="formMonitorReport">
			From Date: <input type="text" name="from" id="from" value="<?php echo $from; ?>" /> To Date: <input type="text" name="to" id="to" value="<?php echo $to; ?>" />
		  <input type="hidden" name="menuTopItem" value="<?php echo $_GET['menuTopItem']; ?>" />
			<input type="submit" name="submit" value="Get Site Monitor Report"  />
		    <input name="export" type="submit" id="export" value="Export To Excel" />
		</form>
	  	<?php if($record) { ?>
		<table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
			<tr>
				<td valign="top" class="thcview2"><strong>Site</strong></td>
				<td valign="top" class="thcview2"><strong>Status</strong></td>
				<td valign="top" class="thcview2"><strong>Check Date</strong></td>
			</tr>
			<?php foreach($record as $detail) { ?>
				<tr>
					<td valign="top" class="tdcview2"><?php echo $detail['site']; ?></td>
					<td valign="top" class="tdcview2"><?php echo $detail['status']; ?>&nbsp;</td>
					<td valign="top" class="tdcview2"><?php echo $detail['checktime']; ?></td>
				</tr>
			<?php } ?>
		</table>
		<p align="right"><?php echo $pagination; ?></p>
		<?php } else { ?>
			<p><strong>No Record Found.</strong></p>
		<?php } ?>
      </td>
    </tr>
</table> 
<!-- Attach the datepicker to dateinput after document is ready -->
<script type="text/javascript" charset="utf-8">
	jQuery(function($){
		$("#from").datepicker();
		$("#to").datepicker();
	});
</script> 
<p>&nbsp;</p>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body>
<!-- InstanceEnd --></html>