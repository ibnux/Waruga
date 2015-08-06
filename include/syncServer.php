<?php
include "conf.php";
$config = json_decode(file_get_contents("config.json"));
if($config->Sandi_Sinkronisasi!=$_GET['sandi']){
	echo json_encode(array("pesan"=>"gagal","error"=>"Sandi Salah"));;
	die();
}
$apa = $_REQUEST['apa'];
$id_warga = $_REQUEST['id'];
$tipe = $_REQUEST['tipe'];
$data = $_REQUEST['data'];

if($apa == 'put'){
	if($tipe=='tambahan')
		$sql = "select id_warga_tambahan from t_warga_tambahan where id_warga_tambahan=$id_warga";
	else
		$sql = "select id_warga from t_warga where id_warga=$id_warga";
	$hasil = $db->querySingle($sql);
	try{
		$json = json_decode($data);
		
		if(!empty($hasil)){
			foreach($json as $h=>$v){
				$queri = "`$h`='$v',";
			}
			$queri = substr($queri,0,strlen($queri)-1);
			
			if($tipe=='tambahan')
				$sql = "Update t_warga_tambahan SET $queri WHERE `id_warga_tambahan`=$id_warga";
			else
				$sql = "Update t_warga SET $queri WHERE `id_warga`=$id_warga";
			
			if($db->exec($sql)){
				echo json_encode(array("pesan"=>"sukses"));
			}else{
				echo json_encode(array("pesan"=>"gagal","error"=>$db->lastErrorMsg()));
			}
		}else{
			foreach($json as $h=>$v){
				$table = "`$h`,";
				$value = "'$v',";
			}
			$table = substr($table,0,strlen($table)-1);
			$value = substr($queri,0,strlen($value)-1);
			
			if($tipe=='tambahan')
				$sql = "insert into t_warga_tambahan($table) values($value);";
			else
				$sql = "insert into t_warga($table) values($value);";
			
			if($db->exec($sql)){
				echo '{"pesan":"sukses"}';
			}else{
				echo json_encode(array("pesan"=>"gagal","error"=>$db->lastErrorMsg()));;
			}
		}
	}catch(Exception $e){
		echo json_encode(array("pesan"=>"gagal","error"=>$e->getMessage()));
	}
}else if($apa == 'del'){
	if($tipe=='tambahan')
		$sql = "DELETE FROM t_warga_tambahan WHERE `id_warga_tambahan`=$id_warga";
	else
		$sql = "DELETE FROM t_warga WHERE `id_warga`=$id_warga";
	
	if($db->exec($sql)){
		echo json_encode(array("pesan"=>"sukses"));
	}else{
		echo json_encode(array("pesan"=>"gagal","error"=>$db->lastErrorMsg()));
	}
}else if($apa == 'get'){
	if($tipe=='tambahan')
		$hasil = $db->querySingle("SELECT * FROM t_warga_tambahan WHERE `id_warga_tambahan`=$id_warga",true);
	else
		$hasil = $db->querySingle("SELECT * FROM t_warga WHERE `id_warga`=$id_warga",true);
	echo json_encode($hasil);
}else if($apa == 'getAll'){
	if($tipe=='tambahan')
		$hasil = $db->query("SELECT * FROM t_warga_tambahan ORDER BY `id_warga_tambahan` ASC");
	else
		$hasil = $db->query("SELECT * FROM t_warga ORDER BY `id_warga` ASC");
	$data = array();
	$n = 0;
	while($row = $hasil->fetchArray(SQLITE_ASSOC)){
		$data[$n] = $row;
		$n++;
	}
	echo json_encode($data);
}else if($apa == 'data'){
	if($tipe=='tambahan')
		$hasil = $db->query("SELECT `id_warga_tambahan`,`last_update` FROM t_warga_tambahan");
	else
		$hasil = $db->query("SELECT `id_warga`,`last_update` FROM t_warga");
	$data = array();
	$n = 0;
	while($row = $hasil->fetchArray(SQLITE_ASSOC)){
		$data[$n] = $row;
		$n++;
	}
	echo json_encode($data);
}
