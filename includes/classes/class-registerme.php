<?php
class registerme extends autotable {
	public function __construct() {
		parent::__construct();
	}
	function processRegister($post) {
		$queryCheck = "select * from users_validation where email = '".addslashes(stripslashes(trim($post['email'])))."'";
		$rsCheck = mysql_query($queryCheck) or die("Error in line no. ".__LINE__." in file ".__FILE__." due to ".mysql_error()); 
		if(mysql_num_rows($rsCheck)) {
			$rs = mysql_query("select * from users where (username = '".addslashes(stripslashes(trim($post['username'])))."' or email = '".addslashes(stripslashes(trim($post['email'])))."')") or die("Error in line no. ".__LINE__." in file ".__FILE__." due to ".mysql_error()); 
			if(mysql_num_rows($rs)>0) {
				$errorMessage .= "Email or Username already exits. Please try another.";
				return 0;
			} else {
				$recCheck = mysql_fetch_array($rsCheck);
				$post['ref_id'] = $recCheck['user_id'];
				
				$post['password'] = md5($post['password']);
				$uid = parent::tableInsert("users", "user_id", $post);
				
				mysql_query("update users_validation set confirmed = 1 where valid_id = '".$recCheck['valid_id']."'") or die("Error in line no. ".__LINE__." in file ".__FILE__." due to ".mysql_error()); 
				return 1;
			}
		} else {
			return 2;
		}
	}
}
?>