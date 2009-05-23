<?php
class imagethumbnail extend mysql {
	public function __construct() {
		parent::__construct();
	}
	
	public function convertImageName($title) {
		$input = $title; 
		$input = trim($input);
		$url_lookup = strip_tags($input);
		$url_lookup = str_replace(" ", "-", $url_lookup);
		$url_lookup = str_replace("&amp", "and", $url_lookup);
		$url_lookup = ereg_replace("[^a-zA-Z0-9]+", "-", $url_lookup);
		$url_lookup = ereg_replace("-+$", "", $url_lookup);
		$url_lookup = strtolower($url_lookup);
		return $url_lookup;
	}
	
	function converFile($src_file, $dest_path, $src_file_realname, $uid) {
		
		$src_file_realname = strtolower($src_file_realname);
		$ext = strrchr($src_file_realname,".");
		$ext = strtolower($ext);
		$fullext = $ext;
		$ext = substr($ext,1);
		$pos = strpos($src_file_realname,$fullext);
		$first = substr($src_file_realname,0,$pos);
		$first = convertImageName($first);
		$fn = $first."-".$uid.$fullext;
		$fn = strtolower($fn);
		$dest = $dest_path.$fn;
		
		move_uploaded_file($src_file, $dest);
		chmod($dest, 0644);	
		
		return $fn;	
	}
	
	function converThumbnail($settings, $src_file, $dest_path, $src_file_realname, $uid) {
		list($ex_width,$ex_height) = getimagesize($src_file);
		
		$src_file_realname = strtolower($src_file_realname);
		$ext = strrchr($src_file_realname,".");
		$ext = strtolower($ext);
		$fullext = $ext;
		$ext = substr($ext,1);
		$pos = strpos($src_file_realname,$fullext);
		$first = substr($src_file_realname,0,$pos);
		$first = convertImageName($first);
		$fn = $first."-".$uid.$fullext;
		$fn = strtolower($fn);
		$dest = $dest_path.$fn;
		
		$size = get_thumbnail_size($ex_width, $ex_height, $settings);
		build_thumbnail($src_file, $dest, $new_width=$size['width'], $new_height=$size['height'], $width=$ex_width, $height=$ex_height, $format=$ext);
		chmod($dest, 0644);	
		
		return $fn;		
		// usage
		/*
		$settings["photo_width"] = 80;
		$settings["photo_height"] = 80;		
		$src_file = $_FILES['userfile']['tmp_name'][$i];
		$dest_path = "images/myphotos/thumbs/";
		$src_file_realname = $_FILES['userfile']['name'][$i];
		$uid = time();
		$fn = converThumbnail($settings, $src_file, $dest_path, $src_file_realname, $uid);
		*/
	}
 
	public function get_thumbnail_size($ex_width, $ex_height, $settings){
		//$settings["photo_width"] = 300;
		//$settings["photo_height"] = 300;
		
		//landscape image or square
		if($ex_width >= $ex_height){
			
			if($ex_width > $settings["photo_width"]){
				
				$ds_width_ex  = $settings["photo_width"];			
				$ratio_ex     = $ex_width / $ds_width_ex;		
				$ds_height_ex = $ex_height / $ratio_ex;
				$ds_height_ex = round($ds_height_ex);
			
				if($ds_height_ex > $settings["photo_height"])
					$ds_height_ex = $settings["photo_height"];
					
			}//if
			else{
				
				$ds_width_ex  = $ex_width;
				$ds_height_ex = $ex_height;
			
			}//else
			
		}//if
		
		//portrait image
		elseif($ex_width < $ex_height){
			
			if($ex_height > $settings["photo_height"]){
			
				$ds_height_ex = $settings["photo_height"];
				$ratio_ex     = $ex_height / $ds_height_ex;
				$ds_width_ex  = $ex_width / $ratio_ex;
				$ds_width_ex  = round($ds_width_ex);
			
				if($ds_width_ex > $settings["photo_width"])
					$ds_width_ex = $settings["photo_width"];
				
			}//if
			else{
				
				$ds_width_ex  = $ex_width;
				$ds_height_ex = $ex_height;
			
			}//else
			
		}//elseif
			
		$size['width'] = $ds_width_ex;
		$size['height'] = $ds_height_ex;
		return $size;
		
	}//build_thumbnailes

	public function build_thumbnail($filename, $dest, $new_width, $new_height, $width, $height, $format="jpg"){
		// $format = "png" or "jpg" or "gif";
		$image_p = imagecreatetruecolor($new_width, $new_height);
		if($format=="png") {
			$image = imagecreatefrompng($filename);
		} else if($format=="jpg") {
			$image = imagecreatefromjpeg($filename);	
		} else if($format=="gif") {
			$image = imagecreatefromgif($filename);	
		} else {
			$image = imagecreatefromjpeg($filename);
		}
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		if($format=="png") {
			imagepng($image_p, $dest);
		} else if($format=="jpg") {
			imagejpeg($image_p, $dest, 100);
		} else if($format=="gif") {
			imagegif($image_p, $dest);
		} else {
			imagejpeg($image_p, $dest, 100);
		}
		imagedestroy($image_p);
	}

	/*
	// Usage
	$src_path = "images/large/";
	$src_file = "98760003_JPG-1177062613-.jpg";
	$filename = $src_path.$src_file; 
	
	$settings["photo_width"] = 150;
	$settings["photo_height"] = 150;
	
	list($ex_width,$ex_height) = getimagesize($filename);
	$ext = strrchr($src_file,".");
	$fullext = $ext;
	$ext = substr($ext,1);
	$pos = strpos($src_file,$fullext);
	$fn = substr($src_file,0,$pos);
	
	$dest_path = "images/tmp/";
	$dest_file = "naveen3";
	$dest = $dest_path.$dest_file.$fullext;
	
	$size = get_thumbnail_size($ex_width, $ex_height, $settings);
	build_thumbnail($filename, $dest, $new_width=$size['width'], $new_height=$size['height'], $width=$ex_width, $height=$ex_height, $format=$ext);
	*/
}
?>