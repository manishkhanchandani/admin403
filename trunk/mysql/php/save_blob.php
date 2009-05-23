<?php

import_request_variables( "GP" );
require_once 'tool.inc.php';

if( isset($xml) )
{	
	$myses_is = md5( time() );
	session_id( $myses_is );
	
	session_start();
	foreach($HTTP_POST_VARS as $k=>$v)$HTTP_SESSION_VARS[$k] = $v;
	
	openTag('root');
	  cdata( session_id() );
	closeTag('root');
	session_write_close();
	exit;
}

//require_once 'global_vars.inc.php';
require_once 'mysqldb.inc.php';
session_id( $ses_id );
session_start();
$mydb = new mysqlDB($HTTP_SESSION_VARS['host'], $HTTP_SESSION_VARS['usr'], $HTTP_SESSION_VARS['pass'], $HTTP_SESSION_VARS['db']);

if( !isset($HTTP_SESSION_VARS['host']) || !isset($HTTP_SESSION_VARS['usr']) || !isset($HTTP_SESSION_VARS['pass']) ||!isset($HTTP_SESSION_VARS['db']) )
{
	require_once 'global_vars.inc.php';
	
	echo "<center><b>". $Error_Msg_Def['php_session_error'] ."</b></center>";
	exit;
}


// only process if a valid file was designated
if ( $submit && !empty( $_FILES['file']['tmp_name'] ) )
{	
	require_once 'myxml.inc.php';
	require_once 'xml_creator.inc.php';	
	
	$ret = get_update_sql_parts( stripcslashes( $HTTP_SESSION_VARS['xml'] ) );
	
	$tabname = $ret['tabname'];
 	$setfld_arr = $ret['setfld_arr'][0];
  	$where = array( $ret['condition'] );
  	$select_fields = $ret['select_fields'];
  	$newcondition = $ret['newcondition'];
	$xml = $ret['xml'];
	$idname = $ret['id'];
	
	$myxml = $xml->root;
	
	$update = $myxml->attributes['UPDATE'];
	
   	$fileName = $_FILES['file']['tmp_name'];	
   	$data = fread( fopen( $fileName, "rb" ), filesize( $fileName ) ) ;	
   	$file = strlen($data) == 0 ? "''" : '0x' . bin2hex($data);
   
   	if( $update == 1 )
   	{
   		$sql = "UPDATE `$tabname` SET `$setfld_arr`=$file WHERE ";   
   		$sql .= implode(' AND ', $where) . ' LIMIT 1';    
   	}else
   	{
   		$sql = "INSERT INTO `$tabname` (`$setfld_arr`) VALUES ($file)";
   	}   

    $sql = special_replace( $sql );
   
    $mydb->Execute( $sql );
   
   //prepare select
   $select = "SELECT $select_fields FROM `$tabname` ";
   if( $update == 1 )
   {
   		$select .= ' WHERE '. implode(' AND ', $where);
   }
   else//insert
   {
   		if( $idname && mysql_insert_id() != 0)
		{
			$select .= " WHERE `$idname`=" . mysql_insert_id();
		}
		else
		{
			$res = $mydb->TableToArray("SELECT COUNT(*) as cnt FROM `$tabname`");
			$cnt = $res[0]['cnt'] - 1;
			$select .= " LIMIT $cnt,1";
			$is_lim = true;
		}
		
   }
   
   if( !$is_lim )  $select .= " LIMIT 1";
      
   $select = special_replace( $select );
   
   $newrow = TableToXml($select, $mydb, 0, 1);
      
   $HTTP_SESSION_VARS['sel_sql'] = $newrow;     
   //---
   
   // Unset variables
   session_unregister('host');
   session_unregister('usr');
   session_unregister('db');
   session_unregister('pass');
   session_write_close(); 
   
   if( mysql_error() != '' ) echo mysql_error();
   else
   {   		
   		// close this window		
		echo '<script>window.close();</script>';

   }
   exit;	
}

//---------------------------------------------
//---calculate max file size
//---------------------------------------------
$post_max_size = ini_get('post_max_size') * 1024 * 1024;
$upload_max_filesize = ini_get('upload_max_filesize') * 1024 * 1024;

$maxFieldSize = array('tinyblob' => pow(2,8) , 'blob' => pow(2,16), 'mediumblob' => pow(2,24), 'longblob' => pow(2,32)) ;

$field = addslashes($HTTP_SESSION_VARS['field']);
$table = $HTTP_SESSION_VARS['table'];
$res = $mydb->TableToArray("SHOW FIELDS FROM `$table` LIKE '$field'");

$fldSize = $maxFieldSize[$res[0]['Type']];

if( $fldSize == '' ) $fldSize = 10000000;

$maxSize =	min( $fldSize, $post_max_size, $upload_max_filesize) ;

function convertSize( $size )
{
	if( $size < 1024) return $size.' Bytes';
	if( $size > 1024*1024) return ($size/(1024*1024)).' MB';
	
	return ($size/1024).' KB';
}

?>
<html>
<style type="text/css">
<!--
.normalBold {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bolder;
}
.title {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 18px;
	font-weight: bolder;
	color: #000000;
}
.normal {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 14px;
}
-->
</style>



<HEAD>
<meta http-equiv=Content-Type content="text/html;  charset=">
<TITLE>File upload form</TITLE>
<script language="JavaScript" type="text/javascript">
	<!--// open print window		
		function myOnSubmit()
		{				
			if( document.setup.file.value == "")
			{
				window.alert("Please select imported file");
				return false;			
			}			
			return true;
		}		
	//-->
	</script>
</HEAD>




<body bgcolor="#FFFFFF" leftmargin=20 topmargin=20 marginwidth=20 marginheight=25>

<P class="title">
Load File
</P>
<P class="normal">
This tool will enable you to upload a binary file, like an image or document, to your database.
The maximum allowed size of this file <font color=#FF0000><?php echo convertSize($maxSize); ?></font>.
</P>

<form name="setup"
	  method="post" 
	  enctype="multipart/form-data"
	  onSubmit="return myOnSubmit()">
	  
	  
<!--for store ImportID-->
<input type="hidden" name="ses_id" value="<? echo $ses_id; ?>">

<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $maxSize;?>">
<input name="file" type="file" class="normal" size="40">
<input name="submit" type="submit" class="normal" value="Continue">

</form>


	
</body>
</html>
