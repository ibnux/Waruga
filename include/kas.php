<?
ikutkan("head.php");
ikutkan("menu.php");

if(isset($_POST['idKas'])){
	$db->exec("update t_keuangan set keterangan='".$_POST['isiKomentar']."' where id_keuangan=".($_POST['idKas']*1));
}
if(isset($_GET['hapus']) && $config->Boleh_Hapus_Data_Kas=='true'){
	$id = $_GET['hapus']*1;
	$cek = $db->querySingle("select tipe_transaksi,jumlah_uang,id_kategori from t_keuangan where id_keuangan=$id",true);
	$saldo = $db->querySingle("select saldo_kategori from t_kategori where id_kategori=".$cek['id_kategori'])*1;
	$jumlah = $cek['jumlah_uang'];
	if($cek['tipe_transaksi']=="MASUK"){
		$db->exec("update t_kategori SET saldo_kategori=(saldo_kategori-$jumlah) where id_kategori=".$cek['id_kategori']);
		$hasil = $saldo-$jumlah;
	}else{
		$db->exec("update t_kategori SET saldo_kategori=(saldo_kategori+$jumlah) where id_kategori=".$cek['id_kategori']);
		$hasil = $saldo+$jumlah;
	}
	$db->exec("DELETE FROM t_keuangan where id_keuangan=$id");
	?><div class="alert alert-success" role="alert">Transaksi berhasil dihapus,<br>
	Saldo dari Rp. <?=number_format($saldo)?> menjadi Rp. <?=number_format($hasil)?></div><?
}
if(isset($_GET['add'])){
	$idwarga = $_POST['idWarga']*1;
	$jumlah = $_POST['jumlah']*1;
	$tanggal = $_POST['tanggal'];
	$kategori = $_POST['kategori']*1;
	$catatan = $_POST['catatan'];
	$tipe = $_POST['tipe'];
	if((!empty($idwarga) || !empty($catatan) ) && !empty($kategori) && $jumlah>0){
		$saldo = $db->querySingle("select saldo_kategori from t_kategori where id_kategori=$kategori")*1;
		if($db->exec("insert into t_keuangan(`id_warga`,`jumlah_uang`,`tipe_transaksi`,
					`keterangan`,`id_kategori`,`tanggal_transaksi`)
					values($idwarga,$jumlah,'$tipe','$catatan',$kategori,".strtotime($tanggal).")")){
			if($tipe=="MASUK"){
				$db->exec("update t_kategori SET saldo_kategori=(saldo_kategori+$jumlah) where id_kategori=$kategori");
				$hasil = $saldo+$jumlah;
			}else{
				$db->exec("update t_kategori SET saldo_kategori=(saldo_kategori-$jumlah) where id_kategori=$kategori");
				$hasil = $saldo-$jumlah;
			}
			?><div class="alert alert-success" role="alert"><b>SUKSES!</b> Transaksi berhasil ditambahkan,<br>
            Saldo dari Rp. <?=number_format($saldo)?> menjadi Rp. <?=number_format($hasil)?></div><?
		}else{
			?><div class="alert alert-danger" role="alert"><b>GAGAL....</b><?=$db->lastErrorMsg()?></div><?
		}
	}else{
		?><div class="alert alert-warning" role="alert"><b>GAGAL...</b> data tidak lengkap, mohon isi Nama Warga atau Keterangan</div><?
	}
} 

$bulan = $_GET['bulan']*1;
if(empty($bulan))
	$bulan = date("m");
if(strlen($bulan)==1)
	$bulan = "0$bulan";
$tahun = $_GET['tahun']*1;
if(empty($tahun))
	$tahun = date("Y");
$kategori = $_GET['kategori']*1;
$tipe = $_GET['tipe'];

$link = "?apa=$apa&tipe=$tipe&kategori=$kategori&bulan=$bulan&tahun=$tahun";
	
?><legend>Kas di bulan <?=$bulanArray[$bulan-1]?> </legend>
<div class="row">
	<div class="col-md-4">
    <form class="form well well-sm" action="./" method="get" id="lihatSubmit" enctype="application/x-www-form-urlencoded">
    	<input type="hidden" name="apa" value="<?=$apa?>">
    	<input type="hidden" name="tipe" value="<?=$tipe?>">
    	<input type="hidden" name="kategori" value="<?=$kategori?>">
        <div class="row">
            <div class="col-xs-4 col-md-6">
                <select name="bulan" class="form-control input-sm" onChange="document.getElementById('lihatSubmit').submit()">
                    <? for($n=0;$n<12;$n++){ ?>
                    <option value="<?=($n+1)?>" <? if($n==$bulan-1) echo 'selected';?>><?=$bulanArray[$n]?></option>
                    <? } ?>
                </select>
            </div>
            <div class="col-xs-4 col-md-3">
            <input type="text" name="tahun" value="<?=$tahun?>" class="form-control input-sm">
            </div>
            <div class="col-xs-4 col-md-3">
            <button type="submit" class="btn btn-sm btn-info btn-block"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Lihat</button>
            </div>
        </div>
    </form>
    <form class="form well well-sm" action="./" method="get" id="kategoriSubmit" enctype="application/x-www-form-urlencoded">
    	<input type="hidden" name="apa" value="<?=$apa?>">
    	<input type="hidden" name="bulan" value="<?=$bulan?>">
    	<input type="hidden" name="tahun" value="<?=$tahun?>">
    	<input type="hidden" name="tipe" value="<?=$tipe?>">
        <select class="form-control" name="kategori" onChange="document.getElementById('kategoriSubmit').submit()">
            <option value="0">-Tampilkan semua kategori</option>
            <?
            $hasil = $db->query("select id_kategori,nama_kategori,saldo_kategori from t_kategori where tipe_kategori='KAS' order by id_kategori asc");
            $totalSaldo = 0;
			$saldo = "";
			while($row = $hasil->fetchArray()){
				//untuk menghemat Query, query saldo disini juga
				$totalSaldo += $row['saldo_kategori'];
				$saldo .= '<tr><td>'.$row['nama_kategori'].'</td><td>Rp. '.number_format($row['saldo_kategori']).'</td></tr>';
				?>
            <option value="<?=$row['id_kategori']?>" <? if($row['id_kategori']==$kategori)echo 'selected';?>><?=$row['nama_kategori']?></option>
            <? } 
				$saldo .= '<tr class="success"><td><b>Total Saldo</b></td><td>Rp. '.number_format($totalSaldo).'</td></tr>';
			?>
        </select>
    </form>
    </div>
    <div class="col-md-4">
    <form class="form well well-sm" action="./" method="get" id="tipeSubmit" enctype="application/x-www-form-urlencoded">
    	<input type="hidden" name="apa" value="<?=$apa?>">
    	<input type="hidden" name="bulan" value="<?=$bulan?>">
    	<input type="hidden" name="tahun" value="<?=$tahun?>">
    	<input type="hidden" name="kategori" value="<?=$kategori?>">
        <select class="form-control" name="tipe" onChange="document.getElementById('tipeSubmit').submit()">
            <option value="ALL">Tampilkan semua Transaksi</option>
            <option value="MASUK" <? if($tipe=="MASUK")echo 'selected';?>>Uang Masuk</option>
            <option value="KELUAR" <? if($tipe=="KELUAR")echo 'selected';?>>Uang Keluar</option>
        </select>
    </form>
    <div class="btn-group btn-group-justified well well-sm" role="group" aria-label="...">
    	<div class="btn-group" role="group">
	    	<a href="./?apa=kasstats" class="btn btn-warning btn-block btn-sm"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Statistik</a>
    	</div>
        <div class="btn-group" role="group">
        	<button type="button" class="btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#AddIuranModal"><span class="glyphicon glyphicon-plus"></span> Tambah Transaksi</button>
    	</div>
    </div>
    </div>
    <div class="col-md-4">
    <table  class="table table-condensed table-bordered table-striped">
            <thead>
              <th><strong>Kategori</strong></th>
              <th><strong>Saldo</strong></th>
            </thead>
          <tbody>
          <?=$saldo?>
          </tbody>
        </table>
    </div>
</div>
<hr>

<div class="table-responsive">
<span class="label label-success">Uang masuk</span> 
<span class="label label-warning">Uang keluar</span> 
<table  class="table table-condensed table-bordered table-hover">
    <thead>
      <th><strong>Tanggal</strong></th>
      <th><strong>Nama/Keterangan</strong></th>
      <th><strong>Jumlah</strong></th>
      <th><strong>Kategori</strong></th>
      <th><strong>Aksi</strong></th>
    </thead>
  <tbody>
  <?
$where = "";
if($kategori>0)
	$where .= " AND t_keuangan.id_kategori=$kategori";
if($tipe=="MASUK")
	$where .= " AND t_keuangan.tipe_transaksi='MASUK'";
if($tipe=="KELUAR")
	$where .= " AND t_keuangan.tipe_transaksi='KELUAR'";
$sql = "SELECT t_keuangan.`id_warga`,t_keuangan.id_keuangan,t_keuangan.id_kategori,t_keuangan.jumlah_uang,t_kategori.nama_kategori,t_keuangan.keterangan,
			t_keuangan.tanggal_transaksi,t_keuangan.tipe_transaksi
			FROM t_keuangan 
			LEFT JOIN t_kategori ON t_keuangan.id_kategori=t_kategori.id_kategori
			WHERE strftime('%m', datetime(`tanggal_transaksi`, 'unixepoch')) ='$bulan' AND strftime('%Y', datetime(`tanggal_transaksi`, 'unixepoch')) ='$tahun' $where 
			ORDER BY id_keuangan DESC";
//echo $sql;
$hasil = $db->query($sql);
$totalM = 0;
$totalK = 0;
if($hasil)
while($row = $hasil->fetchArray()){
	if($row['tipe_transaksi']=='MASUK'){
		$class = ' class="success"';
		$totalM += $row['jumlah_uang'];
	}else{
		$class = ' class="warning"';
		$totalK += $row['jumlah_uang'];
	}
	$warga = $db->querySingle("select `blok_rumah`, `nomor_rumah`, `nama_suami`, `nama_istri` 
					from t_warga where id_warga=".$row['id_warga'],true);
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
	if(!empty($nama)){	
		?>
		<tr<?=$class ?> id="kas<?=$row['id_keuangan']?>">
          <? if(!empty($row['keterangan']))
		  		echo '<td rowspan="2">';
			else echo '<td>'; ?>
		  <?=date("d M Y H:i",$row['tanggal_transaksi'])?></td>
          
		  <td><a href="?apa=dataView&id=<?=$row['id_warga']?>"><?=$nama?></a></td>
		  <td>Rp. <?=number_format($row['jumlah_uang'])?></td>
		  <td><?=$row['nama_kategori']?></td>
		  <td><div class="btn-group"><? if(empty($row['keterangan'])){ ?>
          <a href="javascript:editKeterangan(<?=$row['id_keuangan']?>,'')" class="btn btn-xs btn-default" title="edit komentar">&nbsp;&nbsp;<span class="glyphicon glyphicon-comment" aria-hidden="true"></span>&nbsp;&nbsp;</a>
          <? } if($config->Boleh_Hapus_Data_Kas=='true'){?>
          <a href="./<?=$link?>&hapus=<?=$row['id_keuangan']?>" onClick="return confirm('Yakin mau dihapus?')" class="btn btn-xs btn-danger" title="hapus kas">&nbsp;&nbsp;<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>&nbsp;&nbsp;</a>
		  <? }?></div></td>
		</tr>
		<? if(!empty($row['keterangan'])){ ?>
			<tr<?=$class ?>>
              <td colspan="3"><?=$row['keterangan']?></td>
		  	  <td><a href="javascript:editKeterangan(<?=$row['id_keuangan']?>,'<?=$row['keterangan']?>')" class="btn btn-xs btn-default" title="edit komentar">&nbsp;&nbsp;<span class="glyphicon glyphicon-comment" aria-hidden="true"></span>&nbsp;&nbsp;</a></td>
			</tr>
		<? }
	}else{
		?>
		<tr<?=$class ?> id="kas<?=$row['id_keuangan']?>">
		  <td><?=date("d M Y H:i",$row['tanggal_transaksi'])?></td>
		  <td><?=$row['keterangan']?></td>
		  <td>Rp. <?=number_format($row['jumlah_uang'])?></td>
          <td><?=$row['nama_kategori']?></td>
          <td><div class="btn-group">
          <a href="javascript:editKeterangan(<?=$row['id_keuangan']?>,'<?=$row['keterangan']?>')" class="btn btn-xs btn-default" title="edit komentar">&nbsp;&nbsp;<span class="glyphicon glyphicon-comment" aria-hidden="true"></span>&nbsp;&nbsp;</a>
          <? if($config->Boleh_Hapus_Data_Kas=='true'){?>
          <a href="./<?=$link?>&hapus=<?=$row['id_keuangan']?>" onClick="return confirm('Yakin mau dihapus?')" class="btn btn-xs btn-danger" title="hapus kas">&nbsp;&nbsp;<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>&nbsp;&nbsp;</a>
		  <? }?></div></td>
		</tr>
	<? }
	$n++;
} ?>
	<tr class="info">
      <td colspan="2" align="right">Total Uang Masuk</td>
      <td>Rp. <?=number_format($totalM)?></td>
      <td colspan="2"></td>
    </tr>
	<tr class="danger">
      <td colspan="2" align="right">Total Uang Keluar</td>
      <td>Rp. <?=number_format($totalK)?></td>
      <td colspan="2"></td>
    </tr>
  </tbody>
</table>
</div>

<!-- Modal -->
<div class="modal fade" id="AddIuranModal" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambah Iuran</h4>
      </div>
      <form class="form" action="./<?=$link?>&add" method="post" enctype="application/x-www-form-urlencoded">
      <div class="modal-body">
      <label>Nama Warga</label>
      <div class="row">
      	<div class="col-xs-9">
      		<input type="text" class="form-control" name="cari" onKeyUp="cekAja(this);" id="autocomplete"/>
        </div>
        <div class="col-xs-3">
        	<input type="text" class="form-control" name="idWarga" id="idWarga" value="0" readonly>
        </div>
      </div>
           <div class="form-group">
            <label>Keterangan</label>
            <input type="text" class="form-control" name="catatan">
          </div>
          <div class="form-group">
                <label class="radio-inline">
                <input type="radio" <? if(empty($tipe))echo 'checked'; else if($tipe=="MASUK")echo 'checked';?> name="tipe" value="MASUK"> Uang masuk
                </label>
                <label class="radio-inline">
                <input type="radio" <? if($tipe=="MASUK")echo 'checked';?> name="tipe" value="KELUAR"> Uang keluar
                </label>
          </div>
         <div class="input-group">
            <span class="input-group-addon" id="basic-addon1">Rp</span>
            <input type="number" class="form-control" name="jumlah" value="">
          </div>
          <div class="form-group">
            <label>Tanggal</label>
            <input type="text" class="form-control datetimepicker" name="tanggal" value="<?=date("d")."-$bulan-$tahun".date(" H:i:s")?>">
          	<p class="help-block">tgl-bln-thn jam:mnt:dtk</p>
          </div>
          <div class="form-group">
            <label>Kategori</label>
            <select class="form-control" name="kategori">
            	<?
				$hasil = $db->query("select id_kategori,nama_kategori from t_kategori where tipe_kategori='KAS' order by id_kategori asc");
				while($row = $hasil->fetchArray()){?>
                <option value="<?=$row['id_kategori']?>" <? if($kategori==$row['id_kategori']) echo "selected";?>
              ><?=$row['nama_kategori']?></option>
                <? } ?>
            </select>
          </div>
      
      </div>
      <div class="modal-footer">
        <a class="btn btn-default" data-dismiss="modal">Close</a>
        <button type="submit" class="btn btn-primary">Tambahkan</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- form edit komentar-->
<form method="post" id="keteranganSubmit" enctype="application/x-www-form-urlencoded">
    	<input type="hidden" name="idKas" id="idKas" >
    	<input type="hidden" name="isiKomentar" id="isiKomentar" value="">
        
</form>

<script src="?file&apa=js/jquery.autocomplete.min.js"></script>
<script src="?file&apa=js/jquery.datetimepicker.js"></script> 
<script>

function editKeterangan(id,txt){
	document.getElementById('idKas').value = id;
	var ket = prompt("Keterangan:", txt);
	if (ket != null) {
		document.getElementById('isiKomentar').value = ket;
		document.getElementById('keteranganSubmit').action = './<?=$link?>#kas'+id;
		document.getElementById('keteranganSubmit').submit();
	}
}

var leng = 0;
jQuery.datetimepicker.setLocale('id');
jQuery('.datetimepicker').datetimepicker({format:'d-m-Y H:i:s',timepicker:false});
function cekAja(obj){
	if(leng>0)
		if(leng>obj.value.length){
			leng = 0;
			document.getElementById('idWarga').value = '0';
		}
}

$('#autocomplete').autocomplete({
    serviceUrl: './?apa=ajaxLookupNama',
    onSelect: function (suggestion) {
		document.getElementById('idWarga').value = suggestion.data;
		leng = suggestion.value.length;
    }
});
</script>