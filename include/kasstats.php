<?
ikutkan("head.php");
ikutkan("menu.php");

$tahun = $_GET['tahun']*1;
if(empty($tahun))
	$tahun = date("Y");
	

	
?><legend>Statistik KAS <?=$tahun?> </legend>
<div class="row">
    <div class="col-md-2">
    <div class="well well-sm">
    	<a href="./?apa=kas" class="btn btn-warning btn-block btn-sm"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> kembali</a>
    </div>
    </div>
    <div class="col-md-4">
    <form class="form well well-sm" action="./" method="get" id="tahunSubmit" enctype="application/x-www-form-urlencoded">
    	<input type="hidden" name="apa" value="<?=$apa?>">
        <select class="form-control" name="tahun" onChange="document.getElementById('tahunSubmit').submit()">
            <?
			$hasil = $db->query("select DISTINCT strftime('%Y', datetime(`tanggal_iuran`, 'unixepoch')) as thn
							from t_iuran");
			while($row = $hasil->fetchArray()){
				?><option><?=$row["thn"]?></option><?	
			}
			?>
            </select>
    </form>
    </div>
</div><div class="table-responsive">
<table  class="table table-condensed table-bordered">  <tbody>
<?
$hsl = $db->query("select id_kategori,nama_kategori from t_kategori where tipe_kategori='KAS'");
$totalSemuaM = 0;
$totalSemuaK = 0;
while($kat = $hsl->fetchArray()){
	?>
		<thead>
		  <th colspan="4"><center><strong><?=$kat["nama_kategori"]?></strong></center></th>
		</thead>
        <thead>
          <th><strong>Bulan</strong></th>
          <th><strong>Masuk</strong></th>
          <th><strong>Keluar</strong></th>
          <th></th>
        </thead>
        <tbody>
	<? 
	$array = array();
	for($n=0;$n<12;$n++){
		$array[$n] = array("keluar"=>0,"masuk"=>0);	
	}
	
	$sql = "SELECT sum(t_keuangan.jumlah_uang) as jml,  strftime('%m', datetime(`tanggal_transaksi`, 'unixepoch')) as bln FROM t_keuangan
	WHERE strftime('%Y', datetime(`tanggal_transaksi`, 'unixepoch')) ='$tahun' 
	and id_kategori=".$kat['id_kategori']." 
	and tipe_transaksi = 'MASUK'
	GROUP BY bln
	ORDER BY bln ASC";
	//echo $sql;
	$hasil = $db->query($sql);
	while($row = $hasil->fetchArray()){
		$array[($row["bln"]*1)-1]["masuk"] = $row["jml"];
	}
	$sql = "SELECT sum(t_keuangan.jumlah_uang) as jml,  strftime('%m', datetime(`tanggal_transaksi`, 'unixepoch')) as bln FROM t_keuangan
	WHERE strftime('%Y', datetime(`tanggal_transaksi`, 'unixepoch')) ='$tahun' 
	and id_kategori=".$kat['id_kategori']." 
	and tipe_transaksi = 'KELUAR'
	GROUP BY bln
	ORDER BY bln ASC";
	//echo $sql;
	$hasil = $db->query($sql);
	while($row = $hasil->fetchArray()){
		$array[($row["bln"]*1)-1]["keluar"] = $row["jml"];
	}
	$totalM = 0;
	$totalK = 0;
	for($n=0;$n<12;$n++){
		?>
		<tr>
		  <td><?=$bulanArray[$n]?></td>
		  <td>Rp. <?=number_format($array[$n]["masuk"])?></td>
		  <td>Rp. <?=number_format($array[$n]["keluar"])?></td>
		  <td></td>
		</tr>
	<? 
		$totalM += $array[$n]["masuk"];
		$totalK += $array[$n]["keluar"];
	} ?>
		<tr class="info">
		  <td></td>
		  <td>Rp. <?=number_format($totalM)?></td>
		  <td>Rp. <?=number_format($totalK)?></td>
		  <td>Rp. <?=number_format($totalM-$totalK)?></td>
		</tr>
        </tbody>
<? $totalSemuaM += $totalM;
$totalSemuaK += $totalK;
} ?>
  <tr>
      <td colspan="2"></td>
    </tr>
	<tr class="info">
      <td>Total Semua</td>
      <td>Rp. <?=number_format($totalSemuaM)?></td>
      <td>Rp. <?=number_format($totalSemuaK)?></td>
      <td>Rp. <?=number_format($totalSemuaM-$totalSemuaK)?></td>
    </tr>
</table>