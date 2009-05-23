<?php
class messages extends autotable {
	public $user_id;
	public function __construct() {
		parent::__construct();
	}
	function compose($post) {	
		$uid = parent::tableInsert("messages", "message_id", $post);
		return 1;
	}
	function getMessageList() {
		$query = "select messages.*, users.username from messages LEFT JOIN users ON messages.from_user_id = users.user_id where messages.to_user_id = '".$this->user_id."' and messages.to_deleted = 0 order by message_id DESC";
		$rs = mysql_query($query) or die('error in line no. '.__LINE__." of file ".__FILE__." due to ".mysql_error());
		$return['query'] = $query;
		$return['num'] = mysql_num_rows($rs);
		while($rec = mysql_fetch_array($rs)) {
			$return['rec'][] = $rec;
		}
		return $return;
	}
	function delMessage($post) {		
		if($post['rem']) {
			foreach($post['rem'] as $key=>$value) {
				$query = "update messages set to_deleted = 1 where message_id = '".$key."'";
				$rs = mysql_query($query) or die('error in line no. '.__LINE__." of file ".__FILE__." due to ".mysql_error());
			}
			$query = "delete from messages where to_deleted = 1 and from_deleted = 1";
			$rs = mysql_query($query) or die('error in line no. '.__LINE__." of file ".__FILE__." due to ".mysql_error());
		}
		return 1;
	}
	function getMessageListSent() {
		$query = "select messages.*, users.username from messages LEFT JOIN users ON messages.to_user_id = users.user_id where messages.from_user_id = '".$this->user_id."' and messages.from_deleted = 0 order by message_id DESC";
		$rs = mysql_query($query) or die('error in line no. '.__LINE__." of file ".__FILE__." due to ".mysql_error());
		$return['query'] = $query;
		$return['num'] = mysql_num_rows($rs);
		while($rec = mysql_fetch_array($rs)) {
			$return['rec'][] = $rec;
		}
		return $return;
	}
	function delMessageSent($post) {		
		if($post['rem']) {
			foreach($post['rem'] as $key=>$value) {
				$query = "update messages set from_deleted = 1 where message_id = '".$key."'";
				$rs = mysql_query($query) or die('error in line no. '.__LINE__." of file ".__FILE__." due to ".mysql_error());
			}
			$query = "delete from messages where to_deleted = 1 and from_deleted = 1";
			$rs = mysql_query($query) or die('error in line no. '.__LINE__." of file ".__FILE__." due to ".mysql_error());
		}
		return 1;
	}
}
?>