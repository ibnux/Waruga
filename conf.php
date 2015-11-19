<?php
ini_set('display_errors','off');
date_default_timezone_set('Asia/Jakarta');
$pathDB = "./data/base.db";
if(!file_exists('./data/'))
	mkdir("./data/");
if(!file_exists($pathDB))
	$buattabel = true;
else
	$buattabel = false;
$db = new SQLite3($pathDB);
if($buattabel)createTableDefault();
if(!file_exists($pathDB))die("Cannot Create Database. please chmod 777 folder data");

$KELUARGA = "./data/.foto/keluarga_";
if(!file_exists("./data/.foto/"))
	mkdir("./data/.foto/",0777,true);

if(isset($_GET['recreate']))createTableDefault();
function createTableDefault(){
	global $db;
	$db->exec("CREATE TABLE IF NOT EXISTS `t_warga` (
	  `id_warga` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	  `X` INTEGER,
	  `Y` INTEGER,
	  `label_warga` varchar(200),
	  `blok_rumah` varchar(4),
	  `nomor_rumah` varchar(4),
	  `nomor_kk` varchar(50),
	  `ktp_suami` varchar(50),
	  `agama_suami` varchar(15) DEFAULT 'ISLAM',
	  `warga_negara_suami` varchar(30),
	  `nama_suami` varchar(100),
	  `telepon_suami` varchar(30),
	  `email_suami` varchar(255),
	  `pekerjaan_suami` varchar(200),
	  `pendidikan_suami` varchar(50),
	  `tempat_lahir_suami` varchar(50),
	  `tglahir_suami` INTEGER DEFAULT -30610245972,
	  `ktp_istri` varchar(50),
	  `agama_istri` varchar(15) DEFAULT 'ISLAM',
	  `warga_negara_istri` varchar(30),
	  `nama_istri` varchar(100),
	  `telepon_istri` varchar(30),
	  `email_istri` varchar(255),
	  `pekerjaan_istri` varchar(200),
	  `pendidikan_istri` varchar(50),
	  `tempat_lahir_istri` varchar(50),
	  `tglahir_istri` INTEGER DEFAULT -30610245972,
	  `catatan` varchar(300),
	  `custom_data` varchar,	  
	  `tgl_masuk` INTEGER DEFAULT 0,
	  `tgl_keluar` INTEGER DEFAULT 0,
	  `last_update` INTEGER
	)");
	$db->exec("CREATE TABLE IF NOT EXISTS `t_warga_tambahan` (
	  `id_warga_tambahan` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	  `id_warga` INTEGER NOT NULL,
	  `no_ktp` varchar(50),
	  `warga_negara` varchar(30) DEFAULT 'WNI',
	  `agama` varchar(15) DEFAULT 'ISLAM',
	  `pendidikan` varchar(50),
	  `pekerjaan` varchar(200),
	  `nama_lengkap` varchar(300),
	  `jenis_kelamin` varchar(1),
	  `nomor_hp` varchar(20),
	  `email` varchar(255),
	  `status_kawin` varchar(10),
	  `hubungan_keluarga` varchar(30),
	  `catatan` varchar(300),
	  `tempat_lahir` varchar(50),
	  `tanggal_lahir` INTEGER DEFAULT -30610245972,
	  `tanggal_masuk` INTEGER DEFAULT 0,
	  `tanggal_keluar` INTEGER DEFAULT 0,
	  `last_update` INTEGER
	)");
	$db->exec("CREATE TABLE IF NOT EXISTS `t_sms` (
	  `id_sms` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	  `topik_sms` varchar(30) NOT NULL,
	  `isi_sms` varchar(300) NOT NULL,
	  `terakhir_dikirim` INTEGER,
	  `tanggal_dibuat` INTEGER
	)");
	$db->exec("CREATE TABLE IF NOT EXISTS `t_iuran` (
	  `id_iuran` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	  `id_warga` INTEGER NOT NULL,
	  `jumlah_iuran` varchar(300),
	  `id_kategori` INTEGER,
	  `catatan` varchar(300),
	  `tanggal_iuran` INTEGER
	)");
	$db->exec("CREATE TABLE IF NOT EXISTS `t_tamu` (
	  `id_tamu` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	  `id_warga` INTEGER NOT NULL,
	  `nama_tamu` varchar(50),
	  `hp_tamu` varchar(50),
	  `alamat_tamu` varchar(300),
	  `lama_bertamu` INTEGER,
	  `tanggal_bertamu` INTEGER
	)");
	$db->exec("CREATE TABLE IF NOT EXISTS `t_keuangan` (
	  `id_keuangan` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	  `keterangan` varchar(100) NOT NULL,
	  `jumlah_uang` INTEGER,
	  `tipe_transaksi` varchar(10) NOT NULL,
	  `id_kategori` INTEGER,
	  `id_warga` INTEGER NOT NULL,
	  `tanggal_transaksi` INTEGER
	)");
	$db->exec("CREATE TABLE t_kategori (id_kategori INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, 
			nama_kategori varchar (100) NOT NULL, 
			tipe_kategori varchar (100) NOT NULL, 
			saldo_kategori BIGINT DEFAULT (0));");
	$db->exec("INSERT INTO `t_kategori` VALUES(0,'Tidak Ada','',0)");
	$db->exec("INSERT INTO `t_kategori` VALUES(1,'Iuran Bulanan','KAS',0)");
}
function array_unique_compact($a){
  $tmparr = array_unique($a);
  $i=0;
  $newarr = array();
  foreach ($tmparr as $v) {
    $newarr[$i] = trim($v);
    $i++;
  }
  return array_unique(array_filter($newarr));
}

function getTags(){
	global $db;
	$types = array();
	$rs = $db->query("select distinct label_warga from t_warga where label_warga<>'' and tgl_keluar=0");
	while($ftypes = $rs->fetchArray()){
		$types = array_merge($types, explode(",", $ftypes['label_warga']));
	}
	$types = array_unique_compact($types);
	return $types;
}

function hitungTahun($tgl1,$tgl2){
	$d1 = new DateTime($tgl1);
	$d2 = new DateTime($tgl2);
	
	return $d2->diff($d1);
}

function showAlert($msg,$tipe){
?><div class="alert alert-<?=$tipe?> alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <?=$msg?>
</div><?
}

function selfURL() { 
    $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : ""; 
    $protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s; 
    $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]); 
    return $protocol."://".$_SERVER['SERVER_NAME'].$port.substr($_SERVER['REQUEST_URI'],0,strpos($_SERVER['REQUEST_URI'],'?')); 
} 

function strleft($s1, $s2){ 
	return substr($s1, 0, strpos($s1, $s2)); 
}

function deleteDir($dirPath) {
	if(file_exists($dirPath)){
		$temp = opendir($dirPath);
		$folderpanel = array();
		while ($folder = readdir($temp)) {
			if (!in_array($folder, array("..", "."))) {
				if (is_dir($dirPath.$folder)) {
					deleteDir($dirPath.$folder."/");
				}else
					unlink($dirPath.$folder);
			}
		}
		rmdir($dirPath);
		closedir($temp);
	}
}

function sendSMS($nomorhp,$sms){
	global $config;
	$status = file_get_contents($config->SMS_URL_Parameter."?".
								$config->SMS_Number_Parameter."=$nomorhp&".
								$config->SMS_Text_Parameter."=".urlencode($sms)."&".
								$config->SMS_Additional_Parameter);
	if(strpos($status,$config->SMS_Success_Parameter)===false)
		return '<span class="label label-danger"><span class="glyphicon glyphicon-remove"></span>'." GAGAL</span><br>\r\n";
	else
		return '<span class="label label-success"><span class="glyphicon glyphicon-ok"></span>'." SUKSES</span><br>\r\n";
}
$db->createFunction('labelExist', 'labelExist', 2);
function labelExist($label, $dicari){
	if(strpos($label,$dicari)===false)
		return false;
	else
		return true;
}
$bulanArray = array("Januari","Pebruari","Maret","April","Mei","Juni",
				"Juli","Agustus","September","Oktober","November","Desember");
$blnArray = array("Jan","Peb","Mar","Apr","Mei","Jun",
				"Jul","Agt","Sep","Okt","Nov","Des");

//ENKRIPSI
class Security {
	public static function encrypt($input, $key) {
		$size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB); 
		$input = Security::pkcs5_pad($input, $size); 
		$td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, ''); 
		$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND); 
		mcrypt_generic_init($td, $key, $iv); 
		$data = mcrypt_generic($td, $input); 
		mcrypt_generic_deinit($td); 
		mcrypt_module_close($td); 
		$data = base64_encode($data); 
		return $data; 
	} 
	private static function pkcs5_pad ($text, $blocksize) { 
		$pad = $blocksize - (strlen($text) % $blocksize); 
		return $text . str_repeat(chr($pad), $pad); 
	} 
	public static function decrypt($sStr, $sKey) {
		$decrypted= mcrypt_decrypt(
			MCRYPT_RIJNDAEL_128,
			$sKey, 
			base64_decode($sStr), 
			MCRYPT_MODE_ECB
		);
		$dec_s = strlen($decrypted); 
		$padding = ord($decrypted[$dec_s-1]); 
		$decrypted = substr($decrypted, 0, -$padding);
		return $decrypted;
	}	
}