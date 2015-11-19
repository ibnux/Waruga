<?

ikutkan("head.php");
ikutkan("menu.php");
$do = $_GET['do'];
$id = $_GET['id']*1;
?>
<legend>SMS</legend>
<div class="row">
	<div class="col-md-6 col-md-offset-3">
<?
if($_POST['save']=='yes'){
	if(!empty($_POST['topik']) && !empty($_POST['topik'])){
		if($do=='tambahkan'){
			if($db->exec("insert into t_sms(topik_sms,isi_sms,terakhir_dikirim,tanggal_dibuat)
						values('".$db->escapeString($_POST['topik'])."','".$db->escapeString($_POST['sms'])."',0,".time().");")){
				showAlert("SMS berhasil ditambahkan",'success');
			}else{
				showAlert("SMS gagal ditambahkan.<br>".$db->lastErrorMsg,'danger');
			}
		}else{
			if($db->exec("update t_sms set topik_sms='".$db->escapeString($_POST['topik'])."',
						isi_sms='".$db->escapeString($_POST['sms'])."' where id_sms=$id")){
				showAlert("SMS berhasil diubah",'success');
			}else{
				showAlert("SMS gagal diubah.<br>".$db->lastErrorMsg,'danger');
			}
		}
	}else{
		showAlert("Mohon isi semua data",'danger');
	}
}
$SMS = array();
if(!empty($id)){
	$SMS = $db->querySingle("select isi_sms,topik_sms from t_sms where id_sms=$id",true);
}
?>
    <form role="form" action="?apa=<?=$apa?>&do=<?=$do?>&id=<?=$id?>" method="post">
    <div class="form-group">
        <label for="topik">Topik SMS</label>
        <input type="text" class="form-control" id="topik" name="topik" value="<?=$SMS['topik_sms']?>">
    </div>
    <div class="form-group">
        <label for="sms">Isi SMS</label>
        <textarea onKeyUp="cekKarakter(this.value);" class="form-control" id="sms" name="sms"><? if(empty($SMS['isi_sms'])) echo "Yth. {nama}, "; else echo $SMS['isi_sms']; ?></textarea>
        <p class="help-block">gunakan <strong>{nama}</strong> akan otomatis diganti dengan nama warga<br><span class="label label-primary"><span id="jumlah"></span> karakter</span></p>
    </div>
    <div class="row">
		<div class="col-xs-8">
        <button type="submit" class="btn btn-primary btn-block" name="save" value="yes"><?=ucfirst($do)?></button>
    	</div>
        <div class="col-xs-4">
        <a href="?apa=SMSlist" class="btn btn-warning btn-block">Kembali</a>
        </div>
    </div>
    </form>
    </div>
</div>
<script>
function cekKarakter(field){
	document.getElementById('jumlah').innerHTML = field.length;
}
</script>