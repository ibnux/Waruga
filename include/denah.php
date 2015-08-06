<?php
ikutkan("head.php");
ikutkan("menu.php");
$minimal = $_GET['minimal'];
?>
<div style="width:100%; overflow:scroll;">
<table border="0" cellspacing="2" cellpadding="0">
<?php
$tmp = $db->querySingle('SELECT max(`X`) as mx FROM t_warga', true);
$maxX = $tmp["mx"];
$tmp = $db->querySingle('SELECT max(`Y`) as mx FROM t_warga', true);
$maxY = $tmp["mx"];
$hasil = $db->query("SELECT `X`, `Y`, `blok_rumah`, `nomor_rumah`, `nama_suami`, `nama_istri` FROM t_warga");
$data = array();
while($row = $hasil->fetchArray()){
	$data[$row['X']][$row['Y']] = array("Blok"=>$row["blok_rumah"],"Nomor"=>$row["nomor_rumah"],"Suami"=>$row["nama_suami"],"Istri"=>$row["nama_istri"]);
}
//print_r($data);
for($y =0; $y < ($maxY+2);$y++){
	echo "<tr>";
	for($x=0;$x<($maxX+2);$x++){
		if(!empty($data[$x][$y])){
			if($data[$x][$y]["Suami"]!='belum'){
				$warna = '#99FFCC';
			}else{
				$warna = '#CC6666';
			}
			if(!empty($data[$x][$y]["Suami"])){
				$nama = $data[$x][$y]["Suami"];
			}else{
				$nama = $data[$x][$y]["Istri"];
			}
			if($minimal=='yes'){
				?>
                <td bgcolor="<?=$warna?>" align="center" class="home <? if($nama!='belum') echo 'kursor'; ?>" id="<?= $data[$x][$y]["Blok"] ?><?= $data[$x][$y]["Nomor"] ?>" title="<? if($nama!='belum') echo $nama; ?>">
                    <a href="./?apa=dataView&blok=<?= $data[$x][$y]["Blok"]?>&nomor=<?= $data[$x][$y]["Nomor"] ?>"><? if($config->Gunakan_Blok=='true') echo "<b>".$data[$x][$y]["Blok"]."</b>";?>
                    <div style="height:2px !important;"><img src="./?file&apa=kosong.png" width="40" height="1"></div>
                    <?= $data[$x][$y]["Nomor"] ?></a>
                </td>
                <?
			}else{
			?>
			<td bgcolor="<?=$warna?>" align="center" class="home" id="<?= $data[$x][$y]["Blok"] ?><?= $data[$x][$y]["Nomor"] ?>">
                <a href="./?apa=dataView&blok=<?= $data[$x][$y]["Blok"]?>&nomor=<?= $data[$x][$y]["Nomor"] ?>"><b><?= $data[$x][$y]["Blok"] ?> - <?= $data[$x][$y]["Nomor"] ?></b></a>
                <div style="height:5px !important;"><img src="./?file&apa=kosong.png" width="90" height="1"></div>
                <?= $nama ?>
			</td>
            <? }
        }else{ ?>
			<td><img src="./?file&apa=kosong.png" width="50" height="50"></td>
<? 		}
	} 
	echo "</tr>";
}
?>
</table>
<br>
</div>
<center>
	<a href="?apa=denahEdit" class="btn btn-info"><span class="glyphicon glyphicon-pencil"></span> Ubah Denah</a>&nbsp;&nbsp;&nbsp;
    <? if($minimal=='yes'){ ?>
	<a href="?apa=<?=$apa?>" class="btn btn-warning"><span class="glyphicon glyphicon-th-large"></span> detail</a>
	<? }else{ ?>
    <a href="?apa=<?=$apa?>&minimal=yes" class="btn btn-warning"><span class="glyphicon glyphicon-th"></span> minimal</a>
    <? } ?>
    </center>
<br>
