<?
$nama = $_GET['nama'];
$kode = $_GET['kode'];
$url = 'http://zxing.org/w/chart?cht=qr&chs=480x480&chld=L&choe=UTF-8&chl='.$kode;
$hasil = file_get_contents($url);
if(empty($hasil)){
	header("location: $url");
	die();
}
$im = imagecreatefromstring($hasil);
$textcolor = imagecolorallocate($im, 0, 0, 0);
$textWidth = imagefontwidth( 5 ) * strlen( $nama );
imagestring($im, 5, (480-$textWidth)/2, 20, $nama, $textcolor);
imagepng($im,"./data/temp.qr",0);
imagedestroy($im);
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.str_replace(' ','_',$nama).".png");
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . filesize("./data/temp.qr"));
flush();
readfile("./data/temp.qr");
exit();