<?php
class Default_Table
{
	var $tablename;         // table name
	var $dbname;            // database name
	var $rows_per_page;     // used in pagination
	var $pageno;            // current page number
	var $lastpage;          // highest page number
	var $fieldlist;         // list of fields in this table
	var $data_array;        // data from the database
	var $errors;            // array of error messages
	
	public function __construct() {
		$this->tablename       = 'default';
		$this->dbname          = 'default';
		$this->rows_per_page   = 10;
		
		$this->fieldlist = array('column1', 'column2', 'column3');
		$this->fieldlist['column1'] = array('pkey' => 'y');
	} // constructor
	
	function getData ($where) {
		$this->data_array = array();
		$pageno          = $this->pageno;
		$rows_per_page   = $this->rows_per_page;
		$this->numrows   = 0;
		$this->lastpage  = 0;
		
		global $dbconnect, $query;
      	$dbconnect = db_connect($this->dbname) or trigger_error("SQL", E_USER_ERROR);
		
		/*
			column1='value'
			column1='value' AND column2='value'
			(column1='value' AND column2='value') OR (column1='value' AND column2='value')
		*/
		
		if (empty($where)) {
			$where_str = NULL;
		} else {
			$where_str = "WHERE $where";
		} // if
		
		$query = "SELECT count(*) FROM $this->tablename $where_str";
		$result = mysql_query($query, $dbconnect) or trigger_error("SQL", E_USER_ERROR);
		$query_data = mysql_fetch_row($result);
		$this->numrows = $query_data[0];
		
		if ($this->numrows <= 0) {
			$this->pageno = 0;
			return;
		} // if
		
		if ($rows_per_page > 0) {
			$this->lastpage = ceil($this->numrows/$rows_per_page);
		} else {
			$this->lastpage = 1;
		} // if
		
		if ($pageno == '' OR $pageno <= '1') {
			$pageno = 1;
		} elseif ($pageno > $this->lastpage) {
			$pageno = $this->lastpage;
		} // if
		$this->pageno = $pageno;
		
		if ($rows_per_page > 0) {
			$limit_str = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;
		} else {
			$limit_str = NULL;
		} // if
		
		//$query = "SELECT $select_str FROM $from_str $where_str $group_str $having_str $sort_str $limit_str";
		$query = "SELECT * FROM $this->tablename $where_str $limit_str";
      	$result = mysql_query($query, $dbconnect) or trigger_error("SQL", E_USER_ERROR);
		
		while ($row = mysql_fetch_assoc($result)) {
			$this->data_array[] = $row;
		} // while
		
		mysql_free_result($result);
		
		return $this->data_array;
	}
	function insertRecord ($fieldarray) {
		$this->errors = array();
		
		global $dbconnect, $query;
		$dbconnect = db_connect($this->dbname) or trigger_error("SQL", E_USER_ERROR);
		$fieldlist = $this->fieldlist;
		
		foreach ($fieldarray as $field => $fieldvalue) {
			if (!in_array($field, $fieldlist)) {
				unset ($fieldarray[$field]);
			} // if
		} // foreach
		
		$query = "INSERT INTO $this->tablename SET ";
		foreach ($fieldarray as $item => $value) {
			$query .= "$item='".addslashes(stripslashes(trim($value)))."', ";
		} // foreach
		
		$query = rtrim($query, ', ');
		
		$result = @mysql_query($query, $dbconnect);
		if (mysql_errno() <> 0) {
			if (mysql_errno() == 1062) {
				$this->errors[] = "A record already exists with this ID.";
			} else {
				trigger_error("SQL", E_USER_ERROR);
			} // if
		} // if
		
		return;
	}
	
	function updateRecord ($fieldarray) {
		$this->errors = array();
		global $dbconnect, $query;
		$dbconnect = db_connect($this->dbname) or trigger_error("SQL", E_USER_ERROR);
		
		$fieldlist = $this->fieldlist;
		foreach ($fieldarray as $field => $fieldvalue) {
			if (!in_array($field, $fieldlist)) {
				unset ($fieldarray[$field]);
			} // if
		} // foreach
		
		$where  = NULL;
		$update = NULL;
		foreach ($fieldarray as $item => $value) {
			if (isset($fieldlist[$item]['pkey'])) {
				$where .= "$item='$value' AND ";
			} else {
				$update .= "$item='$value', ";
			} // if
		} // foreach
		
		$where  = rtrim($where, ' AND ');
		$update = rtrim($update, ', ');
		
		$query = "UPDATE $this->tablename SET $update WHERE $where";
		$result = mysql_query($query, $dbconnect) or trigger_error("SQL", E_USER_ERROR);
		
		return;
	}
	
	function deleteRecord ($fieldarray) {
		$this->errors = array();
		global $dbconnect, $query;
		$dbconnect = db_connect($this->dbname) or trigger_error("SQL", E_USER_ERROR);
		
		$fieldlist = $this->fieldlist;
		$where  = NULL;
		foreach ($fieldarray as $item => $value) {
			if (isset($fieldlist[$item]['pkey'])) {
				$where .= "$item='$value' AND ";
			} // if
		} // foreach
		
		$where  = rtrim($where, ' AND ');
		
		$query = "DELETE FROM $this->tablename WHERE $where";
		$result = mysql_query($query, $dbconnect) or trigger_error("SQL", E_USER_ERROR);
		
		return;
	}
}	  
?>