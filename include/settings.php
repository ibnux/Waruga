<?
$path = "./config.json";
$msg = '';
if(isset($_GET['save'])){
	if(count($_POST)>2){
		$json = json_encode($_POST);
		if(file_put_contents($path,$json)){
			$msg = '<div class="alert alert-success">
				<button class="close" data-dismiss="alert">×</button>
				Saving data <b>success!</b>
				</div>';
		}else{
			$msg = '<div class="alert alert-error">
			<button class="close" data-dismiss="alert">×</button>
			 <h4 class="alert-heading">Error!</h4>
				Saving data <b>failed!</b>
			</div>';	
		}
	}
}
ikutkan("head.php");
ikutkan("menu.php");
echo $msg;
if(isset($_POST['boleh'])){
?>

<form role="form" class="form-horizontal" method="post" enctype="application/x-www-form-urlencoded" action="?apa=<?=$apa?>&save">
    <fieldset>
    <legend align="center">Pengaturan</legend>
		<?php
            $conf = json_decode(file_get_contents($path),true);
            foreach ( $conf as $key => $value ) {
				if($value=='true' || $value=='false'){
					?>
					<div class="control-group">
						<label class="control-label" for="<?=$key?>"><b><?=str_replace("_"," ",$key)?></b></label>
                        <select id="<?=$key?>" name="<?=$key?>" style="width: 100%;" class="form-control">
                        	<option value="true" <? if($value=='true') echo 'selected';?>>Ya</option>
                        	<option value="false" <? if($value=='false') echo 'selected';?>>Tidak</option>
                        </select>
					</div>
					<? 

				}else if($value=='separator'){
					?><input type="hidden" name="<?=$key?>" value="<?=$value?>"><hr><?
				}else{
					?>
					<div class="control-group">
						<label class="control-label" for="<?=$key?>"><b><?=str_replace("_"," ",$key)?></b></label>
						<input type="text" style="width: 100%;" class="form-control" id="<?=$key?>" name="<?=$key?>" value="<?= $value?>">
					</div>
					<? 
				}
			} ?>
        <br>
        <button type="submit" class="btn" style="width: 100%;" >Simpan</button>
    </fieldset>
</form>
<? } 
	$conf = json_decode(file_get_contents($path),true);
?>
<form role="form" class="form-horizontal" method="post" enctype="application/x-www-form-urlencoded" action="?apa=settings&save">
    <fieldset>
    <legend align="center">Pengaturan</legend>
        <div class="control-group">
            <label class="control-label" for="Judul_Aplikasi"><b>Judul Aplikasi</b></label>
            <input type="text" style="width: 100%;" class="form-control" id="Judul_Aplikasi" name="Judul_Aplikasi" value="<?=$conf['Judul_Aplikasi']?>">
        </div>
        <div class="control-group">
            <label class="control-label" for="Blok"><b>Blok</b></label>
            <input type="text" style="width: 100%;" class="form-control" id="Blok" name="Blok" value="<?=$conf['Blok']?>">
        </div>
        <div class="control-group">
            <label class="control-label" for="Nomor"><b>Nomor</b></label>
            <input type="text" style="width: 100%;" class="form-control" id="Nomor" name="Nomor" value="<?=$conf['Nomor']?>">
        </div>
        <div class="control-group">
            <label class="control-label" for="Iuran_Bulanan"><b>Iuran Bulanan</b></label>
            <input type="text" style="width: 100%;" class="form-control" id="Iuran_Bulanan" name="Iuran_Bulanan" value="<?=$conf['Iuran_Bulanan']?>">
        </div>
        <div class="control-group">
            <label class="control-label" for="Sync_URL"><b>Sync URL</b></label>
            <input type="text" style="width: 100%;" class="form-control" id="Sync_URL" name="Sync_URL" value="<?=$conf['Sync_URL']?>">
        </div>
        <div class="control-group">
            <label class="control-label" for="Font_Size"><b>Font Size</b></label>
            <input type="text" style="width: 100%;" class="form-control" id="Font_Size" name="Font_Size" value="<?=$conf['Font_Size']?>">
        </div>
        <input type="hidden" name="separator" value="separator"><hr>					
        <div class="control-group">
            <label class="control-label" for="SMS_URL_Parameter"><b>SMS URL Parameter</b></label>
            <input type="text" style="width: 100%;" class="form-control" id="SMS_URL_Parameter" name="SMS_URL_Parameter" value="<?=$conf['SMS_URL_Parameter']?>">
        </div>
        <div class="control-group">
            <label class="control-label" for="SMS_Text_Parameter"><b>SMS Text Parameter</b></label>
            <input type="text" style="width: 100%;" class="form-control" id="SMS_Text_Parameter" name="SMS_Text_Parameter" value="<?=$conf['SMS_Text_Parameter']?>">
        </div>
        <div class="control-group">
            <label class="control-label" for="SMS_Number_Parameter"><b>SMS Number Parameter</b></label>
            <input type="text" style="width: 100%;" class="form-control" id="SMS_Number_Parameter" name="SMS_Number_Parameter" value="<?=$conf['SMS_Number_Parameter']?>">
        </div>
        <div class="control-group">
            <label class="control-label" for="SMS_Additional_Parameter"><b>SMS Additional Parameter</b></label>
            <input type="text" style="width: 100%;" class="form-control" id="SMS_Additional_Parameter" name="SMS_Additional_Parameter" value="<?=$conf['SMS_Additional_Parameter']?>">
        </div>
        <div class="control-group">
            <label class="control-label" for="SMS_Success_Parameter"><b>SMS Success Parameter</b></label>
            <input type="text" style="width: 100%;" class="form-control" id="SMS_Success_Parameter" name="SMS_Success_Parameter" value="<?=$conf['SMS_Success_Parameter']?>">
        </div>
        <div class="control-group">
            <label class="control-label" for="SMS_Jeda_Pengiriman"><b>SMS Jeda Pengiriman</b></label>
            <input type="text" style="width: 100%;" class="form-control" id="SMS_Jeda_Pengiriman" name="SMS_Jeda_Pengiriman" value="<?=$conf['SMS_Jeda_Pengiriman']?>">
        </div>
        <div class="control-group">
            <label class="control-label" for="SMS_Jumlah_Pengiriman"><b>SMS Jumlah Pengiriman</b></label>
            <input type="text" style="width: 100%;" class="form-control" id="SMS_Jumlah_Pengiriman" name="SMS_Jumlah_Pengiriman" value="<?=$conf['SMS_Jumlah_Pengiriman']?>">
        </div>
        <input type="hidden" name="separator2" value="separator"><hr>
        <div class="control-group">
            <label class="control-label" for="Gunakan_Blok"><b>Gunakan Blok</b></label>
            <select id="Gunakan_Blok" name="Gunakan_Blok" style="width: 100%;" class="form-control">
                <option value="true"<? if($conf['Boleh_Hapus_Data_Kas']=='true') echo ' selected';?>>Ya</option>
                <option value="false"<? if($conf['Gunakan_Blok']=='false') echo ' selected';?>>Tidak</option>
            </select>
        </div>
        <div class="control-group">
            <label class="control-label" for="Gunakan_Label"><b>Gunakan Label</b></label>
            <select id="Gunakan_Label" name="Gunakan_Label" style="width: 100%;" class="form-control">
                <option value="true"<? if($conf['Boleh_Hapus_Data_Kas']=='true') echo ' selected';?>>Ya</option>
                <option value="false"<? if($conf['Gunakan_Label']=='false') echo ' selected';?>>Tidak</option>
            </select>
        </div>
        <div class="control-group">
            <label class="control-label" for="Gunakan_Denah"><b>Gunakan Denah</b></label>
            <select id="Gunakan_Denah" name="Gunakan_Denah" style="width: 100%;" class="form-control">
                <option value="true"<? if($conf['Boleh_Hapus_Data_Kas']=='true') echo ' selected';?>>Ya</option>
                <option value="false"<? if($conf['Gunakan_Denah']=='false') echo ' selected';?>>Tidak</option>
            </select>
        </div>
        <input type="hidden" name="separator3" value="separator"><hr>
        <div class="control-group">
            <label class="control-label" for="Sandi_Sinkronisasi"><b>Sandi Sinkronisasi</b></label>
            <input type="text" style="width: 100%;" class="form-control" id="Sandi_Sinkronisasi" name="Sandi_Sinkronisasi" value="<?=$conf['Sandi_Sinkronisasi']?>">
        </div>
        <div class="control-group">
            <label class="control-label" for="Unggah_Foto_KTP_KK"><b>Unggah Foto KTP KK</b></label>
            <select id="Unggah_Foto_KTP_KK" name="Unggah_Foto_KTP_KK" style="width: 100%;" class="form-control">
                <option value="true"<? if($conf['Unggah_Foto_KTP_KK']=='true') echo ' selected';?>>Ya</option>
                <option value="false"<? if($conf['Unggah_Foto_KTP_KK']=='false') echo ' selected';?>>Tidak</option>
            </select>
        </div>
        <div class="control-group">
            <label class="control-label" for="Boleh_Hapus_Data_Kas"><b>Boleh Hapus Data Kas</b></label>
            <select id="Boleh_Hapus_Data_Kas" name="Boleh_Hapus_Data_Kas" style="width: 100%;" class="form-control">
                <option value="true"<? if($conf['Boleh_Hapus_Data_Kas']=='true') echo ' selected';?>>Ya</option>
                <option value="false"<? if($conf['Boleh_Hapus_Data_Kas']=='false') echo ' selected';?>>Tidak</option>
            </select>
        </div>
        <br>
        <button type="submit" class="btn btn-success" style="width: 100%;" >Simpan</button>
    </fieldset>
</form>
<br><br>
