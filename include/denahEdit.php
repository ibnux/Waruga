<?php
ikutkan("head.php");
ikutkan("menu.php");
?><legend>Edit Denah</legend>
<div style="width:100%; overflow:scroll">
<table border="1" cellspacing="2" cellpadding="0">
<?php
if($_GET['do']=='geser'){
		if($_POST['fromX']+$_POST['geserX']<0)
				showAlert("Hasil geser tidak boleh kurang dari nol","warning");
		else
			if($_POST['fromY']+$_POST['geserY']<0)
				showAlert("Hasil geser tidak boleh kurang dari nol","warning");
			else
				$db->exec("update t_warga SET X=(X+".$_POST['geserX']."), Y=(Y+".$_POST['geserY']."),last_update=".time()." where X>=".$_POST['fromX']." and Y>=".$_POST['fromY']);
}

if($_GET['do']=='add'){
	if(!empty($_POST['blok']) && !empty($_POST['nomor'])){
		$db->exec("insert into t_warga(X,Y,blok_rumah,nomor_rumah,nama_suami,tgl_keluar) values(".$_POST['X'].",".$_POST['Y'].",'".$_POST['blok']."','".$_POST['nomor']."','belum',0)");
	}else
		showAlert("Mohon isi blok dan nomor rumah","warning");
}

$maxX = $db->querySingle('SELECT max(`X`) as mx FROM t_warga', false);
$maxY = $db->querySingle('SELECT max(`Y`) as mx FROM t_warga', false);

$hasil = $db->query("SELECT `X`, `Y`, `blok_rumah`, `nomor_rumah`,nama_suami FROM t_warga");
$data = array();
while($row = $hasil->fetchArray()){
    $data[$row['X']][$row['Y']] = array("Blok"=>$row["blok_rumah"],"Nomor"=>$row["nomor_rumah"],"Suami"=>$row["nama_suami"]);
}
//print_r($data);
for($y =0; $y < ($maxY+2);$y++){
    echo "<tr>";
    for($x=0;$x<($maxX+2);$x++){
        if(!empty($data[$x][$y])){
            if($data[$x][$y]["Suami"]!='belum'){
                $warna = '#99FFCC';
            }else{
                $warna = '#CC6666';
            }
            
            ?>
            <td bgcolor="<?=$warna?>" align="center" class="home" id="<?= $data[$x][$y]["Blok"] ?><?= $data[$x][$y]["Nomor"] ?>">
                <a href="javascript:addHome(<?="$x,$y"?>)"><b><? if($config->Gunakan_Blok=='true') echo $data[$x][$y]["Blok"]." - "; ?><?= $data[$x][$y]["Nomor"] ?></b></a>
                <div style="height:5px !important;"><img src="./?file&apa=kosong.png" width="90" height="1"></div>
                <?="$x,$y"?>
            </td>
            <? 
        }else{ ?>
            <td align="center" class="home" valign="middle"><a href="javascript:addHome(<?="$x,$y"?>)" class="btn"><?="$x,$y"?></a></td>
<? 		}
    } 
    echo "</tr>";
}
?>
</table>
<br><br>
</div>
<div class="row">
	<div class="col-md-4">
        <form class="form" method="post" action="?apa=<?=$apa?>&do=add">
        <legend>Tambah</legend>
        <div class="row">
        	<div class="col-xs-6">
            <div class="form-group">
            <div class="input-group">
            <div class="input-group-addon">X</div>
            <input class="form-control" type="number" id="fromX" name="X" placeholder="Posisi" value="0" readonly>
            </div>
            </div>
            </div>
            <div class="col-xs-6">
            <div class="form-group">
            <div class="input-group">
            <div class="input-group-addon">Y</div>
            <input class="form-control" type="number" id="fromY" name="Y" placeholder="Posisi" value="0" readonly>
            </div>
            </div>
            </div>
        </div>
        <? if($config->Gunakan_Blok=='true'){ ?>
        <div class="row">
        	<div class="col-xs-6">
            <div class="form-group">
            <div class="input-group">
            <div class="input-group-addon">Blok</div>
            <input class="form-control" required type="text" id="blok" name="blok" placeholder="rumah">
            </div>
            </div>
            </div>
            <div class="col-xs-6">
            <div class="form-group">
            <div class="input-group">
            <div class="input-group-addon">No</div>
            <input class="form-control" required type="text" id="nomor" name="nomor" placeholder="rumah">
            </div>
            </div>
            </div>
        </div>
        <? }else{ ?>
        <div class="row">
            <div class="col-xs-12">
            <div class="form-group">
            <div class="input-group">
            <div class="input-group-addon">No</div>
            <input class="form-control" required type="text" id="nomor" name="nomor" placeholder="rumah">
            </div>
            </div>
            </div>
        </div>
        <? } ?>
        <button type="submit" class="btn btn-primary btn-block">Tambah KK</button>
        </form>
	</div>
	<div class="col-md-4">
        <form class="form" method="post" action="?apa=<?=$apa?>&do=geser">
        <legend>Geser banyak posisi</legend>
        <label>Dari</label>
        <div class="row">
        	<div class="col-xs-6">
            <div class="form-group">
            <div class="input-group">
            <div class="input-group-addon">X</div>
            <input class="form-control" type="number" readonly id="fromXX" name="fromX" placeholder="Posisi" value="0">
            </div>
            </div>
            </div>
            <div class="col-xs-6">
            <div class="form-group">
            <div class="input-group">
            <div class="input-group-addon">Y</div>
            <input class="form-control" type="number" readonly id="fromYY" name="fromY" placeholder="Posisi" value="0">
            </div>
            </div>
            </div>
        </div>
        <label>berapa langkah:</label>
        <div class="row">
        	<div class="col-xs-6">
            <div class="form-group">
            <div class="input-group">
            <div class="input-group-addon">X</div>
            <input class="form-control" type="number" id="geserX" name="geserX" value="0">
            </div>
            </div>
            </div>
            <div class="col-xs-6">
            <div class="form-group">
            <div class="input-group">
            <div class="input-group-addon">Y</div>
            <input class="form-control" type="number" id="geserX" name="geserY" value="0">
            </div>
            </div>
            </div>
        </div>
        <span class="help-block">Gunakan angka minus &quot;-&quot; untuk mundur atau naik (cth: -6)</span>
        <button type="submit" class="btn btn-primary btn-block">Geser</button>
        </form>
    </div>
    <div class="col-md-4">
    	
    </div>
<script>
function addHome(x,y){
	//document.getElementById('ajaxian').src = 'ajax.php?x='+x+'&y='+y;
	document.getElementById('fromX').value = x;
	document.getElementById('fromY').value = y;
	document.getElementById('fromXX').value = x;
	document.getElementById('fromYY').value = y;
	document.getElementById('blok').focus();
}
</script>
</div>
<br>
<a href="?apa=denah" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Kembali</a>
