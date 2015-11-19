<?
if($_GET['filter']=="bapak")
	$hasil = $db->query("SELECT `blok_rumah`, `nomor_rumah`, 
				`nama_suami`, `telepon_suami` 
				FROM t_warga 
				WHERE tgl_keluar=0");
else if($_GET['filter']=="ibu")
	$hasil = $db->query("SELECT `blok_rumah`, `nomor_rumah`,
				`nama_istri`,telepon_istri 
				FROM t_warga 
				WHERE tgl_keluar=0");
else $hasil = $db->query("SELECT `blok_rumah`, `nomor_rumah`, `nama_suami`, 
				`nama_istri`, `telepon_suami`,telepon_istri 
				FROM t_warga 
				WHERE tgl_keluar=0");
//header('Content-Type: text/x-vcard');  
//header('Content-Disposition: inline; filename= "kartuWarga.vcf"');  
$kartu = "";
while($row = $hasil->fetchArray()){
	if(!empty($row['nama_suami']) && !empty($row['telepon_suami'])){
		$kartu .= "BEGIN:VCARD\r\n";
		$kartu .= "VERSION:3.0\r\n";
		$kartu .= "FN:".$row['nama_suami']." ".$row['blok_rumah']."-".$row['nomor_rumah']."\r\n";
		$kartu .= "ORG:".$config->Judul_Aplikasi.";\r\n";
		if($config->Gunakan_Blok=="true")
			$kartu .= "TITLE:Blok ".$row['blok_rumah']." No ".$row['nomor_rumah']."\r\n";
		else
			$kartu .= "TITLE:No ".$row['nomor_rumah']."\r\n";
		$kartu .= "TEL;type=CELL:".$row['telepon_suami']."\r\n";
		$kartu .= "X-ABUID:5AD380FD-B2DE-4261-BA99-DE1D1DB52FBE\:ABPerson\r\n";
		$kartu .= "END:VCARD\r\n\r\n";
	}
	if(!empty($row['nama_istri']) && !empty($row['telepon_istri'])){
		$kartu .= "BEGIN:VCARD\r\n";
		$kartu .= "VERSION:3.0\r\n";
		$kartu .= "FN:".$row['nama_istri']." ".$row['blok_rumah']."-".$row['nomor_rumah']."\r\n";
		$kartu .= "ORG:".$config->Judul_Aplikasi.";\r\n";
		if($config->Gunakan_Blok=="true")
			$kartu .= "TITLE:Blok ".$row['blok_rumah']." No ".$row['nomor_rumah']."\r\n";
		else
			$kartu .= "TITLE:No ".$row['nomor_rumah']."\r\n";
		$kartu .= "TEL;type=CELL:".$row['telepon_istri']."\r\n";
		$kartu .= "X-ABUID:5AD380FD-B2DE-4261-BA99-DE1D1DB52FBE\:ABPerson\r\n";
		$kartu .= "END:VCARD\r\n\r\n";
	}
}
//die($kartu);
$file = "./data/".$_GET['filter']."_".str_replace(" ","_",$config->Judul_Aplikasi).".vcf";
file_put_contents($file,$kartu);
header("location: $file");