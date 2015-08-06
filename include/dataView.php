<?
ikutkan("head.php");
ikutkan("menu.php");

if(isset($_POST['simpan']) && $_POST['simpan']=='simpan'){
	$db->query("UPDATE t_warga SET 
					`blok_rumah`='".$_POST['blok']."', `nomor_rumah`='".$_POST['nomor']."', 
					`nama_suami`='".$_POST['suami']."', `nama_istri`='".$_POST['istri']."', 
					`anak1`='".$_POST['anak1']."', `anak2`='".$_POST['anak2']."', `anak3`='".$_POST['anak3']."', 
					`anak4`='".$_POST['anak4']."', `telepon_suami`='".$_POST['hp1']."', `telepon_istri`='".$_POST['hp2']."', 
					`pekerjaan`='".$_POST['pekerjaan']."', `last_update`='".date("Y-m-d H:i:s")."'
					WHERE id_warga=".$_POST['id']);
}
if(isset($_GET['id']))
	$where = "id_warga=".strtoupper($_GET['id']);
else
	$where = "blok_rumah='".strtoupper($_GET['blok'])."' and nomor_rumah='".$_GET['nomor']."'";

$hasil = $db->query("SELECT `id_warga`, `X`, `Y`, `blok_rumah`, `nomor_rumah`,label_warga, 
					`nama_suami`, `nama_istri`, `telepon_suami`, `telepon_istri`,email, `pekerjaan_suami`,
					pekerjaan_istri, `last_update`,tglahir_suami,tglahir_istri,tgl_keluar 
					FROM t_warga WHERE $where ORDER BY `id_warga` DESC");
?>
	<div class="row"><div class="col-md-8 col-md-offset-2">
<?
$n = 0;
while($row = $hasil->fetchArray()){
	$folderKeluarga = $KELUARGA.$row['id_warga']."/";
	?><br class="garistipis">
    <div class="panel <? if($n==0) echo 'panel-primary'; else echo 'panel-default';?>">
    	<div class="panel-heading"><? 
			if($n==0 || $row['tgl_keluar']==0)
				if($row['nama_suami']!='') echo "Keluarga Bapak ".$row['nama_suami']; else echo "Keluarga Ibu ".$row['nama_istri'];
			else
				echo "Keluar tanggal ".date("d M Y H:i",$row['tgl_keluar']);
		?></div>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr><td>Nomor Rumah</td>
                <? if($config->Gunakan_Blok=="true"){ ?>
                    <td><strong><?=$row['blok_rumah']?> - <?=$row['nomor_rumah']?></strong></td>
                <? }else{ ?>
                    <td><strong><?=$row['nomor_rumah']?></strong></td>
                <? } ?>
            </tr>
            <? if(!empty($row['nama_suami'])){ ?>
            	<tr class="success"><td>Bapak</td><td><strong><?=$row['nama_suami']?></strong></td></tr>
            <? } 
			if(!empty($row['telepon_suami'])){
				if(strpos($row['telepon_suami'],',')>-1){
					$tmp = explode(',',$row['telepon_suami']);
					?><tr class="success"><td>Nomor Telepon</td><td><strong><? for($n=0;$n<count($tmp);$n++){
						?><a href="javascript:caltel('<?=$tmp[$n]?>')"><strong><?=$tmp[$n]?></strong></a>&nbsp;&nbsp;<?
					} ?></strong></td></tr>
				<? }else{ ?>
                	<tr class="success"><td>Nomor Telepon</td><td><a href="javascript:caltel('<?=$row['telepon_suami']?>')"><strong><?=$row['telepon_suami']?></strong></a></td></tr>
            <?	} 
			} 
			if(!empty($row['pekerjaan_suami'])){ ?>
            	<tr class="success"><td>Pekerjaan</td><td><strong><?=$row['pekerjaan_suami']?></strong></td></tr>
            <? } 
			if(!empty($row['tglahir_suami'])){ ?>
            	<tr class="success"><td>Umur</td><td><strong><?=date("d M Y",$row['tglahir_suami'])?></strong> (<?
				$ttl = hitungTahun(date("Y-m-d"),date("Y-m-d",$row['tglahir_suami']));
				echo $ttl->y." tahun ".$ttl->m." bulan <span class=\"hidden-xs\">".$ttl->d." hari</span>";
				?>)</td></tr>
            <? } 
			if(!empty($row['nama_istri'])){ ?>
            <tr class="info"><td>Ibu</td><td><strong><?=$row['nama_istri']?></strong></td></tr>
        	<? } 
			if(!empty($row['telepon_istri'])){ 
				if(strpos($row['telepon_istri'],',')>-1){
					$tmp = explode(',',$row['telepon_istri']);
					?><tr class="info"><td>Telepon</td><td><?
					for($n=0;$n<count($tmp);$n++){
						?><a href="javascript:caltel('<?=$tmp[$n]?>')"><strong><?=$tmp[$n]?></strong></a>&nbsp;&nbsp;<?
					}?></td></tr>
				<? }else{ ?>
        			<tr class="info"><td>Telepon</td><td><a href="javascript:caltel('<?=$row['telepon_istri']?>')"><strong><?=$row['telepon_istri']?></strong></a></td></tr>
            <?	} 
			}
			if(!empty($row['pekerjaan_istri'])){ ?>
            <tr class="info"><td>Pekerjaan</td><td><strong><?=$row['pekerjaan_istri']?></strong></td></tr>
            <? } 
			if(!empty($row['tglahir_istri'])){ ?>
            <tr class="info"><td>Umur</td><td><strong><?=date("d M Y",$row['tglahir_istri'])?></strong> (<?
			$ttl = hitungTahun(date("Y-m-d"),date("Y-m-d",$row['tglahir_istri']));
			echo $ttl->y." tahun ".$ttl->m." bulan <span class=\"hidden-xs\">".$ttl->d." hari</span>";
			?>)</td></tr>
        	
            <? } 
			if(!empty($row['email'])){ 
				?><tr><td>Email</td><td><strong><?=$row['email']?></strong></td></tr><?
			}
			?>
        </table>
        <? if(file_exists($folderKeluarga."ktp.suami.wrg") || file_exists($folderKeluarga."ktp.istri.wrg") || file_exists($folderKeluarga."KK.wrg")){ ?>
        <div class="btn-group btn-group-justified">
        	<? if(file_exists($folderKeluarga."ktp.suami.wrg")){ ?>
            <div class="btn-group"><button type="button"  class="btn btn-primary" onClick="tampilkanGambar('<?=$folderKeluarga."ktp.suami.wrg?".time()?>','<?=$row['nama_suami']?>')" data-toggle="modal" data-target=".bs-example-modal-sm"><span class="glyphicon glyphicon-fullscreen"></span> KTP Bapak</button></div>
            <? } if(file_exists($folderKeluarga."ktp.istri.wrg")){ ?>
            <div class="btn-group"><button type="button"  class="btn btn-info" onClick="tampilkanGambar('<?=$folderKeluarga."ktp.istri.wrg?".time()?>','<?=$row['nama_istri']?>')" data-toggle="modal" data-target=".bs-example-modal-sm"><span class="glyphicon glyphicon-fullscreen"></span> KTP Ibu</button></div>
            <? } if(file_exists($folderKeluarga."KK.wrg")){ ?>
            <div class="btn-group"><button type="button"  class="btn btn-warning" onClick="tampilkanGambar('<?=$folderKeluarga."KK.wrg?".time()?>','Kartu Keluarga')" data-toggle="modal" data-target=".bs-example-modal-sm"><span class="glyphicon glyphicon-fullscreen"></span> <span class="hidden-xs">Kartu Keluarga</span><span class="visible-xs-inline">KK</span></button></div>
            <? } ?>
        </div>
        <? }
		$jml = $db->querySingle("select count(nama_lengkap) as jml from t_warga_tambahan 
									where id_warga=".$row['id_warga']);
		if($jml>0){?><br>
          	<? $hazil = $db->query("select nama_lengkap, jenis_kelamin,nomor_hp,hubungan_keluarga,
									tanggal_masuk,tanggal_keluar,tanggal_lahir,status_kawin from t_warga_tambahan 
									where id_warga=".$row['id_warga']." order by tanggal_keluar asc");
				$nn = 1;
				while($relasi = $hazil->fetchArray()){ 
					if($nn==1){
						?><div class="row"><?
					}
					?><div class="col-md-6">
                        <div class="panel panel-<? if($relasi['tanggal_keluar']!=0) echo 'danger'; else echo 'success';?>">
                            <div class="panel-heading"><h3 class="panel-title"><?=$relasi['nama_lengkap']?></h3></div>
                            <table class="table table-striped table-bordered table-hover table-condensed">
                                <? if(!empty($relasi['nomor_hp'])){ ?>
                                <tr><td>Telepon</td><td><a href="javascript:caltel('<?=$relasi['nomor_hp']?>')"><?=$relasi['nomor_hp']?></a></td></tr>
                                <? } if(!empty($relasi['jenis_kelamin'])){ ?>
                                <tr><td>L/P</td><td><? if($relasi['jenis_kelamin']=="L")echo"Laki-laki"; else echo "Perempuan";?></td></tr>
                                <? } if(!empty($relasi['hubungan_keluarga'])){ ?>
                                <tr><td>Hubungan</td><td><?=$relasi['hubungan_keluarga']?></td></tr>
                                <? } if(!empty($relasi['status_kawin'])){ ?>
                                <tr><td>Status</td><td><?=$relasi['status_kawin']?></td></tr>
                                <? } if($relasi['tanggal_lahir']!=0){ ?>
                                <tr><td>Umur</td><td><?=date("d M Y",$relasi['tanggal_lahir'])?> (<?
                                $ttl = hitungTahun(date("Y-m-d"),date("Y-m-d",$relasi['tanggal_lahir']));
                                echo $ttl->y." tahun ".$ttl->m." bulan <span class=\"hidden-xs\">".$ttl->d." hari</span>";
                                ?>)</td></tr>
                                <? } if($relasi['tanggal_masuk']!=0){ ?>
                                <tr><td>Masuk</td><td><?=date("d M Y",$relasi['tanggal_masuk'])?></td></tr>
                                <? } if($relasi['tanggal_keluar']!=0){ ?>
                                <tr><td>Keluar</td><td><?=date("d M Y",$relasi['tanggal_keluar'])?></td></tr>
                                <? } ?>
                            </table>
                        </div>
                    </div>
                    <? 
				if($nn==2){
					?></div><?
					$nn = 0;
				}
				$nn++;
			}
			if($nn==2 || $nn<>1){
				?></div><?
			}
		} 
		if($config->Gunakan_Label=='true'){ 
			$tags = array_filter(explode(",", $row['label_warga']));
			if(count($tags)>0){
			?>
			<div class="panel-footer" align="center">
			<? $warna = array('warning','primary','success','info','danger');
				for($n=0;$n<count($tags);$n++){
					?><span class="label label-<?=$warna[rand(0,4)]?>"><?=trim($tags[$n])?></span>&nbsp;<?
				} ?>
			</div>
			<? }
		}?>
        <div class="panel-footer btn-group btn-group-justified">
        		<a class="btn btn-sm btn-info btn-block" href="#"><span class="glyphicon glyphicon-calendar"></span> <span class="hidden-xs">Riwayat</span> Tamu</a>
        		<a class="btn btn-sm btn-info btn-block" href="?apa=iuranriwayat&id=<?=$row['id_warga']?>"><span class="glyphicon glyphicon-inbox"></span> <span class="hidden-xs">Riwayat</span> Iuran</a>
        		<a class="btn btn-sm btn-info btn-block" href="?apa=dataEdit&id=<?=$row['id_warga']?>"><span class="glyphicon glyphicon-pencil"></span> Edit Data</a>
        </div>
    </div>
        <br>
    <?
$n++;
}
?></div></div>
<script type="text/javascript">
function tampilkanGambar(url,judul){
	document.getElementById('gambar').src = url;
	document.getElementById('judulGambar').innerHTML = judul;
	document.getElementById('linkGambar').setAttribute("href", url);
}
</script>
<div class="modal bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
   <div class="modal-content">
  	<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="judulGambar"></h4>
      </div>
    <div class="modal-body">
      <img src="" id="gambar" class="img-responsive img-rounded">
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <a target="_blank" id="linkGambar" class="btn btn-primary">Download</a>
      </div>
   </div>
  </div>
</div>