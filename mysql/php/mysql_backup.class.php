<?php
class mysql_backup{
	//---------------------------------------------
	//---Class public variables.
	//---------------------------------------------	
	var $host;    // host name e.g. localhost
	var $dbname;  // db name
	var $user; 	  // db username
	var $pass;    // db password	
	var $fname;
	var $drop_table_if_exists = false;
	var $buffer = ""; //output buffer
	var $out_type = 0;//1 to string , 0 to browser
	var $backup_only_tables;//array with only backup tables	
	
	var $structure_backup = true; // dump structure
	var $data_backup = true;      // dump data
	var $set_backquote = true;
	
	//---------------------------------------------
	//---Class private variables
	//---------------------------------------------
	var $mysql_version;
	var $mysql_int_version;
	var $nl = "\n";
	var $user_os;
	var $tables_enum;
	
//---------------------------------------------
//---constructor
//---------------------------------------------
function mysql_backup($host,$dbname,$user,$pass, $fname, $outtype = 0){	
	//set_time_limit (30*60);
	
	$this->host   = $host;
	$this->dbname = $dbname;
	$this->user   = $user;
	$this->pass   = $pass;	
	$this->fname  = $fname;	
	$this->out_type = $outtype;
	
}
//---------------------------------------------
//---my sql backup
//---------------------------------------------
function backup(){	
	//init output buffer
	$this->buffer = "";
	
	//connect to MySQL database		
	$con = @mysql_connect($this->host,$this->user, $this->pass)
	       or die ( "There was a MySQL configuration problem - your host name, username, or password is incorrect!" );
	$db = @mysql_select_db($this->dbname, $con)
	       or die ( "Unable to make connection to database: $this->dbname. The system connected to MySQL, but did not find the specified database source." );	 
	
	$this->set_defines();	
	
	//create header info
	$info = '#'. $this->nl .
			'# phpFlashMyAdmin v1.4' . $this->nl .
			'# http://tufat.com/' . $this->nl .
			'#' . $this->nl .
			'# Host: ' . $this->host . $this->nl .
			'# Generation Time: ' . date('r'). $this->nl .
			'# mySQL server version: ' . $this->mysql_version . $this->nl .
			'# PHP Version: ' . phpversion() . $this->nl .
			'# ' . $this->nl .
			'# Database : ' . strtoupper($this->dbname) . $this->nl .
			'#' . $this->nl . $this->nl ;

	$this->output( $info )	;
	
	
	$result = mysql_query('SHOW TABLES FROM ' . $this->backquote($this->dbname));

	if ($result == FALSE || mysql_num_rows($result) == 0) {
	  return false;
	}
	
	$this->tables_enum = array();
	while ($row = mysql_fetch_array($result, MYSQL_NUM)) 
	{ 			
		$frn[] = $row[0];		
    }	
	
	$this->tables_enum = $frn;//$this->sortTableByRelation( $frn );	
	
	//---
	if(isset($this->backup_only_tables) && is_array($this->backup_only_tables))
	{
		$this->tables_enum = array_intersect($this->tables_enum,$this->backup_only_tables);
	}
	
	mysql_query('SET SESSION SQL_QUOTE_SHOW_CREATE = ' . (int)$this->set_backquote);//back quotes for crete sql
	
	if( sizeof($this->tables_enum) > 0 )
	{
		//fix relation problem		
		$this->output("#Temporary ignore foreign key constraints".$this->nl);
		$this->output("SET FOREIGN_KEY_CHECKS=0;".$this->nl."###".$this->nl);
	}
	
	foreach( $this->tables_enum as $v )
	{		
		//$this->output($this->nl . '##############################################################################' . $this->nl);
		if( $this->structure_backup )
		{
			$this->output(  $this->getTableCreateSQL( $v ) . $this->nl);
		}
		
		if( $this->data_backup )
		{
		    $this->output(  $this->exportData( $v ) . $this->nl);
		}		
	}
	
	if( sizeof($this->tables_enum) > 0 )
	{	//fix relation problem		
		$this->output($this->nl."#Restore foreign key constraints checks".$this->nl);
		$this->output("SET FOREIGN_KEY_CHECKS=1;".$this->nl."###");
	}
	
	if($this->out_type == 1) return ($this->buffer);
	else echo ($this->buffer);
	
} 

function getValue( $query )
 {
 	$result = mysql_query( $query );

 	if ( !$result || mysql_num_rows( $result ) == 0 )
 		return "";

 	return mysql_result( $result, 0, 0 );
 }
//---------------------------------------------
//---getTableCreateSQL get table create sql
//---------------------------------------------
function getTableCreateSQL( $table ){
     $schema_create = '';
    $auto_increment = '';
	
	$schema_create = $this->nl . 
					'#--------------------------------------------------------------------------' . $this->nl . 
					'# Table structure for table ' . $this->backquote($table) . $this->nl . 
					'#--------------------------------------------------------------------------' . $this->nl . $this->nl;

	
	if($this->drop_table_if_exists){
	  $schema_create .= '# drop table '	. $this->backquote( $table ) . $this->nl;
	  $schema_create .= 'DROP TABLE IF EXISTS  ' . $this->backquote( $table ) . ';' . $this->nl;
	  $schema_create .= '#' . $this->nl . $this->nl;
	}	
	
	$result = mysql_query('SHOW CREATE TABLE ' . $this->backquote($this->dbname) . '.' . $this->backquote($table));
	
	if ($result == FALSE || mysql_num_rows($result) == 0) {
	  return '';
	}
    
	$tmpres = mysql_fetch_array($result);
	
	$schema_create .= str_replace("\n", $this->nl."     ", $tmpres[1]);//set create sql
	
	//get autoincrement line
	$result = mysql_query('SHOW TABLE STATUS FROM ' . $this->backquote($this->dbname) . " LIKE '$table'");
	
    if ($result != FALSE && mysql_num_rows($result) > 0) {
      $tmpres = mysql_fetch_array($result);
      if (!empty($tmpres['Auto_increment'])) {
       $auto_increment = ' AUTO_INCREMENT=' . $tmpres['Auto_increment'] . ' ';
      }
	}
	
	$schema_create .= $auto_increment . ';';
	
	
	return $schema_create;	
	 
} 
//---------------------------------------------
//---exportData - create dump data SQL
//---------------------------------------------
function exportData( $table ){
	$buffer = '';
	$schema_insert = $this->nl .
			  		 '#' . $this->nl .
			  		 '# Dumping data for table '. $this->backquote( $table ) . $this->nl .
			  		 '#' . $this->nl . $this->nl;
	
	$result      = mysql_query( 'SELECT * FROM ' . $table );
    
	if ($result == FALSE) return "";
	
    $fields_cnt = mysql_num_fields($result);
    $rows_cnt   = mysql_num_rows($result);
	
	if($rows_cnt <= 0) return "";

    $field_types = $this->fieldTypes( $table );

        // Checks whether the field is an integer or not
    for ($j = 0; $j < $fields_cnt; $j++) {
       $field_set[$j] = $this->backquote(mysql_field_name($result, $j));
       $type          = $field_types[$field_set[$j]];

       if ($type == 'tinyint' || $type == 'smallint' || $type == 'mediumint' || $type == 'int' ||
                $type == 'bigint'  || ($this->mysql_int_version < 40100 && $type == 'timestamp')) {
                $field_num[$j] = TRUE;
       } else {
                $field_num[$j] = FALSE;
       }
       // blob
       if ($type == 'blob' || $type == 'mediumblob' || $type == 'longblob' || $type == 'tinyblob') {
                $field_blob[$j] = TRUE;
       } else {
                $field_blob[$j] = FALSE;
       }
     }
	 
	 
	 $fields = implode(', ', $field_set);
     $schema_insert .= 'INSERT INTO ' . $this->backquote( $table ) . ' (' . $fields . ') VALUES ' . $this->nl;
	 
	 $search       = array("\x00", "\x0a", "\x0d", "\x1a"); //\x08\\x09, not required
     $replace      = array('\0', '\n', '\r', '\Z');
     $current_row  = 0;

     while ($row = mysql_fetch_row($result)) {
            $current_row++;
            for ($j = 0; $j < $fields_cnt; $j++) {
                if (!isset($row[$j])) {
                    $values[]     = 'NULL';
                } else if ($row[$j] == '0' || $row[$j] != '') {
                    // a number
                    if ($field_num[$j]) {
                        $values[] = $row[$j];
                    // a not empty blob
                    } else if ($field_blob[$j] && !empty($row[$j])) {
                        $values[] = '0x' . bin2hex($row[$j]);
                    // a string
                    } else {
                        $values[] = "'" . str_replace($search, $replace, $this->sqlAddslashes($row[$j])) . "'";
                    }
                } else {
                    $values[]     = "''";
                } // end if
            } // e

			
	   $schema_insert  .= '(' . implode(', ', $values) . ')';
       unset($values);
	   
	   $schema_insert .=  ($current_row < $rows_cnt ? ',' : ';') . $this->nl;
	 }
	 
	 mysql_free_result($result);
	 
	 return $schema_insert;
}
//---------------------------------------------
//---sqlAddslashes
//---------------------------------------------
function sqlAddslashes($a_string = '', $is_like = FALSE, $crlf = FALSE){
            if ($is_like) {
                $a_string = str_replace('\\', '\\\\\\\\', $a_string);
            } else {
                $a_string = str_replace('\\', '\\\\', $a_string);
            }

            if ($crlf) {
                $a_string = str_replace("\n", '\n', $a_string);
                $a_string = str_replace("\r", '\r', $a_string);
                $a_string = str_replace("\t", '\t', $a_string);
            }

            $a_string = str_replace('\'', '\\\'', $a_string);

            return $a_string;
} // en
//---------------------------------------------
//---add back quotes
//---------------------------------------------
function backquote( $a_name, $do_it = TRUE ){
	
	$do_it = $do_it && $this->set_backquote;

    if ($do_it && $this->mysql_int_version >= 32306 && !empty($a_name) && trim($a_name) != '*') {
      if (is_array($a_name)){
         $result = array();
         reset($a_name);
         while(list($key, $val) = each($a_name)) {
           $result[$key] = '`' . $val . '`';
         }return $result;
      } else {
	      return '`' . $a_name . '`';
        }
     } 
	 
	 return $a_name;
     
}
//---------------------------------------------
//---field types
//---------------------------------------------
function fieldTypes( $table ) {

   	$table_def = mysql_query('SHOW FIELDS FROM ' . $this->dbname . '.' . $table);
	
   	while($row = @mysql_fetch_array( $table_def )) {
       	$types[$this->backquote($row['Field'])] = ereg_replace('\\(.*', '', $row['Type']);
   	}
	
   	return $types;
}
//---------------------------------------------
//---output handlet (to strim or file)
//---------------------------------------------
function output( $line ){
	$this->buffer .= $line;
} 
//---------------------------------------------
//---set defines
//---------------------------------------------
function set_defines(){
	// MySQL Version
    $result = mysql_query('SELECT VERSION() AS version');
	$row = mysql_fetch_array($result);
	
	$this->mysql_version = $row['version'];
	
	$match = explode('.', $row['version']);
	$this->mysql_int_version =  (int)sprintf('%d%02d%02d', $match[0], $match[1], intval($match[2]));
	
	
    if (!empty($_SERVER['HTTP_USER_AGENT'])) {
        $HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
    } else if (!empty($HTTP_SERVER_VARS['HTTP_USER_AGENT'])) {
        $HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];
    } else if (!isset($HTTP_USER_AGENT)) {
        $HTTP_USER_AGENT = '';
    }

    
	$this->user_os = strtoupper( $HTTP_USER_AGENT );//
	
	$this->nl = "\n";
	
    if($this->user_os == 'WIN') {
      $this->nl = "\r\n";
    }else 
	if($this->nl == 'MAC') {
      $this->nl = "\r";
    }
 	
}
}
?>