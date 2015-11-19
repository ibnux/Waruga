<?php
ikutkan("head.php");
ikutkan("menu.php");

$bapak = $db->querySingle("SELECT count(nama_suami) FROM t_warga WHERE nama_suami<>'' and nama_suami<>'belum' and tgl_keluar<1");
$ibu = $db->querySingle("SELECT count(nama_istri) FROM t_warga WHERE nama_istri<>'' and nama_istri<>'belum' and tgl_keluar<1");
$laki = $db->querySingle("SELECT count(id_warga_tambahan) FROM t_warga_tambahan WHERE jenis_kelamin='L' and tanggal_keluar<1");
$perempuan = $db->querySingle("SELECT count(id_warga_tambahan) FROM t_warga_tambahan WHERE jenis_kelamin='P' and tanggal_keluar<1");

$t150 = strtotime('-150 years', time());
$t50 = strtotime('-50 years', time());
$t40 = strtotime('-40 years', time());
$t30 = strtotime('-30 years', time());
$t20 = strtotime('-20 years', time());
$t10 = strtotime('-10 years', time());
$t5 = strtotime('-5 years', time());

$lk = array();
$wn = array();
$lk[0] = $db->querySingle("SELECT count(nama_suami) FROM t_warga WHERE nama_suami<>'' and nama_suami<>'belum' and tgl_keluar<1 and tglahir_suami<>'' and tglahir_suami>$t150 and tglahir_suami<=$t50");
$lk[1] = $db->querySingle("SELECT count(nama_suami) FROM t_warga WHERE nama_suami<>'' and nama_suami<>'belum' and tgl_keluar<1 and tglahir_suami<>'' and tglahir_suami>$t50 and tglahir_suami<=$t40");         
$lk[2] = $db->querySingle("SELECT count(nama_suami) FROM t_warga WHERE nama_suami<>'' and nama_suami<>'belum' and tgl_keluar<1 and tglahir_suami<>'' and tglahir_suami>$t40 and tglahir_suami<=$t30");
$lk[3] = $db->querySingle("SELECT count(nama_suami) FROM t_warga WHERE nama_suami<>'' and nama_suami<>'belum' and tgl_keluar<1 and tglahir_suami<>'' and tglahir_suami>$t30 and tglahir_suami<=$t20");
$lk[4] = $db->querySingle("SELECT count(nama_suami) FROM t_warga WHERE nama_suami<>'' and nama_suami<>'belum' and tgl_keluar<1 and tglahir_suami<>'' and tglahir_suami>$t20 and tglahir_suami<=$t10");
$lk[5] = $db->querySingle("SELECT count(nama_suami) FROM t_warga WHERE nama_suami<>'' and nama_suami<>'belum' and tgl_keluar<1 and tglahir_suami<>'' and tglahir_suami>$t10 and tglahir_suami<=$t5");
$lk[6] = $db->querySingle("SELECT count(nama_suami) FROM t_warga WHERE nama_suami<>'' and nama_suami<>'belum' and tgl_keluar<1 and tglahir_suami<>'' and tglahir_suami>$t5");

$wn[0] = $db->querySingle("SELECT count(nama_istri) FROM t_warga WHERE nama_istri<>'' and nama_suami<>'belum' and tgl_keluar<1 and tglahir_istri<>'' and tglahir_istri>$t150 and tglahir_istri<=$t50");
$wn[1] = $db->querySingle("SELECT count(nama_istri) FROM t_warga WHERE nama_istri<>'' and nama_suami<>'belum' and tgl_keluar<1 and tglahir_istri<>'' and tglahir_istri>$t50 and tglahir_istri<=$t40");
$wn[2] = $db->querySingle("SELECT count(nama_istri) FROM t_warga WHERE nama_istri<>'' and nama_suami<>'belum' and tgl_keluar<1 and tglahir_istri<>'' and tglahir_istri>$t40 and tglahir_istri<=$t30");
$wn[3] = $db->querySingle("SELECT count(nama_istri) FROM t_warga WHERE nama_istri<>'' and nama_suami<>'belum' and tgl_keluar<1 and tglahir_istri<>'' and tglahir_istri>$t30 and tglahir_istri<=$t20");
$wn[4] = $db->querySingle("SELECT count(nama_istri) FROM t_warga WHERE nama_istri<>'' and nama_suami<>'belum' and tgl_keluar<1 and tglahir_istri<>'' and tglahir_istri>$t20 and tglahir_istri<=$t10");
$wn[5] = $db->querySingle("SELECT count(nama_istri) FROM t_warga WHERE nama_istri<>'' and nama_suami<>'belum' and tgl_keluar<1 and tglahir_istri<>'' and tglahir_istri>$t10 and tglahir_istri<=$t5");
$wn[6] = $db->querySingle("SELECT count(nama_istri) FROM t_warga WHERE nama_istri<>'' and nama_suami<>'belum' and tgl_keluar<1 and tglahir_istri<>'' and tglahir_istri>$t5");

$lk[0] += $db->querySingle("SELECT count(id_warga_tambahan) FROM t_warga_tambahan WHERE nama_lengkap<>'' and tanggal_keluar<1 and tanggal_lahir<>'' and jenis_kelamin='L' and tanggal_lahir>$t150 and tanggal_lahir<=$t50");
$lk[1] += $db->querySingle("SELECT count(id_warga_tambahan) FROM t_warga_tambahan WHERE nama_lengkap<>'' and tanggal_keluar<1 and tanggal_lahir<>'' and jenis_kelamin='L' and tanggal_lahir>$t50 and tanggal_lahir<=$t40");     
$lk[2] += $db->querySingle("SELECT count(id_warga_tambahan) FROM t_warga_tambahan WHERE nama_lengkap<>'' and tanggal_keluar<1 and tanggal_lahir<>'' and jenis_kelamin='L' and tanggal_lahir>$t40 and tanggal_lahir<=$t30");
$lk[3] += $db->querySingle("SELECT count(id_warga_tambahan) FROM t_warga_tambahan WHERE nama_lengkap<>'' and tanggal_keluar<1 and tanggal_lahir<>'' and jenis_kelamin='L' and tanggal_lahir>$t30 and tanggal_lahir<=$t20");
$lk[4] += $db->querySingle("SELECT count(id_warga_tambahan) FROM t_warga_tambahan WHERE nama_lengkap<>'' and tanggal_keluar<1 and tanggal_lahir<>'' and jenis_kelamin='L' and tanggal_lahir>$t20 and tanggal_lahir<=$t10");
$lk[5] += $db->querySingle("SELECT count(id_warga_tambahan) FROM t_warga_tambahan WHERE nama_lengkap<>'' and tanggal_keluar<1 and tanggal_lahir<>'' and jenis_kelamin='L' and tanggal_lahir>$t10 and tanggal_lahir<=$t5");
$lk[6] += $db->querySingle("SELECT count(id_warga_tambahan) FROM t_warga_tambahan WHERE nama_lengkap<>'' and tanggal_keluar<1 and tanggal_lahir<>'' and jenis_kelamin='L' and tanggal_lahir>$t5");

$wn[0] += $db->querySingle("SELECT count(id_warga_tambahan) FROM t_warga_tambahan WHERE nama_lengkap<>'' and tanggal_keluar<1 and tanggal_lahir<>'' and jenis_kelamin='P' and tanggal_lahir>$t150 and tanggal_lahir<=$t50");
$wn[1] += $db->querySingle("SELECT count(id_warga_tambahan) FROM t_warga_tambahan WHERE nama_lengkap<>'' and tanggal_keluar<1 and tanggal_lahir<>'' and jenis_kelamin='P' and tanggal_lahir>$t50 and tanggal_lahir<=$t40");
$wn[2] += $db->querySingle("SELECT count(id_warga_tambahan) FROM t_warga_tambahan WHERE nama_lengkap<>'' and tanggal_keluar<1 and tanggal_lahir<>'' and jenis_kelamin='P' and tanggal_lahir>$t40 and tanggal_lahir<=$t30");
$wn[3] += $db->querySingle("SELECT count(id_warga_tambahan) FROM t_warga_tambahan WHERE nama_lengkap<>'' and tanggal_keluar<1 and tanggal_lahir<>'' and jenis_kelamin='P' and tanggal_lahir>$t30 and tanggal_lahir<=$t20");
$wn[4] += $db->querySingle("SELECT count(id_warga_tambahan) FROM t_warga_tambahan WHERE nama_lengkap<>'' and tanggal_keluar<1 and tanggal_lahir<>'' and jenis_kelamin='P' and tanggal_lahir>$t20 and tanggal_lahir<=$t10");
$wn[5] += $db->querySingle("SELECT count(id_warga_tambahan) FROM t_warga_tambahan WHERE nama_lengkap<>'' and tanggal_keluar<1 and tanggal_lahir<>'' and jenis_kelamin='P' and tanggal_lahir>$t10 and tanggal_lahir<=$t5");
$wn[6] += $db->querySingle("SELECT count(id_warga_tambahan) FROM t_warga_tambahan WHERE nama_lengkap<>'' and tanggal_keluar<1 and tanggal_lahir<>'' and jenis_kelamin='P' and tanggal_lahir>$t5");
?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart",'bar']});
  google.setOnLoadCallback(drawChart);
  function drawChart() {

	var data = google.visualization.arrayToDataTable([
	  ['Tipe', 'Jumlah'],
	  ['L: <?=$bapak+$laki?>', <?=$bapak+$laki?>],
	  ['P: <?=$ibu+$perempuan?>',  <?=$ibu+$perempuan?>]
	]);

	var options = {
	  legend: {'position':'bottom'}
	};
	var chart = new google.visualization.PieChart(document.getElementById('piechart'));
	chart.draw(data, options);
	drawMultSeries();
  }
  
  function drawMultSeries() {
      var data = google.visualization.arrayToDataTable([
        ['Umur', 'Pria', 'Wanita'],
        ['>50', 		<?=$lk[0]*1?>, <?=$wn[0]*1?>],
        ['40-49', 	<?=$lk[1]*1?>, <?=$wn[1]*1?>],
        ['30-39', 	<?=$lk[2]*1?>, <?=$wn[2]*1?>],
        ['20-29', 	<?=$lk[3]*1?>, <?=$wn[3]*1?>],
        ['10-19', 	<?=$lk[4]*1?>, <?=$wn[4]*1?>],
        ['5-9', 		<?=$lk[5]*1?>, <?=$wn[5]*1?>],
        ['<5', 		<?=$lk[6]*1?>, <?=$wn[6]*1?>]
      ]);

      var options = {
        title: 'Populasi berdasarkan Umur',
        chartArea: {width: '50%'},
        hAxis: {
          title: 'Jumlah',
          minValue: 0
        },
        vAxis: {
          title: 'Umur'
        }
      };

      var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }
</script>
<legend>Grafik</legend>
<div class="row">
	<div class="col-md-6">
    	<div class="panel panel-primary">
        <!-- Default panel contents -->
        <div class="panel-heading">Jumlah Laki-laki dan Perempuan</div>
        <div id="piechart" class="panel-body"></div>
        </div>
    </div>
	<div class="col-md-6">
    	<div class="panel panel-primary">
        <!-- Default panel contents -->
        <div class="panel-heading">Populasi berdasarkan Umur</div>
        <div id="chart_div" class="panel-body"></div>
        </div>
    </div>
</div>
<p class="help-block">*membutuhkan koneksi internet.</p>