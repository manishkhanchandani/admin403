<?php require_once('../Connections/conn.php'); ?>
<?php 
$db_list = mysql_list_dbs($conn);

$i = 0;
$j = 0; $j++;
$cnt = mysql_num_rows($db_list);
while ($i < $cnt) {
	if(eregi("mkhancha", mysql_db_name($db_list, $i))) {
		
		echo mysql_db_name($db_list, $i)."<br>";
		$database_conn = mysql_db_name($db_list, $i);
		$tables = mysql_list_tables($database_conn, $conn) or die("error");
		echo "<blockquote>";
		while($rtables = mysql_fetch_array($tables)) {
			echo $j.". ".$rtables['Tables_in_'.$database_conn]."<br>";
			
			$tableName  = $rtables['Tables_in_'.$database_conn];
			$backupFile = 'backup/'.$rtables['Tables_in_'.$database_conn].'.sql';
			$query      = "SELECT * INTO OUTFILE '$backupFile' FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\n' FROM $tableName";
			$result = mysql_query($query) or die('error'.mysql_error());
			exit;
			$j++;
		}
		echo "</blockquote>";
		$i++;
		
	} else {
		$i++;
		continue;
	}
}
?>