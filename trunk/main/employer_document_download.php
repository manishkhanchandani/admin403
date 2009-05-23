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
   	$len = $row_rsFile['size'];
	$filename = urlencode($row_rsFile['real_filename']);
    $file_extension = $row_rsFile['extension'];

    //This will set the Content-Type to the appropriate setting for the file

    switch( $file_extension ) {
      case "pdf": $ctype="application/pdf"; break;
      case "exe": $ctype="application/octet-stream"; break;
      case "zip": $ctype="application/zip"; break;
      case "doc": $ctype="application/msword"; break;
      case "xls": $ctype="application/vnd.ms-excel"; break;
      case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
      case "gif": $ctype="image/gif"; break;
      case "png": $ctype="image/png"; break;
      case "jpeg":
      case "jpg": $ctype="image/jpg"; break;
      case "mp3": $ctype="audio/mpeg"; break;
      case "wav": $ctype="audio/x-wav"; break;
      case "mpeg":
      case "mpg":
      case "mpe": $ctype="video/mpeg"; break;
      case "mov": $ctype="video/quicktime"; break;
      case "avi": $ctype="video/x-msvideo"; break;
      case "txt": $ctype="text/html"; break;
      //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
      case "php":
      case "htm":
      case "html": die("<b>Cannot be used for ". $file_extension ." files!</b>"); break;
      default: $ctype="application/force-download";
    }
    //Begin writing headers
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    //Use the switch-generated Content-Type
    header("Content-Type: $ctype");
    //Force the download
    $header="Content-Disposition: attachment; filename=".$filename.";";
    header($header );
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".$len);
    @readfile($file);
    exit;

mysql_free_result($rsFile);
?>