<?php
class autotable extends mysql {
	public function __construct() {
		parent::__construct();
	}
	public function display_table_fields($table_name) {
		$rs = mysql_query("select * from ".$table_name);
		$i = 0;
		$register_main_arr = array();
		while ($i < mysql_num_fields($rs)) {
			$meta = mysql_fetch_field($rs, $i);
			$register_arr[] = $meta->name;
			$i++;
		}
		return $register_arr;
	}
	public function tableInsert($table_name,$pk,$postarray) {
		$register_arr = array();
		$register_arr = $this->display_table_fields($table_name);
		$rs = mysql_query("insert into ".$table_name."(".$pk.")values('')") or die("Error in inserting ".__LINE__." ".mysql_error());
		$uid = mysql_insert_id();
		$query = "update ".$table_name." set ";
		foreach($postarray as $key=>$value) {
			if(gettype($value)=="array") {
				$string = '';
				foreach($value as $val) {
					if(strlen($val)>0) { 
						$val = (!get_magic_quotes_gpc()) ? addslashes($val) : $val;
						$string .= $val.'|';
					}
				}
				$string = substr($string,0,-1);
				if(in_array($key,$register_arr)) {
					$query .= $key."='".$string."',"; 
				}
			} else {
				if(in_array($key,$register_arr)) {
					$value = (!get_magic_quotes_gpc()) ? addslashes($value) : $value;
					$query .= $key."='".$value."',";
				}
			}
		}
		$query = substr($query,0,-1);
		$query .= " where ".$pk." = '".$uid."'";
		$result = mysql_query($query) or die('Error in line '.__LINE__.' of File '.__FILE__.': '.mysql_error());
		return $uid;
	}
	
	public function tableEdit($table_name,$pk,$postarray,$uid) {
		$register_arr = array();
		$register_arr = $this->display_table_fields($table_name);
		$query = "update ".$table_name." set ";
		foreach($postarray as $key=>$value) {
			if(gettype($value)=="array") {
				$string = '';
				foreach($value as $val) {
					if(strlen($val)>0) { 
						$val = (!get_magic_quotes_gpc()) ? addslashes($val) : $val;
						$string .= $val.'|';
					}
				}
				$string = substr($string,0,-1);
				if(in_array($key,$register_arr)) {
					$query .= $key."='".$string."',"; 
				}
			} else {
				if(in_array($key,$register_arr)) {
					$value = (!get_magic_quotes_gpc()) ? addslashes($value) : $value;
					$query .= $key."='".$value."',";
				}
			}
		}
		$query = substr($query,0,-1);
		$query .= " where ".$pk." = '".$uid."'";
		$result = mysql_query($query) or die("Error"); 
		return $uid;
	}

}
?>