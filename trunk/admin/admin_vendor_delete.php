<?php require_once('../Connections/dw_conn.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

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
}

$colname_rsFile = "-1";
if (isset($_GET['vendor_id'])) {
  $colname_rsFile = $_GET['vendor_id'];
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsFile = sprintf("SELECT * FROM vendor_documents WHERE vendor_id = %s", GetSQLValueString($colname_rsFile, "int"));
$rsFile = mysql_query($query_rsFile, $dw_conn) or die(mysql_error());
$row_rsFile = mysql_fetch_assoc($rsFile);
$totalRows_rsFile = mysql_num_rows($rsFile);

if($totalRows_rsFile) {
	do {
		$file = "../vendor/files/".$row_rsFile['filename'];
		$len = $row_rsFile['size'];
		$filename = urlencode($row_rsFile['real_filename']);
		$file_extension = $row_rsFile['extension'];
		if(file_exists($file)) unlink($file);
	} while ($row_rsFile = mysql_fetch_assoc($rsFile));
}

if ((isset($_GET['vendor_id'])) && ($_GET['vendor_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM employee_contribution WHERE vendor_id=%s",
                       GetSQLValueString($_GET['vendor_id'], "int"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($deleteSQL, $dw_conn) or die(mysql_error());
}

if ((isset($_GET['vendor_id'])) && ($_GET['vendor_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM employee_vendor WHERE vendor_id=%s",
                       GetSQLValueString($_GET['vendor_id'], "int"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($deleteSQL, $dw_conn) or die(mysql_error());
}

if ((isset($_GET['vendor_id'])) && ($_GET['vendor_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM employer_vendor WHERE vendor_id=%s",
                       GetSQLValueString($_GET['vendor_id'], "int"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($deleteSQL, $dw_conn) or die(mysql_error());
}

if ((isset($_GET['vendor_id'])) && ($_GET['vendor_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM vendor_plan WHERE vendor_id=%s",
                       GetSQLValueString($_GET['vendor_id'], "int"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($deleteSQL, $dw_conn) or die(mysql_error());
}

if ((isset($_GET['vendor_id'])) && ($_GET['vendor_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM vendor_documents WHERE vendor_id=%s",
                       GetSQLValueString($_GET['vendor_id'], "int"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($deleteSQL, $dw_conn) or die(mysql_error());
}

if ((isset($_GET['vendor_id'])) && ($_GET['vendor_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM vendor WHERE vendor_id=%s",
                       GetSQLValueString($_GET['vendor_id'], "int"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($deleteSQL, $dw_conn) or die(mysql_error());

  $deleteGoTo = "admin_vendor_list.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>

</body>
</html>
<?php
mysql_free_result($rsFile);
?>
