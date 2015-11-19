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

/*
$y = 2015;
$b = 9;
$jams = array(array(22,0,2,4),array(22,23,0,2,4),array(22,23,0,1,2,3,4),array(23,0,1,2,3));
$mnts = array(array(10,25,35,50),array(12,26,37,53),array(11,27,36,51),array(14,29,41,58));
$tiang = array("P4","Q5","Q2","P2");
for($d=1;$d<29;$d++){
	$jam = $jams[rand(0,3)];
	for($n=0;$n<count($jam);$n++){
		$mnt = $mnts[rand(0,3)];
		for($m=0;$m<4;$m++){
			$dtk = rand(0,59);
			shuffle($tiang);
			//die("$y-$m-$d ".$jam[$n].":".$mnt[$m].":$dtk");
			$waktu = strtotime("$y-$b-$d ".$jam[$n].":".$mnt[$m].":$dtk");
			$dbronda->exec("insert into t_ronda (waktu,tanggal,bulan,tahun,jam,menit,detik,label,siapa)
			values($waktu,$d,$b,$y,".$jam[$n].",".$mnt[$m].",$dtk,'".$tiang[$m]."','RondaErs')");
		}
	}
}*/
	

$tgl1 = $_POST['tgl1'];
$tgl2 = $_POST['tgl2'];
if(!empty($tgl1) && !empty($tgl2)){
	$tgl1 = strtotime($tgl1.' 00:00:00');
	$tgl2 = strtotime($tgl2.' 23:59:59');
}

if(empty($tgl1) && empty($tgl2)){
	$tgl2 = strtotime(date('Y-m-d 23:59:59'));
	$tgl1 = strtotime('-2 days', strtotime(date('Y-m-d 00:00:00',$tgl2)));
}
if(empty($tgl1)){
	
}

ikutkan("head.php");
ikutkan("menu.php");

?><script src="?file&apa=js/jquery.datetimepicker.js"></script>    
<legend>Absensi Ronda - percobaan</legend>
<div class="row">
	<div class="col-md-8"><div class="well well-sm">
    	<form class="form-inline" method="post" action="?apa=<?=$apa?>" enctype="application/x-www-form-urlencoded">
        <div class="form-group">
        <label for="exampleInputName2">Dari</label>
        <input type="text" class="form-control datetimepicker" value="<?=date("d M Y",$tgl1)?>" name="tgl1">
        </div>
        <div class="form-group">
        <label for="exampleInputEmail2">Sampai</label>
        <input type="text" class="form-control datetimepicker" value="<?=date("d M Y",$tgl2)?>" name="tgl2">
        </div>
        <button type="submit" class="btn btn-default">filter</button>
        </form>
    </div></div>
    <div class="col-md-4"><div class="well well-sm">
    <a href="?apa=QrCodeGenerator" class="btn btn-success btn-block">
    <span class="glyphicon glyphicon-qrcode" aria-hidden="true"></span>
    QR Code Generator</a>
    </div></div>
</div>
<div class="table-responsive">
<table class="table table-condensed table-bordered">
  <thead>
    <tr>
      <td align="center"><strong>Jam</strong></td>
      <?
		$waktu = array();
		$hasil = $dbronda->query("select distinct tanggal,bulan, tahun from t_ronda ".
						"where waktu>=$tgl1 and waktu<=$tgl2 order by waktu asc");
		$n = 0;
		while($data = $hasil->fetchArray()){
			$waktu[$n] = $data["tanggal"]."-".$data["bulan"]."-".$data['tahun'];
			//echo $tgl[$n];
			$n++;
		?><td align="center"><strong><?= date("d M Y",strtotime($data["tahun"]."-".$data["bulan"]."-".$data["tanggal"])); ?></strong></td><?
		}
		
		$hasil = $dbronda->query("select distinct jam from t_ronda ".
					"where waktu>=$tgl1 and waktu<=$tgl2 order by jam asc");
		$jams = array();
		$n = 0;
		while($data = $hasil->fetchArray()){
			$jams[$n] = $data['jam'];
			$n++;
		}
	  ?>
    </tr>
    
  </thead>
  <tbody>
  <?
  	$hasil = $dbronda->query("select tanggal,bulan, tahun,jam,menit,detik,label,siapa from t_ronda ".
	  							"where waktu>=$tgl1 and waktu<=$tgl2 order by waktu asc");
	$data = array();
	$j = -1;
	$apa = "";
	$n = 0;
	while($tgl = $hasil->fetchArray()){
		if($apa!=$tgl["tanggal"]."-".$tgl["bulan"]."-".$tgl["tahun"]){
			$n = 0;
			$apa = $tgl["tanggal"]."-".$tgl["bulan"]."-".$tgl["tahun"];
		}
		if($j!=$tgl["jam"]){
			$n=0;
			$j=$tgl["jam"];
		}
		$data[$apa][$j][$n]['menit'] = $tgl['menit'];
		$data[$apa][$j][$n]['label'] = $tgl['label'];
		$data[$apa][$j][$n]['siapa'] = $tgl['siapa'];
		$n++;
	}
	//print_r($data);
	for($n=0;$n<count($jams);$n++){
  	?>
    <tr>
      <td align="center"><h4><?=$jams[$n]?></h4></td>
      <? for($z=0;$z<count($waktu);$z++){ 
	  	$array = $data[$waktu[$z]][$jams[$n]];
		$jml = count($array);
			if($jml>0){
				echo '<td bgcolor="#C0F6CB">';
				for($y=0;$y<$jml;$y++){ 
					?><span class="label label-default"><span class="glyphicon glyphicon-time" aria-hidden="true"></span><?
					echo " ".$jams[$n].':'.$array[$y]['menit'].'</span> <span class="label label-primary">'.$array[$y]['label'].
					'</span>';
					if($y!=$jml-1)
						echo '<br>';
				}
				echo '</td>';
			}else
				echo '<td></td>';
      	} ?>
    </tr>
    <? } ?>
  </tbody>
</table>
</div>
<span class="label label-success">Total <span class="badge"><? echo $dbronda->querySingle("select count(id_ronda) as jml from t_ronda ".
	  							"where waktu>=$tgl1 and waktu<=$tgl2")?></span> titik didatangi</span>
<script>
jQuery('.datetimepicker').datetimepicker({format:'d M Y',timepicker:false,lang:'id'});
</script>
