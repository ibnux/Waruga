<?php
/*if(isset($_GET['s'])){
	$width = $_GET['s'];
	$height = $_GET['s'];
}else{
	$width = $_GET['w'];
	$height = $_GET['h'];
}
if(empty($width)) $width = 200;
if(empty($height)) $height = 200;*/
$width = 200;
$height = 200;

if(file_exists($_GET['p'])){
	if(isset($_GET['kotak'])){
		$filename = $_GET['p'].".thumb.kotak";
		if(!file_exists($filename)){
			//$filename = $_GET['p'];
			$temp = getimagesize($_GET['p']);
			$width_orig = $temp[0];
			$height_orig = $temp[1];
			$mime = $temp['mime'];
			if($width_orig < $height_orig) $smallSide = $width_orig; //find biggest length
			else $smallSide = $height_orig; 
			$image_p = imagecreatetruecolor($width,$height); 
			//imagesavealpha($image_p, true); 
			//imagealphablending($image_p, false); 
			//$background = imagecolorallocatealpha($image_p, 255, 255, 255, 127); 
			//imagefilledrectangle($image_p, 0, 0, $width, $height, $background); 
			//imagealphablending($image_p, true); 
		
			if($mime=="image/jpeg") $image = imagecreatefromjpeg($_GET['p']);
			if($mime=="image/png") $image = imagecreatefrompng($_GET['p']);
			if($mime=="image/gif") $image = imagecreatefromgif($_GET['p']);
			imagecopyresampled($image_p, $image, 0, 0, ($width_orig-$smallSide)/2, 0, $width, $height, $smallSide, $smallSide);
			imagedestroy($image);
			header("Content-Type: image/jpeg");
			imagejpeg($image_p,$filename,80);
			imagedestroy($image_p);
		}
		header("location: $filename");
		die();
	}else if(isset($_GET['kotakasli'])){
		$filename = $_GET['p'].".thumb.kotakasli";
		if(!file_exists($filename)){
			$temp = getimagesize($_GET['p']);
			$width_orig = $temp[0];
			$height_orig = $temp[1];
			$mime = $temp['mime'];
			$ratio_orig = $width_orig/$height_orig;
			if ($width/$height > $ratio_orig) {
			   $width_res = $height*$ratio_orig;
			   $height_res = $width_res/$ratio_orig;
			}else{
				$height_res = $width/$ratio_orig;
				$width_res = $height_res*$ratio_orig;
			}
			/*echo "$width $height\r\n".
				 ($width/$height)." $ratio_orig\r\n".
				 "$width_orig $height_orig\r\n". 
				 "$width_res $height_res";
			die();*/
			if($mime=="image/jpeg") $image = imagecreatefromjpeg($_GET['p']);
			if($mime=="image/png") $image = imagecreatefrompng($_GET['p']);
			if($mime=="image/gif") $image = imagecreatefromgif($_GET['p']);
			$image_p = imagecreatetruecolor($width,$height); 
			$background = imagecolorallocate($image_p, 255, 255, 255);
			imagefilledrectangle($image_p, 0, 0, $width, $height, $background); 
			imagecopyresampled($image_p, $image,($width-$width_res)/2,($height-$height_res)/2,
								0,0,$width_res,$height_res,$width_orig,$height_orig);
			imagedestroy($image);
			imagejpeg($image_p,$filename,80);
			imagedestroy($image_p);
		}
		header("location: $filename");
		die();
	}
	
}else{
	$filename = "./data/.foto/thumb.kosong";
	if(!file_exists($filename)){
		$im = imagecreatetruecolor($width,$width);
		imagesavealpha($im, true);
		imagealphablending($im, false); 
		$background = imagecolorallocatealpha($im, 255,255,255, 127);
		imagefilledrectangle($im, 0, 0, $width, $height, $background); 
		//imagealphablending($im, true); 
		imagegif($im,$filename);
		imagedestroy($im);
	}
	header("location: $filename");
	die();
}
die();