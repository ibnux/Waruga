<?
/*
Sumpah pusing saya disini :))

*/
$pathDBRonda = "./data/baseRonda.db";
if(!file_exists('./data/'))
	mkdir("./data/");
if(!file_exists($pathDBRonda))
	$buattabel = true;
else
	$buattabel = false;
$dbronda = new SQLite3($pathDBRonda);
if($buattabel)createTableRonda();
if(!file_exists($pathDBRonda))die("Cannot Create Database. please chmod 777 folder data");
function createTableRonda(){
	global $dbronda;
	$dbronda->exec("CREATE TABLE IF NOT EXISTS `t_ronda` (
	  `id_ronda` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	  `waktu` INTEGER,
	  `tanggal` INTEGER(2),
	  `bulan` INTEGER(2),
	  `tahun` INTEGER(4),
	  `jam` INTEGER(2),
	  `menit` INTEGER(2),
	  `detik` INTEGER(2),
	  `label` varchar(200),
	  `siapa` varchar(50)
	)");
}
if(empty($_REQUEST['data'])) die("Tidak ada data");
$data = json_decode($_REQUEST['data']);
$jml = count($data);
$s = 0;
for($n=0;$n<$jml;$n++){
	if($dbronda->querySingle("select count(id_ronda) as jml from t_ronda where waktu="
		.$data[$n]->waktu." AND label='".$data[$n]->label."' AND siapa='".$data[$n]->siapa."'")==0){
		if($dbronda->exec("insert into t_ronda
			(waktu,tanggal,bulan,tahun,jam,menit,detik,label,siapa)
			values(".$data[$n]->waktu.",".date("d",$data[$n]->waktu).",".date("m",$data[$n]->waktu).",
			".date("Y",$data[$n]->waktu).",".date("H",$data[$n]->waktu).",".date("i",$data[$n]->waktu).",
			".date("s",$data[$n]->waktu).",'".$data[$n]->label."','".$data[$n]->siapa."')"))
			$s++;
	}
}
die("OKE:$jml:$s");
