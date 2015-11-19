<?php

if(isset($_GET['p']) && strlen($_GET['p'])>3 && file_exists($_GET['p'])){
	$ext = substr($_GET['p'],-3);
	if(strpos('|php|js|css|',$ext)===false){
		$filename = $_GET['f'];
		if(empty($filename))
			$filename = basename($_GET['p'],'wrg')."jpg";
		else
			$filename = str_replace(' ','_',$filename).".jpg";
		/*header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.basename($_GET['p'],'wrg').'.jpg"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($_GET['p']));
		*/
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.$filename);
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($_GET['p']));
		flush();
		readfile($_GET['p']);
		exit;
	}else{
		header("HTTP/1.0 403 Forbidden");
		echo "File dilarang.\n";
	}
}else{
	header("HTTP/1.0 404 Not Found");
	echo "File tidak ada.\n";
}
die();