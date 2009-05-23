<?php
include_once('adodb_conn.php');
require_once('HTTP/Upload.php');
/*
global $db;	
$ADODB_CACHE_DIR = 'ADODB_cache';
$db->SetFetchMode(ADODB_FETCH_ASSOC);
*/
class MyEngine {

	public $form_id;
	public $form_name;
	public $form;
	public $postarray;
	
	/*
	 * check email is valid
	 * param: email
	 * return boolean
	*/
	public function emailvalidity($email) {
		if (eregi('^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$', $email)) {
			// this is a valid email domain!
			return 1;
		} else {
			// this email domain doesn't exist! bad dog! no biscuit!
			return 0;
		}
	}
	
	/*
	 * check value validity
	 * param: post variables, key, detail
	 * return boolean
	*/
	public function checkvalidity($postarray, $key, $detail) {
		if($detail['compulsory']==1) {
			if(!trim($postarray[$key])) {
				$ret['compulsory'] = 1;
			}
		} 
		if($detail['validity']=="") {
		
		} else if(gettype($detail['validity'])=="array") {
			foreach($detail['validity'] as $validitykey=>$validityval) {
				switch($validitykey) {
					case 'length':
						$min = $validityval[0];
						$max = $validityval[1];
						if($min) {
							if(strlen(trim($postarray[$key]))<$min) {
								$ret['validity'] = 1;
							} 
						}
						if($max) {
							if(strlen(trim($postarray[$key]))>$max) {
								$ret['validity'] = 1;
							}
						}
						break;
					case 'field':
						if(trim($postarray[$key])!=trim($postarray[$validityval])) {
							$ret['validity'] = 1;
						} 
						break;
					case 'multiple':
						$min = $validityval[0];
						$max = $validityval[1];
						if($min) {
							if(count($postarray[$key])<$min) {
								$ret['validity'] = 1;
							} 
						}
						if($max) {
							if(count($postarray[$key])>$max) {
								$ret['validity'] = 1;
							}
						}
						break;
					case 'file':
						if($_FILES[$key]['name']) {
							$ext = $this->getExtention($_FILES[$key]['name']);
							if(in_array($ext, $validityval)) {
							
							} else {
								$ret['validity'] = 1;
							}
						} else {
							$ret['validity'] = 1;
						}
						break;
				}
			}
		} else if(gettype($detail['validity'])!="array") {
			switch($detail['validity']) {
				case 'int':
					if(is_numeric(trim($postarray[$key])) || is_float(trim($postarray[$key]))) {
					
					} else {
						$ret['validity'] = 1;
					}
					break;
				case 'string':
					if(is_string(trim($postarray[$key]))) {
					
					} else {
						$ret['validity'] = 1;
					}
					break;
				case 'email':
					if($this->emailvalidity(trim($postarray[$key]))) {
					
					} else {
						$ret['validity'] = 1;
					}
					break;
			}
		}
		return $ret;
	}
	
	public function processNewForm($form, $post, $postarray) {
		$return = array();
		if($form) {
			$return[$name]['sample'] = "";
			foreach($form as $name => $array) {
				foreach($array as $key=>$detail) {
					$default = $detail['defaultvalue'];
					$return[$name][$key]['label'] = $detail['label'];
					if($post==1) {
						$default = $postarray[$key];
					}
					switch($form[$name][$key]['type']) {
						case 'text':
							if($post==1) {
								$ret = $this->checkvalidity($postarray, $key, $detail);
								if($ret['compulsory']) { 
									$return[$name]['errorMessage'][$key] =  $detail['errormessage'];
								}
								if($ret['validity']) { 
									$return[$name]['errorMessage'][$key] .=  $detail['errormessagevalidity'];
								}							
								$return[$name]['errorMessageSample'] .= $return[$name]['errorMessage'][$key];
							}
							$return[$name][$key]['field'] = "<input type='text' name='".$key."' value='".$default."' size='".$detail['size']."'>";
							$return[$name]['sample'] .= "<p><span class='errorMessage'>".$return[$name]['errorMessage'][$key]."</span><br /><b>".$return[$name][$key]['label'].": </b>".$return[$name][$key]['field']."</p>
							";						
							break;
						case 'textarea':
							if($post==1) {
								$ret = $this->checkvalidity($postarray, $key, $detail);
								if($ret['compulsory']) { 
									$return[$name]['errorMessage'][$key] =  $detail['errormessage'];
								}
								if($ret['validity']) { 
									$return[$name]['errorMessage'][$key] .=  $detail['errormessagevalidity'];
								}							
								$return[$name]['errorMessageSample'] .= $return[$name]['errorMessage'][$key];
							}
							$return[$name][$key]['field'] = "<textarea name='".$key."' rows='".$detail['size']['rows']."' cols='".$detail['size']['cols']."' style='".$detail['size']['style']."'>".$default."</textarea>";
							$return[$name]['sample'] .= "<p><span class='errorMessage'>".$return[$name]['errorMessage'][$key]."</span><br /><b>".$return[$name][$key]['label'].": </b><br />".$return[$name][$key]['field']."</p>
							";
							break;
						case 'hidden':
							if($post==1) {
								$ret = $this->checkvalidity($postarray, $key, $detail);
								if($ret['compulsory']) { 
									$return[$name]['errorMessage'][$key] =  $detail['errormessage'];
								}
								if($ret['validity']) { 
									$return[$name]['errorMessage'][$key] .=  $detail['errormessagevalidity'];
								}							
								$return[$name]['errorMessageSample'] .= $return[$name]['errorMessage'][$key];
							}
							$return[$name][$key]['field'] = "<input type='hidden' name='".$key."' value='".$default."'>";
							$hidden .= $return[$name][$key]['field'];
							break;
						case 'password':
							if($post==1) {
								$ret = $this->checkvalidity($postarray, $key, $detail);
								if($ret['compulsory']) { 
									$return[$name]['errorMessage'][$key] =  $detail['errormessage'];
								}
								if($ret['validity']) { 
									$return[$name]['errorMessage'][$key] .=  $detail['errormessagevalidity'];
								}							
								$return[$name]['errorMessageSample'] .= $return[$name]['errorMessage'][$key];
							}
							$return[$name][$key]['field'] = "<input type='password' name='".$key."' value='".$default."' size='".$detail['size']."'>";
							$return[$name]['sample'] .= "<p><span class='errorMessage'>".$return[$name]['errorMessage'][$key]."</span><br /><b>".$return[$name][$key]['label'].": </b>".$return[$name][$key]['field']."</p>
							";
							break;
						case 'radio':
							if($post==1) {
								$ret = $this->checkvalidity($postarray, $key, $detail);
								if($ret['compulsory']) { 
									$return[$name]['errorMessage'][$key] =  $detail['errormessage'];
								}
								if($ret['validity']) { 
									$return[$name]['errorMessage'][$key] .=  $detail['errormessagevalidity'];
								}							
								$return[$name]['errorMessageSample'] .= $return[$name]['errorMessage'][$key];
							}
							$return[$name][$key]['field'] = "";
							foreach($detail['initialvalue'] as $ky=>$val) {
								$return[$name][$key]['field'] .= "<input type='radio' name='".$key."' value='".$ky."'";
								if($default==$ky) {
									$return[$name][$key]['field'] .= " checked";
								}
								$return[$name][$key]['field'] .= ">".$val." ";
							}
							$return[$name]['sample'] .= "<p><span class='errorMessage'>".$return[$name]['errorMessage'][$key]."</span><br /><b>".$return[$name][$key]['label'].": </b>".$return[$name][$key]['field']."</p>
							";
							break;
						case 'list':
							if($post==1) {
								$ret = $this->checkvalidity($postarray, $key, $detail);
								if($ret['compulsory']) { 
									$return[$name]['errorMessage'][$key] =  $detail['errormessage'];
								}
								if($ret['validity']) { 
									$return[$name]['errorMessage'][$key] .=  $detail['errormessagevalidity'];
								}							
								$return[$name]['errorMessageSample'] .= $return[$name]['errorMessage'][$key];
							}
							$return[$name][$key]['field'] = "<select name='".$key."'><option value=''>Select</option>
							";
							foreach($detail['initialvalue'] as $ky=>$val) {
								$return[$name][$key]['field'] .= "<option value='".$ky."'";
								if($default==$ky) {
									$return[$name][$key]['field'] .= " selected";
								}
								$return[$name][$key]['field'] .= ">".$val."</option>
								";
							}
							$return[$name][$key]['field'] .= "</select>";
							$return[$name]['sample'] .= "<p><span class='errorMessage'>".$return[$name]['errorMessage'][$key]."</span><br /><b>".$return[$name][$key]['label'].": </b>".$return[$name][$key]['field']."</p>
							";
							break;
						case 'listmultiple':
							if($post==1) {
								$ret = $this->checkvalidity($postarray, $key, $detail);
								if($ret['compulsory']) { 
									$return[$name]['errorMessage'][$key] =  $detail['errormessage'];
								}
								if($ret['validity']) { 
									$return[$name]['errorMessage'][$key] .=  $detail['errormessagevalidity'];
								}							
								$return[$name]['errorMessageSample'] .= $return[$name]['errorMessage'][$key];
							}
							$return[$name][$key]['field'] = "<select name='".$key."[]' size='".$detail['size']."' multiple>";
							foreach($detail['initialvalue'] as $ky=>$val) {
								$return[$name][$key]['field'] .= "<option value='".$ky."'";
								if($default) {
									if(in_array($ky,$default)) {
										$return[$name][$key]['field'] .= " selected";
									}
								}
								$return[$name][$key]['field'] .= ">".$val."</option>
								";
							}
							$return[$name][$key]['field'] .= "
							</select>
							";
							$return[$name]['sample'] .= "<p><span class='errorMessage'>".$return[$name]['errorMessage'][$key]."</span><br /><b>".$return[$name][$key]['label'].": </b><br />".$return[$name][$key]['field']."</p>
							";
							break;
						case 'checkboxmultiple':
							if($post==1) {
								$ret = $this->checkvalidity($postarray, $key, $detail);
								if($ret['compulsory']) { 
									$return[$name]['errorMessage'][$key] =  $detail['errormessage'];
								}
								if($ret['validity']) { 
									$return[$name]['errorMessage'][$key] .=  $detail['errormessagevalidity'];
								}							
								$return[$name]['errorMessageSample'] .= $return[$name]['errorMessage'][$key];
							}
							$return[$name][$key]['field'] = "";
							foreach($detail['initialvalue'] as $ky=>$val) {
								$return[$name][$key]['field'] .= "<input type='checkbox' name='".$key."[]' value='".$ky."'";
								if($default) {
									if(in_array($ky,$default)) {
										$return[$name][$key]['field'] .= " checked";
									}
								}
								$return[$name][$key]['field'] .= ">".$val." ";
							}
							$return[$name]['sample'] .= "<p><span class='errorMessage'>".$return[$name]['errorMessage'][$key]."</span><br /><b>".$return[$name][$key]['label'].": </b>".$return[$name][$key]['field']."</p>
							";
							break;
						case 'checkbox':
							if($post==1) {
								$ret = $this->checkvalidity($postarray, $key, $detail);
								if($ret['compulsory']) { 
									$return[$name]['errorMessage'][$key] =  $detail['errormessage'];
								}
								if($ret['validity']) { 
									$return[$name]['errorMessage'][$key] .=  $detail['errormessagevalidity'];
								}							
								$return[$name]['errorMessageSample'] .= $return[$name]['errorMessage'][$key];
							}
							$return[$name][$key]['field'] = "";
							$return[$name][$key]['field'] .= "<input type='checkbox' name='".$key."' value='".$detail['initialvalue']."'";
							if($detail['initialvalue']==$default) {
								$return[$name][$key]['field'] .= " checked";
							}
							$return[$name][$key]['field'] .= ">";
							$return[$name]['sample'] .= "<p><span class='errorMessage'>".$return[$name]['errorMessage'][$key]."</span><br />".$return[$name][$key]['field']." ".$return[$name][$key]['label']."</p>
							";
							break; 
						case 'file':
							if($post==1) {
								$ret = $this->checkvalidity($postarray, $key, $detail);
								if($ret['compulsory']) { 
									$return[$name]['errorMessage'][$key] =  $detail['errormessage'];
								}
								if($ret['validity']) { 
									$return[$name]['errorMessage'][$key] .=  $detail['errormessagevalidity'];
								}							
								$return[$name]['errorMessageSample'] .= $return[$name]['errorMessage'][$key];
							}
							$return[$name][$key]['field'] = "<input type='file' name='".$key."' value='".$default."' size='".$detail['size']."'>";
							$return[$name]['sample'] .= "<p><span class='errorMessage'>".$return[$name]['errorMessage'][$key]."</span><br /><b>".$return[$name][$key]['label'].": </b>".$return[$name][$key]['field']."</p>
							";						
							break;
					}
				}
			}
		}
		$return[$name]['sample'] .= $hidden;
		return $return;
	}
	/*
	 * create new form in database
	 * param form_name
	 * return form_id
	*/
	public function createForm($form_name) {
		global $db;
		
		$sql = "INSERT INTO `engine_form` (`form_name` ) VALUES ( '".addslashes(stripslashes(trim($form_name)))."' )";
		$rs = $db->Execute($sql);
		if ($rs === false) { 
			print 'error inserting: '.$db->ErrorMsg().'<BR>'; 
		} 
		$sql = "SELECT max(form_id) as lastID from engine_form";
		$rs = $db->Execute($sql);
		if ($rs === false) { 
			print 'error inserting: '.$db->ErrorMsg().'<BR>'; 
		} 
		$rec = $rs->FetchRow();
		$this->form_id = $rec['lastID']; 
		return $rec['lastID'];
	}
	
	/*
	 * create new fields in table
	 * param form_fields as array
	 * return boolean
	*/
	public function createFields($form, $form_name) {
		global $db;
		
		if($form[$form_name]) {
			$sql = "INSERT INTO `engine_field` (`form_id`, `field_name`, `field_label` ) VALUES ";
			foreach($form[$form_name] as $key => $value) {
				$sql .= "('".$this->form_id."',  '".addslashes(stripslashes(trim($key)))."', '".addslashes(stripslashes(trim($value)))."' ), ";
			}
			$sql = substr($sql, 0, -2);
			
			$rs = $db->Execute($sql);
			if ($rs === false) { 
				print 'error inserting: '.$db->ErrorMsg().'<BR>'; 
			} 
		}
		return 1;
	}
	public function getExtention($image) {
		$ext = substr(strrchr($image, "."),1);
		return $ext;
	}
	public function getThumbnailSize($ex_width, $ex_height, $maxheight=80, $maxwidth=80) {
		if($ex_width >= $ex_height){		
			if($ex_width > $maxwidth){			
				$ds_width_ex  = $maxwidth;			
				$ratio_ex     = $ex_width / $ds_width_ex;		
				$ds_height_ex = $ex_height / $ratio_ex;
				$ds_height_ex = round($ds_height_ex);		
				if($ds_height_ex > $maxheight)
					$ds_height_ex = $maxheight;				
			} else {			
				$ds_width_ex  = $ex_width;
				$ds_height_ex = $ex_height;		
			}		
		} else if($ex_width < $ex_height){		
			if($ex_height > $maxheight){		
				$ds_height_ex = $maxheight;
				$ratio_ex     = $ex_height / $ds_height_ex;
				$ds_width_ex  = $ex_width / $ratio_ex;
				$ds_width_ex  = round($ds_width_ex);		
				if($ds_width_ex > $maxwidth)
					$ds_width_ex = $maxwidth;			
			} else {			
				$ds_width_ex  = $ex_width;
				$ds_height_ex = $ex_height;
			}		
		}		
		$size['width'] = $ds_width_ex;
		$size['height'] = $ds_height_ex;
		return $size;
	}

	public function buildThumbnail($url, $destination, $maxheight, $maxwidth, $format) {
		list($ex_width, $ex_height) = getimagesize($url);
		$size = $this->getThumbnailSize($ex_width, $ex_height, $maxheight, $maxwidth);
	
		$image_p = imagecreatetruecolor($size['width'], $size['height']);
		
		if($format=="png") {
			$image = imagecreatefrompng($url);
		} else if($format=="jpg") {
			$image = imagecreatefromjpeg($url);	
		} else if($format=="gif") {
			$image = imagecreatefromgif($url);	
		}
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $size['width'], $size['height'], $ex_width, $ex_height);
		if($format=="png") {
			imagepng($image_p, $destination);
		} else if($format=="jpg") {
			imagejpeg($image_p, $destination, 100);
		} else if($format=="gif") {
			imagegif($image_p, $destination);
		}
		imagedestroy($image_p);
	}

	public function insertRecord($form, $form_name, $form_id, $user_id, $pid, $status, $postarray, $formarray='') {
		global $db;
		
		$sql = "insert into engine_record (form_id, user_id, created_dt, status, pid) values ('".$form_id."', '".$user_id."', '".date('Y-m-d H:i:s')."', '".$status."', '".$pid."')";
		$rs = $db->Execute($sql);
		if ($rs === false) { 
			print 'error inserting: '.$db->ErrorMsg().'<BR>'; 
		}		
		$sql = "SELECT max(record_id) as lastID from engine_record";
		$rs = $db->Execute($sql);
		if ($rs === false) { 
			print 'error inserting: '.$db->ErrorMsg().'<BR>'; 
		} 
		$rec = $rs->FetchRow(); 
		$uid = $rec['lastID'];
		
		foreach($form[$form_name] as $field => $value) {
			if($value['value_type']== "normal") {
				$sql = "insert into engine_data (record_id, form_id, field_id, ".$value['data_type'].") values ('".$uid."', '".$form_id."', '".$value['field_id']."', '".addslashes(stripslashes(trim($postarray[$field])))."')";
				$rs = $db->Execute($sql);
				if ($rs === false) { 
					print 'error inserting: '.$db->ErrorMsg().'<BR>'; 
				}		
			} else if($value['value_type']== "array") {
				$string = serialize($postarray[$field]);
				$string = addslashes(stripslashes(trim($string)));
				$sql = "insert into engine_data (record_id, form_id, field_id, ".$value['data_type'].") values ('".$uid."', '".$form_id."', '".$value['field_id']."', '".$string."')";	
				$rs = $db->Execute($sql);
				if ($rs === false) { 
					print 'error inserting: '.$db->ErrorMsg().'<BR>'; 
				}		
			} else if($value['value_type'] == "file") {
				$filecheck = 1;
				$filecheckfields[$field] = $value;
			}
		}
		if($filecheck==1) {
			$upload = new HTTP_Upload("en");
			$files = $upload->getFiles();
			foreach($files as $key => $file){
				if (PEAR::isError($file)) {
					echo $file->getMessage();
				}
				if ($file->isValid()) {
					$file->setName("uniq");
					$dest_name = $file->moveTo("files/");
					if (PEAR::isError($dest_name)) {
						echo $dest_name->getMessage();
					}
					$real = $file->getProp("real");
					$retFile = $file->getProp();
					$retArray = array('real'=>$retFile['real'], 'name'=>$retFile['name'], 'ext'=>$retFile['ext'], 'size'=>$retFile['size'], 'type'=>$retFile['type']);
					if($retFile['type']=="image/jpeg" || $retFile['type']=="image/png" || $retFile['type']=="image/gif") {
						// create Thumbnails
						$url = "files/".$retFile['name'];
						$destination = "files/thumbs/".$retFile['name'];
						$this->buildThumbnail($url, $destination, $maxheight=70, $maxwidth=70, $format=$retFile['ext']);
					}
					$string = serialize($retArray);
					$string = addslashes(stripslashes(trim($string)));
					$sql = "insert into engine_data (record_id, form_id, field_id, ".$filecheckfields[$key]['data_type'].") values ('".$uid."', '".$form_id."', '".$filecheckfields[$key]['field_id']."', '".$string."')";	
					$rs = $db->Execute($sql);
					if ($rs === false) { 
						print 'error inserting: '.$db->ErrorMsg().'<BR>'; 
					}	
				} elseif ($file->isMissing()) {
					echo "No file was provided.";
				} elseif ($file->isError()) {
					echo $file->errorMsg();
				}
			}
		}
		return $uid;
	}
	public function getFormDetails($form, $post, $postarray) {
	
	}
	public function listRecord() {
		print_r($this->postarray);
	}
	
	public function getEditDetails($record_id) {
		global $db;
		
		$sql = "SELECT *";
		$rs = $db->Execute($sql);
		if ($rs === false) { 
			print 'error inserting: '.$db->ErrorMsg().'<BR>'; 
		} 
		$rec = $rs->FetchRow();

	}
	
	public function editRecord() {
		print_r($this->postarray);
	}
	
	public function deleteRecord() {
		print_r($this->postarray);
	}
	
	public function searh() {
		print_r($this->postarray);
	}
	
	public function searchResult() {
		print_r($this->postarray);
	}
	
	public function createParentChildListBox() {
	
	}
	
	public function createBreadCrumb(){
	
	}
}
?>