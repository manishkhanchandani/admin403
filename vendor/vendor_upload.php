<?php require_once('../Connections/dw_conn.php'); ?>
<?php
if($_POST['MM_Insert']==1 && $_FILES['userfile']['name']) {
	if($_POST['display']) {
		require_once('HTTP/Upload.php');
		$upload = new HTTP_Upload("en");
		$files = $upload->getFiles();
		foreach($files as $file){		
			if (PEAR::isError($file)) {
				echo $file->getMessage();
			}
			if ($file->isValid()) {
				$file->setName("uniq");
				if(!is_dir("files")) {
					mkdir("files", 0777);
					chmod("files", 0777);
				}
				$dest_name = $file->moveTo("../vendor/files");
				if (PEAR::isError($dest_name)) {
					echo $dest_name->getMessage();
				}
				$real = $file->getProp("real");
				$ext = $file->getProp("ext");
				$size = $file->getProp("size");
				$filetype = $file->getProp("type");
				$name = $file->getProp("name");
				$sql = "insert into vendor_documents set vendor_id = '".$_POST['vendor_id']."', filename = '".addslashes(stripslashes(trim($name)))."', real_filename = '".addslashes(stripslashes(trim($real)))."', display = '".addslashes(stripslashes(trim($_POST['display'])))."', comments = '".addslashes(stripslashes(trim($_POST['comments'])))."', extension = '".addslashes(stripslashes(trim($ext)))."', size = '".addslashes(stripslashes(trim($size)))."', filetype = '".addslashes(stripslashes(trim($filetype)))."', upload_dt = '".time()."'"; 
				mysql_query($sql) or die('error in uploading');
			} elseif ($file->isMissing()) {
				echo "No file was provided.";
			} elseif ($file->isError()) {
				echo $file->errorMsg();
			}
		}
	} else {
		$error = "Please choose file name.";
	}
}
?>
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

$colname_rsDocu = "-1";
if (isset($_COOKIE['vendor']['vendor_id'])) {
  $colname_rsDocu = (get_magic_quotes_gpc()) ? $_COOKIE['vendor']['vendor_id'] : addslashes($_COOKIE['vendor']['vendor_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsDocu = sprintf("SELECT * FROM vendor_documents WHERE vendor_id = %s", $colname_rsDocu);
$rsDocu = mysql_query($query_rsDocu, $dw_conn) or die(mysql_error());
$row_rsDocu = mysql_fetch_assoc($rsDocu);
$totalRows_rsDocu = mysql_num_rows($rsDocu);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Upload Plan Documents</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Upload Documents for Vendor <?php echo $_COOKIE['vendor']['name']; ?></font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
<?php echo $error; ?>
		<table border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
			<tr>
				<td class="thc2">File:</td>
				<td class="tdc2"><input type="file" name="userfile" id="userfile" /></td>
			</tr>
			<tr>
              <td class="thc2">File Name: </td>
			  <td class="tdc2"><input name="display" type="text" id="display" />              </td>
		  </tr>
			<tr>
				<td class="thc2">Comments:</td>
				<td class="tdc2"><textarea name="comments" id="comments" cols="45" rows="5"></textarea></td>
			</tr><tr>
			 <td class="thc2">&nbsp;</td>
			 <td class="tdc2"><input type="submit" name="button" id="button" value="Upload Document" />
    <input name="MM_Insert" type="hidden" id="MM_Insert" value="1" />
    <input name="vendor_id" type="hidden" id="vendor_id" value="<?php echo $_COOKIE['vendor']['vendor_id']; ?>" /><input name="menuTopItem" type="hidden" id="menuTopItem" value="4" />
			</td>
			</tr>
		</table>
</form>
      </td>
    </tr>
</table>
<br />

<?php if ($totalRows_rsDocu > 0) { // Show if recordset not empty ?>
<table width="100%" border="6" cellspacing="0" cellpadding="3" class="blacktbl">
    <tr valign="bottom" >
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Uploaded Documents</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
    <tr>
      <td class="thcview2"><strong>Filename</strong></td>
      <td class="thcview2"><strong>Upload Date</strong></td>
      <td class="thcview2"><strong>Comments</strong></td>
      <td class="thcview2"><strong>Download</strong></td>
      <td class="thcview2"><strong>Delete</strong></td>
    </tr>
    <?php do { ?>
      <tr>
        <td class="tdcview2"><?php echo $row_rsDocu['display']; ?></td>
        <td class="tdcview2"><?php echo date('j M, Y', $row_rsDocu['upload_dt']); ?></td>
        <td class="tdcview2"><?php echo $row_rsDocu['comments']; ?></td>
        <td class="tdcview2"><a href="vendor_download.php?docu_id=<?php echo $row_rsDocu['docu_id']; ?>&menuTopItem=4">Download</a></td>
        <td class="tdcview2"><a href="vendor_delete_document.php?docu_id=<?php echo $row_rsDocu['docu_id']; ?>&menuTopItem=4">Delete</a></td>
      </tr>
      <?php } while ($row_rsDocu = mysql_fetch_assoc($rsDocu)); ?>
  </table>
      </td>
    </tr>
</table>
<br />
<?php } // Show if recordset not empty ?>
<p>&nbsp;</p>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsDocu);
?>