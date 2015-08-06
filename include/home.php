<?
ikutkan("head.php");
ikutkan("menu.php"); 
if(isset($_GET['add'])){
	if(!empty($_POST['nomor'])){
		$db->exec("insert into t_warga(X,Y,blok_rumah,nomor_rumah,nama_suami,telepon_suami,nama_istri,telepon_istri,tgl_masuk)
		Values(0,0,'".$_POST['blok']."','".$_POST['nomor']."','".$db->escapeString($_POST['suami'])."','".$_POST['hp1']."'
		,'".$db->escapeString($_POST['istri'])."','".$_POST['hp2']."','".time()."');");
	}else{
		showAlert("Mohon isi nomor rumah","danger");
	}
}

?>
<br>
<div class="row">
    <form id="blokSubmit" role="form" class="form"  action="./" method="get" enctype="application/x-www-form-urlencoded">
    <?  if($config->Gunakan_Blok=='true'){ ?>
    <div class="col-sm-4">
        <select class="form-control input-sm" id="blok" name="blok" onChange="document.getElementById('blokSubmit').submit()">
        <option value="" <? if(empty($_GET['blok'])) echo 'selected';?>>--pilih blok--</option>
        <option value="all" <? if($_GET['blok']=='all') echo 'selected';?>>Semua Blok</option>
        <? $hasil = $db->query("select distinct blok_rumah from t_warga"); 
        while($row = $hasil->fetchArray()){
            ?><option value="<?=$row['blok_rumah']?>" <? if($_GET['blok']==$row['blok_rumah']) echo 'selected';?>>Tampilkan <?=$config->Blok.' '.$row['blok_rumah']?></option><?
        } ?>
        </select>
    <hr class="garistipis visible-xs-inline">
    </div>
    <? } if($config->Gunakan_Label=='true'){ ?>
    <div class="col-sm-4">
        <select class="form-control input-sm" id="tag" name="tag" onChange="document.getElementById('blokSubmit').submit()" placeholder="Label">
        <option value="" <? if(empty($_GET['tag'])) echo 'selected';?>>--pilih label--</option>
        <option value="all" <? if($_GET['tag']=='all') echo 'selected';?>>Semua label</option>
        <? $tags = getTags();
			foreach ($tags as $tag){
            ?><option value="<?=$tag?>" <? if($_GET['tag']==$tag) echo 'selected';?>><?=$tag?></option><?
        } ?>
        </select>
    <hr class="garistipis visible-xs-inline">
    </div>
    <? } ?>
    </form>
    <div class="col-sm-4">
        <div class="form-group">
        	<button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#AddWargaModal"><span class="glyphicon glyphicon-plus"></span> Tambah warga</button>            
        	
        </div>
    </div>
</div>
<?
if(!empty($_GET['blok']) && $_GET['blok']!='all')
	$where = " `blok_rumah`='".$_GET['blok']."' and ";
if(!empty($_GET['tag']) && $_GET['tag']!='all')
	$where .= " labelExist(label_warga,'".$_GET['tag']."') and ";
if($_GET['blok']=='all' || $_GET['tag']=='all')
	$where .= " 1=1 and ";

if(strlen($where)==0 && $config->Gunakan_Blok=='true'){
	//tampilkan Statistik jika tidak ada blok/tag dipilih
	?><legend>Dashboard</legend>
    <div class="row">
        <div class="col-md-4">
        <table class="table table-condensed table-bordered table-striped">
            <thead>
              <th colspan="2"><strong>Saldo Kas</strong></th>
            </thead>
          <tbody>
            <?
            $hasil = $db->query("select id_kategori,nama_kategori,saldo_kategori from t_kategori where tipe_kategori='KAS' and saldo_kategori>0 order by id_kategori asc");
            $totalSaldo = 0;
            $saldo = "";
            while($row = $hasil->fetchArray()){
                $totalSaldo += $row['saldo_kategori'];
                echo '<tr><td>'.$row['nama_kategori'].'</td><td>Rp. '.number_format($row['saldo_kategori']).'</td></tr>';
            } 
            echo '<tr class="success"><td><b>Total Saldo</b></td><td>Rp. '.number_format($totalSaldo).'</td></tr>';
            ?>
          </tbody>
        </table>
        </div>
	<? if($config->Gunakan_Blok=='true'){ ?>
        <div class="col-md-4">
        <table class="table table-condensed table-bordered table-striped">
            <thead>
              <th colspan="2"><strong>Jumlah KK Terisi</strong></th>
            </thead>
            <thead>
              <th><strong>Blok</strong></th>
              <th><strong>jumlah</strong></th>
            </thead>
          <tbody>
            <?
            $hasil = $db->query("select blok_rumah,count(id_warga) as jml from t_warga 
            where nama_suami<>'belum' and tgl_keluar=0 
            group by blok_rumah order by `blok_rumah`, CAST( nomor_rumah AS INTEGER) ASC");
            $total = 0;
            while($row = $hasil->fetchArray()){
                $total += $row['jml'];
                echo '<tr><td>'.$row['blok_rumah'].'</td><td>'.($row['jml']).'</td></tr>';
            } 
            echo '<tr class="success"><td><b>Total KK</b></td><td>'.($total).'</td></tr>';
            ?>
          </tbody>
        </table>
        </div>
        <div class="col-md-4">
        <table class="table table-condensed table-bordered table-striped">
            <thead>
              <th colspan="2"><strong>Jumlah Rumah</strong></th>
            </thead>
            <thead>
              <th><strong>Blok</strong></th>
              <th><strong>jumlah</strong></th>
            </thead>
          <tbody>
            <?
            $hasil = $db->query("select blok_rumah,count(id_warga) as jml from t_warga 
            WHERE tgl_keluar=0 
            group by blok_rumah order by `blok_rumah`, CAST( nomor_rumah AS INTEGER) ASC");
            $total = 0;
            while($row = $hasil->fetchArray()){
                $total += $row['jml'];
                echo '<tr><td>'.$row['blok_rumah'].'</td><td>'.($row['jml']).'</td></tr>';
            } 
            echo '<tr class="success"><td><b>Total Rumah</b></td><td>'.($total).'</td></tr>';
            ?>
          </tbody>
        </table>
        </div>
    <?  }else{
        ?><div class="col-md-4"><table class="table table-condensed table-bordered table-striped">
          <tbody>
            <?
            $hasil = $db->query("select count(id_warga) as jml from t_warga 
            WHERE tgl_keluar=0");
            while($row = $hasil->fetchArray()){
                echo '<tr><td>Jumlah Rumah</td><td>'.($row['jml']).'</td></tr>';
            } 
    
            $hasil = $db->query("select count(id_warga) as jml from t_warga 
            WHERE tgl_keluar=0 and nama_suami<>'belum'");
            while($row = $hasil->fetchArray()){
                echo '<tr><td>Jumlah KK </td><td>'.($row['jml']).'</td></tr>';
            } 
            ?>
          </tbody>
        </table></div><?
        }
    ?></div><?

}else{
	//tampilkan data sesuai blok/tag dipilih
$hasil = $db->query("SELECT `id_warga`, `blok_rumah`, `nomor_rumah`, `nama_suami`, `nama_istri`, 
					`telepon_suami`,telepon_istri FROM t_warga WHERE $where tgl_keluar=0 
					ORDER BY `blok_rumah`, CAST( nomor_rumah AS INTEGER) ASC");
?>
<style>
.belum{ display: none;}
</style>
<div class="row">
	<div class="col-sm-6">
    <div class="checkbox">
      <label>
        <input id="belum" onChange="sembunyikan()" type="checkbox">
        Tampilkan yang belum didata.
      </label>
    </div>  
    </div>
    <div class="col-sm-6" align="right">
    <div class="input-group">
            <div class="input-group-addon">Cari</div>
            <input class="form-control input-sm" type="text" onKeyDown="cariNama(this)" placeholder="ketikkan nama">
        </div>
    </div>
</div>
<div id="overflow">
<table id="tabelData" class="table table-bordered table-hover table-condensed" border="1" cellspacing="0" cellpadding="1">
	<thead>
    	<th><center><span class="glyphicon glyphicon-tree-deciduous"></span></center></th>
    	<th>No. Rumah</th>
        <th>Nama</th>
        <th>Telepon</th>
    </thead>
<?

$n =0;
$total = 0;
while($row = $hasil->fetchArray()){
	$total++;
	$blok = '';
	if($config->Gunakan_Blok=='true')
		$blok = $row['blok_rumah'].' - ';
	
	
	if($row['nama_suami']=='belum'){
		?>
		<tr class="belum">
			<td></td>
			<td><a href="./?apa=dataView&blok=<?= str_replace(' - ','',$blok) ?>&nomor=<?= $row['nomor_rumah']?>"><?= $blok.$row['nomor_rumah']?></a></td>
			<td><a href="./?apa=dataEdit&id=<?= $row['id_warga']?>"><?= $row['nama_suami']?></a></td>
			<td>&nbsp;</td>
		</tr>	
		<?
	}else{
		$n++;
		?>
        <tr class="namaOrang" cari="<?= $row['nama_suami'].' '.$row['nama_istri']?>">
            <td align="center"><?=$n?></td>
            <td><a href="./?apa=dataView&blok=<?= str_replace(' - ','',$blok) ?>&nomor=<?= $row['nomor_rumah']?>"><?= $blok.$row['nomor_rumah']?></a></td>
            <td><a href="./?apa=dataView&id=<?= $row['id_warga']?>"><?= $row['nama_suami']?><br><i><?= $row['nama_istri']?></i></a></td>
            <td><a href="javascript:caltel('<?=$row['telepon_suami']?>')"><?=$row['telepon_suami']?></a><br>
            	<i><a href="javascript:caltel('<?=$row['telepon_istri']?>')"><?=$row['telepon_istri']?></a></i></td>
        </tr>
        <?
	}
}
?>
	<tr>
        <td></td>
        <td colspan="3">Terdata <?=$n?> dari <?=$total?> KK</td>
    </tr>
</table>
</div>
<script>
function cariNama(fild){
	var nama = fild.value.toLowerCase();
	//document.getElementById('map-canvas')
	var namaitem = document.getElementsByClassName('namaOrang');
	if(nama.length<1){
		for(var n = 0;n<namaitem.length;n++){
			namaitem.item(n).style.display = 'table-row';
		}
	}else if(nama.length==1){
		document.getElementById('belum').checked = false;
		sembunyikan();
	}else if(nama.length>2){
		var namaitem = document.getElementsByClassName('namaOrang');
		for(var n = 0;n<namaitem.length;n++){
			var ygdicari = namaitem.item(n).getAttribute('cari').toLowerCase();
			console.log(nama+' : '+ygdicari+' : '+ygdicari.indexOf(nama));
			if(ygdicari.indexOf(nama) > -1){
				namaitem.item(n).style.display = 'table-row';
			}else{
				namaitem.item(n).style.display = 'none';
			}
		}
	}
}
function sembunyikan(){
	var cek = document.getElementById('belum').checked;
	if(cek){
		var useritem = document.getElementsByClassName('belum');
		for(var n = 0;n< useritem.length;n++){
			useritem.item(n).style.display = 'table-row';
		}
	}else{
		var useritem = document.getElementsByClassName('belum');
		for(var n = 0;n< useritem.length;n++){
			useritem.item(n).style.display = 'none';
		}
	}
}
</script>
<!-- Modal -->
<div class="modal fade" id="AddWargaModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambah Warga</h4>
      </div>
      <form class="form" action="./?add" method="post" enctype="application/x-www-form-urlencoded">
      <div class="modal-body">
		  <? if($config->Gunakan_Blok=='true'){ ?>
            <div class="row">
                <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                <div class="input-group-addon">Blok</div>
                <input class="form-control" required type="text" id="blok" name="blok" <?
				if($_GET['blok']<>'all' && !empty($_GET['blok']))
					echo 'value="'.$_GET['blok'].'"';
				?> placeholder="rumah">
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
          <div class="form-group">
            <label for="exampleInputEmail1">Nama Bapak</label>
            <input type="text" class="form-control" name="suami">
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Nomor HP:</label>
            <input type="text" class="form-control" name="hp1">
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Nama Ibuc</label>
            <input type="text" class="form-control" name="istri">
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Nomor HP</label>
            <input type="text" class="form-control" name="hp2">
          </div>
      
      </div>
      <div class="modal-footer">
        <a class="btn btn-default" data-dismiss="modal">Close</a>
        <button type="submit" class="btn btn-primary">Tambahkan</button>
      </div>
      </form>
    </div>
  </div>
</div>
<? } ?>
