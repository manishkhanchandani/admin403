<?php require_once('../Connections/dw_conn.php'); ?>
<?php
$colname_rsDocs = "-1";
if (isset($_GET['vendor_id'])) {
  $colname_rsDocs = (get_magic_quotes_gpc()) ? $_GET['vendor_id'] : addslashes($_GET['vendor_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsDocs = sprintf("SELECT * FROM vendor_documents WHERE vendor_id = %s", $colname_rsDocs);
$rsDocs = mysql_query($query_rsDocs, $dw_conn) or die(mysql_error());
$row_rsDocs = mysql_fetch_assoc($rsDocs);
$totalRows_rsDocs = mysql_num_rows($rsDocs);
?>
<p><strong>DOCUMENTS:</strong></p>
<?php if($totalRows_rsDocs>0) { ?>
<ol>
<?php do { ?>
  <li><a href="../vendor/files/<?php echo $row_rsDocs['filename']; ?>" target="_blank"><?php echo $row_rsDocs['display']; ?></a></li>
<?php } while ($row_rsDocs = mysql_fetch_assoc($rsDocs)); ?>
</ol>
<?php } else { ?>
No Document Found.
<?php } ?>
<?php
mysql_free_result($rsDocs);
?>