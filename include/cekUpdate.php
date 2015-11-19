<?
ikutkan("head.php");
ikutkan("menu.php");
?><legend>Check Update</legend>
<?
$versi = file_get_contents("versi.txt");
$ch = curl_init();
$timeout = 30;
curl_setopt($ch, CURLOPT_URL, "http://ibnux.github.io/Waruga/unduh/versi.txt");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$versiServer = curl_exec($ch);
curl_close($ch);

?><span class="label label-primary">lokal: <?=$versi?></span> : 
<span class="label label-success">server: <?=$versiServer?></span><br><br>
<?
if($versi!=$versiServer){
	?><div class="alert alert-danger" role="alert"><strong>Versi baru telah ada!!</strong> 
    <a href="./?upgrade" class="alert-link">Upgrade?</a></div><br>
 Riwayat Perubahan:
<iframe src="http://ibnux.github.io/Waruga/unduh/perubahan.txt?<?=$versiServer?>" frameborder="0" width="100%" height="300px" scrolling="auto" ></iframe><?
}else{
	?><div class="alert alert-success" role="alert"><strong>Belum ada versi Baru</strong></div><?
}

