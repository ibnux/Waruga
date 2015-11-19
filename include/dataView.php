<?
ikutkan("head.php");
ikutkan("menu.php");

//upgrade database
//t_warga
if(!$db->query("select nomor_kk from t_warga limit 1"))
	$db->query("ALTER TABLE t_warga ADD COLUMN nomor_kk varchar(50);");
if(!$db->query("select ktp_suami from t_warga limit 1"))
	$db->query("ALTER TABLE t_warga ADD COLUMN ktp_suami varchar(50);");
if(!$db->query("select ktp_istri from t_warga limit 1"))
	$db->query("ALTER TABLE t_warga ADD COLUMN ktp_istri varchar(50);");
if(!$db->query("select pendidikan_suami from t_warga limit 1"))
	$db->query("ALTER TABLE t_warga ADD COLUMN pendidikan_suami varchar(50);");
if(!$db->query("select pendidikan_istri from t_warga limit 1"))
	$db->query("ALTER TABLE t_warga ADD COLUMN pendidikan_istri varchar(50);");
if(!$db->query("select tempat_lahir_suami from t_warga limit 1"))
	$db->query("ALTER TABLE t_warga ADD COLUMN tempat_lahir_suami varchar(50);");
if(!$db->query("select tempat_lahir_istri from t_warga limit 1"))
	$db->query("ALTER TABLE t_warga ADD COLUMN tempat_lahir_istri varchar(50);");
if(!$db->query("select warga_negara_suami from t_warga limit 1"))
	$db->query("ALTER TABLE t_warga ADD COLUMN warga_negara_suami varchar(30) DEFAULT 'WNI';");
if(!$db->query("select warga_negara_istri from t_warga limit 1"))
	$db->query("ALTER TABLE t_warga ADD COLUMN warga_negara_istri varchar(30) DEFAULT 'WNI';");
if(!$db->query("select agama_suami from t_warga limit 1"))
	$db->query("ALTER TABLE t_warga ADD COLUMN agama_suami varchar(15) DEFAULT 'ISLAM';");
if(!$db->query("select agama_istri from t_warga limit 1"))
	$db->query("ALTER TABLE t_warga ADD COLUMN agama_istri varchar(15) DEFAULT 'ISLAM';");
if(!$db->query("select email_suami from t_warga limit 1"))
	$db->query("ALTER TABLE t_warga ADD COLUMN email_suami varchar(255);");
if(!$db->query("select email_istri from t_warga limit 1"))
	$db->query("ALTER TABLE t_warga ADD COLUMN email_istri varchar(255);");
if(!$db->query("select catatan from t_warga limit 1"))
	$db->query("ALTER TABLE t_warga ADD COLUMN catatan varchar(300);");
//t_warga_tambahan
if(!$db->query("select no_ktp from t_warga_tambahan limit 1"))
	$db->query("ALTER TABLE t_warga_tambahan ADD COLUMN no_ktp varchar(50);");
if(!$db->query("select warga_negara from t_warga_tambahan limit 1"))
	$db->query("ALTER TABLE t_warga_tambahan ADD COLUMN warga_negara varchar(30) DEFAULT 'WNI';");
if(!$db->query("select agama from t_warga_tambahan limit 1"))
	$db->query("ALTER TABLE t_warga_tambahan ADD COLUMN agama varchar(15) DEFAULT 'ISLAM';");
if(!$db->query("select pendidikan from t_warga_tambahan limit 1"))
	$db->query("ALTER TABLE t_warga_tambahan ADD COLUMN pendidikan varchar(50);");
if(!$db->query("select pekerjaan from t_warga_tambahan limit 1"))
	$db->query("ALTER TABLE t_warga_tambahan ADD COLUMN pekerjaan varchar(200);");
if(!$db->query("select tempat_lahir from t_warga_tambahan limit 1"))
	$db->query("ALTER TABLE t_warga_tambahan ADD COLUMN tempat_lahir varchar(50);");
if(!$db->query("select catatan from t_warga_tambahan limit 1"))
	$db->query("ALTER TABLE t_warga_tambahan ADD COLUMN catatan varchar(300);");
if(!$db->query("select email from t_warga_tambahan limit 1"))
	$db->query("ALTER TABLE t_warga_tambahan ADD COLUMN email varchar(255);");


if(isset($_POST['simpan']) && $_POST['simpan']=='simpan'){
	$db->query("UPDATE t_warga SET 
					`blok_rumah`='".$_POST['blok']."', `nomor_rumah`='".$_POST['nomor']."', 
					`nama_suami`='".$_POST['suami']."', `nama_istri`='".$_POST['istri']."', 
					`anak1`='".$_POST['anak1']."', `anak2`='".$_POST['anak2']."', `anak3`='".$_POST['anak3']."', 
					`anak4`='".$_POST['anak4']."', `telepon_suami`='".$_POST['hp1']."', `telepon_istri`='".$_POST['hp2']."', 
					`pekerjaan`='".$_POST['pekerjaan']."', `last_update`='".date("Y-m-d H:i:s")."'
					WHERE id_warga=".$_POST['id']);
}
if(isset($_GET['id'])){
	$hasil = $db->query("SELECT * 
		FROM t_warga WHERE id_warga=".strtoupper($_GET['id'])." ORDER BY `id_warga` DESC");
}else{
	$hasil = $db->query("SELECT `id_warga`, `X`, `Y`, `blok_rumah`, `nomor_rumah`,label_warga, 
		`nama_suami`, `nama_istri`, `telepon_suami`, `telepon_istri`, tgl_keluar 
		FROM t_warga WHERE blok_rumah='".strtoupper($_GET['blok'])."' and nomor_rumah='".$_GET['nomor']."' ORDER BY `id_warga` DESC");
}

?>
<style>
.nopadding {
   padding: 2px !important;
   margin: 0 !important;
}
</style>
	<div class="row"><div class="col-md-8 col-md-offset-2">
<?
$n = 0;
while($row = $hasil->fetchArray()){
	$folderKeluarga = $KELUARGA.$row['id_warga']."/";
	?><br class="garistipis">
    <div class="panel <? if($row['tgl_keluar']==0) echo 'panel-primary'; else echo 'panel-danger';?>">
    	<div class="panel-heading"><? 
			if($row['tgl_keluar']==0)
				if($row['nama_suami']!='') echo "Keluarga Bapak ".$row['nama_suami']; else echo "Keluarga Ibu ".$row['nama_istri'];
			else
				echo "Keluar tanggal ".date("d M Y H:i",$row['tgl_keluar']);
		?></div>
        <? if(isset($_GET['id'])){ ?>
        	<table class="table table-striped table-bordered table-hover table-condensed">
                <? if(file_exists($folderKeluarga."foto.suami.wrg") || file_exists($folderKeluarga."foto.istri.wrg")){ ?>
                <tr align="center">
                    <td align="center">
                        <img <? if(file_exists($folderKeluarga."foto.suami.wrg")){ 
						?>onClick="tampilkanGambar('<?=$folderKeluarga."foto.suami.wrg"?>','Foto <?=$row['nama_suami']?>')" data-toggle="modal" data-target=".bs-foto-modal-sm" <?
						}?>src="?apa=thumb&kotak&s=200&p=<?=$folderKeluarga?>foto.suami.wrg" class="img-rounded img-responsive">
                        </a>
                        </td><td align="center">
                        <img <? if(file_exists($folderKeluarga."foto.istri.wrg")){ 
						?>onClick="tampilkanGambar('<?=$folderKeluarga."foto.istri.wrg"?>','Foto <?=$row['nama_istri']?>')" data-toggle="modal" data-target=".bs-foto-modal-sm" <?
						} ?>src="?apa=thumb&kotak&s=200&p=<?=$folderKeluarga?>foto.istri.wrg" class="img-rounded img-responsive">
                        </td>
                </tr>
                <? }?>
                <tr><td>Nomor Rumah</td>
                    <? if($config->Gunakan_Blok=="true"){ 
						$blokno = $row['blok_rumah'].' - '.$row['nomor_rumah'];
						?>
                        <td><strong><?=$row['blok_rumah']?> - <?=$row['nomor_rumah']?></strong></td>
                    <? }else{ 
						$blokno = $row['nomor_rumah'];?>
                        <td><strong><?=$row['nomor_rumah']?></strong></td>
                    <? } ?>
                </tr>
                <? if(!empty($row['nomor_kk'])){ ?>
                <tr>
                	<td>Nomor KK</td>
                    <td><strong><?=$row['nomor_kk']?></strong></td>
                </tr>
                <? }?>
            </table>
        	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <? if(!empty($row['nama_suami']) && $row['nama_suami']!='belum'){ ?>
            <div class="panel panel-info">
                <div class="panel-heading" role="tab" id="headingOne">
                  <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  <h4 class="panel-title">
                      Bapak <?=$row['nama_suami']?>
                  </h4>
                  </a>
                </div>
                <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                  <table class="table table-striped table-bordered table-hover table-condensed">
                     <? if(!empty($row['telepon_suami'])){
                        if(strpos($row['telepon_suami'],',')>-1){
                            $tmp = explode(',',$row['telepon_suami']);
                            ?><tr class="success"><td>Telepon</td><td><strong><? for($n=0;$n<count($tmp);$n++){
                                ?><a href="javascript:caltel('<?=$tmp[$n]?>')"><strong><?=$tmp[$n]?></strong></a>&nbsp;&nbsp;<?
                            } ?></strong></td></tr>
                        <? }else{ ?>
                            <tr class="success"><td>Telepon</td><td><a href="javascript:caltel('<?=$row['telepon_suami']?>')"><strong><?=$row['telepon_suami']?></strong></a></td></tr>
                    <?	} 
                    } 
					if(!empty($row['email_suami'])){ ?>
                        <tr class="success"><td>Email</td><td><strong><a href="mailto:<?=$row['email_suami']?>"><?=$row['email_suami']?></a></strong></td></tr>
                    <? } 
                    if(!empty($row['pekerjaan_suami'])){ ?>
                        <tr class="success"><td>Pekerjaan</td><td><strong><?=$row['pekerjaan_suami']?></strong></td></tr>
                    <? }  
					if(!empty($row['pendidikan_suami'])){ ?>
                        <tr class="success"><td>Pendidikan</td><td><strong><?=$row['pendidikan_suami']?></strong></td></tr>
                    <? } 
					if(!empty($row['ktp_suami'])){ ?>
                        <tr class="success"><td>No. KTP/Passport</td><td><strong><?=$row['ktp_suami']?></strong></td></tr>
                    <? }
					if(!empty($row['warga_negara_suami'])){ ?>
                        <tr class="success"><td>Warga Negara</td><td><strong><?=$row['warga_negara_suami']?></strong></td></tr>
                    <? }
					if(!empty($row['agama_suami'])){ ?>
                        <tr class="success"><td>Agama</td><td><strong><?=$row['agama_suami']?></strong></td></tr>
                    <? } 
                    if(!empty($row['tglahir_suami'])){ ?>
                        <tr class="success"><td>TTL</td><td><strong>
                        <? if(!empty($row['tempat_lahir_suami'])){ echo $row['tempat_lahir_suami'].", "; }?>
                        <?=date("d M Y",$row['tglahir_suami'])?></strong> <br><small><?
                        $ttl = hitungTahun(date("Y-m-d"),date("Y-m-d",$row['tglahir_suami']));
                        echo $ttl->y." tahun ".$ttl->m." bulan <span class=\"hidden-xs\">".$ttl->d." hari</span>";
                        ?></small></td></tr>
                    <? } ?>
                  </table>
                  <!--<div class="panel-body">
                    buat pengingat, siapa tahu nanti bisa digunakan
                  </div>-->
                </div>
            </div>
            <? } 
			if(!empty($row['nama_istri'])){ ?>
                <div class="panel panel-danger">
                <div class="panel-heading" role="tab" id="headingTwo">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    <h4 class="panel-title">
                      Ibu <?=$row['nama_istri']?>
                    </h4>
                    </a>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                  <table class="table table-striped table-bordered table-hover table-condensed">
                      <? if(!empty($row['telepon_istri'])){ 
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
					if(!empty($row['email_istri'])){ ?>
                        <tr class="info"><td>Email</td><td><strong><a href="mailto:<?=$row['email_istri']?>"><?=$row['email_istri']?></a></strong></td></tr>
                    <? } 
                    if(!empty($row['pekerjaan_istri'])){ ?>
                        <tr class="info"><td>Pekerjaan</td><td><strong><?=$row['pekerjaan_istri']?></strong></td></tr>
                    <? } 
					if(!empty($row['pendidikan_istri'])){ ?>
                        <tr class="info"><td>Pendidikan</td><td><strong><?=$row['pendidikan_istri']?></strong></td></tr>
                    <? } 
					if(!empty($row['ktp_istri'])){ ?>
                        <tr class="info"><td>No. KTP/Passport</td><td><strong><?=$row['ktp_istri']?></strong></td></tr>
                    <? }
					if(!empty($row['warga_negara_istri'])){ ?>
                        <tr class="info"><td>Warga Negara</td><td><strong><?=$row['warga_negara_istri']?></strong></td></tr>
                    <? }
					if(!empty($row['agama_istri'])){ ?>
                        <tr class="info"><td>Agama</td><td><strong><?=$row['agama_istri']?></strong></td></tr>
                    <? } 
                    if(!empty($row['tglahir_istri'])){ ?>
                    <tr class="info"><td>TTL</td><td><strong>
                    <? if(!empty($row['tempat_lahir_istri'])){ echo $row['tempat_lahir_istri'].", "; }?>
                    <?=date("d M Y",$row['tglahir_istri'])?></strong> <br><small><?
                    $ttl = hitungTahun(date("Y-m-d"),date("Y-m-d",$row['tglahir_istri']));
                    echo $ttl->y." tahun ".$ttl->m." bulan <span class=\"hidden-xs\">".$ttl->d." hari</span>";
                    ?></small></td></tr>
                    <? }  ?>
                  </table>
                </div>
                </div>
            <? } ?>
            </div>
        <? }else{ ?>
            <table class="table table-striped table-bordered table-hover table-condensed">
                <? if(file_exists($folderKeluarga."foto.suami.wrg") || file_exists($folderKeluarga."foto.istri.wrg")){ ?>
                <tr align="center">
                    <td align="center">
                        <img src="?apa=thumb&kotak&s=200&p=<?=$folderKeluarga?>foto.suami.wrg" class="img-rounded img-responsive">
                        </td><td align="center">
                        <img src="?apa=thumb&kotak&s=200&p=<?=$folderKeluarga?>foto.istri.wrg" class="img-rounded img-responsive">
                        </td>
                </tr>
                <? }?>
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
				if(!empty($row['email_suami'])){ 
                    ?><tr><td>Email</td><td><strong><a href="mailto:<?=$row['email_suami']?>"><?=$row['email_suami']?></a></strong></td></tr><?
                } 
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
                if(!empty($row['email_istri'])){ 
                    ?><tr><td>Email</td><td><strong><a href="mailto:<?=$row['email_istri']?>"><?=$row['email_istri']?></a></strong></td></tr><?
                }
                ?>
            </table>
            <? 
		}//end isset($_GET['id'])
		if(!empty($row['catatan'])){ ?>
        	<div class="panel-footer" align="center"><?=nl2br(strip_tags($row['catatan']))?></div>
		<? } 
		if(file_exists($folderKeluarga."ktp.suami.wrg") || file_exists($folderKeluarga."ktp.istri.wrg") || file_exists($folderKeluarga."KK.wrg")){ ?>
        <div class="btn-group btn-group-justified">
        	<? if(file_exists($folderKeluarga."ktp.suami.wrg")){ ?>
            <div class="btn-group"><button type="button"  class="btn btn-sm btn-primary" onClick="tampilkanGambar('<?=$folderKeluarga."ktp.suami.wrg"?>','KTP <?=$row['nama_suami']?>')" data-toggle="modal" data-target=".bs-foto-modal-sm"><span class="glyphicon glyphicon-fullscreen"></span> KTP Bapak</button></div>
            <? } if(file_exists($folderKeluarga."ktp.istri.wrg")){ ?>
            <div class="btn-group"><button type="button"  class="btn btn-sm btn-info" onClick="tampilkanGambar('<?=$folderKeluarga."ktp.istri.wrg"?>','KTP <?=$row['nama_istri']?>')" data-toggle="modal" data-target=".bs-foto-modal-sm"><span class="glyphicon glyphicon-fullscreen"></span> KTP Ibu</button></div>
            <? } if(file_exists($folderKeluarga."KK.wrg")){ ?>
            <div class="btn-group"><button type="button"  class="btn btn-sm btn-warning" onClick="tampilkanGambar('<?=$folderKeluarga."KK.wrg"?>','Kartu Keluarga <?=$blokno?>')" data-toggle="modal" data-target=".bs-foto-modal-sm"><span class="glyphicon glyphicon-fullscreen"></span> <span class="hidden-xs">Kartu Keluarga</span><span class="visible-xs-inline">KK</span></button></div>
            <? } ?>
        </div>
        <? } 
		if(isset($_GET['id'])){ 
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
		}
		?>
        <div class="btn-group btn-group-justified">
        		<a class="btn btn-sm btn-warning btn-block hide" href="#"><span class="glyphicon glyphicon-calendar"></span> <span class="hidden-xs">Riwayat</span> Tamu</a>
        		<a class="btn btn-sm btn-primary btn-block" href="?apa=iuranriwayat&id=<?=$row['id_warga']?>"><span class="glyphicon glyphicon-inbox"></span> <span class="hidden-xs">Riwayat</span> Iuran</a>
        		<a class="btn btn-sm btn-info btn-block" href="?apa=dataEdit&id=<?=$row['id_warga']?>"><span class="glyphicon glyphicon-pencil"></span> Edit Data</a>
        </div>
        <div class="panel-body">
		<?
		$jml = $db->querySingle("select count(nama_lengkap) as jml from t_warga_tambahan 
									where id_warga=".$row['id_warga']);
		if($jml>0){?>
        	<legend>Anggota Keluarga</legend>
          	<? $hazil = $db->query("select id_warga_tambahan,nama_lengkap, jenis_kelamin,nomor_hp,email,hubungan_keluarga,tanggal_keluar 
									from t_warga_tambahan 
									where id_warga=".$row['id_warga']." order by tanggal_keluar asc");
				while($relasi = $hazil->fetchArray()){ 
					?>
                    <div class="col-sm-6 col-md-4 col-xs-6 nopadding">
                        <div class="thumbnail<? if($relasi['tanggal_keluar']==0) echo ' alert alert-success'; else echo ' alert alert-danger';?>">
                        <? if(file_exists($folderKeluarga.$relasi['id_warga_tambahan'].".foto.wrg")){ ?>
                        <img src="?apa=thumb&kotak&s=80&p=<?=$folderKeluarga.$relasi['id_warga_tambahan']?>.foto.wrg" alt="foto" class="img-rounded">
                        <? } ?>
                            <div class="caption">
                            <a href="#" onClick="tampilkanBiodata('<?=$relasi['id_warga_tambahan']?>','<?=$row['id_warga']?>','<?=$relasi['nama_lengkap']?>')" data-toggle="modal" data-target=".bs-biodata-modal-sm">
                            <h4><?=$relasi['nama_lengkap']?></h4>
                            </a>
                            <p><? if(!empty($relasi['nomor_hp'])){ ?>
                                <a href="javascript:caltel('<?=$relasi['nomor_hp']?>')"><?=$relasi['nomor_hp']?></a><br>
                                <? }?>
                                <? if(!empty($relasi['email'])){ ?>
                                <a href="mailto:<?=$relasi['email']?>"><?=$relasi['email']?></a><br>
                                <? }?>
                                <? if(!empty($relasi['jenis_kelamin'])){ 
                                    if($relasi['jenis_kelamin']=="L")echo"Laki-laki<br>"; else echo "Perempuan<br>";
                                 }?>
                                <? if(!empty($relasi['hubungan_keluarga'])) echo $relasi['hubungan_keluarga'].'<br>';?>
                                <? if($relasi['tanggal_keluar']>0){ ?>
                                <br><span class="label label-danger"><?=date("d M Y", $relasi['tanggal_keluar']);?></span>
                                <? } ?>
                            </p>
                            </div>
                        </div>
                    </div>
                    <? 
				}
			?></div><?
		} 
		}//end isset($_GET['id']) 
		else{ ?>
		<div class="panel-footer btn-group btn-group-justified">
        		<a class="btn btn-sm btn-info btn-block" href="?apa=<?=$apa?>&id=<?=$row['id_warga']?>"><span class="glyphicon glyphicon-user"></span> Lihat Data Lengkap</a>
        </div>
        <? } ?>
    </div>
        <br>
    <?
$n++;
}
?></div></div>
<script type="text/javascript">
function tampilkanGambar(url,judul){
	document.getElementById('gambar').src = url+'?<?=time()?>';
	document.getElementById('judulGambar').innerHTML = judul;
	document.getElementById('linkGambar').setAttribute("href", '?apa=unduh&f='+judul+'&p='+url);
}
function tampilkanBiodata(id,k,judul){
	document.getElementById('judulBiodata').innerHTML = judul;
	$.ajax({url: '?apa=ajaxAnggotaKeluarga&id='+id+'&k='+k, success: function(result){
        $("#isiBiodata").html(result);
    }});
}
</script>
<div class="modal bs-foto-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
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
        <a id="linkGambar" class="btn btn-primary">Download</a>
      </div>
   </div>
  </div>
</div>
<div class="modal bs-biodata-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
   <div class="modal-content">
  	<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="judulBiodata"></h4>
      </div>
    <div class="modal-body" id="isiBiodata">
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
   </div>
  </div>
</div>