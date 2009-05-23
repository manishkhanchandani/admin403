<?php
class users extends mysql {
	public $user_id;
	public $orderby;
	public $order;
	public $table;
	public $condition;
	public $query;
	
	function __construct() {
		parent::__construct();
	}	
	
	public function getUserDetail() {
		$rs = mysql_query("select * from users where user_id = '".$this->user_id."'") or die('error');
		$return['num'] = mysql_num_rows($rs);
		$return['rec'] = mysql_fetch_array($rs);
		return $return;
	}
	
	public function getCplDetail() {
		$rs = mysql_query("select * from cpl_details where user_id = '".$this->user_id."'") or die('error');
		$return['num'] = mysql_num_rows($rs);
		$return['rec'] = mysql_fetch_array($rs);
		return $return;
	}
	
	public function getGenList() {
		$query = "select * from ".$this->table;
		if($this->condition) {
			$query .= " where ".$this->condition;
		}
		if($this->orderby) {
			$query .= " order by ".$this->orderby;
		}
		if($this->order) {
			$query .= " ".$this->orderby;
		}
		$rs = mysql_query($query) or die('error'.mysql_error());
		$return['query'] = $query;
		$return['num'] = mysql_num_rows($rs);
		while($rec = mysql_fetch_array($rs)) {
			$return['rec'][] = $rec;
		}
		return $return;
	}
	
	public function getGenList2() {
		$rs = mysql_query($this->query) or die('error'.mysql_error());
		$return['query'] = $this->query;
		$return['num'] = mysql_num_rows($rs);
		while($rec = mysql_fetch_array($rs)) {
			$return['rec'][] = $rec;
		}
		return $return;
	}
	
	public function getImageOne() {
		$query = "select * from cpl_images where user_id = '".$this->user_id."' and approved = 1 LIMIT 1";
		$rs = mysql_query($this->query) or die('error'.mysql_error());
		$return['query'] = $query;
		$return['num'] = mysql_num_rows($rs);
		if(mysql_num_rows($rs)) {
			$return['rec'] = mysql_fetch_array($rs);
		}
		return $return;
	}
}
?>