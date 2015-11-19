<?
ikutkan("head.php");
ikutkan("menu.php");

$tipe = $_REQUEST['tipe'];
$judul = $_REQUEST['judul'];
$do=0;
if(isset($_GET['simpan'])){
	$db->exec("UPDATE t_kategori SET nama_kategori='$judul',tipe_kategori='$tipe' where id_kategori=".($_GET['simpan']*1));
	$tipe = '';
	$judul = '';
}
if(isset($_GET['tambah'])){
	$db->exec("insert into t_kategori(nama_kategori,tipe_kategori) values ('$judul','$tipe')");
	$tipe = '';
	$judul = '';
}

if(isset($_GET['edit'])){
	$id = $_GET['edit']*1;
	$do = 'simpan';
	$hasil = $db->querySingle("select nama_kategori,tipe_kategori from t_kategori where id_kategori=$id",true);
	$tipe = $hasil['tipe_kategori'];
	$judul = $hasil['nama_kategori'];
}else
	$do = 'tambah';
if(isset($_GET['hapus']) && $_GET['hapus']!=1){
	$tipe = $db->querySingle("select tipe_kategori from t_kategori where id_kategori=$id",true);
	$db->exec("delete from t_kategori where id_kategori=".($_GET['hapus']*1));
	if($tipe=="KAS")
		$db->exec("delete from t_keuangan where id_kategori=".($_GET['hapus']*1));
}


?><legend>Kategori</legend>
<form class="form" action="?apa=<?=$apa?>&<?=$do?>=<?=$id?>" method="post">
<div class="row">
	<div class="col-sm-4">
    	<div class="input-group">
        <span class="input-group-addon">Judul</span>
        <input type="text" name="judul" value="<?=$judul?>" class="form-control">
        </div>
    </div>
	<div class="col-sm-4">
    	<div class="input-group">
        <span class="input-group-addon">Tipe</span>
        <select class="form-control" name="tipe">
        <option <? if($tipe=='KAS') echo 'selected';?>>KAS</option>
        <!--<option <? if($tipe=='WARGA') echo 'selected';?>>WARGA</option>-->
        </select>
        </div>
    </div>
	<div class="col-sm-4">
    	<div class="btn-group btn-group-justified" role="group" aria-label="...">
    	<div class="btn-group" role="group">
        	<button type="submit" class="btn btn-primary"><?=$do?></button>
        </div>
        <? if($do=='simpan'){ ?><div class="btn-group" role="group">
        <a href="?apa=<?=$apa?>"  class="btn btn-warning">batal</a>
        </div><? } ?>
        </div>
    </div>
</div>
</form><br>&nbsp;
<table  class="table table-condensed table-bordered table-striped">
    <thead>
      <th><strong>No</strong></th>
      <th><strong>Judul</strong></th>
      <th><strong>Tipe</strong></th>
      <th><strong>Saldo</strong></th>
      <th><strong>Aksi</strong></th>
    </thead>
	<tbody>	
	<?
	$hasil = $db->query("select id_kategori,nama_kategori,saldo_kategori,tipe_kategori 
						from t_kategori order by nama_kategori asc");
	$n=1;
	while($row = $hasil->fetchArray()){
		?><tr>
            <td><?=$n?></td>
            <td><?=$row["nama_kategori"]?></td>
            <td><?=$row["tipe_kategori"]?></td>
            <td><?=number_format($row["saldo_kategori"])?></td>
            <td><a href="?apa=<?=$apa?>&edit=<?=$row["id_kategori"]?>" class="btn btn-xs btn-info">ubah</a>
            <? if($row["id_kategori"]!=1){ ?>
            <a href="?apa=<?=$apa?>&hapus=<?=$row["id_kategori"]?>" onClick="return tanya()" class="btn btn-xs btn-danger">hapus</a>
			<? } ?></td>
        </tr> <?
		$n++;
	}
	?>
    </tbody>
</table>
Tipe KAS untuk keuangan, Tipe WARGA untuk digunakan di data warga
<script type="text/javascript">
function tanya(){
	if(tanya1())
		if(tanya2())
			if(tanya3())
				return true;
	return false;
}

function tanya1(){
	return confirm('Jika tipe KAS maka semua Transaksi Kas ikut dihapus juga.');
}
function tanya2(){
	return confirm('Yakin mau dihapus?');
}
function tanya3(){
	return confirm('Serius mau dihapus?');
}


</script>