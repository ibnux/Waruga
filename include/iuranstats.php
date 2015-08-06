<?
ikutkan("head.php");
ikutkan("menu.php");

$tahun = $_GET['tahun']*1;
if(empty($tahun))
	$tahun = date("Y");
	

	
?><legend>Statistik iuran <?=$tahun?> </legend>
<div class="row">
    <div class="col-md-2">
    <div class="well well-sm">
    	<a href="./?apa=iuran" class="btn btn-warning btn-block btn-sm"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> kembali</a>
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
</div>
<div class="table-responsive">
<table  class="table table-condensed table-bordered">  
<?
$hsl = $db->query("select id_kategori,nama_kategori from t_kategori where tipe_kategori='KAS'");
$totalSemua = 0;
while($kat = $hsl->fetchArray()){
	?>
		<thead>
		  <th colspan="4" align="center"><strong><?=$kat["nama_kategori"]?></strong></th>
		</thead>
        <thead>
          <th><strong>Bulan</strong></th>
          <th><strong>Warga</strong></th>
          <th><strong>Jumlah</strong></th>
          <th><strong>Keterangan</strong></th>
        </thead>
        <tbody>
	<? 
	$sql = "SELECT count(t_iuran.id_iuran) as warga, sum(t_iuran.jumlah_iuran) as jml,  strftime('%m', datetime(`tanggal_iuran`, 'unixepoch')) as bln FROM t_iuran
	WHERE strftime('%Y', datetime(`tanggal_iuran`, 'unixepoch')) ='$tahun' and id_kategori=".$kat['id_kategori']."  
	GROUP BY bln
	ORDER BY bln ASC";
	//echo $sql;
	$hasil = $db->query($sql);
	$total = 0;
	if($hasil)
	while($row = $hasil->fetchArray()){
		?>
		<tr>
		  <td><a href="?apa=iuran&bulan=<?=$row["bln"]?>&tahun=<?=$tahun?>"><?=$bulanArray[$row["bln"]-1]?></a></td>
		  <td><?=$row["warga"]?></td>
		  <td>Rp. <?=number_format($row["jml"])?></td>
          <td><? $data = $db->query("SELECT jumlah_iuran,count(id_iuran) as warga 
								FROM t_iuran
								WHERE strftime('%Y', datetime(`tanggal_iuran`, 'unixepoch')) ='$tahun'
								and strftime('%m', datetime(`tanggal_iuran`, 'unixepoch')) ='".$row["bln"]."' 
								and id_kategori=".$kat['id_kategori']."  
								GROUP BY jumlah_iuran order by jumlah_iuran desc");
			$tmp = "";
		  	while($baris = $data->fetchArray()){
				$tmp .= '<span class="label label-default">'.number_format($baris['jumlah_iuran'])." = ".$baris['warga'].'</span> ';
			}
			//if(strlen($tmp)>1)
				//$tmp = substr($tmp,0,strlen($tmp)-2);
			echo  $tmp;
		  ?>
          </td>
		</tr>
	<? 
		$total += $row['jml'];
	} ?>
		<tr class="info">
		  <td colspan="2"></td>
		  <td>Rp. <?=number_format($total)?></td>
      	<td></td>
		</tr>
	<tr>
      <td colspan="4"></td>
    </tr>
     <tbody>
<? 
	$totalSemua += $total;
} ?>
	<tr>
      <td colspan="4"></td>
    </tr>
	<tr class="info">
      <td></td>
      <td></td>
      <td>Total Semua</td>
      <td>Rp. <?=number_format($total)?></td>
    </tr>
</table>