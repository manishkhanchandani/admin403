<?php
class login extends mysql {
	function __construct() {
		parent::__construct();
	}
	function processLogin($post) {
		$rs = mysql_query("select * from users where username = '".addslashes(stripslashes(trim($post['username'])))."' AND password = '".md5(trim($post['password']))."'") or die("Error in line no. ".__LINE__." in file ".__FILE__." due to ".mysql_error()); 
		if(mysql_num_rows($rs)>0) {
			$rec = mysql_fetch_array($rs);
			if($rec['status']=='Active') {
				mysql_query("update users set last_login_dt = '".time()."' where user_id = '".$rec['user_id']."'") or die("Error in line no. ".__LINE__." in file ".__FILE__." due to ".mysql_error()); 
				if($rec) {
					foreach($rec as $key => $value) {
						setcookie("user[$key]", $value, 0, "/");
					}
				}
				$success = 1;
				return 1;
			} else {
				switch($rec['status']) {
					case 'Inactive':
						return 2;
						break;
					case 'Suspended':
						return 3;
						break;
					case 'Pending':
						return 4;
						break;
				}
			}
		} else {
			return 0;
		}
	}
}
?>