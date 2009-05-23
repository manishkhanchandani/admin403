<?php require_once('../Connections/dw_conn.php'); ?>
<?php
include_once('start.php');
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

$colname_rsEmployee = "-1";
if (isset($_GET['employer_id'])) {
  $colname_rsEmployee = $_GET['employer_id'];
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployee = sprintf("SELECT * FROM employee WHERE employer_id = %s", GetSQLValueString($colname_rsEmployee, "int"));
$rsEmployee = mysql_query($query_rsEmployee, $dw_conn) or die(mysql_error());
$row_rsEmployee = mysql_fetch_assoc($rsEmployee);
$totalRows_rsEmployee = mysql_num_rows($rsEmployee);

if ((isset($_GET['employer_id'])) && ($_GET['employer_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM employer_vendor WHERE employer_id=%s",
                       GetSQLValueString($_GET['employer_id'], "int"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($deleteSQL, $dw_conn) or die(mysql_error());
}

if ((isset($_GET['employer_id'])) && ($_GET['employer_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM employee_contribution WHERE employer_id=%s",
                       GetSQLValueString($_GET['employer_id'], "int"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($deleteSQL, $dw_conn) or die(mysql_error());
}

if ((isset($_GET['employer_id'])) && ($_GET['employer_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM employer WHERE employer_id=%s",
                       GetSQLValueString($_GET['employer_id'], "int"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($deleteSQL, $dw_conn) or die(mysql_error());
}

if ((isset($_GET['employer_id'])) && ($_GET['employer_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM workflow_employer_list WHERE employer_id=%s",
                       GetSQLValueString($_GET['employer_id'], "int"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($deleteSQL, $dw_conn) or die(mysql_error());
}
do {
$eid = $row_rsEmployee['employee_id'];
if ((isset($eid)) && ($eid != "")) {
  $deleteSQL = sprintf("DELETE FROM employee_history WHERE employee_id=%s",
                       GetSQLValueString($eid, "int"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($deleteSQL, $dw_conn) or die(mysql_error());
}

if ((isset($eid)) && ($eid != "")) {
  $deleteSQL = sprintf("DELETE FROM employee_vendor WHERE employee_id=%s",
                       GetSQLValueString($eid, "int"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($deleteSQL, $dw_conn) or die(mysql_error());
}
} while ($row_rsEmployee = mysql_fetch_assoc($rsEmployee));
header("Location: ".$_SERVER['HTTP_REFERER']);
exit;
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
mysql_free_result($rsEmployee);
?>
