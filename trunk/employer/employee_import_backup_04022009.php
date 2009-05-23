<?php require_once('../Connections/dw_conn.php'); ?>
<?php
include_once('../main/functions.php');
if($_POST['MM_Insert']==1) {
	$postarray = $_POST;
	if($_FILES['userfile']['name']) {
		$ext = substr(strrchr($_FILES['userfile']['name'],'.'),1);
		if($ext=="xls") {
			require_once 'Excel/reader.php';
			$data = new Spreadsheet_Excel_Reader();
			$data->setOutputEncoding('CP1251');
			$data->read($_FILES['userfile']['tmp_name']);
			for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
				for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
					if($i==1) {
						$array['header'][$j] = trim(strtolower($data->sheets[0]['cells'][$i][$j]));
					} else {
						$array['details'][$i][$array['header'][$j]] = $data->sheets[0]['cells'][$i][$j];
						$array['detailIds'][$i][$j] = $data->sheets[0]['cells'][$i][$j];
					}
				}		
			}
		} else if($ext=="csv") {
			$file = file_get_contents($_FILES['userfile']['tmp_name']);
			$rows = explode("\n", $file);
			if($rows) {
				foreach($rows as $i=>$row) {
					$i2 = $i+1;
					if(!$row) continue;
					$cols = explode("\t", $row);
					if($cols) {
						foreach($cols as $j=>$col) {
							$j2 = $j+1;
							if($i==0) {
								$array['header'][$j2] = trim(strtolower($col));
							} else {
								$array['details'][$i2][$array['header'][$j2]] = $col;
								$array['detailIds'][$i2][$j2] = $col;							
							}
						}
					}
				}
			}
		}
		$total = count($array['detailIds']);
		if($array['detailIds']) {
			foreach($array['detailIds'] as $i => $value) {			
				$fn = $array['detailIds'][$i][1];	
				$mn = $array['detailIds'][$i][2];	
				$ln = $array['detailIds'][$i][3];	
				$s = $array['detailIds'][$i][4];	
				$a = $array['detailIds'][$i][5];		
				$e = $array['detailIds'][$i][6];	
				$p = $array['detailIds'][$i][7];	
				$d = $array['detailIds'][$i][8];	
				$h = $array['detailIds'][$i][9];	
				$ph = $array['detailIds'][$i][10];
				$v = $array['detailIds'][$i][11];
				$pl = $array['detailIds'][$i][12];
				if(isValidSSN($s)) {
					
				} else {
					$failureSSN[$i] = $e;
					$error = 1;
				}
				//if(validateEmail($e)) {
					
				//} else {
					//$failureEmail[$i] = $e;
					//$error = 1;
				//}
				if($error!=1) {
					$sql = "select * from employee where ssn = '".addslashes(stripslashes(trim($encryption->processEncrypt('ssn', $s))))."'";
					$result = mysql_query($sql);
					$num = mysql_num_rows($result);
					if($num==0) {					
						// added 
						$sql = "select * from employer_vendor where employer_id = '".$postarray['employer_id']."'"; 
						$rsEmployer = mysql_query($sql) or die('error'); 
						if(mysql_num_rows($rsEmployer)) {
							
						} else {
							$failureEmployer[$i] = $e;
							continue;
						}
						// added
						
						$dob1 = explode('/',$d);
						$dob = $dob1[2]."-".$dob1[0]."-".$dob1[1];
						
						$sd1 = explode('/',$h);
						$sd = $sd1[2]."-".$sd1[0]."-".$sd1[1];
						
						$ed1 = explode('/',$array['detailIds'][$i][11]);
						$ed = $ed1[2]."-".$ed1[0]."-".$ed1[1];
						
						if($e && $p) {
							$sql = "insert into employee set email = '".addslashes(stripslashes(trim($e)))."', password = '".addslashes(stripslashes(trim(md5($p))))."', ssn = '".addslashes(stripslashes(trim($encryption->processEncrypt('ssn', $s))))."', firstname = '".addslashes(stripslashes(trim($fn)))."', phone = '".addslashes(stripslashes(trim($p)))."',  dob = '".addslashes(stripslashes(trim($encryption->processEncrypt('dob', $dob))))."', hire_date = '".addslashes(stripslashes(trim($sd)))."', middlename = '".addslashes(stripslashes(trim($mn)))."', lastname = '".addslashes(stripslashes(trim($ln)))."', account_number = '".addslashes(stripslashes(trim($encryption->processEncrypt('account_number', $a))))."', employer_id = '".$postarray['employer_id']."', created_dt = '".time()."'";
							$result = mysql_query($sql);									
							$eid = mysql_insert_id();
							$sql = "INSERT INTO `users` (`email` , `password` , `created_dt` , `login_type` , `acting_as`, `id` ) VALUES ('".addslashes(stripslashes(trim($e)))."', '".addslashes(stripslashes(trim(md5($p))))."', '".date('Y-m-d H:i:s')."', 'Employee', NULL, '".$eid."')";
							$rs = mysql_query($sql) or die('could not insert in users table'.mysql_error());
						} else {							
							$sql = "insert into employee set ssn = '".addslashes(stripslashes(trim($encryption->processEncrypt('ssn', $s))))."', firstname = '".addslashes(stripslashes(trim($fn)))."', phone = '".addslashes(stripslashes(trim($p)))."',  dob = '".addslashes(stripslashes(trim($encryption->processEncrypt('dob', $dob))))."', hire_date = '".addslashes(stripslashes(trim($sd)))."', middlename = '".addslashes(stripslashes(trim($mn)))."', lastname = '".addslashes(stripslashes(trim($ln)))."', account_number = '".addslashes(stripslashes(trim($encryption->processEncrypt('account_number', $a))))."', employer_id = '".$postarray['employer_id']."', created_dt = '".time()."'";
							$result = mysql_query($sql);									
							$eid = mysql_insert_id();
							$e = $s;
						}
						$vid = getVendorIdByName($v, $postarray['employer_id']);
						$pid = getPlanIdByName($pl, $vid);
						if($pid) {
							setEmployeeVendor($eid, $pid, $vid);
						}
						$success[$i] = $e;	
					} else {
						$failure[$i] = $e;	
					}
				}
			}
		}		
		$successtotal = count($success);
		$errorMessage = "$successtotal of $total records <b>were successfully imported.</b>";
		$errorMessage .= "<br>";
		if($success) {
			$errorMessage .= implode(', ',$success);
			$errorMessage .= " <b>emails were successfully imported.</b>";
			$errorMessage .= "<br>";
		} else {
			$errorMessage .= '';
		}
		if($failureSSN) {
			$errorMessage .= implode(', ',$failureSSN);
			$errorMessage .= " <b>failed due to invalid SSN.</b>";
			$errorMessage .= "<br>";
		} else {
			$errorMessage .= '';
		}
		if($failureEmail) {
			$errorMessage .= implode(', ',$failureEmail);
			$errorMessage .= " <b>failed due to invalid Email.</b>";
			$errorMessage .= "<br>";
		} else {
			$errorMessage .= '';
		}
		if($failure) {
			$errorMessage .= implode(', ',$failure);
			$errorMessage .= " <b>failed due to employee already in database.</b>";
			$errorMessage .= "<br>";
		} else {
			$errorMessage .= '';
		}
		// added
		if($failureEmployer) {
			$errorMessage .= implode(', ',$failureEmployer);
			$errorMessage .= " <b>failed due to employer not having any vendor and plan selected.</b>";
			$errorMessage .= "<br>";
		} else {
			$errorMessage .= '';
		}
		// added
	} else {
		$errorMessage = '<p class=error>No File selected.</p>';
	}
}
?>
<?php
mysql_select_db($database_dw_conn, $dw_conn);
$query_rsEmployer = "SELECT employer_id, name, email FROM employer ORDER BY name ASC";
$rsEmployer = mysql_query($query_rsEmployer, $dw_conn) or die(mysql_error());
$row_rsEmployer = mysql_fetch_assoc($rsEmployer);
$totalRows_rsEmployer = mysql_num_rows($rsEmployer);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/dw.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php include('beginning.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Import Employee</title>
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
        <td colspan="2" class="blackth"><font color="#ffffff" face="Tahoma" size="2">Import Employee List</font></td>
    </tr>
    <tr valign="top" >
      <td colspan="2" class="blacktd">
<?php echo $errorMessage; ?><br />
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <table border="6" cellspacing="0" cellpadding="5" style="border-style:solid" bordercolor="#999999" class="tbl">
    <tr>
      <td align="right" class="thc2">Employee List File </td>
      <td class="tdc2"><input type="file" name="userfile" /></td>
    </tr>
    <tr>
      <td align="right" class="thc2">&nbsp;</td>
      <td class="tdc2"><input type="submit" name="Submit" value="Upload" />
        <input name="MM_Insert" type="hidden" id="MM_Insert" value="1" />
        <input name="menuTopItem" type="hidden" id="menuTopItem" value="7" />
        <input name="employer_id" type="hidden" id="employer_id" value="<?php echo $_COOKIE['employer']['employer_id']; ?>" /></td>
    </tr>
    <tr>
      <td align="right" class="thc2">&nbsp;</td>
      <td class="tdc2"><a href="../main/employeelist.xls" target="_blank">Sample File</a> | <a href="../main/employeelist.csv">Sample CSV File (Tab Seperated)</a></td>
    </tr>
  </table>
</form>
      </td>
    </tr>
</table>
<br />
<p>&nbsp; </p>
<!-- InstanceEndEditable -->
<?php include('foot.php'); ?>
<?php include('end.php'); ?>
</body><!-- InstanceEnd --></html>
<?php
mysql_free_result($rsEmployer);
?>
