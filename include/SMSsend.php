<?

ikutkan("head.php");
ikutkan("menu.php"); 

if(!empty($_GET['id'])){
	$_POST['sms'] = $db->querySingle("select isi_sms from t_sms where id_sms=".($_GET['id']*1));
	$db->exec("UPDATE t_sms SET terakhir_dikirim=".time()." WHERE id_sms=".($_GET['id']*1));
}

?>
<hr class="garistipis">
<center><b>Broadcast SMS</b></center>
<hr class="garistipis">
<script>
var detik = <?=$config->SMS_Jeda_Pengiriman?>;
function hitungWaktu(){
	this.detik = this.detik-1;
	if(detik==0){
		<? if($_POST['test']!='yes') { ?>
		document.getElementById('testing').checked = false;
		<? } ?>
		document.getElementById('formulirSMS').submit();
	}else{
		document.getElementById('waktu').innerHTML = detik + " detik";
		setTimeout(hitungWaktu,1000);
	}
}
</script>
<div style="max-height:200px;overflow:scroll">
<?
$hal = $_GET['hal'];
if(empty($hal)) $hal = 0;

if($_GET['send']=='true'){
	//echo shell_exec($smstext);
	$sms = urldecode($_POST['sms']);
	$perpage = $config->SMS_Jumlah_Pengiriman;
	$halnya = $hal * $perpage;
	//if($_POST['test']=='yes')
	//	$hasil = $db->query("SELECT `blok_rumah`, `nomor_rumah`, `nama_suami`, `nama_istri`,`telepon_suami`,`telepon_istri` FROM t_warga WHERE nama_suami<>'belum'");
	//else
	$where = "";
	if($config->Gunakan_Blok=='true'){
		foreach ($_POST['blok'] as $blok){
			$bloks .= "'$blok',";
		}
		if(count($_POST['blok'])>0)
			$where = "AND blok_rumah IN (".substr($bloks,0,strlen($bloks)-1).")";
	}
	if($config->Gunakan_Label=='true'){
		#INCLUDE
		if(count($_POST['include'])>0){
			$where .= " AND ";
			foreach ($_POST['include'] as $inc){
				$incs .= " labelExist(label_warga,'$inc') or ";
			}
			$where .= "(".substr($incs,0,strlen($incs)-3).")";
		}
		#EXCLUDE
		if(count($_POST['exclude'])>0){
			$where .= " AND ";
			foreach ($_POST['exclude'] as $exc){
				$excs .= " not labelExist(label_warga,'$exc') and ";
			}
			$where .= "(".substr($excs,0,strlen($excs)-4).")";
		}
	}
	$sql = "SELECT `blok_rumah`, `nomor_rumah`, `nama_suami`, `nama_istri`,`telepon_suami`,`telepon_istri` 
						FROM t_warga 
						WHERE nama_suami<>'belum' AND tgl_keluar<1 $where ORDER BY `blok_rumah`, CAST( nomor_rumah AS INTEGER) ASC limit $halnya,$perpage";
	//echo $sql;
	$hasil = $db->query($sql);
	$n = 0;
	while($row = $hasil->fetchArray()){
		$lanjut = true;
		$nama = "";
		$nomorhp = "";
		if($_POST['siapa']=="bapak"){
			if(empty($row['nama_suami'])){ 
				echo "Ga ada nama suami [".$row['blok_rumah']."-".$row['nomor_rumah']."]<hr>";
				$lanjut = false;
			}
			if(empty($row['telepon_suami'])) { 
				echo "Ga ada nomor HP [".$row['blok_rumah']."-".$row['nomor_rumah']."]<hr>";
				$lanjut = false;
			}
			$nama = " pak ".$row['nama_suami'];
			$nomorhp = $row['telepon_suami'];
		}else if($_POST['siapa']=="ibu"){
			if(empty($row['nama_istri'])) { 
				echo "Ga ada nama istri [".$row['blok_rumah']."-".$row['nomor_rumah']."]<hr>";
				$lanjut = false;
			}
			if(empty($row['telepon_istri'])) { 
				echo "Ga ada nomor istri [".$row['blok_rumah']."-".$row['nomor_rumah']."]<hr>";
				$lanjut = false;
			}
			$nama = " ibu ".$row['nama_istri'];
			$nomorhp = $row['telepon_istri'];
		}else{
			if(!empty($row['nama_suami']))
				$nama = " pak ".$row['nama_suami'];
			else
				$nama = " ibu ".$row['nama_istri'];
			//jika ga ada nama, maka keluar
			if(empty($row['nama_suami']) && empty($row['nama_istri'])){ 
				echo "Ga ada nama suami dan istri [".$row['blok_rumah']."-".$row['nomor_rumah']."]<hr>";
				$lanjut = false;
			}
			//pilih nomor yang ada
			if(!empty($row['telepon_suami']))
				$nomorhp = $row['telepon_suami'];
			else
				$nomorhp = $row['telepon_istri'];
			//jika ga ada nomor, maka keluar
			if(empty($row['telepon_suami']) && empty($row['telepon_istri'])) { 
				echo "Ga ada nomor hp suami/istri [".$row['blok_rumah']."-".$row['nomor_rumah']."]<hr>";
				$lanjut = false;
			}
		}
		
		$sms = str_replace("{nama}",$nama,$_POST['sms']);
		if($_POST['test']=='yes' && $lanjut){
			if(strlen($sms)>160)
				echo "$nama $nomorhp [".$row['blok_rumah']."-".$row['nomor_rumah']."]<br><font color=\"red\">".strlen($sms).": <i>&ldquo;".nl2br($sms)."&rdquo;</i></font><br><hr class=\"garistipis\">";
			else
				echo "$nama $nomorhp [".$row['blok_rumah']."-".$row['nomor_rumah']."]<br>".strlen($sms).": <i>&ldquo;".nl2br($sms)."&rdquo;</i><br><hr class=\"garistipis\">";
		}else if($lanjut){
			//$status = file_get_contents("http://127.0.0.1:9090/sendsms?phone=$nomorhp&text=".urlencode($sms));
			
			echo "$nama $nomorhp [".$row['blok_rumah']."-".$row['nomor_rumah']."] : ".sendSMS($nomorhp,$sms);
		}
		$n++;
	}
	if($n>0){
		$hal++;
		?></div><br><div class="alert alert-info" role="alert" id="waktu"><?=$config->SMS_Jeda_Pengiriman?> detik<script>hitungWaktu();</script><?
	}else if($hal>0){
		$hal =0;
		?></div><br><div class="alert alert-success" role="alert" id="waktu">sms sukses dikirim</script><?
	}
}
?>
</div>
<hr class="garistipis">
<form id="formulirSMS" action="./?apa=<?=$apa?>&send=true&hal=<?=$hal?>" method="post" enctype="application/x-www-form-urlencoded">
<div class="row">
	<?  if($config->Gunakan_Blok=='true'){ ?>
    <div class="col-sm-4">
    <a class="btn btn-xs btn-block btn-primary" data-toggle="collapse" data-parent="#accordion" href="#collapseBlok" aria-expanded="true" aria-controls="collapseBlok">
    	<span class="glyphicon glyphicon-th"></span> Pilih Blok
    </a>
    <div id="collapseBlok" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingBlok">
    	<select name="blok[]" multiple size="4" class="form-control">
        <? $hasil = $db->query("select distinct blok_rumah from t_warga"); 
			while($row = $hasil->fetchArray()){
				?><option value="<?=$row['blok_rumah']?>" <? if(empty($_POST['blok']))$_POST['blok']=array(); if(in_array($row['blok_rumah'],$_POST['blok'])) echo 'selected';?>><?=$config->Blok.' '.$row['blok_rumah']?></option><?
			} ?>
        </select>
        <span class="help-block">Jika tidak ada yang dipilih, maka diikutkan semua</span>
    </div>
	</div>
<? } if($config->Gunakan_Label=='true'){ ?>
    <div class="col-sm-4">
    <a class="btn btn-xs btn-block btn-primary" data-toggle="collapse" data-parent="#accordion" href="#collapseInclude" aria-expanded="true" aria-controls="collapseInclude">
    	<span class="glyphicon glyphicon-ok"></span> Ikutkan - Include
    </a>
    <div id="collapseInclude" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingInclude">
    	<select name="include[]" multiple size="4" class="form-control">
        <? $tags = getTags();
		foreach ($tags as $tag){ ?>
        	<option value="<?=$tag?>" <? if(empty($_POST['include']))$_POST['include']=array(); if(in_array($tag,$_POST['include'])) echo 'selected';?>><?=$tag?></option>
		<? } ?>
        </select>
        <span class="help-block">Jika tidak ada yang dipilih, maka diikutkan semua</span>
    </div>
    </div>
    <div class="col-sm-4">
    <a class="btn btn-xs btn-block btn-danger" data-toggle="collapse" data-parent="#accordion" href="#collapseExclude" aria-expanded="true" aria-controls="collapseExclude">
    	<span class="glyphicon glyphicon-remove"></span> Kecualikan - Exclude
    </a>
    <div id="collapseExclude" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingExclude">
        <select name="exclude[]" multiple size="4" class="form-control">
        <? foreach ($tags as $tag){ ?>
        	<option value="<?=$tag?>" <? if(empty($_POST['exclude']))$_POST['exclude']=array(); if(in_array($tag,$_POST['exclude'])) echo 'selected';?>><?=$tag?></option>
		<? } ?>
        </select>
        <span class="help-block">Pilih yang tidak akan dikirimi sms</span>
    </div>
    </div>
<? } ?>
</div>
<hr>
<label><input type="radio" <? if($_POST['siapa']=='bapak') echo 'checked'; ?> name="siapa" value="bapak">Bapak2</label> | 
<label><input type="radio" <? if($_POST['siapa']=='ibu') echo 'checked'; ?> name="siapa" value="ibu">Ibu2</label> | 
<label><input type="radio" <? if(!isset($_POST['siapa'])) echo 'checked'; else if($_POST['siapa']=='bebas') echo 'checked'; ?> name="siapa" value="bebas">yang ada</label>
<textarea onKeyUp="cekKarakter(this.value);" name="sms" cols="" rows="4" style="width:100%"><? if(empty($_POST['sms'])) echo "Yth. {nama} "; else echo $_POST['sms']; ?></textarea>
<label><input id="testing" type="checkbox" checked name="test" value="yes"> Testing</label><br>

<span id="jumlah"><? if(empty($_POST['sms'])) echo "12"; else echo strlen($_POST['sms']);?></span> karakter<br>
<input name="" type="submit" value="Send SMS">
</form>
<script>
function cekKarakter(field){
	document.getElementById('jumlah').innerHTML = field.length;
}
</script>