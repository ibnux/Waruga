<?php

$cari = $db->escapeString($_REQUEST['query']);
	$sql = "SELECT `id_warga`, `blok_rumah`, `nomor_rumah`, `nama_suami`, `nama_istri` 
						FROM t_warga WHERE (`nama_suami` like '%$cari%' OR `nama_istri` like '%$cari%')
						AND nama_suami!='belum' AND tgl_keluar=0
						ORDER BY `blok_rumah`, CAST( nomor_rumah AS INTEGER) ASC LIMIT  10";
	//echo $sql;
	$hasil = $db->query($sql);
	$n = 0;
	$array = array();
	while($row = $hasil->fetchArray()){
		if(!empty($row['nama_suami']))
				$nama = $row['nama_suami'];
			else
				$nama = $row['nama_istri'];
		if($config->Gunakan_Blok=='true')
			$array[$n] = array("value"=>$nama." ".$row['blok_rumah']."-".$row['nomor_rumah'],"data"=>$row['id_warga']);
		else 
			$array[$n] = array("value"=>$nama." No. ".$row['nomor_rumah'],"data"=>$row['id_warga']);
		$n++;
	}
	echo json_encode(array("suggestions"=>$array));
die();