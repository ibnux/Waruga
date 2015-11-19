<?
ikutkan("head.php");
ikutkan("menu.php");



?>
<script type="text/javascript" src='http://maps.google.com/maps/api/js?libraries=places&key=AIzaSyCH_lysyTH--bTSwrtEwzB1L0rPECZnes4'></script>
<script src="./?file&apa=js/locationpicker.jquery.js"></script>
<legend>Absensi Ronda</legend>
<div class="row">
	<div class="col-md-4 col-md-offset-4">
    	<div class="alert alert-success" role="alert">Unduh aplikasi <a href="http://ibnux.github.io/Waruga/"><strong>Waruga Patrol</strong></a> untuk tukang ronda.</div>
    </div>
</div>

<div class="row">
	<div class="col-md-4">
    	<?
		if(!empty($_POST['nama']) && !empty($_POST['koordinat'])){
			$text = Security::encrypt("waruga<::>".$_POST['nama']."<::>".$_POST['koordinat'],"57238004e784498bbc2f8bf984565090");	
		?>
        <a href="?apa=QRCodeUnduh&nama=<?=$_POST['nama']?>&kode=<?=urlencode($text)?>">
        <img class="img-thumbnail img-responsive" src="https://zxing.org/w/chart?cht=qr&chs=350x350&chld=L&choe=UTF-8&chl=<?=urlencode($text)?>">
    	</a>
        <br>
		Klik gambar untuk unduh: <? echo $_POST['nama']; } ?>
    </div>
	<div class="col-md-8">
    	<form class="form" id="formlokasi" enctype="application/x-www-form-urlencoded" method="post" action="?apa=<?=$apa?>">
        <div class="form-group">
            <label>Lokasi</label>
            <input type="text" class="form-control" name="nama" value="<?=$_POST['nama']?>" placeholder="nama Lokasi">
        </div>
        <div class="form-group">
            <label>Koordinat</label>
            <div class="row">
				<div class="col-xs-9 col-md-8">
            		<input type="text" id="koordinat" name="koordinat" value="<?=$_POST['koordinat']?>" class="form-control"  placeholder="Koordinat Lokasi">
            	</div>
                <div class="col-xs-3 col-md-4">
                	<a href="javascript:getLocation()" class="btn btn-block btn-info">
                    <span class="glyphicon glyphicon-screenshot" aria-hidden="true"></span>
                    <span class="hidden-xs">cek lokasi saya</span></a>
                </div>
            </div>
            <input type="hidden" id="lat" name="lat" value="<?=(!empty($_POST['lat']))? $_POST['lat']:"-6.1729543";?>">
            <input type="hidden" id="lon" name="lon" value="<?=(!empty($_POST['lon']))? $_POST['lon']:"106.8257117";?>">

            <p class="help-block" id="help-block">Klik cek lokasi sampai lokasi akurat</p>
        </div>
        <input class="form-control" type="search" placeholder="Cari Alamat" value="<?=$_POST['alamat'];?>" id="alamat" name="alamat" value="">
        <div class="embed-responsive embed-responsive-4by3" id="map-canvas"></div>
        <div class="form-group">
            <button type="submit" class="btn btn-block btn-primary">Buat QRCode</button>
        </div>
        </form>
    </div>
</div>
<script>
var x = document.getElementById("help-block");

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
}

function showPosition(position) {
    var latlon = position.coords.latitude + "," + position.coords.longitude;
	document.getElementById('koordinat').value = latlon;
	document.getElementById('lat').value = position.coords.latitude+"";
	document.getElementById('lon').value = position.coords.longitude+"";
	document.getElementById('formlokasi').submit();
	
}


$('#map-canvas').locationpicker({
	location: {latitude: <?=(!empty($_POST['lat']))? $_POST['lat']:"-6.1729543";?>, longitude: <?=(!empty($_POST['lon']))? $_POST['lon']:"106.8257117";?>},
	radius: 1,zoom: 15,
	inputBinding: {
        latitudeInput: $('#lat'),
        longitudeInput: $('#lon'),
		locationNameInput: $('#alamat'),
    },
	onchanged: function (currentLocation, radius, isMarkerDropped) {
		document.getElementById('koordinat').value = currentLocation.latitude+ "," + currentLocation.longitude;
	},
	enableAutocomplete: true
	});
$(document).ready(function () { 
    /*var newHeight = $(window).height() - $('#formulir').height();
    var oldHeight = $('#map-canvas').height();

    if (newHeight > oldHeight) {
      $('#map-canvas').height(newHeight);
    }*/
  });

function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            x.innerHTML = "User denied the request for Geolocation."
            break;
        case error.POSITION_UNAVAILABLE:
            x.innerHTML = "Location information is unavailable."
            break;
        case error.TIMEOUT:
            x.innerHTML = "The request to get user location timed out."
            break;
        case error.UNKNOWN_ERROR:
            x.innerHTML = "An unknown error occurred."
            break;
    }
}
</script>
<p class="help-block">*membutuhkan koneksi internet.</p>