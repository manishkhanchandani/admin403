<?php require_once('../Connections/conn.php'); 
if($_REQUEST['db']) {
$database_conn = $_REQUEST['db'];
mysql_select_db($database_conn, $conn);
}
if(!function_exists("display_table_fields")) {
	function display_table_fields($table_name) {
		$rs = mysql_query("select * from ".$table_name);
		$i = 0;
		$register_main_arr = array();
		while ($i < mysql_num_fields($rs)) {
			$meta = mysql_fetch_field($rs, $i);
			$register_arr[] = $meta->name;
			$i++;
		}
		return $register_arr;
	}
}

if(!function_exists("tableEdit")) {
	function tableEdit($table_name,$pk,$postarray,$uid) {
		$register_arr = array();
		$register_arr = display_table_fields($table_name);
		$query = "update ".$table_name." set ";
		foreach($postarray as $key=>$value) {
			if(gettype($value)=="array") {
				$string = '|';
				foreach($value as $val) {
					if(strlen($val)>0) {
						$val = addslashes(stripslashes(trim($val)));
						$string .= $val.'|';
					}
				}
				$string = substr($string,0,-1);
				$string .= "|";
				if(in_array($key,$register_arr)) {
					$query .= "`".$key."` = '".$string."', ";
				}
			} else {
				if(in_array($key,$register_arr)) {
					$value = addslashes(stripslashes(trim($value)));
					$query .= "`".$key."` = '".$value."', ";
				}
			}
		}
		$query = substr($query,0,-2);
		$query .= " where ".$pk." = '".$uid."'";
		//echo $query."<br>";
		$result = mysql_query($query) or die('Error in line '.__LINE__.' of File '.__FILE__.': '.mysql_error());
		return $uid;
	}
}

if(!function_exists("tableInsert")) {
	function tableInsert($table_name,$pk,$postarray) {
		$register_arr = array();
		$register_arr = display_table_fields($table_name);
		$query = "insert into ".$table_name."(".$pk.")values(NULL)";
		//echo ".<br>".$query."<br>";
		$rs = mysql_query($query) or die('Error in line '.__LINE__.' of File '.__FILE__.': '.mysql_error());
		$uid = mysql_insert_id();
		$query = "update ".$table_name." set ";
		foreach($postarray as $key=>$value) {
			if(gettype($value)=="array") {
				$string = '';
				foreach($value as $val) {
					if(strlen($val)>0) {
						//$val = (!get_magic_quotes_gpc()) ? addslashes($val) : $val;
						$val = addslashes(stripslashes($val));
						$string .= $val.'|';
					}
				}
				$string = substr($string,0,-1);
				if(in_array($key,$register_arr)) {
					$query .= $key."='".$string."',";
				}
			} else {
				if(in_array($key,$register_arr)) {
					//$value = (!get_magic_quotes_gpc()) ? addslashes($value) : $value;
					$value = addslashes(stripslashes($value));
					$query .= $key."='".$value."',";
				}
			}
		}
		$query = substr($query,0,-1);
		$query .= " where ".$pk." = '".$uid."'";
		//echo ".<br>".$query."<br>";
		$result = mysql_query($query) or die('Error in line '.__LINE__.' of File '.__FILE__.': '.mysql_error());
		return $uid;
	}
}
?>
<?php 
if($_GET['del']==1) {
	$query = "delete from ".$_GET['table']." where ".$_GET['field']." = '".$_GET[$_GET['field']]."'";
	mysql_query($query) or die(mysql_error());
	header("Location: ".$_SERVER['PHP_SELF']."?db=".$_GET['db']."&table=".$_GET['table']."&pageNum_rsTable=".$_GET['pageNum_rsTable']);
	exit;
}
if($_POST) {
	tableEdit($_POST['table_name'],$_POST['pk'],$_POST,$_POST[$_POST['pk']]);
}
?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsTable = 10;
$pageNum_rsTable = 0;
if (isset($_GET['pageNum_rsTable'])) {
  $pageNum_rsTable = $_GET['pageNum_rsTable'];
}
$startRow_rsTable = $pageNum_rsTable * $maxRows_rsTable;

$colname_rsTable = "1";
if (isset($_GET['table'])) {
  $colname_rsTable = (get_magic_quotes_gpc()) ? $_GET['table'] : addslashes($_GET['table']);
}
mysql_select_db($database_conn, $conn);
$query_rsTable = sprintf("SELECT * FROM %s", $colname_rsTable);
$query_limit_rsTable = sprintf("%s LIMIT %d, %d", $query_rsTable, $startRow_rsTable, $maxRows_rsTable);
$rsTable = mysql_query($query_limit_rsTable, $conn) or die(mysql_error());
$row_rsTable = mysql_fetch_assoc($rsTable);

if (isset($_GET['totalRows_rsTable'])) {
  $totalRows_rsTable = $_GET['totalRows_rsTable'];
} else {
  $all_rsTable = mysql_query($query_rsTable);
  $totalRows_rsTable = mysql_num_rows($all_rsTable);
}
$totalPages_rsTable = ceil($totalRows_rsTable/$maxRows_rsTable)-1;

$queryString_rsTable = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsTable") == false && 
        stristr($param, "totalRows_rsTable") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsTable = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsTable = sprintf("&totalRows_rsTable=%d%s", $totalRows_rsTable, $queryString_rsTable);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Columns</title>
<link href="../main.css" rel="stylesheet" type="text/css">
</head>

<body>
<p><a href="index.php?db=<?php echo $_GET['db']; ?>">Back</a></p>
<table border="1" cellspacing="0" cellpadding="5">
  <tr>
    <th>blob&nbsp;</th>
    <th>max_length&nbsp;</th>
    <th>multiple_key&nbsp;</th>
    <th>name&nbsp;</th>
    <th>not_null&nbsp;</th>
    <th>numeric&nbsp;</th>
    <th>primary_key&nbsp;</th>
    <th>table&nbsp;</th>
    <th>type&nbsp;</th>
    <th>default&nbsp;</th>
    <th>unique_key&nbsp;</th>
    <th>unsigned&nbsp;</th>
    <th>zerofill&nbsp;</th>
  </tr>
<?php
$result = mysql_query('select * from '.$_GET['table']);
$i = 0;
while ($i < mysql_num_fields($result)) {
   $meta = mysql_fetch_field($result, $i);
   ?>
   <tr>
    <td><?php echo $meta->blob; ?>&nbsp;</td>
    <td><?php echo $meta->max_length; ?>&nbsp;</td>
    <td><?php echo $meta->multiple_key; ?>&nbsp;</td>
    <td><?php echo $meta->name; ?>&nbsp;</td>
    <td><?php echo $meta->not_null; ?>&nbsp;</td>
    <td><?php echo $meta->numeric; ?>&nbsp;</td>
    <td><?php echo $meta->primary_key; if($meta->primary_key==1) { $pid = $meta->name; } ?>&nbsp;</td>
    <td><?php echo $meta->table; ?>&nbsp;</td>
    <td><?php echo $meta->type; ?>&nbsp;</td>
    <td><?php echo $meta->default; ?>&nbsp;</td>
    <td><?php echo $meta->unique_key; ?>&nbsp;</td>
    <td><?php echo $meta->unsigned; ?>&nbsp;</td>
    <td><?php echo $meta->zerofill; ?>&nbsp;</td>
  </tr>
   <?php
   $i++;
}
?>
</table>
<br>
<?php echo "Total Number of Records: ".$totalRows_rsTable; ?>
<?php if($totalRows_rsTable>0) { ?>
<br>
Records <?php echo ($startRow_rsTable + 1) ?> to <?php echo min($startRow_rsTable + $maxRows_rsTable, $totalRows_rsTable) ?> of <?php echo $totalRows_rsTable ?><table border="0" width="50%" align="center">
  <tr>
    <td width="23%" align="center"><?php if ($pageNum_rsTable > 0) { // Show if not first page ?>
      <a href="<?php printf("%s?pageNum_rsTable=%d%s", $currentPage, 0, $queryString_rsTable); ?>">First</a>
      <?php } // Show if not first page ?>
    </td>
    <td width="31%" align="center"><?php if ($pageNum_rsTable > 0) { // Show if not first page ?>
      <a href="<?php printf("%s?pageNum_rsTable=%d%s", $currentPage, max(0, $pageNum_rsTable - 1), $queryString_rsTable); ?>">Previous</a>
      <?php } // Show if not first page ?>
    </td>
    <td width="23%" align="center"><?php if ($pageNum_rsTable < $totalPages_rsTable) { // Show if not last page ?>
      <a href="<?php printf("%s?pageNum_rsTable=%d%s", $currentPage, min($totalPages_rsTable, $pageNum_rsTable + 1), $queryString_rsTable); ?>">Next</a>
      <?php } // Show if not last page ?>
    </td>
    <td width="23%" align="center"><?php if ($pageNum_rsTable < $totalPages_rsTable) { // Show if not last page ?>
      <a href="<?php printf("%s?pageNum_rsTable=%d%s", $currentPage, $totalPages_rsTable, $queryString_rsTable); ?>">Last</a>
      <?php } // Show if not last page ?>
    </td>
  </tr>
</table>
<?php
$fields = mysql_list_fields($database_conn, $_GET['table'], $conn) or die("error");
?>
<table border="1" cellspacing="0" cellpadding="5">
	<tr>
	  <th>Primary Key&nbsp;</th>
		<th>Sr.No.</th>
		<?php while($rfields = mysql_fetch_field($fields)) { ?>
		<th><?php echo $rfields->name; $tdarray[] = $rfields->name; ?></th>
		<?php } ?>
		<th>Edit</th>
		<th>Delete</th>
	</tr>
	<?php do { ?>
	<form name="frm<?php echo $row_rsTable[$pid]; ?>" action="" method="post">
	<tr>
	  <td><?php echo $row_rsTable[$pid]; ?>&nbsp;</td>
		<td><?php static $i=0; $i++; echo $i; ?></td>
	    <?php foreach($tdarray as $value) { ?>
	    <td><textarea name="<?php echo $value; ?>"><?php echo $row_rsTable[$value]; ?></textarea></td>
	    <?php } ?>
	    <td><a href="#" onClick="document.frm<?php echo $row_rsTable[$pid]; ?>.submit();">Edit</a></td>
	    <td><a href="tables.php?field=<?php echo $pid; ?>&<?php echo $pid; ?>=<?php echo $row_rsTable[$pid]; ?>&del=1&db=<?php echo $_GET['db']; ?>&table=<?php echo $_GET['table']; ?>&pageNum_rsTable=<?php echo $pageNum_rsTable; ?>">Delete</a></td>
    </tr>
	<input type="hidden" name="table_name" value="<?php echo $_GET['table']; ?>">
	<input type="hidden" name="db" value="<?php echo $_GET['db']; ?>">

	<input type="hidden" name="pk" value="<?php echo $pid; ?>">
	<input type="hidden" name="<?php echo $pid; ?>" value="<?php echo $row_rsTable[$pid]; ?>">
	</form>
  <?php } while ($row_rsTable = mysql_fetch_assoc($rsTable)); ?>
</table>
<?php } ?>
</body>
</html>
<?php
mysql_free_result($rsTable);
?>
<?php mysql_close(); ?>
