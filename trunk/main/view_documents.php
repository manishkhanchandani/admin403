<?php require_once('../Connections/dw_conn.php'); ?>
<?php
$colname_rsDocu = "-1";
if (isset($_GET['id'])) {
  $colname_rsDocu = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsDocu = sprintf("SELECT * FROM workflow_documents WHERE id = %s", $colname_rsDocu);
$rsDocu = mysql_query($query_rsDocu, $dw_conn) or die(mysql_error());
$row_rsDocu = mysql_fetch_assoc($rsDocu);
$totalRows_rsDocu = mysql_num_rows($rsDocu);
?>
<?php if ($totalRows_rsDocu > 0) { // Show if recordset not empty ?>
<ol>
<?php do { ?>
<li><a href="../workflow/files/<?php echo $row_rsDocu['filename']; ?>" target="_blank">
<?php if($row_rsDocu['display']) echo $row_rsDocu['display']; else echo $row_rsDocu['real_filename']; ?>
</a></li>
<?php } while ($row_rsDocu = mysql_fetch_assoc($rsDocu)); ?>
</ol>
<?php } // Show if recordset not empty ?>
<?php if ($totalRows_rsDocu == 0) { // Show if recordset empty ?>
<p>No Document found. </p>
<?php } // Show if recordset empty ?>
<?php
mysql_free_result($rsDocu);
?>