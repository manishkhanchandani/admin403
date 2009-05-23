<?php require_once('../Connections/dw_conn.php'); ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsRequest = 25;
$pageNum_rsRequest = 0;
if (isset($_GET['pageNum_rsRequest'])) {
  $pageNum_rsRequest = $_GET['pageNum_rsRequest'];
}
$startRow_rsRequest = $pageNum_rsRequest * $maxRows_rsRequest;

mysql_select_db($database_dw_conn, $dw_conn);
$query_rsRequest = "SELECT * FROM workflow WHERE requestor_type = 'Employee' ORDER BY name ASC";
$query_limit_rsRequest = sprintf("%s LIMIT %d, %d", $query_rsRequest, $startRow_rsRequest, $maxRows_rsRequest);
$rsRequest = mysql_query($query_limit_rsRequest, $dw_conn) or die(mysql_error());
$row_rsRequest = mysql_fetch_assoc($rsRequest);


if (isset($_GET['totalRows_rsRequest'])) {
  $totalRows_rsRequest = $_GET['totalRows_rsRequest'];
} else {
  $all_rsRequest = mysql_query($query_rsRequest);
  $totalRows_rsRequest = mysql_num_rows($all_rsRequest);
}
$totalPages_rsRequest = ceil($totalRows_rsRequest/$maxRows_rsRequest)-1;
if($totalRows_rsRequest>0) {
	do {
		$row_rsRequests[] = $row_rsRequest;
		$id[] = $row_rsRequest['id'];
	} while ($row_rsRequest = mysql_fetch_assoc($rsRequest)); 
	$ids = implode(',',$id);
}
$colname_rsdocu = "0";
if (isset($ids)) {
  $colname_rsdocu = (get_magic_quotes_gpc()) ? $ids : addslashes($ids);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsdocu = sprintf("SELECT * FROM workflow_documents WHERE id IN (%s)", $colname_rsdocu);
$rsdocu = mysql_query($query_rsdocu, $dw_conn) or die(mysql_error());
$row_rsdocu = mysql_fetch_assoc($rsdocu);
$totalRows_rsdocu = mysql_num_rows($rsdocu);

$queryString_rsRequest = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsRequest") == false && 
        stristr($param, "totalRows_rsRequest") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsRequest = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsRequest = sprintf("&totalRows_rsRequest=%d%s", $totalRows_rsRequest, $queryString_rsRequest);
?>
<?php 
if ($totalRows_rsdocu > 0) { // Show if recordset not empty 
	do { 
    	$documentList[$row_rsdocu['id']][$row_rsdocu['filename']] = $row_rsdocu['display'];
	} while ($row_rsdocu = mysql_fetch_assoc($rsdocu));
} // Show if recordset not empty

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Create Request</title>
<!-- InstanceEndEditable -->
<?php include('css.php'); ?>
<?php include('js.php'); ?>
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>

<body>
<?php include('head.php'); ?>
<!-- InstanceBeginEditable name="EditRegion3" -->
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Workflow List</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
	  	<?php if ($totalRows_rsRequest > 0) { // Show if recordset not empty ?>
	  	<table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
		    <tr>
		      <td valign="top" class="thcview2"><strong>Name</strong></td>
			    <td valign="top" class="thcview2"><strong>Description</strong></td>
			    <td valign="top" class="thcview2"><strong>Approver Type</strong></td>
			    <td valign="top" class="thcview2"><strong>Documents</strong></td>
			    <td valign="top" class="thcview2"><strong>Actions</strong></td>
	        </tr>
		      <?php foreach($row_rsRequests as $row_rsRequest) { ?>
		      <tr>
		        <td valign="top" class="tdcview2"><?php echo $row_rsRequest['name']; ?>&nbsp;</td>
	  	        <td valign="top" class="tdcview2"><?php echo $row_rsRequest['description']; ?>&nbsp;</td>
	  	        <td valign="top" class="tdcview2"><?php echo $row_rsRequest['approver_type']; ?>&nbsp;</td>
	  	        <td valign="top" class="tdcview2">
			<?php 
            if($documentList[$row_rsRequest['id']]) {
                foreach($documentList[$row_rsRequest['id']] as $key => $value) {
            ?>
                    <a href="../workflow/files/<?php echo $key; ?>" target="_blank"><?php if($value) echo $value; else echo $key; ?></a><br />
            <?php
                }
            }
            ?>&nbsp;</td>
	  	        <td valign="top" class="tdcview2"><a href="create_request.php?id=<?php echo $row_rsRequest['id']; ?>&menuTopItem=3">Create Request</a> <br />
		          <a href="request_detail.php?id=<?php echo $row_rsRequest['id']; ?>&menuTopItem=3">View Requests</a>&nbsp; </td>
	          </tr>
              <?php } ?>
	    </table>
<p> Records <?php echo ($startRow_rsRequest + 1) ?> to <?php echo min($startRow_rsRequest + $maxRows_rsRequest, $totalRows_rsRequest) ?> of <?php echo $totalRows_rsRequest ?></p>
<table border="0" width="50%" align="center">
  <tr>
    <td width="23%" align="center"><?php if ($pageNum_rsRequest > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsRequest=%d%s", $currentPage, 0, $queryString_rsRequest); ?>">First</a>
        <?php } // Show if not first page ?>
    </td>
    <td width="31%" align="center"><?php if ($pageNum_rsRequest > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsRequest=%d%s", $currentPage, max(0, $pageNum_rsRequest - 1), $queryString_rsRequest); ?>">Previous</a>
        <?php } // Show if not first page ?>
    </td>
    <td width="23%" align="center"><?php if ($pageNum_rsRequest < $totalPages_rsRequest) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsRequest=%d%s", $currentPage, min($totalPages_rsRequest, $pageNum_rsRequest + 1), $queryString_rsRequest); ?>">Next</a>
        <?php } // Show if not last page ?>
    </td>
    <td width="23%" align="center"><?php if ($pageNum_rsRequest < $totalPages_rsRequest) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsRequest=%d%s", $currentPage, $totalPages_rsRequest, $queryString_rsRequest); ?>">Last</a>
        <?php } // Show if not last page ?>
    </td>
  </tr>
</table>

	  	<?php } // Show if recordset not empty ?>  
  	  <?php if ($totalRows_rsRequest == 0) { // Show if recordset empty ?>
      <p>No Request List Found. </p>
      <?php } // Show if recordset empty ?></td>
	</tr>
</table>
<p>&nbsp; </p>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsRequest);

mysql_free_result($rsdocu);
?>
