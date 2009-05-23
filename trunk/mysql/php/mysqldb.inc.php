<?
require_once 'tool.inc.php';
//require_once 'global_vars.inc.php';
  
//------------------------------------------------------------------------------
//--------------	mysqlDB	----------------------------------------------------
//------------------------------------------------------------------------------
//begin mysqlDB class
class mysqlDB {

	var	$dblink;
	var $max_ind;
	var $last_res;
	
	var $mysql_version;//alfanum version
	var $mysql_int_version;//integer version
//------------------------------------------------------------------------------
// constructor mysqlDB
//------------------------------------------------------------------------------
	function mysqlDB($p_host=HOST, $p_user=USER, $p_password=PASSWORD, $p_dbname=DATABASENAME) 
	{
//		global $HOST, $USER, $PASSWORD, $DATABASENAME;
//		$p_host=$HOST; $p_user=$USER; $p_password=$PASSWORD; $p_dbname=$DATABASENAME;		
	    $this->dblink = @mysql_connect( $p_host, $p_user, $p_password ) or error( mysql_error()=='' ?  "Could not connect to $p_host" : mysql_error());

		if(trim($p_dbname) != "")
		   if( !mysql_select_db( $p_dbname , $this->dblink ) ) { error( mysql_error()=='' ? "Invalid databasename" : mysql_error() ); };
	}	
//------------------------------------------------------------------------------
// destructor mysqlDB
//------------------------------------------------------------------------------
	function Close() {
	    mysql_close( $this->dblink );
	}
	
	function Free_result()
	{
		mysql_free_result($this->last_res);
	}
	
//------------------------------------------------------------------------------
// execute query
//------------------------------------------------------------------------------
	function Execute( $query ) {
	 
	 $r = mysql_query( $query ,$this->dblink ) or toLog("!!!ERROR!!! ".mysql_errno() . ": " . mysql_error() ,"Invalid query: $query") ;
	 	 	 
	 if(!$r)
	 {
	 	$r = "Error (".mysql_errno().") ".mysql_error();		 
	 }	 	 
	 return $r;
	}
	
//-------------------------------------------------
//define mySQL version integer value
//-------------------------------------------------
function getMySqlVersion()
{
	$result = mysql_query('SELECT VERSION() AS version');
	$row = mysql_fetch_array($result);
	
	$this->mysql_version = $row['version'];
	
	$match = explode('.', $row['version']);
	$this->mysql_int_version =  (int)sprintf('%d%02d%02d', $match[0], $match[1], intval($match[2]));
}

//------------------------------------------------------------------------------
// Get last insert ID
//------------------------------------------------------------------------------
	function Get_last_insert_ID() {
	 $r = mysql_insert_id( $this->dblink );
	 return $r;
	}
	

//------------------------------------------------------------------------------
// get table 
//------------------------------------------------------------------------------
	//!!! not done error catching
	function TableToArray( $query , $key_arr="" , $free=1) 
	{
		$i=0;
		$cnt = sizeof($key_arr);
		$table_array = array();
		
		$result = $this->Execute( $query );
		$this->last_res = $result;
		
		if( mysql_errno($this->dblink) ) return $table_array;
		//empty record set
		if( mysql_num_rows( $result ) <= 0)	return $table_array;		
		
		while ($table_array[$i] = mysql_fetch_assoc($result)) {
			if( $key_arr!="" ) {
				$ta = array();
				for( $ki=0; $ki<$cnt; $ki++ ) {
				 $ta[$ki] = $table_array[$i][$key_arr[$ki]];
				}
			
		 	$table_array[join( "," , $ta )] = $i; 
			}	

		 $i++;
		};

		if( $free == 1) mysql_free_result($result);
		unset($table_array[$i]);
		$this->max_ind = $i; 
		return $table_array;
	}

	
//------------------------------------------------------------------------------
// get table associate $param_key with $param_value 
//------------------------------------------------------------------------------
	function TableAssoc( $param_key , $param_value, $tablename , $where="" ) {
	
		$t_arr = $this->TableToArray("SELECT $param_key,$param_value FROM $tablename".( ($where!="") ? (" WHERE ".$where) : "" ));
		reset ($t_arr);
		$ret_arr = array();
		while (list($key, $value) = each ($t_arr)) {
	           $ret_arr[$t_arr[$key][$param_key]] = $t_arr[$key][$param_value]; 
		}	
		
		return $ret_arr;
	}
	
//------------------------------------------------------------------------------
// show table
//------------------------------------------------------------------------------
	function TableShow( $query ) {

		echo "<hr>";	
		$result = $this->Execute( $query );
		echo "<b>$query</b><br>\n<table border=1>\n";

		while ($row = mysql_fetch_assoc($result)) {

		 	if( !$once ) {
				echo "<tr>";
				while ( list($k,$v) = each($row) ) { echo "<th>$k</th>"; };
				echo "</tr>";	
				$once = 1;
				reset($row);
			}
				
			echo "<tr>";
				while ( list($k,$v) = each($row) ) { echo "<td>$v</td>"; };
			echo "</tr>";	


		 };

	    echo "</table>";

		mysql_free_result($result);
		echo "<hr>";	
	}

}
//end mysqlDB class
  
  





?>