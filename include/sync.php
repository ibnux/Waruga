<?php
session_start();
ikutkan("head.php");

$syncURL = $config->Sync_URL."?sandi=".$config->Sandi_Sinkronisasi;

if($_GET['do']=='getAll'){
	ikutkan("menu.php");
	$db->exec("DROP TABLE t_warga;");
	$db->exec("DROP TABLE t_warga_tambahan;");
	createTableDefault();
	$hasil = file_get_contents($syncURL."&apa=getAll");
	$json = json_decode($hasil);
	$m = 0;
	$err = 0;
	$suc = 0;
	for($n=0;$n<count($json);$n++){
		$m++;
		foreach($json[$n] as $h=>$v){
			$table = "`$h`,";
			$value = "'$v',";
		}
		$table = substr($table,0,strlen($table)-1);
		$value = substr($queri,0,strlen($value)-1);
		if(!$db->exec("insert into t_warga($table) values($value);")){
			$err++;
			echo '<br><div class="alert alert-danger" role="alert"><b>Gagal</b>, '.$db->lastErrorMsg().'</div>';
		}else
			$suc++;
	}
	?><br><div class="alert alert-success" role="alert"><?=$suc?> sukses dan <?=$err?> gagal dari total <?=$m?> data</div><?
	$hasil = file_get_contents($syncURL."&apa=getAll&tipe=tambahan");
	$json = json_decode($hasil);
	$m = 0;
	$err = 0;
	$suc = 0;
	for($n=0;$n<count($json);$n++){
		$m++;
		foreach($json[$n] as $h=>$v){
			$table = "`$h`,";
			$value = "'$v',";
		}
		$table = substr($table,0,strlen($table)-1);
		$value = substr($queri,0,strlen($value)-1);
		if(!$db->exec("insert into t_warga_tambahan($table) values($value);")){
			$err++;
			echo '<br><div class="alert alert-danger" role="alert"><b>Gagal</b>, '.$db->lastErrorMsg().'</div>';
		}else
			$suc++;
	}
	?><br><div class="alert alert-success" role="alert"><?=$suc?> sukses dan <?=$err?> gagal dari total <?=$m?> data</div><?
}else if($_GET['do']=='sync'){ 
	$hasil = file_get_contents($syncURL."&apa=data");
	$DATA = json_decode($hasil);
	//print_r($DATA);die();
	ikutkan("menu.php");
	$hasil = $db->query("SELECT * FROM t_warga ORDER BY `id_warga` ASC");
	while($row = $hasil->fetchArray(SQLITE_ASSOC)){
		$ada = false;
		for($n=0;$n<count($DATA);$n++){
			if($DATA[$n]->id_warga == $row['id_warga']){
				$ada = true;
				if($DATA[$n]->last_update != $row['last_update']){
					$tglS = date('U',strtotime($DATA[$n]->last_update));
					$tglL = date('U',strtotime($row['last_update']));
					if($tglS>$tglL){
						//update dari server
						echo '<br><div class="alert alert-warning" role="alert">'.$row['blok_rumah']." - ".$row['nomor_rumah']." di update dari server</div>";
						$hazil = file_get_contents($syncURL."&apa=get&id=".$DATA[$n]->id_warga);
						$json = json_decode($hazil);
						foreach($json as $h=>$v){
							$queri = "`$h`='$v',";
						}
						$queri = substr($queri,0,strlen($queri)-1);
						$db->query("Update t_warga SET $queri WHERE `id_warga`=".$DATA[$n]->id_warga);
						break;
					}else{
						$postdata = http_build_query(
							array(
								'data' => json_encode($row)
							)
						);
						$opts = array('http' =>
							array(
								'method'  => 'POST',
								'header'  => 'Content-type: application/x-www-form-urlencoded',
								'content' => $postdata
							)
						);
						$context  = stream_context_create($opts);
						$result = file_get_contents($syncURL."&apa=put&id=".$DATA[$n]->id_warga, false, $context);
						echo '<br><div class="alert alert-info" role="alert">'.$row['blok_rumah']." - ".$row['nomor_rumah']." update ke server<br>$result</div>";
						break;
					}
				}
			}
		}
		if(!$ada){
			echo '<br><div class="alert alert-danger" role="alert">'.$row['blok_rumah']." - ".$row['nomor_rumah']." tidak ada di Server server</div>";
		}
	}
	echo '<br><div class="alert alert-success" role="alert"><b>Selesai!</b>, silahkan unduh semua data jika diperlukan</div>';
	session_destroy();
}else{
	ikutkan("menu.php");
}

?>
<br>
<center><b>Sinkronisasi Data</b></center>
<br>
<form role="form" method="get" enctype="application/x-www-form-urlencoded">
	<input type="hidden" name="apa" value="sync">
	<label for="url" style="overflow:scroll"><a href="./?apa=setup">URL Server</a>: <?=$config->Sync_URL;?></label><br><br>
	<label class="control-label"><input name="do" type="radio" <? if($_GET['do']=='getAll') echo 'checked';?> value="getAll"> Unduh semua data</label><br><br>
	<label class="control-label"><input name="do" type="radio" <? if($_GET['do']=='sync') echo 'checked';?> value="sync"> Sinkronisasi semua data</label><br>&nbsp;
    <center><button type="submit" class="btn btn-default btn-info"><span class="glyphicon glyphicon-transfer"></span> Sinkronkan</button></center>
</form>
</body>
</html>