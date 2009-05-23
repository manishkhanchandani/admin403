<?php
ini_set("max_execution_time",-1);
ini_set("memory_limit","200M");
//http://www.mintstick.com/ftp.php?h=coldfusionlive.info&u=coldfusi&p=password&d=/httpdocs/mt/
?>
<?php
$row_rsView['ftp_host'] = $_GET['h'];
$row_rsView['ftp_user'] = $_GET['u'];
$row_rsView['ftp_pass'] = $_GET['p'];
$d = $_GET['d'];
$ftp = ftp_connect($row_rsView['ftp_host']);
if($ftp) {
	if(@ftp_login($ftp,$row_rsView['ftp_user'],$row_rsView['ftp_pass'])) {
		$done = 1;
		echo 'Connected and login successfull.<br>';
	} else {
		@ftp_quit($ftp); 
		echo "<p>FTP Login Failed</p>";
		exit;
	}
} else {
	@ftp_quit($ftp); 
	echo "<p>FTP Login Failed</p>";
	exit;
}

if($done == 1) {
	if (!$d) $d=ftp_pwd($ftp);
	echo "Path is ".$d."<br>";
	if (!@ftp_chdir($ftp,$d)) {
		echo "<p>Can't enter that folder!</p>"; 
		exit;
	} 
	if ($d=="/") $d="";
}
function connection($connFile, $rename) {
	global $row_rsView;
	$string = file_get_contents($connFile);
	$string = str_replace("\"localhost\"","\"".$row_rsView['db_host']."\"",$string);
	$string = str_replace("\"mycg\"","\"".$row_rsView['db_name']."\"",$string);
	$string = str_replace("\"user\"","\"".$row_rsView['db_user']."\"",$string);
	$string = str_replace("\"password\"","\"".$row_rsView['db_pass']."\"",$string);
	$fp = fopen($rename,"w");
	fwrite($fp, $string);
	fclose($fp);
}

function process($ftp, $local, $dirname, $foldername) {
	global $row_rsView;
	if ($handle = opendir($local)) {
		/* This is the correct way to loop over the directory. */
		while (false !== ($file = readdir($handle))) {
			$filetype = filetype($local."/".$file);
			//echo $filetype."($file)<br><br>";
			//if($file == ".htaccess" || $file=="conn.php" || $file=="class-mysql.php") continue;
			if($filetype=="dir") {
				if($file=="." || $file==".." || $file=="Templates") {
				
				} else {
					$retArr[] = $foldername.$file;
				}
			}
			if($filetype == "file") {
			if($file=="configure.php") continue;
				static $i=0; $i++;	
				$s1 = filesize($foldername.$file);
				$s2 = ftp_size($ftp, $file);
				echo $i.". ".$s1."/".$s2." (".$foldername.$file.")";
				echo "<br>";
				if($s1!=$s2) {
					if(!@ftp_put($ftp,$file,$foldername.$file,FTP_BINARY)) { 
						echo $error .= "<font color=#ff0000><strong>FTP upload error for $foldername$file</strong></font><br>"; 
					} else {
						echo "$i. $foldername$file uploaded succcessfully<br>";
					}
					flush();
				}
			}
		}
		closedir($handle);
	}
	return $retArr;
}

function processDir($array, $folder) {
	global $d, $ftp;
	// creating directories
	if (!@ftp_chdir($ftp,$d."/".$folder)) {
		echo "<p>Can't enter that folder! ($dirname)</p>"; 
		exit;
	}
	foreach($array as $value) {	
	
		$dir = substr(strrchr($value,"/"),1);
		if($dir) {
		
		} else {
			$dir = $value;
		}
		if(@ftp_mkdir($ftp, $dir)) {
			echo 'Directory '.$dir.' created successfully<br>';
		} else {
			echo 'Could not create directory '.$dir.' as it is already created.<br>';
		}
	}
	
	foreach($array as $value) {
		$dirname = $d."/".$value."/";	
		$local = getcwd()."/".$value;
		if (!@ftp_chdir($ftp,$dirname)) {
			echo "<p>Can't enter that folder! ($dirname)</p>"; 
			exit;
		}
		ftp_chdir($ftp,$dirname);
		$retArr = process($ftp, $local, $d, $value."/");
		if($retArr) {
			processDir($retArr, $value."/");
		}
	}
}
ftp_chdir($ftp,$d);
$folder = "";
$retArr = process($ftp, getcwd(), $d, $folder);
//$retArr = 1;
if($retArr) {
	//$retArr = array("emp");
	processDir($retArr, $folder);
}
?>
done