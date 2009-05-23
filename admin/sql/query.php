<?php require_once('../Connections/conn.php');
 ?>
<?php if($_GET['db']) {
$database_conn = $_GET['db'];
}
if($_POST['query']) {
	$query = stripslashes(trim($_POST['query']));
	mysql_select_db($database_conn, $conn) or die(mysql_error());
	$query = mysql_query($query) or die('error'.mysql_error());
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Query</title>
<link href="../main.css" rel="stylesheet" type="text/css">
</head>

<body>
 <h3>Database: <?php echo $database_conn; ?>
 </h3>
<p><b>SQL Query</b></p>
 <form name="form1" method="post" action="">
   <p>
     <textarea name="query" cols="45" rows="10" id="query"></textarea>
</p>
   <p>
     <input type="submit" name="Submit" value="Send">
</p>
 </form>
 <p><b></b>
  </p>
</body>
</html>
<?php mysql_close(); ?>
