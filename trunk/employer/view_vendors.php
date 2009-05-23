<?php require_once('../Connections/dw_conn.php'); ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];
?>
<?php 
include_once('../main/functions.php');
$return1 = getDelEmployersVendors($_COOKIE['employer']['employer_id']);
if($return1) {
	$string1 = implode(', ', $return1);
} else {
	$string1 = 0;
}
$return2 = getEmployersVendors($_COOKIE['employer']['employer_id']);
if($return2) {
	$string2 = implode(', ', $return2);
} else {
	$string2 = 0;
}
$return3 = array_merge($return1, $return2);
if($return3) {
	$string3 = implode(', ', $return3);
} else {
	$string3 = 0;
}
?>
<?php
$maxRows_rsVendorDeleted = 10;
$pageNum_rsVendorDeleted = 0;
if (isset($_GET['pageNum_rsVendorDeleted'])) {
  $pageNum_rsVendorDeleted = $_GET['pageNum_rsVendorDeleted'];
}
$startRow_rsVendorDeleted = $pageNum_rsVendorDeleted * $maxRows_rsVendorDeleted;

$colemployer_rsVendorDeleted = "-1";
if (isset($_COOKIE['employer']['employer_id'])) {
  $colemployer_rsVendorDeleted = (get_magic_quotes_gpc()) ? $_COOKIE['employer']['employer_id'] : addslashes($_COOKIE['employer']['employer_id']);
}
$colname_rsVendorDeleted = "0";
if (isset($string1)) {
  $colname_rsVendorDeleted = (get_magic_quotes_gpc()) ? $string1 : addslashes($string1);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsVendorDeleted = sprintf("SELECT vendor_plan.plan_name, vendor.name, vendor.vendor_id, vendor_plan.plan_link, vendor_plan.plan_code, vendor_plan.plan_desc, vendor_plan.plan_id, employer_vendors_contact.contact_name, employer_vendors_contact.contact_email, employer_vendors_contact.contact_phone, employer_vendors_contact.group_plan_number, employer_vendors_contact.contact_id FROM vendor INNER JOIN vendor_plan ON vendor.vendor_id = vendor_plan.vendor_id INNER JOIN employer_vendors_contact ON vendor_plan.plan_id = employer_vendors_contact.plan_id WHERE vendor_plan.plan_id IN (%s) AND employer_vendors_contact.employer_id = %s", $colname_rsVendorDeleted,$colemployer_rsVendorDeleted);
$query_limit_rsVendorDeleted = sprintf("%s LIMIT %d, %d", $query_rsVendorDeleted, $startRow_rsVendorDeleted, $maxRows_rsVendorDeleted);
$rsVendorDeleted = mysql_query($query_limit_rsVendorDeleted, $dw_conn) or die(mysql_error());
$row_rsVendorDeleted = mysql_fetch_assoc($rsVendorDeleted);

if (isset($_GET['totalRows_rsVendorDeleted'])) {
  $totalRows_rsVendorDeleted = $_GET['totalRows_rsVendorDeleted'];
} else {
  $all_rsVendorDeleted = mysql_query($query_rsVendorDeleted);
  $totalRows_rsVendorDeleted = mysql_num_rows($all_rsVendorDeleted);
}
$totalPages_rsVendorDeleted = ceil($totalRows_rsVendorDeleted/$maxRows_rsVendorDeleted)-1;

$maxRows_rsVendorActive = 10;
$pageNum_rsVendorActive = 0;
if (isset($_GET['pageNum_rsVendorActive'])) {
  $pageNum_rsVendorActive = $_GET['pageNum_rsVendorActive'];
}
$startRow_rsVendorActive = $pageNum_rsVendorActive * $maxRows_rsVendorActive;

$colemployer_rsVendorActive = "-1";
if (isset($_COOKIE['employer']['employer_id'])) {
  $colemployer_rsVendorActive = (get_magic_quotes_gpc()) ? $_COOKIE['employer']['employer_id'] : addslashes($_COOKIE['employer']['employer_id']);
}
$colname_rsVendorActive = "0";
if (isset($string2)) {
  $colname_rsVendorActive = (get_magic_quotes_gpc()) ? $string2 : addslashes($string2);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsVendorActive = sprintf("SELECT vendor_plan.plan_name, vendor.name, vendor.vendor_id, vendor_plan.plan_link, vendor_plan.plan_code, vendor_plan.plan_desc, vendor_plan.plan_id, employer_vendors_contact.contact_name, employer_vendors_contact.contact_email, employer_vendors_contact.contact_phone, employer_vendors_contact.group_plan_number, employer_vendors_contact.contact_id FROM vendor INNER JOIN vendor_plan ON vendor.vendor_id = vendor_plan.vendor_id INNER JOIN employer_vendors_contact ON vendor_plan.plan_id = employer_vendors_contact.plan_id WHERE vendor_plan.plan_id IN (%s) AND employer_vendors_contact.employer_id = %s", $colname_rsVendorActive,$colemployer_rsVendorActive);
$query_limit_rsVendorActive = sprintf("%s LIMIT %d, %d", $query_rsVendorActive, $startRow_rsVendorActive, $maxRows_rsVendorActive);
$rsVendorActive = mysql_query($query_limit_rsVendorActive, $dw_conn) or die(mysql_error());
$row_rsVendorActive = mysql_fetch_assoc($rsVendorActive);

if (isset($_GET['totalRows_rsVendorActive'])) {
  $totalRows_rsVendorActive = $_GET['totalRows_rsVendorActive'];
} else {
  $all_rsVendorActive = mysql_query($query_rsVendorActive);
  $totalRows_rsVendorActive = mysql_num_rows($all_rsVendorActive);
}
$totalPages_rsVendorActive = ceil($totalRows_rsVendorActive/$maxRows_rsVendorActive)-1;

$queryString_rsVendorDeleted = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsVendorDeleted") == false && 
        stristr($param, "totalRows_rsVendorDeleted") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsVendorDeleted = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsVendorDeleted = sprintf("&totalRows_rsVendorDeleted=%d%s", $totalRows_rsVendorDeleted, $queryString_rsVendorDeleted);

$queryString_rsVendorActive = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsVendorActive") == false && 
        stristr($param, "totalRows_rsVendorActive") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsVendorActive = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsVendorActive = sprintf("&totalRows_rsVendorActive=%d%s", $totalRows_rsVendorActive, $queryString_rsVendorActive);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>View Vendors</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Active Vendor <?php echo DISPLAYPLANNAME; ?>s</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
	  	<?php if ($totalRows_rsVendorActive > 0) { // Show if recordset not empty ?>
        <table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
          <tr>
            <td valign="top" class="thcview3"><strong>Vendor</strong></td>
            <td valign="top" class="thcview3"><strong><?php echo DISPLAYPLANNAME; ?></strong></td>
            <td valign="top" class="thcview3"><strong>Contact Name </strong></td>
            <td valign="top" class="thcview3"><strong>Contact Email </strong></td>
            <td valign="top" class="thcview3"><strong>Contact Phone </strong></td>
            <td valign="top" class="thcview3"><strong>Group Plan Number </strong></td>
            <td valign="top" class="thcview3"><strong>Actions</strong></td>
          </tr>
          <?php do { ?>
          <tr>
            <td valign="top" class="tdcview2"><?php echo $row_rsVendorActive['name']; ?></td>
            <td valign="top" class="tdcview2"><a href="<?php echo $row_rsVendorActive['plan_link']; ?>" target="_blank"><?php echo $row_rsVendorActive['plan_name']; ?></a></td>
            <td valign="top" class="tdcview2"><?php echo $row_rsVendorActive['contact_name']; ?></td>
            <td valign="top" class="tdcview2"><?php echo $row_rsVendorActive['contact_email']; ?></td>
            <td valign="top" class="tdcview2"><?php echo $row_rsVendorActive['contact_phone']; ?></td>
            <td valign="top" class="tdcview2"><?php echo $row_rsVendorActive['group_plan_number']; ?></td>
            <td valign="top" class="tdcview2">
				<a href="view_vendors_make_inactive.php?vendor_id=<?php echo $row_rsVendorActive['vendor_id']; ?>&plan_id=<?php echo $row_rsVendorActive['plan_id']; ?>&employer_id=<?php echo $_COOKIE['employer']['employer_id']; ?>&menuTopItem=<?php echo $_REQUEST['menuTopItem']; ?>">
					Make Inactive
				</a> 
				| 
				<a href="view_vendors_del_active.php?vendor_id=<?php echo $row_rsVendorActive['vendor_id']; ?>&plan_id=<?php echo $row_rsVendorActive['plan_id']; ?>&employer_id=<?php echo $_COOKIE['employer']['employer_id']; ?>&menuTopItem=<?php echo $_REQUEST['menuTopItem']; ?>">
					Delete
				</a> 
				| 
				<a href="view_vendors_edit.php?vendor_id=<?php echo $row_rsVendorActive['vendor_id']; ?>&plan_id=<?php echo $row_rsVendorActive['plan_id']; ?>&employer_id=<?php echo $_COOKIE['employer']['employer_id']; ?>&menuTopItem=<?php echo $_REQUEST['menuTopItem']; ?>&contact_id=<?php echo $row_rsVendorActive['contact_id']; ?>">
					Edit
				</a>
				| 
				<a href="javascript:;" onclick="doAjaxLoadingText('../main/docs.php','GET','vendor_id=<?php echo $row_rsVendorActive['vendor_id']; ?>&plan_id=<?php echo $row_rsVendorActive['plan_id']; ?>&employer_id=<?php echo $_COOKIE['employer']['employer_id']; ?>','','divDocs_<?php echo $row_rsVendorActive['vendor_id']; ?>','yes');">
					Docs
				</a>
				<div id="divDocs_<?php echo $row_rsVendorActive['vendor_id']; ?>"></div>
			</td>
          </tr>
          <?php } while ($row_rsVendorActive = mysql_fetch_assoc($rsVendorActive)); ?>
        </table>
        <p> Records <?php echo ($startRow_rsVendorActive + 1) ?> to <?php echo min($startRow_rsVendorActive + $maxRows_rsVendorActive, $totalRows_rsVendorActive) ?> of <?php echo $totalRows_rsVendorActive ?>
        <table border="0" width="50%" align="center">
          <tr>
            <td width="23%" align="center"><?php if ($pageNum_rsVendorActive > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_rsVendorActive=%d%s", $currentPage, 0, $queryString_rsVendorActive); ?>">First</a>
                <?php } // Show if not first page ?>
            </td>
            <td width="31%" align="center"><?php if ($pageNum_rsVendorActive > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_rsVendorActive=%d%s", $currentPage, max(0, $pageNum_rsVendorActive - 1), $queryString_rsVendorActive); ?>">Previous</a>
                <?php } // Show if not first page ?>
            </td>
            <td width="23%" align="center"><?php if ($pageNum_rsVendorActive < $totalPages_rsVendorActive) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_rsVendorActive=%d%s", $currentPage, min($totalPages_rsVendorActive, $pageNum_rsVendorActive + 1), $queryString_rsVendorActive); ?>">Next</a>
                <?php } // Show if not last page ?>
            </td>
            <td width="23%" align="center"><?php if ($pageNum_rsVendorActive < $totalPages_rsVendorActive) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_rsVendorActive=%d%s", $currentPage, $totalPages_rsVendorActive, $queryString_rsVendorActive); ?>">Last</a>
                <?php } // Show if not last page ?>
            </td>
          </tr>
        </table>
        <?php } // Show if recordset not empty ?>
        <?php if ($totalRows_rsVendorActive == 0) { // Show if recordset empty ?>
        <p>No Active <?php echo DISPLAYPLANNAME; ?> in list. </p>
        <?php } // Show if recordset empty ?>
        </p></td>
	</tr>
</table>
<br />
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Inactive Vendor <?php echo DISPLAYPLANNAME; ?>s</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
	  	<?php if ($totalRows_rsVendorDeleted > 0) { // Show if recordset not empty ?>
        <table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
          <tr>
            <td valign="top" class="thcview4"><strong>Vendor</strong></td>
            <td valign="top" class="thcview4"><strong><?php echo DISPLAYPLANNAME; ?></strong></td>
            <td valign="top" class="thcview4"><strong>Contact Name </strong></td>
            <td valign="top" class="thcview4"><strong>Contact Email </strong></td>
            <td valign="top" class="thcview4"><strong>Contact Phone </strong></td>
            <td valign="top" class="thcview4"><strong>Group Plan Number </strong></td>
            <td valign="top" class="thcview4"><strong>Actions</strong></td>
          </tr>
          <?php do { ?>
          <tr>
            <td valign="top" class="tdcview2"><?php echo $row_rsVendorDeleted['name']; ?></td>
            <td valign="top" class="tdcview2"><a href="<?php echo $row_rsVendorDeleted['plan_link']; ?>" target="_blank"><?php echo $row_rsVendorDeleted['plan_name']; ?></a></td>
            <td valign="top" class="tdcview2"><?php echo $row_rsVendorDeleted['contact_name']; ?></td>
            <td valign="top" class="tdcview2"><?php echo $row_rsVendorDeleted['contact_email']; ?></td>
            <td valign="top" class="tdcview2"><?php echo $row_rsVendorDeleted['contact_phone']; ?></td>
            <td valign="top" class="tdcview2"><?php echo $row_rsVendorDeleted['group_plan_number']; ?></td>
            <td valign="top" class="tdcview2"><a href="view_vendors_make_active.php?vendor_id=<?php echo $row_rsVendorDeleted['vendor_id']; ?>&plan_id=<?php echo $row_rsVendorDeleted['plan_id']; ?>&employer_id=<?php echo $_COOKIE['employer']['employer_id']; ?>&menuTopItem=<?php echo $_REQUEST['menuTopItem']; ?>">Make Active</a> | <a href="view_vendors_del_inactive.php?vendor_id=<?php echo $row_rsVendorDeleted['vendor_id']; ?>&plan_id=<?php echo $row_rsVendorDeleted['plan_id']; ?>&employer_id=<?php echo $_COOKIE['employer']['employer_id']; ?>&menuTopItem=<?php echo $_REQUEST['menuTopItem']; ?>">Delete</a> | <a href="view_vendors_edit.php?vendor_id=<?php echo $row_rsVendorDeleted['vendor_id']; ?>&plan_id=<?php echo $row_rsVendorDeleted['plan_id']; ?>&employer_id=<?php echo $_COOKIE['employer']['employer_id']; ?>&menuTopItem=<?php echo $_REQUEST['menuTopItem']; ?>&contact_id=<?php echo $row_rsVendorDeleted['contact_id']; ?>">Edit</a> </td>
        </tr>
          <?php } while ($row_rsVendorDeleted = mysql_fetch_assoc($rsVendorDeleted)); ?>
        </table>
        <p> Records <?php echo ($startRow_rsVendorDeleted + 1) ?> to <?php echo min($startRow_rsVendorDeleted + $maxRows_rsVendorDeleted, $totalRows_rsVendorDeleted) ?> of <?php echo $totalRows_rsVendorDeleted ?>
        <table border="0" width="50%" align="center">
          <tr>
            <td width="23%" align="center"><?php if ($pageNum_rsVendorDeleted > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_rsVendorDeleted=%d%s", $currentPage, 0, $queryString_rsVendorDeleted); ?>">First</a>
                <?php } // Show if not first page ?>
            </td>
            <td width="31%" align="center"><?php if ($pageNum_rsVendorDeleted > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_rsVendorDeleted=%d%s", $currentPage, max(0, $pageNum_rsVendorDeleted - 1), $queryString_rsVendorDeleted); ?>">Previous</a>
                <?php } // Show if not first page ?>
            </td>
            <td width="23%" align="center"><?php if ($pageNum_rsVendorDeleted < $totalPages_rsVendorDeleted) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_rsVendorDeleted=%d%s", $currentPage, min($totalPages_rsVendorDeleted, $pageNum_rsVendorDeleted + 1), $queryString_rsVendorDeleted); ?>">Next</a>
                <?php } // Show if not last page ?>
            </td>
            <td width="23%" align="center"><?php if ($pageNum_rsVendorDeleted < $totalPages_rsVendorDeleted) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_rsVendorDeleted=%d%s", $currentPage, $totalPages_rsVendorDeleted, $queryString_rsVendorDeleted); ?>">Last</a>
                <?php } // Show if not last page ?>
            </td>
          </tr>
        </table>
        <?php } // Show if recordset not empty ?>
        <?php if ($totalRows_rsVendorDeleted == 0) { // Show if recordset empty ?>
        <p>No Inactive <?php echo DISPLAYPLANNAME; ?> in list. </p>
        <?php } // Show if recordset empty ?>
        </p></td>
	</tr>
</table>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsVendorDeleted);

mysql_free_result($rsVendorActive);
?>
