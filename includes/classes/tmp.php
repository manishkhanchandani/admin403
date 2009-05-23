<?php
class tmp extends Myabstractclass {

	public function __construct($dbFrameWork) {
		$this->dbFrameWork = $dbFrameWork;
	}
	
	public function validateProduct($post) {
		if(!trim($post['name'])) {
			$error .= "Please fill the name. ";
			unset($post["MM_insert"]);
		}
		if(!trim($post['description'])) {
			$error .= "Please fill the description. ";
			unset($post["MM_insert"]);
		}
		if(!trim($post['tags'])) {
			$error .= "Please fill the tags. ";
			unset($post["MM_insert"]);
		}
		if(trim($_POST['imageurl'])) {
			$images = new Image;
			if(!$images->isUrlTrue(trim($_POST['imageurl']))) {
				$error .= "Please fill the correct image url. ";
				unset($post["MM_insert"]);
			}
		}
		if(!trim($post['end_dt'])) {
			$error .= "Please fill the end date. ";
			unset($post["MM_insert"]);
		}
		if ($post["MM_insert"] != "form1") {
			throw new Exception($error);
		}
		return true;
	}
}
?>