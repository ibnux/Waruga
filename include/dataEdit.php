<?
$ID = $_REQUEST['id']*1;
if(isset($_GET['hapus'])){
	$db->exec("DELETE FROM t_warga WHERE id_warga=$ID");
	$db->exec("DELETE FROM t_warga_tambahan WHERE id_warga=$ID");
	$db->exec("DELETE FROM t_iuran WHERE id_warga=$ID");
	$db->exec("DELETE FROM t_tamu WHERE id_warga=$ID");
	deleteDir($KELUARGA.$ID."/");
	header("location: ./");
	die();
}
ikutkan("head.php");
ikutkan("menu.php");
$folderKeluarga = $KELUARGA."$ID/";

if(isset($_POST['simpan']) && $_POST['simpan']=='simpan'){
	if(!empty($_POST['label'])){
		$label = $_POST['label'];
	}
	if($db->exec("UPDATE t_warga SET 
					blok_rumah='".$_POST['blok']."', 
					nomor_rumah='".$_POST['nomor']."', 
					X='".($_POST['X']*1)."', 
					Y='".($_POST['Y']*1)."', 
					nama_suami='".$db->escapeString($_POST['suami'])."', 
					nama_istri='".$db->escapeString($_POST['istri'])."', 
					telepon_suami='".$_POST['hp1']."', 
					telepon_istri='".$_POST['hp2']."', 
					pekerjaan_suami='".$_POST['pekerjaanSuami']."', 
					pekerjaan_istri='".$_POST['pekerjaanIstri']."',
					tglahir_suami=".(strtotime($_POST['tgLahirSuami'])*1).", 
					tglahir_istri=".(strtotime($_POST['tgLahirIstri'])*1).", 
					tgl_masuk=".(strtotime($_POST['tglMasuk'])*1).", 
					tgl_keluar=".(strtotime($_POST['tglKeluar'])*1).", 
					email='".$_POST['email']."', 
					label_warga='".$label."', 
					last_update=".time()."
					WHERE id_warga=$ID")){
		?><br><div class="alert alert-success alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <strong>Sukses!</strong> mengubah data keluarga.
            </div><?
	}else{
		?><br><div class="alert alert-danger alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <strong>Gagal!</strong> mengubah data keluarga.<br><?= $db->lastErrorMsg() ?>
            </div><?
	}
}
if(isset($_GET['addTambahan'])){
	if($db->exec("insert into t_warga_tambahan values(NULL,$ID,'".$db->escapeString($_POST['nama'])."','".$_POST['kelamin']."','".$_POST['hp']."',".
				"'".$_POST['status']."','".$_POST['hubungan']."',".(strtotime($_POST['tgLahir'])*1).",".(strtotime($_POST['tglMasuk'])*1).",0,0);")){
		?><br><div class="alert alert-success alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <strong>Sukses!</strong> menambahkan data Keluarga lain yang serumah.
            </div><?
	}else{
		?><br><div class="alert alert-danger alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <strong>Gagal!</strong> menambahkan data Keluarga lain yang serumah.<br><?= $db->lastErrorMsg() ?>
            </div><?
	}
}
if(isset($_GET['editTambahan'])){
	if($db->exec("UPDATE t_warga_tambahan SET nama_lengkap='".$db->escapeString($_POST['nama'])."', jenis_kelamin='".$_POST['kelamin']."',
					nomor_hp='".$_POST['hp']."',hubungan_keluarga='".$_POST['hubungan']."',tanggal_masuk=".(strtotime($_POST['tglMasuk'])*1).",
					tanggal_keluar=".(strtotime($_POST['tglKeluar'])*1).",tanggal_lahir=".(strtotime($_POST['tgLahir'])*1).",
					status_kawin='".$_POST['status']."',last_update=".time()." WHERE `id_warga_tambahan`=".($_GET['editTambahan']*1))){
		?><br><div class="alert alert-success alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <strong>Sukses!</strong> mengubah data Keluarga lain yang serumah.
            </div><?
	}else{
		?><br><div class="alert alert-danger alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <strong>Gagal!</strong> mengubah data Keluarga lain yang serumah.<br><?= $db->lastErrorMsg() ?>
            </div><?
	}
}
if(isset($_GET['deleteTambahan'])){
	if($db->exec("DELETE FROM t_warga_tambahan WHERE `id_warga_tambahan`=".($_GET['deleteTambahan']*1))){
		if(file_exists($folderKeluarga.($_GET['deleteTambahan']*1).'.ktp.jpg'))
			unlink($folderKeluarga.($_GET['deleteTambahan']*1).'.ktp.jpg');
		if(file_exists($folderKeluarga.($_GET['deleteTambahan']*1).'.jpg'))
			unlink($folderKeluarga.($_GET['deleteTambahan']*1).'.jpg');
		?><br><div class="alert alert-success alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <strong>Sukses!</strong> menghapus data Keluarga lain yang serumah.
            </div><?
	}else{
		?><br><div class="alert alert-danger alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <strong>Gagal!</strong> menghapus data Keluarga lain yang serumah.<br><?= $db->lastErrorMsg() ?>
            </div><?
	}
}

$hasil = $db->query("SELECT `id_warga`, `X`, `Y`, `blok_rumah`, `nomor_rumah`,pekerjaan_istri,`email`, 
					`nama_suami`, `nama_istri`, `telepon_suami`, `telepon_istri`, `pekerjaan_suami`, `last_update`,
					tgl_masuk, tgl_keluar,tglahir_istri,tglahir_suami,label_warga 
					FROM t_warga WHERE id_warga=$ID");

while($row = $hasil->fetchArray()){
	?>
<script src="?file&apa=js/jquery.datetimepicker.js"></script>    
    <hr class="garistipis">
	<center><?= date("d M Y H:i:s")?> Waktu Sekarang<br>
    <?= date("d M Y H:i:s",$row['last_update'])?> Terakhir diubah&nbsp;&nbsp;</center><hr class="garistipis">
    <form role="form" action="./?apa=<?= $_GET['apa']?>&id=<?=$ID?>" method="post" enctype="application/x-www-form-urlencoded">
    	<input type="hidden" name="id" value="<?=$row['id_warga']?>">
		<div class="row">
            <div class="col-xs-3 col-md-3 col-md-offset-1">
            <a href="?apa=dataView&id=<?=$ID?>" class="btn btn-block btn-default"><span class="glyphicon glyphicon-chevron-left"></span> <span class="hidden-xs">Kembali</span><span class="visible-xs-inline"><span class="glyphicon glyphicon-user"></span></span></a>
            </div>
            <div class="col-xs-9 col-md-6">
            <? if($config->Unggah_Foto_KTP_KK=="true"){ ?>
            <a href="./?apa=dataPhoto&id=<?=$ID?>" class="btn btn-info btn-block"><span class="glyphicon glyphicon-picture"></span> <span class="hidden-xs">Manajemen Foto </span>KTP & KK</a>
            <? } ?>
            </div>
        </div>
    	<hr>        
        <div class="row">
        	<div class="col-md-4 col-md-offset-1">
            <label>Nama Bapak:</label><br>
            <input class="form-control input-sm" id="suami" type="text" style="width:100%" name="suami" value="<?=$row['nama_suami']?>"><br>
            <label>Nomor Hp:</label><br>
            <input class="form-control input-sm" type="tel" style="width:100%" name="hp1" value="<?=$row['telepon_suami']?>"><br>
            <label>Pekerjaan Bapak:</label><br>
            <input class="form-control input-sm" type="text" style="width:100%" name="pekerjaanSuami" value="<?=$row['pekerjaan_suami']?>">
            <label>Tanggal Lahir:</label><br>
            <input class="form-control input-sm datetimepicker" type="text" style="width:100%" name="tgLahirSuami" value="<? if($row['tglahir_suami']!=0) echo date("d M Y",$row['tglahir_suami']);?>">
            </div>
        	<div class="col-md-4 col-md-offset-1">
            <label>Nama Ibu:</label><br>
            <input class="form-control input-sm" type="text" style="width:100%" name="istri" value="<?=$row['nama_istri']?>"><br>
            <label>Nomor HP:</label><br>
            <input class="form-control input-sm" type="tel" style="width:100%" name="hp2" value="<?=$row['telepon_istri']?>"><br>
            <label>Pekerjaan Ibu:</label><br>
            <input class="form-control input-sm" type="text" style="width:100%" name="pekerjaanIstri" value="<?=$row['pekerjaan_istri']?>">
            <label>Tanggal Lahir:</label><br>
            <input class="form-control input-sm datetimepicker" type="text" style="width:100%" name="tgLahirIstri" value="<? if($row['tglahir_istri']!=0) echo date("d M Y",$row['tglahir_istri']);?>">
        	</div>
        </div>
        <hr>
        <div class="row">
        	<div class="col-md-4 col-md-offset-1">
                <label>Email:</label><br>
                <input class="form-control input-sm" type="email" style="width:100%" name="email" value="<?=$row['email']?>"><br>
                <label>Tanggal Masuk:</label><br>
                <input class="form-control input-sm datetimepicker" type="text" style="width:100%" name="tglMasuk" value="<? if($row['tgl_masuk']>10) echo date("d M Y",$row['tgl_masuk']);?>">
                <span class="help-block">Isikan saat KK mulai menempati Rumah</span>
            </div>
            <div class="col-md-4 col-md-offset-1">
            <div class="form-group has-error">
                <label>Tanggal Keluar /  Pindah rumah:</label><br>
                <input class="form-control input-sm datetimepicker" type="text" style="width:100%" name="tglKeluar" value="<?
                if($row['tgl_keluar']==0){
                    ?>" placeholder="Masih menempati<?
                }else{
                    echo date("Y-m-d",$row['tgl_keluar']);
                }
                ?>">
                <span class="help-block">Isikan saat KK mulai meninggalkan Rumah, data akan di arsipkan</span>
                </div>
                
                <? if($config->Gunakan_Label=='true'){ ?>
                <style>
				<?
				ikutkan("css/bootstrap-tokenfield.min.css");
				ikutkan("css/tokenfield-typeahead.min.css");
				?>
				</style>

                <label>Label / Tag / Tanda:</label><br>
                <input class="form-control input-sm" id="label" type="text" style="width:100%" name="label" value="<?=$row['label_warga']?>">
				<script src="?file&apa=js/bootstrap-tokenfield.min.js"></script>                
				<? $tags = getTags();
				$warna = array('warning','primary','success','info','danger');
				$tgs = "";
				for($n=0;$n<count($tags);$n++){
					if(!empty($tags[$n])){
						$tgs .= "'".trim($tags[$n])."',";
						?><a class="btn btn-<?=$warna[rand(0,4)]?> btn-xs" href="javascript:addTag('<?=$tags[$n]?>')"><?=$tags[$n]?></a>&nbsp;&nbsp;<?
					}
				} 
				if(strlen($tgs)>2){
					$tgs = substr($tgs,0,strlen($tgs)-1);
				}
				?>
                <span class="help-block">Label digunakan untuk menandai warga, gunakan spasi sebagai pemisah</span>
                <script>
					$('#label').tokenfield();
					
                	function addTag(tag){
						$('#label').tokenfield('createToken', tag);
						/*var tagfield = document.getElementById('label');
						if(tagfield.value.trim().length>0 && tagfield.value.trim().substring(tagfield.value.trim().length-1)!=','){
							tagfield.value += ', '+tag;
						}else
							tagfield.value += tag;
						*/
					}
                </script>
                <? }?>
           </div>
        </div>
		<? if($config->Gunakan_Denah=='true'){ ?>
        <div class="row">
            <div class="col-sm-9 col-sm-offset-1">
   			<legend>Posisi denah</legend>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-sm-4 col-sm-offset-1">
            <div class="form-group">
            <div class="input-group">
            <div class="input-group-addon">X</div>
            <input class="form-control" type="number" id="fromX" name="X" placeholder="Posisi" value="<?=$row['X']?>">
            </div>
            </div>
            </div>
            <div class="col-xs-6 col-sm-4 col-sm-offset-1">
            <div class="form-group">
            <div class="input-group">
            <div class="input-group-addon">Y</div>
            <input class="form-control" type="number" id="fromY" name="Y" placeholder="Posisi" value="<?=$row['Y']?>">
            </div>
            </div>
            </div>
        </div>
        <? } if($config->Gunakan_Blok=='true'){ ?>
        <div class="row">
            <div class="col-xs-6 col-sm-4 col-sm-offset-1">
            <div class="form-group">
            <div class="input-group">
            <div class="input-group-addon"><?= $config->Blok;?></div>
            <input class="form-control" required type="text" id="blok" name="blok" placeholder="rumah" value="<?=$row['blok_rumah']?>">
            </div>
            </div>
            </div>
            <div class="col-xs-6 col-sm-4 col-sm-offset-1">
            <div class="form-group">
            <div class="input-group">
            <div class="input-group-addon"><?= $config->Nomor;?></div>
            <input class="form-control" required type="text" id="nomor" name="nomor" placeholder="rumah" value="<?=$row['nomor_rumah']?>">
            </div>
            </div>
            </div>
        </div>
        <? }else{ ?>
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
            <div class="form-group">
            <div class="input-group">
            <div class="input-group-addon"><?= $config->Nomor;?></div>
            <input class="form-control" required type="text" id="nomor" name="nomor" placeholder="rumah" value="<?=$row['nomor_rumah']?>">
            </div>
            </div>
            </div>
        </div>
        <? } ?>
    	<hr>        <div class="row">
        	<div class="col-md-6 col-md-offset-3">
			<center><input class="btn btn-default btn-sm btn-primary btn-block" type="submit" name="simpan" value="simpan"></center>
            </div>
        </div>
    </form>
    <br>
    <script>
		<? if($row['nama_suami']=='belum'){ ?>
		document.getElementById('suami').select();
		<? }else{ ?>
		document.getElementById('suami').focus();
		<? } ?>
	</script>
    <div class="row">
	<div class="col-md-12">
    	<legend>Data Keluarga lain yang serumah</legend>
    	<div class="table-responsive" style="overflow:scroll">
        <table class="table table-bordered table-hover table-condensed">
        	<thead>
            	<th><span class="glyphicon glyphicon-sort-by-order"></span></th>
            	<th>Nama</th>
                <th>Telepon</th>
                <th>L/P</th>
                <th>Hubungan</th>
                <th>Status</th>
                <th>Tanggal Lahir</th>
                <th>Tanggal Masuk</th>
                <th>Tanggal Keluar</th>
                <th></th>
                <th></th>
            </thead>
          	<? $hazil = $db->query("select `id_warga_tambahan`, nama_lengkap, jenis_kelamin,nomor_hp,hubungan_keluarga,
									tanggal_masuk,tanggal_keluar,tanggal_lahir,status_kawin from t_warga_tambahan 
									where id_warga=".$row['id_warga']." order by tanggal_keluar asc");
				$n = 1;
            	while($relasi = $hazil->fetchArray()){ ?>
             		<form role="form" onSubmit="return confirm('yakin perubahan data <?=$relasi['nama_lengkap']?> mau disimpan?\r\n data tidak dapat dikembalikan lagi');" action="./?apa=<?= $_GET['apa']?>&id=<?=$ID?>&editTambahan=<?=$relasi['id_warga_tambahan']?>" method="post" enctype="application/x-www-form-urlencoded">
                    <tr>
                    	<td align="center"><?=$n?></td>
                        <td><input name="nama" type="text" value="<?=$relasi['nama_lengkap']?>"></td>
                        <td><input name="hp" type="tel" value="<?=$relasi['nomor_hp']?>"></a></td>
                        <td><select name="kelamin">
							<option value="L" <? if($relasi['jenis_kelamin']=='L') echo 'selected';?>>Laki laki</option>
                            <option value="P" <? if($relasi['jenis_kelamin']=='P') echo 'selected';?>>Perempuan</option>
                            </select></td>
                        <td><input name="hubungan" type="text" value="<?=$relasi['hubungan_keluarga']?>"></td>
                        <td><input name="status" type="text" value="<?=$relasi['status_kawin']?>"></td>
                        <td><input name="tgLahir" type="text" class="datetimepicker" value="<? if($relasi['tanggal_lahir']!=0) echo date("d M Y",$relasi['tanggal_lahir']); ?>"></td>
                        <td><input name="tglMasuk" type="text" class="datetimepicker" value="<? if($relasi['tanggal_masuk']!=0) echo date("d M Y",$relasi['tanggal_masuk']);?>"></td>
                        <td><input name="tglKeluar" type="text" class="datetimepicker" value="<? if($relasi['tanggal_keluar']!=0) echo date("d M Y",$relasi['tanggal_keluar']);?>"></td>
                        <td><button type="submit" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-floppy-disk"></span></button></td>
                        <td><a href="./?apa=<?= $_GET['apa']?>&id=<?=$ID?>&deleteTambahan=<?=$relasi['id_warga_tambahan']?>" onClick="return confirm('yakin <?=$relasi['nama_lengkap']?> mau dihapus?\r\n data tidak dapat dikembalikan');" class="btn btn-danger btn-xs">
                        <span class="glyphicon glyphicon-trash"></span></a></td>
                    </tr>
                    </form>
            <? $n++;} ?>
            <form action="./?apa=<?= $_GET['apa']?>&id=<?=$ID?>&addTambahan" method="post" enctype="application/x-www-form-urlencoded">
            <tr class="info">
            	<td valign="middle" align="center"><span class="glyphicon glyphicon-chevron-right"></span></td>
                <td><input name="nama" type="text" value="<?=$relasi['nama_lengkap']?>"></td>
                <td><input name="hp" type="text" value="<?=$relasi['nomor_hp']?>"></a></td>
                <td><select name="kelamin">
                    <option value="L">Laki laki</option>
                    <option value="P">Perempuan</option>
                    </select></td>
                <td><input name="hubungan" type="text" value="<?=$relasi['hubungan_keluarga']?>"></td>
                <td><input name="status" type="text" value="<?=$relasi['status_kawin']?>"></td>
                <td><input name="tgLahir" type="text" class="datetimepicker" value="<? if($relasi['tanggal_lahir']!=0) echo date("d M Y",$relasi['tanggal_lahir']); ?>"></td>
                <td><input name="tglMasuk" type="text" class="datetimepicker" value="<? if($relasi['tanggal_masuk']!=0) echo date("d M Y",$relasi['tanggal_masuk']);?>"></td>
                <td><button type="submit" class="btn btn-primary btn-xs" onClick="return confirm('yakin mau disimpan?');"><span class="glyphicon glyphicon-plus"></span></button></td>
                <td></td>
                <td></td>
            </tr>
            </form>
        </table>
        </div>
    </div>
</div>
<script>
jQuery('.datetimepicker').datetimepicker({format:'d M Y',timepicker:false,lang:'id'});
</script>
 <?
}
?>
<div class="row">
	<div class="col-xs-6">
		<a href="?apa=dataView&id=<?=$ID?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Kembali</a>
	</div>
	<div class="col-xs-6" align="right">
		<a href="?apa=<?=$apa?>&id=<?=$ID?>&hapus" onClick="return confirm('Yakin mau dihapus?')" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span> Hapus</a>
	</div>
</div>