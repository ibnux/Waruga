<?
$ID = $_REQUEST['id']*1;
$folderKeluarga = $KELUARGA."$ID/";
if(!file_exists($folderKeluarga))
	mkdir($folderKeluarga,0777,true);
	
if(isset($_GET['rotate']) && $_GET['rotate']*1>0){
	$path = $folderKeluarga.$_GET['path'];
	if(file_exists($path)){
		$source = imagecreatefromjpeg($path);
		$source = imagerotate($source, $_GET['rotate'], 0);
		//unlink($path);
		imagejpeg($source,$path,100);
		imagedestroy($source);
		if(file_exists($path.".thumb.kotak"))
			unlink($path.".thumb.kotak");
		if(file_exists($path.".thumb.kotakasli"))
			unlink($path.".thumb.kotakasli");
	}
}
if(isset($_GET['savephoto']) && !empty($_POST['kamera'])){
	if($_FILES['error']==1){
		?><script>alert('Ukuran file terlalu besar');</script>
		<meta http-equiv="refresh" content="2; <?= "./?apa=$apa&id=$ID" ?>"<?
		die();	
	}
	
	$path = $folderKeluarga.$_POST['untuk'].".wrg";
	if(file_exists($path.".thumb.kotak"))
		unlink($path.".thumb.kotak");
	if(file_exists($path.".thumb.kotakasli"))
		unlink($path.".thumb.kotakasli");
	if($_POST['picsource']=='file'){
		if(file_exists($_FILES['fotoUpload']['tmp_name']))
			try{
				move_uploaded_file($_FILES['fotoUpload']['tmp_name'],$path);
			}catch(Exception $e){}
		else
			if(file_exists($path))
				unlink($path);
	}else{
		try{
			$foto = base64_decode(str_replace(' ', '+',str_replace("data:image/jpeg;base64,","",$_POST['kamera'])));
			unlink($path);
			file_put_contents($path,$foto);
		}catch(Exception $e){
			if(file_exists($path))
				unlink($path);
		}
	}
	if(file_exists($path) && strpos($_POST['untuk'],'foto')===false){
		list($width, $height) = getimagesize($path);
		if ($height>$width) {
			$source = imagecreatefromjpeg($path);
			$rotate = imagerotate($source, 90*3, 0);
			unlink($path);
			imagejpeg($rotate,$path,100);
			imagedestroy($source);
			imagedestroy($rotate);
		}
	}
	header("location: ./?apa=$apa&id=$ID");
	die();
}

ikutkan("head.php");
ikutkan("menu.php");

$keluarga = $db->querySingle("SELECT `nama_suami`, `nama_istri`FROM t_warga WHERE `id_warga`=$ID",true);
if(isset($_GET['addPhoto'])){
	?><br>
    <div class="row">
    	<div class="col-md-6 col-md-offset-3" align="center">
        	<form class="form" action="./?apa=<?=$apa?>&id=<?=$ID?>&savephoto" method="post" enctype="multipart/form-data" onSubmit="return siapkanGambar()">
            <div class="panel panel-default">
              <div class="panel-heading">Memfoto KTP atau KK</div>
              	<div class="panel-body">
                	<select name="picsource" class="form-control" id='videoSource' onChange="startCamera()">
                    	<option value="file">Upload File</option>
                    </select>
                    <div class="embed-responsive embed-responsive-4by3" id="fieldKamera" style="display:none">
                        <video id="video" class="embed-responsive-item" width="768" height="1024" autoplay></video>
                    </div>
                    <div id="fotoUpload"><br>
                    <label>Pilih gambar:</label>
                    <input type="file" class="form-control" name="fotoUpload"><br>
                    </div>
                    <input type="hidden" id="foto" name="kamera">
            		<canvas id="canvas" width="768" height="1024" style="display:none"></canvas>
                    <select name="untuk" class="form-control">
                    	<optgroup label="Keluarga Utama">
                    	<option value="KK">Kartu Keluarga</option>
                        <? if(!empty($keluarga['nama_suami'])){ ?>
                        <option value="ktp.suami">KTP <?=$keluarga['nama_suami']?></option>
                        <option value="foto.suami">Foto <?=$keluarga['nama_suami']?></option>
                        <? } ?>
                        <? if(!empty($keluarga['nama_istri'])){ ?>
                        <option value="ktp.istri">KTP <?=$keluarga['nama_istri']?></option>
                        <option value="foto.istri">Foto <?=$keluarga['nama_istri']?></option>
                        <? } ?></optgroup><?
						$hazil = $db->query("select id_warga_tambahan,nama_lengkap from t_warga_tambahan 
									where id_warga=$ID order by tanggal_keluar asc");
            			while($relasi = $hazil->fetchArray()){
						?><optgroup label="<?=$relasi['nama_lengkap']?>"><option value="<?=$relasi['id_warga_tambahan']?>.ktp">KTP <?=$relasi['nama_lengkap']?></option>
						<option value="<?=$relasi['id_warga_tambahan']?>.foto">Foto <?=$relasi['nama_lengkap']?></option></optgroup><?
						} ?>
                    </select>
            	</div>
                <div class="panel-footer">
                	<button type="submit" class="btn btn-block btn-primary"><span class="glyphicon glyphicon-camera"></span> simpan <span class="glyphicon glyphicon-screenshot"></span></button>
                </div>
              </div>
           </div>
           </form>
        </div>
    </div>
    <div class="row">
    	<div class="col-md-6 col-md-offset-3" align="center">
		<div id="gunakanBrowser" class="alert alert-info" role="alert">Jika tidak bisa upload maka gunakan Browser atau akses dari smartphone anda agar bisa menggunakan fitur kamera ke URL <strong><?= selfURL();?></strong></div>
    	<a href="?apa=dataPhoto&id=<?=$ID?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Batal</a>
    	</div>
    </div>
	<script type="text/javascript">
	var videoSelect = document.querySelector("select#videoSource");

    function hasGetUserMedia() {
		return !!(navigator.getUserMedia || navigator.webkitGetUserMedia ||
			navigator.mozGetUserMedia || navigator.msGetUserMedia);
	}
	function gotSources(sourceInfos) {
	  for (var i = 0; i != sourceInfos.length; ++i) {
		var sourceInfo = sourceInfos[i];
		var option = document.createElement("option");
		option.value = sourceInfo.id;
		if (sourceInfo.kind === 'video') {
		  option.text = sourceInfo.label || 'camera ' + (videoSelect.length + 1);
		  videoSelect.appendChild(option);
		}
	  }
	  startCamera();
	}
	
	if (hasGetUserMedia()) {
		if (typeof MediaStreamTrack === 'undefined'){
		  alert('This browser does not support MediaStreamTrack.\n\nTry Chrome Canary.');
		} else {
		  MediaStreamTrack.getSources(gotSources);
		}
	} else {
	  //alert('Browser tidak mendukung kamera');
	}
	
	function startCamera(){
		if(videoSelect.value == 'file'){
			var video = document.getElementById("video");
			if (!!window.stream) {
				video.src = null;
				window.stream.stop();
			}
			document.getElementById("fieldKamera").style.display = "none";
			document.getElementById("fotoUpload").style.display = "block";
			return;
		}
		// Grab elements, create settings, etc.
		var canvas = document.getElementById("canvas"),
			context = canvas.getContext("2d"),
			video = document.getElementById("video"),
			videoObj = { video: {optional: [{sourceId: videoSelect.value}]} },
			errBack = function(error) {
				console.log("Video capture error: ", error.code); 
			};
		document.getElementById("fieldKamera").style.display = "block";
		document.getElementById("fotoUpload").style.display = "none";
		if (!!window.stream) {
			video.src = null;
			window.stream.stop();
		}
	
		// Put video listeners into place
		if(navigator.getUserMedia) { // Standard
			navigator.getUserMedia(videoObj, function(stream) {
				window.stream = stream;
				video.src = stream;
				video.play();
			}, errBack);
		} else if(navigator.webkitGetUserMedia) { // WebKit-prefixed
			navigator.webkitGetUserMedia(videoObj, function(stream){
				window.stream = stream;
				video.src = window.webkitURL.createObjectURL(stream);
				video.play();
			}, errBack);
		}
		else if(navigator.mozGetUserMedia) { // Firefox-prefixed
			navigator.mozGetUserMedia(videoObj, function(stream){
				window.stream = stream;
				video.src = window.URL.createObjectURL(stream);
				video.play();
			}, errBack);
		}
	}
	
	function siapkanGambar(){
		document.getElementById("canvas").getContext("2d").drawImage(document.getElementById("video"), 0, 0, 768, 1024);
		document.getElementById('foto').value = canvas.toDataURL('image/jpeg');
		return true;
	}
    </script>
    <?
	
}else{
	
	?>
    <hr>
    <div class="row">
        <div class="col-xs-3">
        <a href="?apa=dataEdit&id=<?=$ID?>" class="btn btn-block btn-default"><span class="glyphicon glyphicon-chevron-left"></span> <span class="hidden-xs">Kembali</span><span class="visible-xs-inline"><span class="glyphicon glyphicon-user"></span></span></a>
        </div>
        <div class="col-xs-9">
        <a href="./?apa=<?=$apa?>&id=<?=$ID?>&addPhoto" class="btn btn-info btn-block"><span class="glyphicon glyphicon-camera"></span> Tambah Foto KTP/KK</a>
        </div>
    </div>
    <hr>
<div class="row">
<? if(!empty($keluarga['nama_suami']) && file_exists($folderKeluarga."foto.suami.wrg")){ ?>
  <div class="col-md-2 col-xs-6 col-sm-4">
    <div class="thumbnail">
      <img src="?apa=thumb&kotakasli&s=200&p=<?=$folderKeluarga?>foto.suami.wrg&<?=rand(000,999)?>" class="img-rounded" alt="Foto Suami">
        <center>
            <br>rotate: 
            <a href="?apa=<?=$apa?>&id=<?=$ID?>&rotate=90&path=<?=$relasi['id_warga_tambahan']?>foto.suami.wrg" class="btn btn-xs btn-primary">90</a>
            <a href="?apa=<?=$apa?>&id=<?=$ID?>&rotate=180&path=<?=$relasi['id_warga_tambahan']?>foto.suami.wrg" class="btn btn-xs btn-primary">180</a>
        </center>
      <div class="caption" align="center">
        <b><?=$keluarga['nama_suami']?></b>
      </div>
    </div>
  </div>
<? } if(!empty($keluarga['nama_istri']) && file_exists($folderKeluarga."foto.istri.wrg")){ ?>
  <div class="col-md-2 col-xs-6 col-sm-4">
    <div class="thumbnail">
      <img src="?apa=thumb&kotakasli&s=200&p=<?=$folderKeluarga?>foto.istri.wrg&<?=rand(000,999)?>" class="img-rounded" alt="Foto Istri">
        <center>
            <br>rotate: 
            <a href="?apa=<?=$apa?>&id=<?=$ID?>&rotate=90&path=<?=$relasi['id_warga_tambahan']?>foto.istri.wrg" class="btn btn-xs btn-primary">90</a>
            <a href="?apa=<?=$apa?>&id=<?=$ID?>&rotate=180&path=<?=$relasi['id_warga_tambahan']?>foto.istri.wrg" class="btn btn-xs btn-primary">180</a>
        </center>
      <div class="caption" align="center">
        <b><?=$keluarga['nama_istri']?></b>
      </div>
    </div>
  </div>  
<? } ?>
<? if(!empty($keluarga['nama_suami']) && file_exists($folderKeluarga."ktp.suami.wrg")){ ?>
  <div class="col-md-2 col-xs-6 col-sm-4">
    <div class="thumbnail">
      <img src="?apa=thumb&kotakasli&s=200&p=<?=$folderKeluarga?>ktp.suami.wrg&<?=rand(000,999)?>" class="img-rounded" alt="Gambar KTP Suami">
        <center>
            <br>rotate: 
            <a href="?apa=<?=$apa?>&id=<?=$ID?>&rotate=90&path=<?=$relasi['id_warga_tambahan']?>ktp.suami.wrg" class="btn btn-xs btn-primary">90</a>
            <a href="?apa=<?=$apa?>&id=<?=$ID?>&rotate=180&path=<?=$relasi['id_warga_tambahan']?>ktp.suami.wrg" class="btn btn-xs btn-primary">180</a>
        </center>
      <div class="caption" align="center">
        <b><?=$keluarga['nama_suami']?></b>
      </div>
    </div>
  </div>
<? } if(!empty($keluarga['nama_istri']) && file_exists($folderKeluarga."ktp.istri.wrg")){ ?>
  <div class="col-md-2 col-xs-6 col-sm-4">
    <div class="thumbnail">
      <img src="?apa=thumb&kotakasli&s=200&p=<?=$folderKeluarga?>ktp.istri.wrg&<?=rand(000,999)?>" class="img-rounded" alt="Gambar KTP Istri">
        <center>
            <br>rotate: 
            <a href="?apa=<?=$apa?>&id=<?=$ID?>&rotate=90&path=<?=$relasi['id_warga_tambahan']?>ktp.istri.wrg" class="btn btn-xs btn-primary">90</a>
            <a href="?apa=<?=$apa?>&id=<?=$ID?>&rotate=180&path=<?=$relasi['id_warga_tambahan']?>ktp.istri.wrg" class="btn btn-xs btn-primary">180</a>
        </center>
      <div class="caption" align="center">
        <b><?=$keluarga['nama_istri']?></b>
      </div>
    </div>
  </div>  
<? } 
	if(file_exists($folderKeluarga."KK.wrg")){
?>
  <div class="col-md-2 col-xs-6 col-sm-4">
    <div class="thumbnail">
      <img src="?apa=thumb&kotakasli&s=200&p=<?=$folderKeluarga?>KK.wrg&<?=rand(000,999)?>" class="img-rounded" alt="Gambar Kartu Keluarga">
        <center>
            <br>rotate: 
            <a href="?apa=<?=$apa?>&id=<?=$ID?>&rotate=90&path=<?=$relasi['id_warga_tambahan']?>KK.wrg" class="btn btn-xs btn-primary">90</a>
            <a href="?apa=<?=$apa?>&id=<?=$ID?>&rotate=180&path=<?=$relasi['id_warga_tambahan']?>KK.wrg" class="btn btn-xs btn-primary">180</a>
        </center>
      <div class="caption" align="center">
        <b>Kartu Keluarga</b>
      </div>
    </div>
  </div>
  <? } ?>
</div>
<hr>
<div class="row">
<?
$hazil = $db->query("select `id_warga_tambahan`, nama_lengkap from t_warga_tambahan 
					where id_warga=$ID order by tanggal_keluar asc");
$n = 1;
while($relasi = $hazil->fetchArray()){
	if(file_exists($folderKeluarga.$relasi['id_warga_tambahan'].".ktp.wrg") || file_exists($folderKeluarga.$relasi['id_warga_tambahan'].".foto.wrg")){
?>

	<? if(file_exists($folderKeluarga.$relasi['id_warga_tambahan'].".ktp.wrg")){ ?>
    <div class="col-md-2 col-xs-6 col-sm-4">
        <div class="thumbnail">
            <img src="?apa=thumb&kotakasli&s=200&p=<?=$folderKeluarga?><?=$relasi['id_warga_tambahan']?>.ktp.wrg&<?=rand(000,999)?>" class="img-rounded" alt="Gambar KTP">
            <center>
            	<br>rotate: 
                <a href="?apa=<?=$apa?>&id=<?=$ID?>&rotate=90&path=<?=$relasi['id_warga_tambahan']?>.ktp.wrg" class="btn btn-xs btn-primary">90</a>
                <a href="?apa=<?=$apa?>&id=<?=$ID?>&rotate=180&path=<?=$relasi['id_warga_tambahan']?>.ktp.wrg" class="btn btn-xs btn-primary">180</a>
            </center>
            <div class="caption" align="center">
            <b>KTP <?=$relasi['nama_lengkap']?></b>
            </div>
        </div>
    </div>
    <? } if(file_exists($folderKeluarga.$relasi['id_warga_tambahan'].".foto.wrg")){?>
    
    <div class="col-md-2 col-xs-6 col-sm-4">
        <div class="thumbnail">
            <img src="?apa=thumb&kotakasli&s=200&p=<?=$folderKeluarga?><?=$relasi['id_warga_tambahan']?>.foto.wrg&<?=rand(000,999)?>" class="img-rounded" alt="Gambar Foto Diri">
            <center>
            	<br>rotate: 
                <a href="?apa=<?=$apa?>&id=<?=$ID?>&rotate=90&path=<?=$relasi['id_warga_tambahan']?>.foto.wrg" class="btn btn-xs btn-primary">90</a>
                <a href="?apa=<?=$apa?>&id=<?=$ID?>&rotate=180&path=<?=$relasi['id_warga_tambahan']?>.foto.wrg" class="btn btn-xs btn-primary">180</a>
            </center>
            <div class="caption" align="center">
            <b><?=$relasi['nama_lengkap']?></b>
            </div>
        </div>
    </div>
    <? } 
	}
} #end while
?>
</div>

    <?
}
?>