<?php require_once('../Connections/dw_conn.php'); ?>
<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

$colname_rsFile = "-1";
if (isset($_GET['docu_id'])) {
  $colname_rsFile = (get_magic_quotes_gpc()) ? $_GET['docu_id'] : addslashes($_GET['docu_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsFile = sprintf("SELECT * FROM employer_documents WHERE docu_id = %s", $colname_rsFile);
$rsFile = mysql_query($query_rsFile, $dw_conn) or die(mysql_error());
$row_rsFile = mysql_fetch_assoc($rsFile);
$totalRows_rsFile = mysql_num_rows($rsFile);

	/*$file = $_GET['file'];
   	$len = filesize($file);
	$filename = $_GET['filename'];
    $file_extension = strtolower(substr(strrchr($filename,"."),1));*/
	$file = "../main/files/".$row_rsFile['filename'];
	if(file_exists($file) && !is_dir($file)) {
		unlink($file);
	}
	mysql_query("delete from employer_documents where docu_id = '".$_GET['docu_id']."'") or die('error'); 
	header("Location: ".$_SERVER['HTTP_REFERER']);
	exit;

mysql_free_result($rsFile);
?>