<?php
$id = $_GET['id']*1;
$k = $_GET['k']*1;
$hazil = $db->query("select * 
					from t_warga_tambahan 
					where id_warga_tambahan=$id");
$folderKeluarga = $KELUARGA.$k."/";
while($relasi = $hazil->fetchArray()){
	?>
	<table class="table table-striped table-bordered table-hover table-condensed">
    	<tr align="center">
            <td align="center">
            	<a href="?apa=unduh&f=foto <?=$relasi['nama_lengkap']?>&p=<?=$folderKeluarga.$id.".foto.wrg"?>" onClick="return confirm('unduh?');">
                <img src="?apa=thumb&kotak&s=200&p=<?=$folderKeluarga.$id?>.foto.wrg" class="img-rounded img-responsive">
                </a>
            </td>
            <td align="center">
            	<a href="?apa=unduh&f=ktp <?=$relasi['nama_lengkap']?>&p=<?=$folderKeluarga.$id.".ktp.wrg"?>" onClick="return confirm('unduh?');">
                <img src="?apa=thumb&kotak&s=200&p=<?=$folderKeluarga.$id?>.ktp.wrg" class="img-rounded img-responsive">
                </a>
            </td>
        </tr>
		<? if(!empty($relasi['nomor_hp'])){ ?>
        <tr><td>Telepon</td><td><a href="javascript:caltel('<?=$relasi['nomor_hp']?>')"><?=$relasi['nomor_hp']?></a></td></tr>
        <? }if(!empty($relasi['email'])){ ?>
        <tr><td>Email</td><td><a href="mailto:<?=$relasi['email']?>"><?=$relasi['email']?></a></td></tr>
        <? } if(!empty($relasi['jenis_kelamin'])){ ?>
        <tr><td>L/P</td><td><? if($relasi['jenis_kelamin']=="L")echo"Laki-laki"; else echo "Perempuan";?></td></tr>
        <? } if(!empty($relasi['hubungan_keluarga'])){ ?>
        <tr><td>Hubungan</td><td><?=$relasi['hubungan_keluarga']?></td></tr>
        <? } if(!empty($relasi['warga_negara'])){ ?>
        <tr><td>Warga Negara</td><td><?=$relasi['warga_negara']?></td></tr>
        <? } if(!empty($relasi['no_ktp'])){ ?>
        <tr><td>No KTP</td><td><?=$relasi['no_ktp']?></td></tr>
        <? } if(!empty($relasi['agama'])){ ?>
        <tr><td>Agama</td><td><?=$relasi['agama']?></td></tr>
        <? } if(!empty($relasi['pendidikan'])){ ?>
        <tr><td>Pendidikan</td><td><?=$relasi['pendidikan']?></td></tr>
        <? } if(!empty($relasi['pekerjaan'])){ ?>
        <tr><td>Pekerjaan</td><td><?=$relasi['pekerjaan']?></td></tr>
        <? } if(!empty($relasi['status_kawin'])){ ?>
        <tr><td>Status</td><td><?=$relasi['status_kawin']?></td></tr>
        <? } if($relasi['tanggal_lahir']!=0){ ?>
        <tr><td>TTL</td><td><? if(!empty($row['tempat_lahir'])){ echo $row['tempat_lahir'].", "; }?>
		<?=date("d M Y",$relasi['tanggal_lahir'])?> <br><small><?
        $ttl = hitungTahun(date("Y-m-d"),date("Y-m-d",$relasi['tanggal_lahir']));
        echo $ttl->y." tahun ".$ttl->m." bulan <span class=\"hidden-xs\">".$ttl->d." hari</span></small>";
        ?></td></tr>
        <? } if($relasi['tanggal_masuk']!=0){ ?>
        <tr><td>Tanggal Masuk</td><td><?=date("d M Y",$relasi['tanggal_masuk'])?></td></tr>
        <? } if($relasi['tanggal_keluar']!=0){ ?>
        <tr class="danger"><td>Keluar</td><td><?=date("d M Y",$relasi['tanggal_keluar'])?></td></tr>
        <? } if(!empty($relasi['catatan'])){ ?>
        <tr><td colspan="2" align="center" class="info"><em>"<?=$relasi['catatan']?>"</em></td></tr>
        <? } ?>
    </table>
	<?
};
die();