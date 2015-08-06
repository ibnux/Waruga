<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Print</title>
<style>
	.ada {
		background-color: #99FFCC !important;
		-webkit-print-color-adjust: exact;
		border:thick;
		border-width:1px;
	}
	.tidakada {
		border:thick;
		border-width:1px;
		/*background-color: #CC6666 !important;
		-webkit-print-color-adjust: exact;*/
	}
	@media all {
		.page-break	{ display: none; }
	}
	
	@media print {
		.page-break	{ display: block; page-break-before: always; }
		 @page {size: landscape}
	}
</style>
</head>
<body>
<h1 align="center">Warga Citra Gading Blok Q</h1>
<table class="landscape" border="0" cellspacing="2" cellpadding="0">
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
				$warna = 'ada';
			}else{
				$warna = 'tidakada';
			}
			if(!empty($data[$x][$y]["Suami"])){
				$nama = $data[$x][$y]["Suami"];
			}else{
				$nama = $data[$x][$y]["Istri"];
			}
				?>
                <td class="<?=$warna?>" align="center" class="home <? if($nama!='belum') echo 'kursor'; ?>" id="<?= $data[$x][$y]["Blok"] ?><?= $data[$x][$y]["Nomor"] ?>" title="<? if($nama!='belum') echo $nama; ?>">
                    <b><?= $data[$x][$y]["Blok"] ?></b><br>
                    <?= $data[$x][$y]["Nomor"] ?>
                </td>
                <?
			
        }else{ ?>
			<td><img src="kosong.png" width="35" height="35"></td>
<? 		}
	} 
	echo "</tr>";
}
?>
</table>
<div class="page-break"></div>
<table width="100%" class="hoverTable" border="1" cellspacing="0" cellpadding="1">
	<thead>
    	<th width="10">index</th>
    	<th width="100">Rumah</th>
        <th width="500">Bapak</th>
        <th width="500">HP</th>
        <th width="500">Ibu</th>
        <th width="500">HP</th>
    </thead>
<?
$hasil = $db->query("SELECT `id_warga`, `blok_rumah`, `nomor_rumah`, `nama_suami`, `nama_istri`, `nomor_hp`,nomor_hp2 FROM t_warga ORDER BY `blok_rumah`, CAST( nomor_rumah AS INTEGER) ASC");
$n =0;
while($row = $hasil->fetchArray()){
	$n++;
	if($row['nama_suami']=='belum'){
		?>
		<tr class="belum">
			<td align="center"><?=$n?></td>
			<td align="center"><?= $row['blok_rumah']?> - <?= $row['nomor_rumah']?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>	
		<?
	}else{
		?>
        <tr bgcolor="#99FFCC">
            <td align="center"><?=$n?></td>
            <td align="center"><?= $row['blok_rumah']?> - <?= $row['nomor_rumah']?></td>
            <td><?= $row['nama_suami']?></td>
            <td><?=$row['nomor_hp']?></a></td>
            <td><?=$row['nama_istri']?></a></td>
            <td><?=$row['nomor_hp2']?></a></td>
        </tr>
        <?
	}
	if($n % 35 == 0){
		?>
</table>
<div class="page-break"></div>
<table width="100%" class="hoverTable" border="1" cellspacing="0" cellpadding="1">
	<thead>
    	<th width="10">index</th>
    	<th width="100">Rumah</th>
        <th width="500">Bapak</th>
        <th width="500">HP</th>
        <th width="500">Ibu</th>
        <th width="500">HP</th>
    </thead>

        <?
	}
}
?>
</table>
</body>
</html>