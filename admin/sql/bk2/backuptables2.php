<?php
ini_set("memory_limit","500M");
ini_set("max_execution_time","-1");

function mail_attachment ($from , $to, $subject, $message, $attachment, $filename){
$fileatt_type = "application/octet-stream"; // File Type 
$fileatt_name = $filename;
$email_from = $from; // Who the email is from 
$email_subject =  $subject; // The Subject of the email 
$email_txt = $message; // Message that the email has in it 
$email_to = $to; // Who the email is to
$headers = "From: ".$email_from;
$semi_rand = md5(time()); 
	$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 
    
	$headers .= "\nMIME-Version: 1.0\n" . 
            "Content-Type: multipart/mixed;\n" . 
            " boundary=\"{$mime_boundary}\""; 
$email_message .= "This is a multi-part message in MIME format.\n\n" . 
                "--{$mime_boundary}\n" . 
                "Content-Type:text/html; charset=\"iso-8859-1\"\n" . 
               "Content-Transfer-Encoding: 7bit\n\n" . 
	$email_txt . "\n\n"; 
	$data = $attachment;
	$data = chunk_split(base64_encode($data)); 
	$email_message .= "--{$mime_boundary}\n" . 
                  "Content-Type: {$fileatt_type};\n" . 
                  " name=\"{$fileatt_name}\"\n" . 
                  //"Content-Disposition: attachment;\n" . 
                  //" filename=\"{$fileatt_name}\"\n" . 
                  "Content-Transfer-Encoding: base64\n\n" . 
                 $data . "\n\n" . 
                  "--{$mime_boundary}--\n";
$ok = @mail($email_to, $email_subject, $email_message, $headers); 

	if($ok) { 
		//echo 'sent';
	} else { 
		//die("Sorry but the email could not be sent. Please go back and try again!"); 
	} 
}
function backup($tbl, $is, $id, $send_mail, $email="", $cs, $cd, $database, $writetofile) {
	$return = "DROP DATABASE `".$database."`;\nCREATE DATABASE `".$database."`;\n\n USE `".$database."`;\n\n ";
	if($writetofile==1) {
		$dir = "files/".$database;
		if(is_dir($dir)) {
		} else {
			mkdir($dir, 0777);
			chmod($dir, 0777);
		}
	}
	mysql_select_db($database) or die('error in selecting db');
	if($tbl) {
		foreach($tbl as $value) {
			$key = $value;
			// getting structure
			if($is || $cs) {
				$query = "show create table ".$key;
				$rs = mysql_query($query) or die('error'.mysql_error());
				$rec = mysql_fetch_array($rs);
				$tableStructure .= $rec[1].";\n\n";
				$string = $rec[1].";";
				echo $rec[1];
				echo "<hr>";
				$return .= $rec[1].";\n\n";
				$return .= "\n\n";
				
				if($is==1) {
					if($writetofile==1) {
						$fp = fopen($dir."/".$key."_structure.sql","w");
						fwrite($fp, $string);
						fclose($fp);
					}
					//echo "<a href='".$dir."/".$key."_structure.sql' target='_blank'>".$dir."/".$key."_structure.sql</a> write completed ....<br>";
					if($send_mail==1) {
						mail_attachment ("system@mkgalaxy.com" , $email, $dir."/".$key."_structure.sql", $dir."/".$key."_structure.sql", $string, $key."_structure.sql");
					}
				} else {
					//echo $dir."/".$key."_structure.sql write completed ....<br>";
				}
			}
			if($id || $cd) {
				$result = mysql_query('select * from '.$key) or die('error');
				if(mysql_num_rows($result)>0) {
					$data = '';
					$fulldata .= "\n\n";
					while($rec = mysql_fetch_array($result)) {
						$query = "insert into ".$key." set ";
						$i = 0;
						$subquery = '';
						while ($i < mysql_num_fields($result)) {
							$meta = mysql_fetch_field($result, $i);
							$subquery .= "`".$meta->name."`='".addslashes(stripslashes(trim($rec[$meta->name])))."', ";
							$i++;
						}
						$query = $query.substr($subquery,0,-2);
						$data .= $query.";\n";
						$fulldata .= $query.";\n";
					}			
					$return .= $data;
					$return .= "\n\n";
					
					if($id==1) {
						if($writetofile==1) {
							$fp = fopen($dir."/".$key."_data.sql","w");
							fwrite($fp, $data);
							fclose($fp);
						}
						//echo "<a href='".$dir."/".$key."_data.sql' target='_blank'>".$dir."/".$key."_data.sql</a> write completed ....<hr>";			
						if($send_mail==1) {
							mail_attachment ("system@mkgalaxy.com" , $email, $dir."/".$key."_data.sql", $dir."/".$key."_data.sql", $data, $key."_data.sql");
						}
					} else {
						//echo $dir."/".$key."_data.sql write completed ....<br>";
					}
				}
			}
		}
	}
	if($cs==1) {
		if($writetofile==1) {
			$fp = fopen($dir."/".$_POST['d']."_dbstructure.sql","w");
			fwrite($fp, $tableStructure);
			fclose($fp);
		}
		//echo "<a href='".$dir."/".$_POST['d']."_dbstructure.sql' target='_blank'>".$dir."/".$_POST['d']."_dbstructure.sql</a> write completed ....<br>";
		if($_POST['mail']==1) {
			mail_attachment ("system@mkgalaxy.com" , $email, $dir."/".$database."_dbstructure.sql", $dir."/".$database."_dbstructure.sql", $tableStructure, $_POST['d']."_dbstructure.sql");
		}
	}
	if($cd==1) {
		if($writetofile==1) {
			$fp = fopen($dir."/".$_POST['d']."_dbdata.sql","w");
			fwrite($fp, $fulldata);
			fclose($fp);
		}
		//echo "<a href='".$dir."/".$_POST['d']."_dbdata.sql' target='_blank'>".$dir."/".$_POST['d']."_dbdata.sql</a> write completed ....<hr>";
		if($_POST['mail']==1) {
			mail_attachment ("system@mkgalaxy.com" , $email, $dir."/".$database."_dbdata.sql", $dir."/".$database."_dbdata.sql", $fulldata, $_POST['d']."_dbdata.sql");
		}
	}
	return $return;
}
if($_POST['h']) {
	$link = mysql_connect($_POST['h'], $_POST['u'], $_POST['p']) or die('could not connect');
	if($_POST['d']) {
		mysql_select_db($_POST['d'],$link) or die("could not select db");
	}
} else {
	echo 'select db';
	exit;
}
$dir = "files/".$_POST['d'];
if(is_dir($dir)) {
	
} else {
	mkdir($dir, 0777);
	chmod($dir, 0777);
}
if($_POST['mail']) {
	$send_mail = 1;
	$email = $_POST['email'];
} else {
	$send_mail = 0;
	$email = "";
}
$tbls = $_POST['tbl'];
$return = backup($tbls, $is=$_POST['is'], $id=$_POST['id'], $send_mail, $email, $cs=$_POST['cs'], $cd=$_POST['cs'], $database=$_POST['d'], $writetofile=$_POST['writetofile']);
echo nl2br(htmlentities($return));
?>