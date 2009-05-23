<?php require_once('../Connections/conn.php'); 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Database</title>
<link href="../main.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php if($_GET['db']) {
	$database_conn = $_GET['db'];
}
?>
<table border="0" cellpadding="5" cellspacing="1">
  <tr>

<td valign="top">
<h3>Select Database</h3>
<?php
$db_list = mysql_list_dbs($conn);

$i = 0;
$cnt = mysql_num_rows($db_list);
while ($i < $cnt) {
	if(eregi("mkhancha", mysql_db_name($db_list, $i))) {
		
		echo "<a href='index.php?db=".mysql_db_name($db_list, $i)."'>".mysql_db_name($db_list, $i) . "</a><br>";
		$i++;
		
	} else {
		$i++;
		continue;
	}
}

?>
</td>
<?php if($_GET['db']) { ?>
<td valign="top"><h3>Tables in <?php echo $database_conn; ?></h3>
<?php
$tables = mysql_list_tables($database_conn, $conn) or die("error");
while($rtables = mysql_fetch_array($tables)) {
	echo "<a href='tables.php?table=".$rtables['Tables_in_'.$database_conn]."&db=".$database_conn."'>".$rtables['Tables_in_'.$database_conn]."</a><br>";
}
?></td>

<?php } ?>
</tr></table>


<h3>Links</h3>
<p><a href="query.php?db=<?php echo $database_conn; ?>">Run SQL Query</a> </p>
</body>
</html>
<?php mysql_close(); ?>
