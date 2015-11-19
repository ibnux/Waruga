<?
ikutkan("head.php");
ikutkan("menu.php");

if(isset($_POST['idiuran'])){
	$db->exec("update t_iuran set jumlah_iuran=".$_POST['jumlah']." where id_iuran=".($_POST['idiuran']*1));
}


if(isset($_GET['add'])){
	$idwarga = $_POST['idWarga']*1;
	$jumlah = $_POST['jumlah']*1;
	$kategori = $_POST['kategori']*1;
	$catatan = $_POST['catatan'];
	$tanggal = $_POST['tanggal'];
	$_REQUEST['bulan'] = date("m",strtotime($tanggal));
	$_REQUEST['tahun'] = date("Y",strtotime($tanggal));
	if(!empty($idwarga)){
		$saldo = $db->querySingle("select saldo_kategori from t_kategori where id_kategori=1")*1;
		if($db->exec("insert into t_iuran(`id_warga`,`jumlah_iuran`,`id_kategori`,`tanggal_iuran`)
					values($idwarga,$jumlah,1,".strtotime($tanggal).")")){
			if($db->exec("insert into t_keuangan(`id_warga`,`jumlah_uang`,`tipe_transaksi`,
						`keterangan`,`id_kategori`,`tanggal_transaksi`)
						values($idwarga,$jumlah,'MASUK','$catatan',1,".time().")")){
				//tambahkan ke Saldo
				$db->exec("update t_kategori SET saldo_kategori=(saldo_kategori+$jumlah) where id_kategori=1");
				?><div class="alert alert-success" role="alert"><b>SUKSES!</b> Transaksi berhasil ditambahkan.<?=$_REQUEST['cari']?> <?=number_format($jumlah)?> </div><?
			}else{
				?><div class="alert alert-danger" role="alert"><b>GAGAL....</b><?=$db->lastErrorMsg()?></div><?
			}
		}else{
			?><div class="alert alert-danger" role="alert"><b>GAGAL...</b><?=$db->lastErrorMsg()?></div><?
		}
	}else{
		?><div class="alert alert-warning" role="alert"><b>GAGAL...</b> data tidak lengkap></div><?
	}
} 

$bulan = $_REQUEST['bulan']*1;
if(empty($bulan))
	$bulan = date("m");
if(strlen($bulan)==1)
	$bulan = "0$bulan";
$tahun = $_REQUEST['tahun']*1;
if(empty($tahun))
	$tahun = date("Y");
$kategori = $_REQUEST['kategori']*1;
$belum = $_REQUEST['belum'];
$link = "?apa=$apa&bulan=$bulan&tahun=$tahun";
	
?><legend>iuran di bulan <?=$bulanArray[$bulan-1]?> <?=$tahun?></legend>
<div class="row">
	<div class="col-md-4">
    <form class="form well well-sm" action="./" method="get" id="lihatSubmit" enctype="application/x-www-form-urlencoded">
    	<input type="hidden" name="apa" value="<?=$apa?>">
    	<input type="hidden" name="belum" value="<?=$belum?>">
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
    </div>
    <div class="col-md-2">
    <? if($belum=='aaa'){ ?>
    <form class="form well well-sm" action="./" method="get" id="kategoriSubmit" enctype="application/x-www-form-urlencoded">
    	<input type="hidden" name="apa" value="<?=$apa?>">
    	<input type="hidden" name="bulan" value="<?=$bulan?>">
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
    <? } ?>
    </div>
    <div class="col-md-6">
    <div class="btn-group btn-group-justified well well-sm" role="group" aria-label="...">
    	<div class="btn-group" role="group">
        	<? if($belum=="ya"){ ?>
            <a href="./?apa=<?=$apa?>&tahun=<?=$tahun?>&bulan=<?=$bulan?>" class="btn btn-info btn-block btn-sm"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> sudah <span class="hidden-xs">Bayar</span></a>
            <? }else{ ?>
            <a href="./?apa=<?=$apa?>&tahun=<?=$tahun?>&bulan=<?=$bulan?>&belum=ya" class="btn btn-danger btn-block btn-sm"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Belum <span class="hidden-xs">Bayar</span></a>
			<? } ?>
    	</div>
    	<div class="btn-group" role="group">
            <a href="./?apa=iuranstats" class="btn btn-warning btn-block btn-sm"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Statistik</a>
    	</div>
        <div class="btn-group" role="group">
            <button id="tombolTambah" type="button" class="btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#AddIuranModal"><span class="glyphicon glyphicon-plus"></span> <span class="hidden-xs">Tambah</span> Iuran</button>    	</div>
    </div>
    </div>
</div>
<br><div class="table-responsive">
<?
if($kategori>0)
	$where = " AND t_iuran.id_kategori=$kategori";
if($belum=='ya'){
$sql = "SELECT `id_warga`
			FROM t_iuran 
			WHERE t_iuran.id_kategori=1 
			AND strftime('%m', datetime(t_iuran.`tanggal_iuran`, 'unixepoch')) ='$bulan' 
			AND strftime('%Y', datetime(t_iuran.`tanggal_iuran`, 'unixepoch')) ='$tahun' ";
$hasil = $db->query($sql);

while($row = $hasil->fetchArray()){
	$ids .= $row["id_warga"].",";
}
if(strlen($ids)>1){
	$ids = substr($ids,0,strlen($ids)-1);
}
$sql = "SELECT `id_warga`, `blok_rumah`, `nomor_rumah`, `nama_suami`, `nama_istri`
			FROM t_warga 
			WHERE id_warga not in ($ids) 
			and nama_suami<>'belum' AND tgl_keluar=0  ORDER BY `blok_rumah`, CAST( nomor_rumah AS INTEGER) ASC";
	?>
    <table  class="table table-condensed table-bordered table-striped table-hover"> 
		<thead>
		  <th align="center"><strong>No</strong></th>
		  <th><strong>Nomor Rumah</strong></th>
		  <th><strong>Nama</strong></th>
		</thead>
         <tbody>
	<?
	$hasil = $db->query($sql);
	$n = 1;
	if($hasil)
	while($row = $hasil->fetchArray()){
		if($config->Gunakan_Blok=='true')
			$nomor = $row['blok_rumah']."-".$row['nomor_rumah'];
		else
			$nomor = "No. ".$row['nomor_rumah'];
		if(!empty($row['nama_suami']))
				$nama = $row['nama_suami'];
			else
				$nama = $row['nama_istri'];
		?>
		<tr>
		  <td><?=$n?></td>
		  <td><a href="?apa=dataView&id=<?=$row['id_warga']?>"><?=$nomor?></a></td>
		  <td><a href="javascript:tambahini('<?=$nama?>','<?=$row['id_warga']?>')"><?=$nama?></a></td>
		</tr>
		<? 
		$n++;
	} ?>
	  </tbody>
	</table>
    <?
}else{
	/** query kategori
	$sql = "SELECT t_iuran.id_iuran,t_iuran.`id_warga`, t_warga.`blok_rumah`, t_warga.`nomor_rumah`, t_warga.`nama_suami`, 
				t_warga.`nama_istri`,t_iuran.jumlah_iuran,t_kategori.nama_kategori,t_iuran.catatan,t_iuran.tanggal_iuran 
				FROM t_iuran 
				LEFT JOIN t_warga ON t_iuran.id_warga=t_warga.id_warga
				LEFT JOIN t_kategori ON t_iuran.id_kategori=t_kategori.id_kategori
				WHERE strftime('%m', datetime(`tanggal_iuran`, 'unixepoch')) ='$bulan' AND strftime('%Y', datetime(`tanggal_iuran`, 'unixepoch')) ='$tahun' $where 
				ORDER BY tanggal_iuran DESC"; **/
	$sql = "SELECT t_iuran.id_iuran,t_iuran.`id_warga`, t_warga.`blok_rumah`, t_warga.`nomor_rumah`, t_warga.`nama_suami`, 
				t_warga.`nama_istri`,t_iuran.jumlah_iuran,t_iuran.catatan,t_iuran.tanggal_iuran 
				FROM t_iuran 
				LEFT JOIN t_warga ON t_iuran.id_warga=t_warga.id_warga
				WHERE strftime('%m', datetime(`tanggal_iuran`, 'unixepoch')) ='$bulan' AND strftime('%Y', datetime(`tanggal_iuran`, 'unixepoch')) ='$tahun' $where 
				ORDER BY tanggal_iuran DESC";

	//echo $sql;
	?><table  class="table table-condensed table-bordered">
		<thead>
		  <th align="center"><strong>No</strong></th>
		  <th><strong>Nama</strong></th>
		  <th><strong>Jumlah</strong></th>
		  <th><strong>Aksi</strong></th>
		</thead>
        <tbody>
	<?
	$hasil = $db->query($sql);
	$total = 0;
	$n = 1;
	if($hasil)
	while($row = $hasil->fetchArray()){
		if($n%2==0)
			$class = ' class="success"';
		else
			$class = ' class="warning"';
		if($config->Gunakan_Blok=='true')
			$nomor = $row['blok_rumah']."-".$row['nomor_rumah'];
		else
			$nomor = "No. ".$row['nomor_rumah'];
		if(!empty($row['nama_suami']))
				$nama = $row['nama_suami']." $nomor";
			else
				$nama = $row['nama_istri']." $nomor";
		?>
		<tr<?=$class ?> id="iuran<?=$row['id_iuran']?>">
        <? if(!empty($row['catatan'])){ ?>
		  <td rowspan="2" valign="middle"><?=$n?></td>
        <? }else{ ?>
          <td><?=$n?></td>
        <? } ?>
		  <td><a href="?apa=dataView&id=<?=$row['id_warga']?>"><?=$nama?></a></td>
		  <td>Rp. <?=number_format($row['jumlah_iuran'])?></td>
		  <td><a href="javascript:askedit('<?=$row['id_iuran']?>','<?=$row['jumlah_iuran']?>')" class="btn btn-xs btn-info btn-block">edit</a></td>
		</tr>
		<? if(!empty($row['catatan'])){ ?>
		<tr<?=$class ?>>
		  <td colspan="4"><?=$row['catatan']?></td>
		</tr>
	<? }
		$total += $row['jumlah_iuran'];
		$n++;
	} ?>
		<tr class="info">
		  <td colspan="2"></td>
		  <td>Rp. <?=number_format($total)?></td>
		  <td></td>
		</tr>
	  </tbody>
	</table>
<small>Iuran bisa diedit, tetapi tidak dibagian kas, di kas harus dihapus dan ditambah lagi</small>
<? } ?>
</div>

<!-- Modal -->
<div class="modal fade" id="AddIuranModal" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambah Iuran</h4>
      </div>
      <form class="form" action="./?apa=<?=$apa?>&belum=<?=$belum?>&add" method="post" enctype="application/x-www-form-urlencoded">
      <div class="modal-body">
      <label>Nama Warga</label>
      <div class="row">
      	<div class="col-xs-9">
      		<input type="text" class="form-control" name="cari" onKeyUp="cekAja(this);" id="autocomplete"/>
        </div>
        <div class="col-xs-3">
        	<input type="text" class="form-control" name="idWarga" id="idWarga" readonly>
        </div>
      </div>
      <br>
         <div class="input-group">
            <span class="input-group-addon" id="basic-addon1">Rp</span>
            <input type="number" class="form-control" name="jumlah" value="<?=$config->Iuran_Bulanan?>">
          </div>
          <div class="form-group">
            <label>Tanggal</label>
            <input type="text" class="form-control datetimepicker" name="tanggal" value="<?=date("d")."-$bulan-$tahun".date(" H:i:s")?>">
          	<p class="help-block">tgl-bln-thn jam:mnt:dtk</p>
          </div>
          <div class="form-group">
            <label>Catatan</label>
            <input type="text" class="form-control" name="catatan" value="iuran Bulan <?=$bulanArray[$bulan-1]?>">
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
<form method="post" id="editSubmit" enctype="application/x-www-form-urlencoded">
    	<input type="hidden" name="idiuran" id="idiuran" >
    	<input type="hidden" name="jumlah" id="jumlah">
        
</form>

<script src="?file&apa=js/jquery.autocomplete.min.js"></script>
<script src="?file&apa=js/jquery.datetimepicker.js"></script> 
<script>
var leng = 0;
jQuery.datetimepicker.setLocale('id');
jQuery('.datetimepicker').datetimepicker({format:'d-m-Y H:i:s',timepicker:false});

function askedit(id,jml){
	document.getElementById('idiuran').value = id;
	var jm = prompt("Jumlah:", jml);
	if (jm != null) {
		document.getElementById('jumlah').value = jm;
		document.getElementById('editSubmit').action = './<?=$link?>#iuran'+id;
		document.getElementById('editSubmit').submit();
	}
}

function tambahini(nama,id){
	document.getElementById('idWarga').value = id;
	document.getElementById('autocomplete').value = nama;
	document.getElementById('tombolTambah').click();
}

function cekAja(obj){
	if(leng>0)
		if(leng>obj.value.length){
			leng = 0;
			document.getElementById('idWarga').value = '';
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