<?php
class Common {
	public $cacheSecs = 300;
	public $username = "admin";
	public $password = "password";
	
	public function __construct($dbFrameWork) {
		$this->db = $dbFrameWork;
	}
	
	public function auth() {
		if($_POST['SubmitLOGIN']) {
			if($_COOKIE['adminlogin']) {

			} else if($_POST['u']==$this->username && $_POST['p']==$this->password) {
				setcookie('adminlogin',$_POST['u'],0,"/");
				header("Location: ".$_SERVER['PHP_SELF']);
				exit;
			} else {
				echo "<p class='errorMessage'>Please enter correct login details to enter.</p>";
				echo '<form name="form1" method="post" action="">User:<input name="u" type="text" id="u">Password:<input name="p" type="password" id="p"><input type="submit" name="SubmitLOGIN" value="Login"></form>
				';
				exit;
			}
		} else {
			if($_COOKIE['adminlogin']) {

			} else {
				echo '<form name="form1" method="post" action="">User:<input name="u" type="text" id="u">Password:<input name="p" type="password" id="p"><input type="submit" name="SubmitLOGIN" value="Login"></form>
				';
				exit;
			}
		}
	}
	
	public function nocache() {
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
		header("Cache-Control: no-cache, must-revalidate" );
		header("Pragma: no-cache" );
	}
	public function addslash($txt) {
		return addslashes(stripslashes(trim($txt)));
	}
	public function logout($type) {
		switch($type) {
			case 'admin':
				setcookie("admin[admin_id]","",0,"/");
				setcookie("admin[email]","",0,"/");
				setcookie("admin[name]","",0,"/");
			break;
		}
	}
	public function login_admin($post) {		
		$sql = "select admin_id, email, name from admin where email = ".$this->db->qstr($post['email'],get_magic_quotes_gpc())." and password = '".md5($post['password'])."'";
		$return = $this->selectRecord($sql);
		if($return) {
			setcookie("admin[admin_id]",$return[0]['admin_id'],0,"/");
			setcookie("admin[email]",$return[0]['email'],0,"/");
			setcookie("admin[name]",$return[0]['name'],0,"/");
			$this->login_report($return[0]['admin_id'], 'admin');
			return 1;
		} else {
			return 0;
		}
	}
	public function login_vendor($post) {
		$sql = "select vendor_id, email, name from vendor where email = ".$this->db->qstr($post['email'],get_magic_quotes_gpc())." and password = '".md5($post['password'])."'";
		$return = $this->selectRecord($sql);
		if($return) {	
			setcookie("vendor[vendor_id]",$return[0]['vendor_id'],0,"/");
			setcookie("vendor[email]",$return[0]['email'],0,"/");
			setcookie("vendor[name]",$return[0]['name'],0,"/");
			$this->login_report($return[0]['vendor_id'], 'vendor');
			return 1;
		} else {
			return 0;
		}
	}
	public function login_employee($post) {		
		$sql = "select * from employee where email = ".$this->db->qstr($post['email'],get_magic_quotes_gpc())." and password = '".md5($post['password'])."'";		
		$return = $this->selectRecord($sql);
		if($return) {	
			setcookie("employee[employee_id]",$return[0]['employee_id'],0,"/");
			setcookie("employee[email]",$return[0]['email'],0,"/");
			setcookie("employee[name]",$return[0]['firstname'].' '.$rec['lastname'],0,"/");
			$this->login_report($return[0]['employee_id'], 'employee');
			return 1;
		} else {
			return 0;
		}
	}
	
	public function login_employer($post) {
		$sql = "select employer_id, email, name from employer where email = ".$this->db->qstr($post['email'],get_magic_quotes_gpc())." and password = '".md5($post['password'])."'";
		$return = $this->selectRecord($sql);
		if($return) {
			setcookie("employer[employer_id]",$return[0]['employer_id'],0,"/");
			setcookie("employer[email]",$return[0]['email'],0,"/");
			setcookie("employer[name]",$return[0]['name'],0,"/");
			$this->login_report($return[0]['employer_id'], 'employer');
			return 1;
		} else {
			$sql = "select e.employer_id from employee as e, users as u where e.employee_id = u.id and e.email = ".$this->db->qstr($post['email'],get_magic_quotes_gpc())." and e.password = '".md5($post['password'])."' and u.acting_as = 'Employer'";
			$return2 = $this->selectRecord($sql);
			if($return2) {
				$employer_id = $return2[0]['employer_id'];
				$sql = "select employer_id, email, name from employer where employer_id = '".$employer_id."'";
				$return3 = $this->selectRecord($sql);
				if($return3) {
					setcookie("employer[employer_id]",$return3[0]['employer_id'],0,"/");
					setcookie("employer[email]",$return3[0]['email'],0,"/");
					setcookie("employer[name]",$return3[0]['name'],0,"/");
					$this->login_report($return3[0]['employer_id'], 'employer');
					return 1;
				}
			} else {
				$sql = "select e.employer_id from employer_access as e, users as u where e.compliance_id = u.id and e.compliance_designee_email = ".$this->db->qstr($post['email'],get_magic_quotes_gpc())." and e.compliance_designee_password = '".md5($post['password'])."' and u.login_type = 'Designee' AND u.acting_as = 'Employer'";
				$return4 = $this->selectRecord($sql);
				if($return4) {
					$employer_id = $return4[0]['employer_id'];
					$sql = "select employer_id, email, name from employer where employer_id = '".$employer_id."'";
					$return5 = $this->selectRecord($sql);
					if($return5) {
						setcookie("employer[employer_id]",$return5[0]['employer_id'],0,"/");
						setcookie("employer[email]",$return5[0]['email'],0,"/");
						setcookie("employer[name]",$return5[0]['name'],0,"/");
						$this->login_report($return5[0]['employer_id'], 'employer');
						return 1;
					}
				}
			}
		} 
		return 0;
	}
	public function login_report($id, $action) {
		$sql = "INSERT INTO `report_login` (`uid` , `type`) VALUES ( '".$id."', '".$action."')";
		$this->db->Execute($sql);
	}
	
	public function login($action, $post) {
		// login
		switch($action) {
			case 'admin':
				$r = $this->login_admin($post);
				break;
			case 'vendor':
				$r = $this->login_vendor($post);
				break;
			case 'employer':
				$r = $this->login_employer($post);
				break;
			case 'employee':
				$r = $this->login_employee($post);
				break;
		}
		return $r;
	}
	public function insertRecord($table_name, $pk, $record) {
		$sql = "SELECT * FROM $table_name WHERE $pk = -1";  
		# Select an empty record from the database 
		$rs = $this->db->Execute($sql); # Execute the query and get the empty recordset 
		if($this->db->ErrorMsg()) {
			throw new Exception($this->db->ErrorMsg());
		}
		# Pass the empty recordset and the array containing the data to insert 
		# into the GetInsertSQL function. The function will process the data and return 
		# a fully formatted insert sql statement. 
		$insertSQL = $this->db->GetInsertSQL($rs, $record);
		$this->db->Execute($insertSQL); 
		if($this->db->ErrorMsg()) {
			throw new Exception($this->db->ErrorMsg());
		}
		$uid = $this->db->Insert_ID();
		return $uid;
	}
	
	public function editRecord($table_name, $pk, $record, $uid) {
		$sql = "SELECT * FROM `$table_name` WHERE `$pk` = $uid";  
		# Select a record to update 
		$rs = $this->db->Execute($sql); // Execute the query and get the existing record to update 
		if($this->db->ErrorMsg()) {
			throw new Exception($this->db->ErrorMsg());
		}
		# Pass the single record recordset and the array containing the data to update 
		# into the GetUpdateSQL function. The function will process the data and return 
		# a fully formatted update sql statement with the correct WHERE clause. 
		# If the data has not changed, no recordset is returned 

		$updateSQL = $this->db->GetUpdateSQL($rs, $record); # Update the record in the database
		if($updateSQL) {
			$return = $this->db->Execute($updateSQL); 
			if($this->db->ErrorMsg()) {
				throw new Exception($this->db->ErrorMsg());
			}		
		}
	}
	
	public function deleteRecord($table_name, $pk, $uid) {
		$sql = "DELETE FROM `$table_name` WHERE `$pk` = $uid";  
		# Select a record to update 
		$rs = $this->db->Execute($sql); // Execute the query and get the existing record to update 
		if($this->db->ErrorMsg()) {
			throw new Exception($this->db->ErrorMsg());
		}
		return true;
	}
	
	public function selectCount($sql) {
		$rs = $this->db->Execute($sql);
		if($this->db->ErrorMsg()) {
			throw new Exception($this->db->ErrorMsg());
		}
		$arr = $rs->FetchRow();
		$cnt = $arr['cnt'];
		return $cnt;
	}
	
	public function selectCacheCount($sql) {
		$rs = $this->db->CacheExecute($this->cacheSecs, $sql);
		if($this->db->ErrorMsg()) {
			throw new Exception($this->db->ErrorMsg());
		}
		$arr = $rs->FetchRow();
		$cnt = $arr['cnt'];
		return $cnt;
	}
	
	public function selectRecord($sql) {
		$rs = $this->db->Execute($sql);
		if($this->db->ErrorMsg()) {
			throw new Exception($this->db->ErrorMsg());
		}
		while ($arr = $rs->FetchRow()) { 
			$return[] = $arr;
		}
		return $return;
	}
	
	public function selectLimitRecord($sql,$max=10,$start=0) {
		$rs = $this->db->SelectLimit($sql, $max, $start);
		if($this->db->ErrorMsg()) {
			throw new Exception($this->db->ErrorMsg());
		}
		while ($arr = $rs->FetchRow()) { 
			$return[] = $arr;
		}
		return $return;
	}
	
	public function selectCacheRecord($sql) {
		$rs = $this->db->CacheExecute($this->cacheSecs, $sql);
		if($this->db->ErrorMsg()) {
			throw new Exception($this->db->ErrorMsg());
		}
		while ($arr = $rs->FetchRow()) { 
			$return[] = $arr;
		}
		return $return;
	}
	
	public function selectCacheLimitRecord($sql, $max=10, $start=0) {
		$rs = $this->db->CacheSelectLimit($this->cacheSecs, $sql, $max, $start);
		if($this->db->ErrorMsg()) {
			throw new Exception($this->db->ErrorMsg());
		}
		while ($arr = $rs->FetchRow()) { 
			$return[] = $arr;
		}
		return $return;
	}
	
	public function getpaginatedetails($max=10, $page=1, $totalRows) {
		if($max) $return['max'] = $max; else $return['max'] = 10;
		if($page) $return['page'] = $page-1; else $return['page'] = 0;
		$return['start'] = $return['page'] * $return['max'];
		$return['totalPages'] = ceil($totalRows/$max)-1;
		return $return;
	}
	
	public function getUsersUid($array) {
		if($array) {
			foreach($array as $k=>$v) {
				$rec[$v['type']][] = $v['uid'];
			}
			if($rec['admin']) {
				$fieldArr = array_unique($rec['admin']);
				$field = implode(", ", $fieldArr);
				$sql = "select admin_id, name from admin where admin_id in (".$field.")";
				$rs = $this->db->CacheExecute($this->cacheSecs, $sql);
				if($this->db->ErrorMsg()) {
					throw new Exception($this->db->ErrorMsg());
				}
				while ($arr = $rs->FetchRow()) { 
					$return['admin'][$arr['admin_id']] = $arr;
				}
			}
			if($rec['vendor']) {
				$fieldArr = array_unique($rec['vendor']);
				$field = implode(", ", $fieldArr);
				$sql = "select * from vendor where vendor_id in (".$field.")";
				$rs = $this->db->CacheExecute($this->cacheSecs, $sql);
				if($this->db->ErrorMsg()) {
					throw new Exception($this->db->ErrorMsg());
				}
				while ($arr = $rs->FetchRow()) { 
					$return['vendor'][$arr['vendor_id']] = $arr;
				}
			}
			if($rec['employee']) {
				$fieldArr = array_unique($rec['employee']);
				$field = implode(", ", $fieldArr);
				$sql = "select firstname as name, employee_id from employee where employee_id in (".$field.")";
				$rs = $this->db->CacheExecute($this->cacheSecs, $sql);
				if($this->db->ErrorMsg()) {
					throw new Exception($this->db->ErrorMsg());
				}
				while ($arr = $rs->FetchRow()) { 
					$return['employee'][$arr['employee_id']] = $arr;
				}
			}
			if($rec['employer']) {
				$fieldArr = array_unique($rec['employer']);
				$field = implode(", ", $fieldArr);
				$sql = "select name, employer_id from employer where employer_id in (".$field.")";
				$rs = $this->db->CacheExecute($this->cacheSecs, $sql);
				if($this->db->ErrorMsg()) {
					throw new Exception($this->db->ErrorMsg());
				}
				while ($arr = $rs->FetchRow()) { 
					$return['employer'][$arr['employer_id']] = $arr;
				}
			}
		}
		return $return;
	}
	public function monitor($host) {
		$host = str_replace("http://www.","",$host);
		$host = str_replace("https://www.","",$host);
		$host = str_replace("http://","",$host);
		$host = str_replace("https://","",$host);
		$host = str_replace("www.","",$host);
		$up = @fsockopen($host, 80, $errno, $errstr, 10); 
		if($up) { 
			$status = 'up'; 
		} else {
			$status = 'down';
		}
		return $status;
	}
	
	public function monitorEachSite() {
		$curTime = time();
		$time = $curTime-(60*15);
		$sql = "select * from monitor_sites WHERE lastmodified < $time";
		$sites = $this->selectCacheRecord($sql);
		if($sites) {
			foreach($sites as $v) {	
				$siteId[] = $v['site_id'];
				$host = $v['site'];		
				$status = $this->monitor($host);
				$return[$v['site']] = $status;
				$sql = "INSERT INTO `monitor` ( `site_id` , `status` ) VALUES ('".$v['site_id']."', '".$status."' )";
				$this->db->Execute($sql);				
			}
			$siteIds = implode(", ", $siteId);
			$sql = "UPDATE `monitor_sites` set lastmodified = '".$curTime."' WHERE site_id IN (".$siteIds.")";
			$this->db->Execute($sql);
		}
		return $return;
	}
}
?>