<?php require_once('../Connections/dw_conn.php'); ?>
<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

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

if ((isset($_GET['plan_id'])) && ($_GET['plan_id'] != "") && (isset($_GET['employer_id'])) && ($_GET['employer_id'] != "") && (isset($_GET['vendor_id'])) && ($_GET['vendor_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM employer_vendors_contact WHERE plan_id=%s and employer_id=%s and vendor_id=%s and employer_id=%s",
                       GetSQLValueString($_GET['plan_id'], "int"),
                       GetSQLValueString($_GET['employer_id'], "int"),
                       GetSQLValueString($_GET['vendor_id'], "int"),
                       GetSQLValueString($_COOKIE['employer']['employer_id'], "int"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($deleteSQL, $dw_conn) or die(mysql_error());
}

if ((isset($_GET['plan_id'])) && ($_GET['plan_id'] != "") && (isset($_GET['employer_id'])) && ($_GET['employer_id'] != "") && (isset($_GET['vendor_id'])) && ($_GET['vendor_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM employer_vendor_deleted WHERE plan_id=%s and employer_id=%s and vendor_id=%s and employer_id=%s",
                       GetSQLValueString($_GET['plan_id'], "int"),
                       GetSQLValueString($_GET['employer_id'], "int"),
                       GetSQLValueString($_GET['vendor_id'], "int"),
                       GetSQLValueString($_COOKIE['employer']['employer_id'], "int"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($deleteSQL, $dw_conn) or die(mysql_error());
}


  $deleteGoTo = "view_vendors.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
</head>

<body>

</body>
</html>
