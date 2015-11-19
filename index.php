<?php
ikutkan("conf.php");
$config = json_decode(file_get_contents("config.json"));

if(isset($_GET['apa']))
	$apa = $_GET['apa'];
else
	$apa = "";
	
if(isset($_GET['file'])){
	header("Cache-Control: private, max-age=10800, pre-check=10800");
	header("Pragma: private");
	header("Expires: " . date(DATE_RFC822,strtotime(" 7 day")));
	$ext = pathinfo($_GET['apa'], PATHINFO_EXTENSION);
	if($ext=='css') header("Content-Type: text/css");
	if($ext=='js')header("Content-Type: text/js");
	if(strpos(".eot.svg.ttf.woff.otf.",$ext)!==false) header("Content-Type: application/octet-stream");

	if(file_exists($apa))
		readfile($apa);
	else if(file_exists("phar://waruga.phar/$apa")){
		readfile("phar://waruga.phar/$apa");
	}else{
		
		echo "not exists";
	}
	die();
}else
//cek apakah diluar
if(file_exists("include/$apa.php")){
	include "include/$apa.php";

//cek apakah di phar
}else if(file_exists("phar://waruga.phar/include/$apa.php")){
	include "phar://waruga.phar/include/$apa.php";
}else{
	ikutkan("include/home.php");
}
ikutkan("footer.php");

function ikutkan($file){
	global $apa,$_GET,$_POST,$config,$db,$KELUARGA,$bulanArray;
	if(file_exists($file))
		include $file;
	else
		include 'phar://waruga.phar/'.$file;
}