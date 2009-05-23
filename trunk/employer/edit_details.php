<?php require_once('../Connections/dw_conn.php'); ?>
<?php
if($_POST['MM_Insert']==2 && $_FILES['userfile']['name']) {
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
				if(!is_dir("../main/files")) {
					mkdir("../main/files", 0777);
					chmod("../main/files", 0777);
				} 
				$dest_name = $file->moveTo("../main/files");
				if (PEAR::isError($dest_name)) {
					echo $dest_name->getMessage();
				}
				$real = $file->getProp("real");
				$ext = $file->getProp("ext");
				$size = $file->getProp("size");
				$filetype = $file->getProp("type");
				$name = $file->getProp("name");
				$sql = "insert into employer_documents set employer_id = '".$_POST['employer_id']."', filename = '".addslashes(stripslashes(trim($name)))."', real_filename = '".addslashes(stripslashes(trim($real)))."', display = '".addslashes(stripslashes(trim($_POST['display'])))."', comments = '".addslashes(stripslashes(trim($_POST['comments'])))."', extension = '".addslashes(stripslashes(trim($ext)))."', size = '".addslashes(stripslashes(trim($size)))."', filetype = '".addslashes(stripslashes(trim($filetype)))."', upload_dt = '".time()."'"; 
				mysql_query($sql) or die('error in uploading');
				$error = 'Document uploaded Successfully';
			} elseif ($file->isMissing()) {
				$error = "No file was provided.";
			} elseif ($file->isError()) {
				$error = $file->errorMsg();
			}
		}
	} else {
		$error = "Please choose file name.";
	}
}
?>
<?php
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "frmEmployer")) {
	if($_POST['service_provision']!="Y") {
		$_POST['service_eligible_limit'] = '';
	}
}
?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "frmEmployer")) {
  $updateSQL = sprintf("UPDATE employer SET name=%s, phone=%s, address=%s, loan_provision=%s, service_provision=%s, hardship_provision=%s, exchanges=%s, transfers_in=%s, transfers_out=%s, roth_provision=%s, service_eligible_limit=%s WHERE employer_id=%s",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['phone'], "text"),
                       GetSQLValueString($_POST['address'], "text"),
                       GetSQLValueString(isset($_POST['loan_provision']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['service_provision']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['hardship_provision']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['exchanges']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['transfers_in']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['transfers_out']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['roth_provision']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString($_POST['service_eligible_limit'], "double"),
                       GetSQLValueString($_POST['employer_id'], "int"));

  mysql_select_db($database_dw_conn, $dw_conn);
  $Result1 = mysql_query($updateSQL, $dw_conn) or die(mysql_error());
}

$colname_rsEditEmployer = "-1";
if (isset($_COOKIE['employer']['employer_id'])) {
  $colname_rsEditEmployer = (get_magic_quotes_gpc()) ? $_COOKIE['employer']['employer_id'] : addslashes($_COOKIE['employer']['employer_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEditEmployer = sprintf("SELECT * FROM employer WHERE employer_id = %s", $colname_rsEditEmployer);
$rsEditEmployer = mysql_query($query_rsEditEmployer, $dw_conn) or die(mysql_error());
$row_rsEditEmployer = mysql_fetch_assoc($rsEditEmployer);
$totalRows_rsEditEmployer = mysql_num_rows($rsEditEmployer);

$colname_rsDocuments = "-1";
if (isset($row_rsEditEmployer['employer_id'])) {
  $colname_rsDocuments = (get_magic_quotes_gpc()) ? $row_rsEditEmployer['employer_id'] : addslashes($row_rsEditEmployer['employer_id']);
}
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsDocuments = sprintf("SELECT * FROM employer_documents WHERE employer_id = %s", $colname_rsDocuments);
$rsDocuments = mysql_query($query_rsDocuments, $dw_conn) or die(mysql_error());
$row_rsDocuments = mysql_fetch_assoc($rsDocuments);
$totalRows_rsDocuments = mysql_num_rows($rsDocuments);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Edit Employer</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Edit Employer</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
	  
	  
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr valign="top">
    <td>
	<!-- Add form to edit employer -->
	
	
	
<form action="<?php echo $editFormAction; ?>" method="POST" name="frmEmployer">
  <table border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
    <tr valign="baseline">
      <td align="right" nowrap class="thc2">Email:</td>
      <td class="tdc2"><?php echo $row_rsEditEmployer['email']; ?></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap class="thc2">Name:</td>
      <td class="tdc2"><input type="text" name="name" value="<?php echo $row_rsEditEmployer['name']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap class="thc2">Phone:</td>
      <td class="tdc2"><input type="text" name="phone" value="<?php echo $row_rsEditEmployer['phone']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="top" nowrap class="thc2">Address:</td>
      <td class="tdc2"><textarea name="address" cols="50" rows="5"><?php echo $row_rsEditEmployer['address']; ?></textarea>      </td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="thc2">Loan Provision:</td>
      <td class="tdc2"><input <?php if (!(strcmp($row_rsEditEmployer['loan_provision'],"Y"))) {echo "checked=\"checked\"";} ?> type="checkbox" name="loan_provision" value="Y" ></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="thc2">Service Provision:</td>
      <td class="tdc2"><input <?php if (!(strcmp($row_rsEditEmployer['service_provision'],"Y"))) {echo "checked=\"checked\"";} ?> type="checkbox" name="service_provision" value="Y" onclick="if(document.frmEmployer.service_provision.checked==true) toggleLayer('divServiceEligibleLimit',1); else toggleLayer('divServiceEligibleLimit',0);" ></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="thc2">&nbsp;</td>
      <td class="tdc2"><div id="divServiceEligibleLimit" style="display:<?php if (!(strcmp($row_rsEditEmployer['service_provision'],"Y"))) echo ''; else echo 'none' ?>;">Service Eligible Limit: 
      <input name="service_eligible_limit" type="text" id="service_eligible_limit" value="<?php echo $row_rsEditEmployer['service_eligible_limit']; ?>" size="5" />
      </div></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="thc2">Hardship Provision:</td>
      <td class="tdc2"><input <?php if (!(strcmp($row_rsEditEmployer['hardship_provision'],"Y"))) {echo "checked=\"checked\"";} ?> type="checkbox" name="hardship_provision" value="Y" ></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="thc2">Exchanges:</td>
      <td class="tdc2"><input <?php if (!(strcmp($row_rsEditEmployer['exchanges'],"Y"))) {echo "checked=\"checked\"";} ?> type="checkbox" name="exchanges" value="Y" ></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="thc2">Transfers In:</td>
      <td class="tdc2"><input <?php if (!(strcmp($row_rsEditEmployer['transfers_in'],"Y"))) {echo "checked=\"checked\"";} ?> type="checkbox" name="transfers_in" value="Y" ></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="thc2">Transfers Out:</td>
      <td class="tdc2"><input <?php if (!(strcmp($row_rsEditEmployer['transfers_out'],"Y"))) {echo "checked=\"checked\"";} ?> type="checkbox" name="transfers_out" value="Y" ></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="thc2">Roth Provision:</td>
      <td class="tdc2"><input name="roth_provision" type="checkbox" value="Y" <?php if (!(strcmp($row_rsEditEmployer['roth_provision'],"Y"))) {echo "checked=\"checked\"";} ?> ></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap class="thc2">&nbsp;</td>
      <td class="tdc2"><input type="submit" value="Update">
      <input name="employer_id" type="hidden" id="employer_id" value="<?php echo $row_rsEditEmployer['employer_id']; ?>" />
      <input name="modified_dt" type="hidden" id="modified_dt" value="<?php echo time(); ?>" /><input name="menuTopItem" type="hidden" id="menuTopItem" value="3" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="frmEmployer" />
</form>

	<!-- Add end form to edit employer -->
	</td>
	<td width="5%">
	</td>
    <td width="100%" align="center">
	<!-- Add upload documents here -->
	<table width="100%"  border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td><h3>Upload Document </h3>
          <form action="" method="post" enctype="multipart/form-data" name="formUpload" id="formUpload">
<?php echo $error; ?>
		<table border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
			<tr>
				<td class="thc2"  align="left">File:</td>
				<td class="tdc2"  align="left"><input type="file" name="userfile" id="userfile" /></td>
			</tr>
			<tr>
              <td class="thc2"  align="left">File Name: </td>
			  <td class="tdc2"  align="left"><input name="display" type="text" id="display" size="48" />              </td>
		  </tr>
			<tr>
				<td valign="top" class="thc2"  align="left">Description:</td>
				<td class="tdc2"  align="left"><textarea name="comments" id="comments" cols="45" rows="5"></textarea></td>
			</tr><tr>
			 <td class="thc2">&nbsp;</td>
			 <td class="tdc2"><input type="submit" name="button" id="button" value="Upload Document" />
			   <input name="MM_Insert" type="hidden" id="MM_Insert" value="2" />
			   <input name="employer_id" type="hidden" id="employer_id" value="<?php echo $row_rsEditEmployer['employer_id']; ?>" />
			<input name="menuTopItem" type="hidden" id="menuTopItem" value="<?php echo $_REQUEST['menuTopItem']; ?>" />			</td>
			</tr>
		</table>
		</form>  
		  
		</td>
      </tr>
      <tr>
        <td><?php if ($totalRows_rsDocuments > 0) { // Show if recordset not empty ?>
        <p><strong>List of Uploaded Documents </strong></p>
	  	<table width="100%" border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
			<tr>
			  	<td valign="top" class="thcview2"><strong>File Name </strong></td>
				<td valign="top" class="thcview2"><strong>Comments</strong></td>
				<td valign="top" class="thcview2"><strong>Uploaded Date </strong></td>
				<td valign="top" class="thcview2"><strong>View </strong></td>
				<td valign="top" class="thcview2"><strong>Download</strong></td>
			    <td valign="top" class="thcview2"><strong>Delete</strong></td>
			</tr>
			  <?php do { ?>
			  <tr>
			  	<td valign="top" class="tdcview2"><?php echo $row_rsDocuments['display']; ?></td>
				<td valign="top" class="tdcview2"><?php echo $row_rsDocuments['comments']; ?></td>
				<td valign="top" class="tdcview2"><?php echo date('d M, Y', $row_rsDocuments['upload_dt']); ?></td>
				<td valign="top" class="tdcview2"><a href="../main/files/<?php echo $row_rsDocuments['filename']; ?>" target="_blank">View</a> </td>
				<td valign="top" class="tdcview2"><a href="../main/employer_document_download.php?docu_id=<?php echo $row_rsDocuments['docu_id']; ?>">Download</a></td>
			    <td valign="top" class="tdcview2"><a href="../main/employer_document_delete.php?docu_id=<?php echo $row_rsDocuments['docu_id']; ?>">Delete</a></td>
			  </tr>
			  <?php } while ($row_rsDocuments = mysql_fetch_assoc($rsDocuments)); ?>
		</table>
		<?php } // Show if recordset not empty ?></td>
      </tr>
    </table>
	<!-- End upload Documents here -->

	</td>
  </tr>
</table>


      </td>
    </tr>
</table>
<br />

<p>&nbsp; </p>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsEditEmployer);

mysql_free_result($rsDocuments);
?>
