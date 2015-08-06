<?
ikutkan("head.php");
ikutkan("menu.php");

$tahun = $_REQUEST['tahun']*1;
if(empty($tahun))
	$tahun = date("Y");
$kategori = $_REQUEST['kategori']*1;
$id = $_REQUEST['id']*1;

	
?><legend>Riwayat iuran di tahun <?=$tahun?></legend>
<div class="row">
	<div class="col-md-2">
    <div class="well well-sm">
    	<a href="./?apa=dataView&id=<?=$id?>" class="btn btn-warning btn-block btn-sm"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> kembali</a>
    </div>
    </div>
	<div class="col-md-2">
    <form class="form well well-sm" action="./" method="get" id="tahunSubmit" enctype="application/x-www-form-urlencoded">
    	<input type="hidden" name="apa" value="<?=$apa?>">
    	<input type="hidden" name="id" value="<?=$id?>">
    	<input type="hidden" name="kategori" value="<?=$kategori?>">
        <select class="form-control" name="tahun" onChange="document.getElementById('tahunSubmit').submit()">
            <?
			$hasil = $db->query("select DISTINCT strftime('%Y', datetime(`tanggal_transaksi`, 'unixepoch')) as thn
							from t_keuangan where id_warga=$id");
			while($row = $hasil->fetchArray()){
				?><option><?=$row["thn"]?></option><?	
			}
			?>
            </select>
    </form>
    </div>
    <div class="col-md-2">
    <form class="form well well-sm" action="./" method="get" id="kategoriSubmit" enctype="application/x-www-form-urlencoded">
    	<input type="hidden" name="apa" value="<?=$apa?>">
    	<input type="hidden" name="id" value="<?=$id?>">
    	<input type="hidden" name="tahun" value="<?=$tahun?>">
        <select class="form-control" name="kategori" onChange="document.getElementById('kategoriSubmit').submit()">
            <option value="0">Semua</option>
            <?
            $hasil = $db->query("select id_kategori,nama_kategori from t_kategori where tipe_kategori='KAS' order by id_kategori asc");
            while($row = $hasil->fetchArray()){ ?>
            <option value="<?=$row['id_kategori']?>" <? if($row['id_kategori']==$kategori)echo 'selected';?>><?=$row['nama_kategori']?></option>
            <? } ?>
        </select>
    </form>
    </div>
    <div class="col-md-6">
    <div class="btn-group btn-group-justified well well-sm" role="group" aria-label="...">
        <div class="btn-group" role="group">
            <a href="?apa=iuran" class="btn btn-primary btn-sm btn-block"><span class="glyphicon glyphicon-plus"></span> <span class="hidden-xs">Tambah</span> Iuran</a>    	</div>
    </div>
    </div>
</div>
<br>
  <?
if($kategori>0)
	$where = " AND t_keuangan.id_kategori=$kategori";

$sql = "SELECT t_keuangan.jumlah_uang,t_kategori.nama_kategori,t_keuangan.keterangan,
			t_keuangan.tanggal_transaksi,t_keuangan.tipe_transaksi 
			FROM t_keuangan 
			LEFT JOIN t_kategori ON t_keuangan.id_kategori=t_kategori.id_kategori
			WHERE strftime('%Y', datetime(`tanggal_transaksi`, 'unixepoch')) ='$tahun'
			AND t_keuangan.`id_warga`=$id
			$where 
			ORDER BY id_keuangan DESC limit 50";
//echo $sql;
$hasil = $db->query($sql);
$totalM = 0;
$totalK = 0;
$warga = $db->querySingle("select `blok_rumah`, `nomor_rumah`, `nama_suami`, `nama_istri` 
				from t_warga where id_warga=$id",true);
if($config->Gunakan_Blok=='true')
	$nomor = $warga['blok_rumah']."-".$warga['nomor_rumah'];
else
	$nomor = "No. ".$warga['nomor_rumah'];
	
if(!empty($warga['nama_suami']))
		$nama = $warga['nama_suami']." $nomor";
else if(!empty($warga['nama_istri']))
	$nama = $warga['nama_istri']." $nomor";
else
	$nama = "";
echo "<b>$nama</b>";
?><div class="table-responsive">
<table  class="table table-condensed table-bordered table-striped">
    <thead>
      <th><strong>Tanggal</strong></th>
      <th><strong>Jumlah</strong></th>
      <th><strong>Keterangan</strong></th>
      <th><strong>Kategori</strong></th>
    </thead>
  <tbody>	
<?
if($hasil)
while($row = $hasil->fetchArray()){
	if($row['tipe_transaksi']=='MASUK'){
		$class = ' class="warning"';
		$totalM += $row['jumlah_uang'];
	}else{
		$class = ' class="success"';
		$totalK += $row['jumlah_uang'];
	}

	?>
	<tr<?=$class ?>>
	  <td><?=date("d M Y H:i",$row['tanggal_transaksi'])?></td>
	  <td>Rp. <?=number_format($row['jumlah_uang'])?></td>
	  <td><?=$row['keterangan']?></td>
	  <td><?=$row['nama_kategori']?></td>
	</tr>
<?
} ?>
	<tr class="info">
      <td colspan="2" align="right">Total Uang Masuk</td>
      <td colspan="2">Rp. <?=number_format($totalM)?></td>
    </tr>
	<tr class="danger">
      <td colspan="2" align="right">Total Uang Keluar</td>
      <td colspan="2">Rp. <?=number_format($totalK)?></td>
    </tr>
	<tr>
    <td></td>
      <td align="center" class="success">Uang diterima</td>
      <td align="center" class="warning">Uang dibayarkan</td>
    <td></td>
    </tr>
  </tbody>
</table>
</div>



