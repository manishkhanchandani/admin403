<?php
require_once 'mysqldb.inc.php';
require_once 'myxml.inc.php';
require_once 'global_vars.inc.php';

//----functions-------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------
// writeTag()
//------------------------------------------------------------------------------	
function strOpenTag($tag,$param = ""){		
   return ("<$tag $param>");
}

//------------------------------------------------------------------------------
// closeTag()
//------------------------------------------------------------------------------	
function strCloseTag($tag){		
   return ("</$tag>");
}

//------------------------------------------------------------------------------
// cdata()
//------------------------------------------------------------------------------	
function strCdata($data){		
   return ("<![CDATA[$data]]>");
}	

//---------------------------------------------
//---generate metadata xml
//---------------------------------------------
function createmetadata_xml( $db ) 
{   
   $XML.= strOpenTag('databases','host="'.HOST.'" user="'.USER.'"');
   $XML.= strOpenTag("mainerror", "").strCdata(1).strCloseTag("mainerror");
     
   //****$dbdata = $db->TableToArray($sql[0]);
   $dbdata[0]['Database'] = DATABASENAME;
   
   
   foreach ($dbdata as $v) 
   {
      //begin database section
	  $XML .= strOpenTag('database','name="'.$v['Database'].'"');
	  	  
	   //begin tables
	    $tabdata = $db->TableToArray('SHOW TABLE STATUS FROM `'.$v['Database']."`");
		foreach($tabdata as $w)
		{		      		   
		   $XML.= create_table_xml($w, $v['Database'], $w['Name'], '', '1', '', $db);
		}
	   //end tables
	  
	  //end database section
	  $XML .= strCloseTag('database');
   }  
  
   $XML.= strCloseTag('databases');
    
   writeln($XML);
}
//--------------------------------------------------------------------------------------------------------------//
//arguments : $dbname - name of database; $tabname - name of table about information is;
//            $addparams - additional parameters for table tag (error, etc...)     
function gettable_xml($dbname, $tabname, $addparams, $merror, $serror, $db)
{
  $tabdata = $db->TableToArray("SHOW TABLE STATUS FROM `$dbname` LIKE \"$tabname\"");		
  return( create_table_xml($tabdata[0], $dbname, $tabname, $addparams, $merror, $serror, $db) );	   
}

function create_table_xml($tabdata, $dbname, $tabname, $addparams, $merror, $serror, $db)
{
//rows data
  $rowdat[] = "field_name";
  $rowdat[] = "field_type";
  $rowdat[] = "unsigned";
  $rowdat[] = "field_size";
  $rowdat[] = "auto_inc";
  $rowdat[] = "index";
  $rowdat[] = "prikey";
  $rowdat[] = "unique";
  $rowdat[] = "null";
  $rowdat[] = "default_value";   
	
	$baseparams = '';
	
	//---fix for some mySQL versions
	if( !isset($tabdata['Type']) && isset($tabdata['Engine'])) $tabdata['Type'] = $tabdata['Engine'];
	//---
	while (list ($key, $val) = each ( $tabdata )) $baseparams.=' '.strtolower($key).'="'.$val.'"';
		
	
	$XML .= strOpenTag('table',$baseparams.$addparams);
	
	$XML .= strOpenTag("mainerror", "").strCdata($merror).strCloseTag("mainerror");
	$XML .= strOpenTag("error", "").strCdata($serror).strCloseTag("error");
		   
	//begin rows
	$rowdata = $db->TableToArray("SHOW FIELDS FROM `$dbname`.`$tabname`");
	$indexdata = $db->TableToArray("SHOW KEYS FROM `$dbname`.`$tabname`");
			
	 foreach($rowdata as $r) {
	    $param = '';
		$cdata_nodes = '';
	    foreach($rowdat as $c)
		{ 
	       switch ($c) {
	        case 'field_name':
		     $cdata = $r['Field'];
		    break;
					 
			case 'field_type':
			 $end = strpos($r['Type'],'(');
			 if($end === false) $data = $r['Type'];
			 else $data = substr ($r['Type'], 0, $end);
			break;
					 
			case 'unsigned':
			 if(strpos(strtolower($r['Type']),'unsigned') != 0) $data = 'true';
			 else $data = 'false';
			break;
				 
			case 'field_size':
			 $beg = strpos($r['Type'],'(');
			 if($beg === false) $data = '';
			 else $data = substr ($r['Type'], $beg + 1, strpos($r['Type'],')') - $beg - 1);
			break;
					 
			case 'auto_inc':
			 if(strcasecmp($r['Extra'],'auto_increment') == 0) $data = 'true';
			 else $data = 'false';
			break;
				 
			case 'index':
			 $data = 'false';
			 foreach($indexdata as $ind) 
			 {
			  	if(strcmp($ind['Column_name'], $r['Field']) == 0 && 
			     	$ind['Key_name'] != 'PRIMARY' 
					//&&
				 	//$ind['Non_unique'] == '1'
					)
			  	{
			     	$data = 'true';
		    	 	break;
			  	}
			 }
		    break;
					 
			case 'prikey':
			 $data = 'false';
			 foreach($indexdata as $ind) 
			 {
			  	if( strcmp($ind['Column_name'], $r['Field']) == 0 && $ind['Key_name'] == 'PRIMARY' )
			  	{
			     	$data = 'true';
		    	 	break;
			  	}
			 }
			break;
			
			case 'unique':
			 $data = 'false';
			 foreach($indexdata as $ind) 
			 {
			  	if(strcmp($ind['Column_name'], $r['Field']) == 0 && 
			     	$ind['Key_name'] != 'PRIMARY' &&
				 	$ind['Non_unique'] == '0')
			  	{
			     	$data = 'true';
		    	 	break;
			  	}
			 }
			break;
					 
			case 'null':
			 if(strcasecmp($r['Null'],'yes') == 0) $data = 'true';
			 else $data = 'false';
			break;
					 
			case 'default_value':
			 $cdata = $r['Default'];
			break;
			}
				   
			if($c != 'field_name' && $c != 'default_value') $param.=' '.$c.'="'.$data.'"';
			else $cdata_nodes.= strOpenTag($c).strCdata($cdata).strCloseTag($c);
						   
		   }
		  $XML .= strOpenTag('row',$param);
		  $XML .= $cdata_nodes; 
		  $XML .= strCloseTag('row');
		}
		//end rows
		   
		$XML .= strCloseTag('table');
				
		return $XML; 
}

//--------------------------------------------------------------------------------------------------------------//
function getmetadata() 
{
  $mydb = new mysqlDB(HOST, USER, PASSWORD, DATABASENAME);
  createmetadata_xml( $mydb );
  $mydb->Close();
}

function altertable($xmldoc, $type) {
  
  $xml = new myXML($xmldoc);
  
  $mydb = new mysqlDB(HOST, USER, PASSWORD, DATABASENAME);
  
  $myxml = $xml->root;
  //------------------------------------------------------------------------------------------------------//   
  foreach($myxml->children as $prop) {
     switch($prop->name) {
	   case "NAME"   : $tabname  = $prop->value[0]; break;
	   case "DBNAME" : $dbname   = $prop->value[0]; break;
	   case "ROW" : {
	      $row = $prop;
	      
		  $unsigned = (strcasecmp($row->attributes['UNSIGNED'],'true') == 0)? 'UNSIGNED':'';
  
          if(strlen($row->attributes['FIELD_SIZE']) != 0)
             $size = '('.$row->attributes['FIELD_SIZE'].')';
          else $size = '';	 
    
          $type_size = strtoupper($row->attributes['FIELD_TYPE']).$size;
          $null = (strcasecmp($row->attributes['NULL'],'true') == 0)? '':'NOT NULL';
          $autoincr = (strcasecmp($row->attributes['AUTO_INC'],'true') == 0)? 'AUTO_INCREMENT':'';
		  $index = $row->attributes['INDEX'];
		  $prikey = $row->attributes['PRIKEY'];
		  $unique = $row->attributes['UNIQUE'];
		  
		  foreach ($prop->children as $rowprop) {
		      $value = $rowprop->value[0];
			  switch ($rowprop->name) {
			    case "FIELD_NAME"    : $field_name = $value; break;
			    case "FORFIELD_NAME" : $forfield_name = $value; break;
				case "DEFAULT_VALUE" : $default  = ($value != '')? 'DEFAULT "'.$value.'"':''; break;
			  } 
		  }
		    
	   }
	 }
  }
    
  //------------------------------------------------------------------------------------------------------//   
  $indexdata = $mydb->TableToArray('SHOW KEYS FROM `'.$dbname.'`.`'.$tabname."`");
  $sql_ind = $sql_pri = $sql_uni = $add_ind = $drop_ind = '';
  
  //check for indexes
  foreach($indexdata as $v)
	if($v['Column_name'] == $field_name && $v['Key_name'] != 'PRIMARY')
	{
	   $index_name = $v['Key_name'];
	   break;
	} 
  
  if($index == 'true') 
       $sql_ind = "ALTER TABLE `$dbname`.`$tabname` ADD INDEX `$field_name` (`$field_name`)";
  else if($index == 'false' && $index_name != '') 
       $sql_ind = "ALTER TABLE `$dbname`.`$tabname` DROP INDEX `$index_name`";
    
  if($prikey == 'true')
       $sql_pri = "ALTER TABLE `$dbname`.`$tabname` ADD PRIMARY KEY (`$field_name`)";
  else if($prikey == 'false')	   
       $sql_pri = "ALTER TABLE `$dbname`.`$tabname` DROP PRIMARY KEY";
    
  if( $index == 'true' || $index_name != '')
  {
      $add_ind  = ", ADD INDEX `$index_name` (`$index_name`)";
	  if($index != 'false') $drop_ind = "DROP INDEX `$field_name`,";
  }	  
  
  if($unique == 'true')
       $sql_uni = "ALTER TABLE `$dbname`.`$tabname` $drop_ind ADD UNIQUE `$field_name` (`$field_name`)";
  else if($unique == 'false' && $index != 'false') 
       $sql_uni = "ALTER TABLE `$dbname`.`$tabname` DROP INDEX `$index_name`$add_ind";
	     
  //------------------------------------------------------------------------------------------------------//  
  
  if($type == "alterrow"){ 
     $sql1 = "ALTER TABLE `$dbname`.`$tabname` CHANGE `$forfield_name` `$field_name` $type_size $unsigned $default $null $autoincr";
  }	 
  else if($type == "addrow") {
     $sql1 = "ALTER TABLE `$dbname`.`$tabname` ADD `$field_name` $type_size $unsigned $default $null $autoincr";
  }	  
 
  //-execute an action------------------------------------------------------------------------------------//
  $mainerror = $mydb->Execute($sql1);
   
  if(strlen($mainerror) <= 1) 
  {
    if($sql_ind != '')  $error2 = $mydb->Execute($sql_ind);
	if($sql_pri != '')  $error3 = $mydb->Execute($sql_pri);
	if($sql_uni != '')  $error4 = $mydb->Execute($sql_uni);
	
	$error  = $error2;
	$error .= (strlen($error3) > 1)? "\n".$error3 : "";
	$error .= (strlen($error4) > 1)? "\n".$error4 : ""; 
  }
  
  //toLog("SQL_IND", $sql_ind);
  //toLog("SQL_PRI", $sql_pri);
  //toLog("SQL_UNI", $sql_uni);  
   
  //---make more decreptive message
  if(strlen($mainerror) > 1 || strlen($error) > 1)
  {
  		$comment = $mydb->TableToArray( "SHOW TABLE STATUS LIKE '$tabname'" );
		$comment = $comment[0]['Comment'];
		if( strpos( $comment, $forfield_name) > 0 ||  strpos( $comment, $field_name) > 0)
		{
			global $Error_Msg_Def;
			$mainerror = $Error_Msg_Def['update_foreign_field_fault'];
		}
  }
  //---
  //------------------------------------------------------------------------------------------------------//   
  
  //send to client
  writeln(gettable_xml($dbname, $tabname, '', $mainerror, $error, $mydb));
  
  $mydb->Close();
  
}

function removerow($xmldoc) {
  $xml = new myXML($xmldoc);
  
  $mydb = new mysqlDB(HOST, USER, PASSWORD, DATABASENAME);
  
  $myxml = $xml->root;
  
  foreach($myxml->children as $prop) {
     switch($prop->name) {
	   case "NAME"   : $tabname  = $prop->value[0]; break;
	   case "DBNAME" : $dbname   = $prop->value[0]; break;
	   case "ROW" : {
	      $rowprop = $prop->children;
		  $field_name = $rowprop[0]->value[0]; break;  
	   }
	 }
  }
    
  $sql = "ALTER TABLE `$dbname`.`$tabname` DROP `$field_name`";
    
  $error = $mydb->Execute($sql);
     
  //send to client
  writeln(gettable_xml($dbname, $tabname, '', $error, '', $mydb));
  
  $mydb->Close();
 
}

function droptable($dbname, $tabname) {
  $mydb = new mysqlDB(HOST, USER, PASSWORD, DATABASENAME);
  $sql = "DROP TABLE `$dbname`.`$tabname`";
  
  toLog('drop',$sql);
  
  $error = $mydb->Execute($sql);
  
  writeln(strOpenTag("mainerror", "").strCdata($error).strCloseTag("mainerror"));
  
  $mydb->Close();
}

function addtable($xmldoc) {
   
  $xml = new myXML($xmldoc);
  $myxml = $xml->root;
  $rows  = '';
  
  $mydb = new mysqlDB(HOST, USER, PASSWORD, DATABASENAME);  
  $mydb->getMySqlVersion();
  
  $type_str = ' TYPE ';
  $ver_int = $mydb->mysql_int_version;
  if(($ver_int >= 40018 && $ver_int < 40100 ) || $ver_int > 40120 ) $type_str = ' ENGINE '; 
  
  foreach($myxml->children as $prop) {
     switch($prop->name) {
	   case "NAME"    : $tabname  = $prop->value[0]; break;
	   case "DBNAME"  : $dbname   = $prop->value[0]; break;
	   case "COMMENT" : ($prop->value[0] != '')? $comment  = "COMMENT='".$prop->value[0]."'" : ''; break;
	   case "TYPE"    : ($prop->value[0] != '')? $type     = "$type_str=".$prop->value[0] : ''; break;
	   case "ROW" : {
	      $row = $prop;
	      
		  foreach ($prop->children as $rowprop) {
		      $value = $rowprop->value[0];
			  switch ($rowprop->name) {
			    case "FIELD_NAME"    : $field_name = $value; break;
			    case "DEFAULT"       : $default  = ($value != '')? "DEFAULT '$value'":''; break;
			  } 
		  }
		  		  
		  $unsigned = (strcasecmp($row->attributes['UNSIGNED'],'true') == 0)? 'UNSIGNED':'';
  
          if(strlen($row->attributes['FIELD_SIZE']) != 0)
             $size = '('.$row->attributes['FIELD_SIZE'].')';
          else $size = '';	 
    
          $type_size = strtoupper($row->attributes['FIELD_TYPE']).$size;
          $null = (strcasecmp($row->attributes['NULL'],'true') == 0)? '':'NOT NULL';
          $autoincr = (strcasecmp($row->attributes['AUTO_INC'],'true') == 0)? 'AUTO_INCREMENT':'';
		  $index = (strcasecmp($row->attributes['INDEX'],'true') == 0)? ",INDEX(`$field_name`)":'';
		  $prikey = (strcasecmp($row->attributes['PRIKEY'],'true') == 0)? ",PRIMARY KEY(`$field_name`)":'';
		  $unique = (strcasecmp($row->attributes['UNIQUE'],'true') == 0)? ",UNIQUE(`$field_name`)":'';
		  
		  if(strlen($rows) != 0) $rows.=' ,';		  
		     
		  $rows.= "`$field_name` $type_size $unsigned $default $null $autoincr $prikey $unique $index" ;
		  	 
		    
	   }
	 }
  }
  
  
  $sql = "CREATE TABLE `$dbname`.`$tabname` ($rows) $comment $type";
  
  //toLog("CREATE TABLE",$sql);  
  
  $error = $mydb->Execute($sql);
  
  if(strlen($error) > 1) writeln(main_error($error));
  else writeln(gettable_xml($dbname, $tabname, '', $error, '', $mydb));
  
  $mydb->Close();
  
}

function main_error($err)
{
	return (strOpenTag("mainerror", "").strCdata($err).strCloseTag("mainerror"));
}

function executesql($sql, $cnt)
{
  $sql = stripslashes( $sql );  
    
  splitSql(&$qry, $sql, 30329);
    
  $mydb = new mysqlDB(HOST, USER, PASSWORD, DATABASENAME);  
  
  $error = 0;
  $affected_rows = 0;
  $time_start = getmicrotime();
  
  //openTag('execSQL');
  $xml = strOpenTag('execSQL');
  
  for( $i = 0; $i < sizeof($qry); $i++)
  {
	   $sql_str = $qry[$i];
	    
	   if( strpos( strtoupper($sql_str), "SELECT") === 0
	       ||
		   strpos( strtoupper($sql_str), "SHOW") === 0)
	   {
	       if( $i == (sizeof($qry) - 1))
		   {		      	
			  $xml .= TableToXml($sql_str, $mydb, 0, $cnt, 1);
			  //writeln( $res );			 
	   	   }
	   }
	   else
	   {
	       $error = $mydb->Execute( $sql_str );
		   if( mysql_affected_rows() > 0) $affected_rows += mysql_affected_rows();
		   
	       if(strlen( $error ) > 1) 
		   {		     
			  break;
		   }
	   }
  }
  $time_end = round((getmicrotime() - $time_start)*1000) / 1000;
  $msg = $affected_rows == 0 ? " (query took $time_end sec)" : " $affected_rows row(s) affected. (query took $time_end sec)";
    
  $xml .= strOpenTag("mainerror", "").strCdata( $error ).strCloseTag("mainerror");
  
  $xml .= strOpenTag('msg');
  $xml .= strCdata( $msg );
  $xml .= strCloseTag('msg');
  
  $xml .= strCloseTag('execSQL');
  
  writeln( $xml );
  
  
  
  //closeTag('execSQL');        
  
  $mydb->Close();  
}

function renametable($fortabname, $tabname)
{
  $mydb = new mysqlDB(HOST, USER, PASSWORD, DATABASENAME);  
  
  $sql = "ALTER TABLE `$fortabname` RENAME `$tabname`";
  $error = $mydb->Execute($sql);
  
  writeln(strOpenTag("mainerror", "").strCdata($error).strCloseTag("mainerror"));
  
  $mydb->Close(); 
}

function getdatabases(){
  global $only_databases;
  
  $mydb = new mysqlDB(HOST, USER, PASSWORD, DATABASENAME);
  
  $sql = "SHOW DATABASES";
    
  $dbdata = $mydb->TableToArray($sql); 
  
  $xml  = strOpenTag("databases", "");
   $xml.= strOpenTag("mainerror", "").strCdata("1").strCloseTag("mainerror");
   foreach($dbdata as $v)
   {
    if( sizeof( $only_databases ) > 0 && is_array( $only_databases ) && !in_array($v['Database'], $only_databases) ) continue;
	
	//fix some mySQL version issue with databases right
	$dbcheck = @mysql_select_db( $v['Database'] ); 
	if( ! $dbcheck ) continue;//DB not accessible
	//---
	
	$xml.= strOpenTag("database", "");
     $xml.= strOpenTag("name", "").strCdata($v['Database']).strCloseTag("name");
	$xml.= strCloseTag("database");  
   }	  
  $xml.= strCloseTag("databases");
  
  writeln($xml);
  
  $mydb->Close();
}

function movetable($dbname, $oldtabname, $newtabname){
  $mydb = new mysqlDB(HOST, USER, PASSWORD, DATABASENAME); 
  
  $sql1 = "CREATE TABLE `$dbname`.`$newtabname` () SELECT * FROM `$oldtabname`";
  $sql2 = "DROP TABLE `$oldtabname`";
    
  $error = $mydb->Execute($sql1);
  if(strlen($error) == 1) $error = $mydb->Execute($sql2);
  
  if($dbname == DATABASENAME) 
     writeln(gettable_xml($dbname, $newtabname, '', $error, '', $mydb));
  else writeln(strOpenTag("mainerror", "").strCdata($error).strCloseTag("mainerror"));
  
  $mydb->Close();
}

function copytable($dbname, $oldtabname, $newtabname, $struct)
{
  $mydb = new mysqlDB(HOST, USER, PASSWORD, DATABASENAME); 
  
  $sql1 = "CREATE TABLE `$dbname`.`$newtabname` () SELECT * FROM `$oldtabname`";
  $sql2 = " WHERE 1=0";
      
  if(strcasecmp($struct,"true") == 0) { $sql1.=$sql2;}

  $error = $mydb->Execute($sql1);
  
  if($dbname == DATABASENAME) 
     writeln(gettable_xml($dbname, $newtabname, '', $error, '', $mydb));
  else writeln(strOpenTag("mainerror", "").strCdata($error).strCloseTag("mainerror"));
  
  $mydb->Close();  
}

function optimizetab($dbname, $tabname)
{
  $mydb = new mysqlDB(HOST, USER, PASSWORD, DATABASENAME); 
  
  $sql = "OPTIMIZE TABLE `$dbname`.`$tabname`";
  
  $error = $mydb->Execute( $sql );
  
  if(mysql_errno() == 0)$error = 0;
  
  writeln(strOpenTag("mainerror", "").strCdata($error).strCloseTag("mainerror"));
  
  $mydb->Close();
}

function createdb($dbname)
{
  $mydb = new mysqlDB(HOST, USER, PASSWORD, DATABASENAME); 
  
  $sql = "CREATE DATABASE `$dbname`";
  
  $error = $mydb->Execute($sql);
  
  writeln(strOpenTag("mainerror", "").strCdata($error).strCloseTag("mainerror"));
  
  $mydb->Close();
}

function dropdb($dbname)
{
  $mydb = new mysqlDB(HOST, USER, PASSWORD, DATABASENAME); 
  
  $sql = "DROP DATABASE `$dbname`";
  
  $error = $mydb->Execute($sql);
  
  writeln(strOpenTag("mainerror", "").strCdata($error).strCloseTag("mainerror"));
  
  $mydb->Close();
}

function backupdb($params)
{	
  toLog('backup',$params);

   $dbname      = $params['dbname'];
   $tab_str     = $params['tables'];
   $str_only    = $params['str_only'];
   $data_only   = $params['data_only'];
   $strndata    = $params['strndata'];
   $drop_tab    = $params['drop_tab'];
   $names_backq = $params['names_backq'];
   $zipped      = $params['zipped'];  
   
   $tables = explode(",", $tab_str);
      
   $dateString = date( "n_j_y_g_i" );

  //---- The name of the file which you want to save backup file in it.
  //---- If this file does not exist it will be created.(over writed).
  //---- You need to use physical path to this file. e.g. ../data/backup.txt
  $output = $dbname."_db_".$dateString.".sql";  

  //---- Including the class.
   include "mysql_backup.class.php";

  //---- Database host, name user and pass.
   $db_host = HOST;			//---- Database host (usually 'localhost')
   $db_name = DATABASENAME;	//---- Your database name.
   $db_user = USER;			//---- Your database username.
   $db_pass = PASSWORD;		//---- Your database password.  
    
  //output type = 1 => to string
   $outtype = 1;
   
  //---- instantiating object.
   $backup = new mysql_backup($db_host,$db_name,$db_user,$db_pass,$output,$outtype);

   $backup->backup_only_tables = $tables;
   $backup->structure_backup = $strndata || $str_only;
   $backup->data_backup      = $strndata || $data_only;
   $backup->drop_table_if_exists = $drop_tab;
   $backup->set_backquote = $names_backq;
   
  //---- calling the backup method finally creats a file with the name specified in $output
  //     and stors everythig so you can copy the file anywhere you want. This file will be
  //     restored with another method of this class named "restore" that is described in
  //     example-backup.php

   $resline = $backup->backup();
   
   // set headers for direct download by browser
   $mime = 'application/octetstream';
   
   if($zipped && @function_exists('gzencode'))
   {
   		$output .= '.gz';
		$mime = 'application/x-gzip';		
		$zipline = gzencode($resline); 
   }
   
   header("Content-disposition: filename=$output");
   header("Content-type: $mime");
   header("Pragma: no-cache");
   header("Expires: 0");  
   
   if( $zipped && $zipline) echo($zipline);
   else echo($resline);

}

function tab_type_change($params)
{
   global $Success_Msg_Def;
   
   $tab_str     = $params['tables'];
   $tab_type    = $params['tabtype'];
   
   $tables = explode(",", $tab_str);
   
   $mydb = new mysqlDB(HOST, USER, PASSWORD, DATABASENAME);

   $mydb->getMySqlVersion();
   
   $type_str = ' TYPE ';
   $ver_int = $mydb->mysql_int_version;
   if(($ver_int >= 40018 && $ver_int < 40100 ) || $ver_int > 40120 ) $type_str = ' ENGINE ';   
 
   $success_cnt = 0;
   $total_cnt   = 0;
   $err_msg = '';
   foreach($tables as $name)
   {
      $sql = "ALTER TABLE `$name` $type_str = $tab_type";
      $err = $mydb->Execute($sql);
	  
	  $total_cnt++;
	  	  
	  if(strlen($err) == 1) $success_cnt++; 
	  else $err_msg .= "Table '$name' not converted:\n$err\n";	 
   } 
   
   $msg  = str_replace("%one%", $success_cnt, $Success_Msg_Def["tab4_tab_type_change"]);
   $msg  = str_replace("%total%", $total_cnt, $msg);
   if( $err_msg != '')
   {
   		$msg .= "\n\n".$err_msg;
   }
   
        
   $XML =  strOpenTag("root", "");
   $XML .= strOpenTag("mainerror", "").strCdata(0).strCloseTag("mainerror");
   $XML .= strOpenTag("message", "").strCdata($msg).strCloseTag("message");
   $XML .=  strCloseTag("root");
   
   writeln($XML);
     
   $mydb->Close();
}

//generate table xml
function TableToXml($select, $db, $from, $cnt, $is_qry = 0)
{
  if($is_qry == 1) $rec_cnt = sizeof($db->TableToArray($select));
  
  $to = $from + $cnt;
    
  $error = "Error (".mysql_errno().") ".mysql_error();
      
  if( mysql_error() != "") 
  {
  	return ( strOpenTag("mainerror", "").strCdata($error).strCloseTag("mainerror") ); 
  }
  
  
  	//check for LIMIT
	$is_limit = eregi('[[:space:]]LIMIT[[:space:]0-9,-]+$', $select) || (strpos( strtoupper($select), "SHOW") === 0);
	
	if( !$is_limit )
	  $qry = $select." LIMIT $from,$cnt";
	else
	  $qry = $select;
	
	$res = $db->TableToArray($qry, "", 0);
	
	$XML = strOpenTag('table', "from=\"$from\" to=\"$to\" cnt=\"$rec_cnt\"");
  	$XML .= strOpenTag("mainerror", "").strCdata(0).strCloseTag("mainerror");

	$select = str_replace(chr(13), "\n" , $select);

  	$XML .= strOpenTag("sql", "").strCdata($select).strCloseTag("sql");
    
	$fl = Array();
	//what fields is blob ?
	for($i = 0; $i < sizeof($res[0]); $i++)
	{
		$field_flags = mysql_field_flags($db->last_res, $i); 
   		if (eregi('BLOB', $field_flags) && eregi('BINARY', $field_flags))
		 	$fl[$i] = 1;
		else $fl[$i] = 0;	
   	}
	
	//fields names section
   	$XML.= strOpenTag('fields');
   	for ($i = 0; $i < mysql_num_fields( $db->last_res ); $i++)
    	 $XML.= strCdata( mysql_field_name ( $db->last_res, $i) ); 
   	$XML.= strCloseTag('fields');
	$db->Free_result();
			
	if( $is_limit )
	{
	   //get sub data from res
	   $res = array_slice( $res, $from, $cnt);
	}
				        
   	//data section
   	foreach($res as $row)
   	{
      $XML.= strOpenTag('row');
	  $i = 0;
	  foreach($row as $cell) 
	  {
	  	if($fl[$i] == 1)
		     $XML.= strCdata(' [BLOB - '.formatSize(strlen($cell)).']');
	    else $XML.= strCdata($cell);
		$i++;       
	  } 
      $XML.= strCloseTag('row');
   	}	  
  	$XML.= strCloseTag('table');
	
	return ($XML);
  
 
}
//---------------------------------------------
//---format size for more fine visible
//---------------------------------------------
function formatSize( $size )
{	
	$postf = "KB";
	if( $size < 1024) return $size." Bytes";
	else $size = $size / (1024);
	
	return number_format( $size, 1, '.', ',').' '.$postf;
}

function getdata($tabname, $from, $cnt, $qry, $fields="")
{
  $mydb = new mysqlDB(HOST, USER, PASSWORD, DATABASENAME); 
   
  $is_qry = ($qry == "")? 0 : 1;  
  $fields = $fields=="" ? "*" : $fields;
  
  $sql  = ($is_qry == 0)? "SELECT $fields FROM `$tabname`" : $qry;  
    
  $sql = str_replace(chr(13), "\n", $sql);
      
  writeln(TableToXml($sql, $mydb, $from, $cnt, $is_qry));
  
  $mydb->Close();
}

function insert($xmldoc)
{
  $xml = new myXML($xmldoc);
  $myxml = $xml->root;
  
  $tabname = $fields = $values = $idname = $newrow ="";
  $select_fields = "*";
  
  foreach($myxml->children as $prop) {
     switch($prop->name) 
	 {
	   case "NAME"    : $tabname  = $prop->value[0]; break;
	   case "ID"      : $idname   = $prop->value[0]; break;
	   case "FIELDS"  : 
	       foreach ($prop->value as $field) {
		      if($fields == "") $fields .= "`$field`";
			  else $fields .= ",`$field`";
		   }
	   break;
	   case "VALUES"  : 
	       //foreach ($prop->value as $value)
		   {
		   	  $value = $prop->getvalue("");
		      $val = special_replace( $value );
			  if( $val != "") $val = addslashes( $value );
			  $val = "'$val'";	
			  			  
			  if($values == "") $values .= $val;
			  else $values .= ",".$val;
		   }
	   break;
   	   case "SELECT_FIELDS" : $select_fields = $prop->getvalue();break; 
	 }
  }
  
  $select_fields = $select_fields=="" ? "*" : $select_fields;
       
  $mydb = new mysqlDB(HOST, USER, PASSWORD, DATABASENAME);
  
  $sql = "INSERT INTO `$tabname` ($fields) VALUES ($values)";
  $sql = special_replace( $sql );
            
  $merror = $mydb->Execute($sql);
  
  $ins_id = $mydb->Get_last_insert_ID();
  if( $idname ) 
  {
     $select = "SELECT $select_fields FROM `$tabname` WHERE `$idname`=$ins_id";
	 $select = special_replace( $select );
	 $newrow = TableToXml($select, $mydb, 0, 1);
  }
  
  $retval = strOpenTag("mainerror", "").strCdata($merror).strCloseTag("mainerror").$newrow;
  writeln($retval);
   
  $mydb->Close();
}


//---------------------------------------------
//---prepare update sql return array of sql parts
//---------------------------------------------
function get_update_sql_parts( $xmldoc )
{
	  $xml = new myXML($xmldoc);
  $myxml = $xml->root;

  $tabname = $id = $condition = $newcondition = $sets = "";
  $fld_arr = $setfields = $newval_arr = $oldval_arr = $search_cond = array();
  
  $select_fields = "*";
  
  foreach($myxml->children as $prop) {
     switch($prop->name) 
	 {
	   case "NAME"       : $tabname     = $prop->value[0]; break;
	   case "ID"         : $id          = $prop->value[0]; break;
	   case "SETFIELDS"  : $setfld_arr  = $prop->value;    break;
	   case "FIELDS"     : $fld_arr     = $prop->value;    break;
	   case "NEWVALUES"  : 
	       //foreach ($prop->value as $value)
		   {
		   	  $value = $prop->getvalue("");
		      $val = special_replace( $value );
			  if( $val != "") $val = addslashes( $value );
			  $newval_arr[] = "'$val'";
		   }
       break;
	   case "OLDVALUES"  : 		  
	       foreach ($prop->children as $fldVal)
		   {
		   	  $value = $fldVal->getvalue("");
		      $val = special_replace( $value );
			  if( $val != "") $val = addslashes( $value );
			  $oldval_arr[] = "'$val'";
		   }
	   break;
	   case "SELECT_FIELDS" : $select_fields = $prop->getvalue();break;
	 }
  }
  
  $select_fields = $select_fields=="" ? "*" : $select_fields;
   
  //prepare sets for update
  for($i = 0; $i < sizeof($setfld_arr); $i++)
  {
      if($sets == "") $sets.= "`".$setfld_arr[$i]."`=".$newval_arr[$i];
	  else $sets.= ",`".$setfld_arr[$i]."`=".$newval_arr[$i];
	  $search_cond[$setfld_arr[$i]] = $newval_arr[$i];  
  }
  
  //prepare update condition
  for($i = 0; $i < sizeof($fld_arr); $i++)
  {
     if($id == "")
	 {
	    if($condition == "") $condition.= "`".$fld_arr[$i]."`=".$oldval_arr[$i];
	    else $condition.= " AND `".$fld_arr[$i]."`=".$oldval_arr[$i];
	 }
	 else if($id == $fld_arr[$i])
	 {
	      $condition.= "`".$fld_arr[$i]."`=".$oldval_arr[$i];
	 }
	 if(!$search_cond[$fld_arr[$i]]) $search_cond[$fld_arr[$i]] = $oldval_arr[$i];	
  }
  
  //prepare select condition
  $keys = array_keys($search_cond);
  $i = 0;
  if($id == "")
     foreach($search_cond as $item)
     {
        if($newcondition == "") $newcondition.= "`".$keys[$i++]."`=".$item;
	    else $newcondition.= " AND `".$keys[$i++]."`=".$item;
     }
  else $newcondition = "`$id`=$search_cond[$id]";	 
 
 
 $ret = array();
 
 $ret['tabname'] = $tabname;
 $ret['sets']	 = $sets; 
 $ret['condition']=$condition;
 $ret['select_fields']=$select_fields;
 $ret['newcondition']=$newcondition;
 $ret['id'] = $id;
 $ret['xml'] = $xml;
 $ret['setfld_arr'] = $setfld_arr;
 
 
 return $ret;
 
} 


function update($xmldoc)
{  
  $ret = get_update_sql_parts( $xmldoc );
  
  $tabname = $ret['tabname'];
  $sets = $ret['sets'];
  $condition = $ret['condition'];
  $select_fields = $ret['select_fields'];
  $newcondition = $ret['newcondition'];
 
            
  $mydb = new mysqlDB(HOST, USER, PASSWORD, DATABASENAME);
  
  $sql = "UPDATE `$tabname` SET $sets WHERE $condition LIMIT 1";
  $sql = special_replace( $sql );
  
  $select = "SELECT $select_fields FROM `$tabname` WHERE $newcondition";
  $select = special_replace( $select );
                       
  $merror = $mydb->Execute($sql);
  
  if(strlen($merror) > 1)
    $v = strOpenTag("mainerror", "").strCdata($merror).strCloseTag("mainerror");
  else
  	$v = TableToXml($select, $mydb, 0, 1);  

  
  writeln($v);
   
  $mydb->Close();
}

function delete_row( $xmldoc )
{
  //toLog("DELETE 1", $xmldoc);
  $xml = new myXML($xmldoc);
  $myxml = $xml->root;
  
  $tabname = $id = $condition = "";
  $fld_arr = $val_arr = array();
  
  //toLog('xml', $myxml->children);  
  
  foreach($myxml->children as $prop) {
     switch($prop->name) {
	   case "NAME"       : $tabname     = $prop->value[0]; break;
	   case "ID"         : $id          = $prop->value[0]; break;
	   case "FIELDS"     : $fld_arr     = $prop->value;    break;
	   case "OLDVALUES"  :
	   case "VALUES"     : 
	       foreach ($prop->children as $fldVal) 
		   {
		      $value = $fldVal->getvalue("");  
		   	  $val = special_replace( $value );
			  if( $val != "") $val = addslashes( $value );
			  $val = "'$val'";			  
			  		  
			  $val_arr[] = $val;			  
		   }
	   break;
	 }
  }
  
  //prepare condition section of sql query 
  for($i = 0; $i < sizeof($fld_arr); $i++)
  {
     if($id == "")
	 {
	    if($condition == "") $condition.= "`".$fld_arr[$i]."` = ".$val_arr[$i];
	    else $condition.= " AND `".$fld_arr[$i]."` = ".$val_arr[$i];
	 }
	 else if($id == $fld_arr[$i])
	 {
	      $condition.= "`".$fld_arr[$i]."`=".$val_arr[$i];
	 }	
  }
  
  $condition = preg_replace('/=\s*IS\s*NULL/i', 'IS NULL', $condition);
           
  $mydb = new mysqlDB(HOST, USER, PASSWORD, DATABASENAME);
  
  $sql = "DELETE FROM `$tabname` WHERE $condition LIMIT 1";
  
  //toLog("DELETE 2", $sql);
  
  $sql = special_replace( $sql );
  
  //toLog("DELETE-replace", $sql);
   
  $merror = $mydb->Execute($sql);
      
  writeln(strOpenTag("mainerror", "").strCdata($merror).strCloseTag("mainerror"));
   
  $mydb->Close();

}

function get_hosts()
{
	// Parse with sections
	$ini_array = parse_ini_file("hosts.php", TRUE);
	
	$XML = strOpenTag('hosts');
	$XML .= strOpenTag("mainerror", "").strCdata("0").strCloseTag("mainerror");
	
	$i = 0;
	$host_arr = array_keys($ini_array);
	foreach($ini_array as $section)
	{
		$XML .= strOpenTag('host', 'name = "'.$host_arr[$i++].'"');
			$XML .= strCdata($section['host']);
			$XML .= strCdata($section['user']);
			$XML .= strCdata($section['pass']);
		$XML .= strCloseTag('host');
	}
	
	$XML .= strCloseTag('hosts');
	
	writeln($XML);
	
}

function get_defs()
{
	global $Vars_Def, $Types_Def,
	       $Rowdata_Headnames_Def, $Tabtypes_Def,
		   $Titles_Def, $Window_Align_Def,
		   $Error_Msg_Def, $Success_Msg_Def;
	
	$XML = strOpenTag('configuration');
	$XML .= strOpenTag("mainerror", "").strCdata("0").strCloseTag("mainerror");
		
		$XML .= arr2xml_2("vars", $Vars_Def);
		$XML .= arr2xml_1("types", $Types_Def);
		
		$XML .= arr2xml_1("rowdata_headnames", $Rowdata_Headnames_Def);
		$XML .= arr2xml_1("tabtypes", $Tabtypes_Def);
		$XML .= arr2xml_2("titles", $Titles_Def);
		$XML .= arr2xml_2("window_align", $Window_Align_Def);
		$XML .= arr2xml_2("error_msg", $Error_Msg_Def);
		$XML .= arr2xml_2("success_msg", $Success_Msg_Def);
					
	$XML .= strCloseTag('configuration');
	
	writeln($XML);
		
}

function arr2xml_1($tagname, $arr)
{
	$XML = strOpenTag($tagname);
		foreach ( $arr as $val) $XML .= strCdata($val);
	$XML .= strCloseTag($tagname);
	
	return ($XML);
}

function arr2xml_2($tagname, $arr)
{
	$XML = strOpenTag($tagname);
		while (list ($key, $val) = each ($arr))
		{
			$XML .= strOpenTag($key);
			$XML .= strCdata($val);
			$XML .= strCloseTag($key);    
		}
	$XML .= strCloseTag($tagname);
	
	return ( $XML );
}

function get_record_cnt( $tab_name )
{
  $mydb = new mysqlDB(HOST, USER, PASSWORD, DATABASENAME); 
  
  $sql = "SELECT COUNT(*) as cnt FROM `$tab_name`";
  
  $res = $mydb->TableToArray( $sql );
  
  $cnt = $res[0]['cnt'];
         
  $XML =  strOpenTag("root", "");
  $XML .= strOpenTag("mainerror", "").strCdata(0).strCloseTag("mainerror");
  $XML .= strOpenTag('record_cnt', 'cnt="'.$cnt.'"').strCloseTag('record_cnt');		  
  $XML .= strCloseTag("root");
  
  writeln($XML);
    
  $mydb->Close();
}

function splitSql(&$ret, $sql, $release)
    {
        $sql          = trim($sql);
        $sql_len      = strlen($sql);
        $char         = '';
        $string_start = '';
        $in_string    = FALSE;
        $time0        = time();
    
        for ($i = 0; $i < $sql_len; ++$i) {
            $char = $sql[$i];
    
            // We are in a string, check for not escaped end of strings except for
            // backquotes that can't be escaped
            if ($in_string) {
                for (;;) {
                    $i         = strpos($sql, $string_start, $i);
                    // No end of string found -> add the current substring to the
                    // returned array
                    if (!$i) {
                        $ret[] = $sql;
                        return TRUE;
                    }
                    // Backquotes or no backslashes before quotes: it's indeed the
                    // end of the string -> exit the loop
                    else if ($string_start == '`' || $sql[$i-1] != '\\') {
                        $string_start      = '';
                        $in_string         = FALSE;
                        break;
                    }
                    // one or more Backslashes before the presumed end of string...
                    else {
                        // ... first checks for escaped backslashes
                        $j                     = 2;
                        $escaped_backslash     = FALSE;
                        while ($i-$j > 0 && $sql[$i-$j] == '\\') {
                            $escaped_backslash = !$escaped_backslash;
                            $j++;
                        }
                        // ... if escaped backslashes: it's really the end of the
                        // string -> exit the loop
                        if ($escaped_backslash) {
                            $string_start  = '';
                            $in_string     = FALSE;
                            break;
                        }
                        // ... else loop
                        else {
                            $i++;
                        }
                    } // end if...elseif...else
                } // end for
            } // end if (in string)
    
            // We are not in a string, first check for delimiter...
            else if ($char == ';') {
                // if delimiter found, add the parsed part to the returned array
                $ret[]      = substr($sql, 0, $i);
                $sql        = ltrim(substr($sql, min($i + 1, $sql_len)));
                $sql_len    = strlen($sql);
                if ($sql_len) {
                    $i      = -1;
                } else {
                    // The submited statement(s) end(s) here
                    return TRUE;
                }
            } // end else if (is delimiter)
    
            // ... then check for start of a string,...
            else if (($char == '"') || ($char == '\'') || ($char == '`')) {
                $in_string    = TRUE;
                $string_start = $char;
            } // end else if (is start of string)
    
            // ... for start of a comment (and remove this comment if found)...
            else if ($char == '#'
                     || ($char == ' ' && $i > 1 && $sql[$i-2] . $sql[$i-1] == '--')) {
                // starting position of the comment depends on the comment type
                $start_of_comment = (($sql[$i] == '#') ? $i : $i-2);
                // if no "\n" exits in the remaining string, checks for "\r"
                // (Mac eol style)
                $end_of_comment   = (strpos(' ' . $sql, "\012", $i+2))
                                  ? strpos(' ' . $sql, "\012", $i+2)
                                  : strpos(' ' . $sql, "\015", $i+2);
                if (!$end_of_comment) {
                    // no eol found after '#', add the parsed part to the returned
                    // array if required and exit
                    if ($start_of_comment > 0) {
                        $ret[]    = trim(substr($sql, 0, $start_of_comment));
                    }
                    return TRUE;
                } else {
                    $sql          = substr($sql, 0, $start_of_comment)
                                  . ltrim(substr($sql, $end_of_comment));
                    $sql_len      = strlen($sql);
                    $i--;
                } // end if...else
            } // end else if (is comment)
    		else if( $char == '/' && $sql[$i+1] == "*")//parse /* */ comment
			{	
				$start_of_comment = $i;
				$i += 1;
				while( ++$i < $sql_len)
				  if( $sql[$i] == "/" && $sql[$i-1]=="*") break;
				
				$com_len = $i+1 - $start_of_comment;
				$sql = substr($sql, 0, $start_of_comment).ltrim(substr($sql, $i+1));
				$sql_len      = strlen($sql);
				
				$i -= $com_len;
			}
			
            // ... and finally disactivate the "/*!...*/" syntax if MySQL < 3.22.07
            /*else if ($release < 32270
                     && ($char == '!' && $i > 1  && $sql[$i-2] . $sql[$i-1] == '/*')) {
                $sql[$i] = ' ';
            } */// end else if
    
            // loic1: send a fake header each 30 sec. to bypass browser timeout
            /*
			$time1     = time();
            if ($time1 >= $time0 + 30) {
                $time0 = $time1;
                header('X-pmaPing: Pong');
			} // end if
			*/
        } // end for
    
        // add any rest to the returned array
        if (!empty($sql) && ereg('[^[:space:]]+', $sql)) {
            $ret[] = $sql;
        }
    
        return TRUE;
    } // end of the 'PMA_splitSqlFile()' function



?>