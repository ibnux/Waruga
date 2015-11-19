<?
$pathDBsms = "./data/WarugaSMS.db";
$dbSMS = new SQLite3($pathDBsms);


ikutkan("head.php");
ikutkan("menu.php");
?><legend>SMS</legend>
<div class="btn-group btn-group-justified">
  <a href="warugasms://" class="btn btn-primary"><span class="glyphicon glyphicon-send"></span> Kirim SMS</a>
</div>
<?
if(isset($_GET['delete'])){
	$db->exec("delete from t_sms where id_sms=".($_GET['delete']*1));
}
$hasil = $db->query("select id_sms,topik_sms,isi_sms,terakhir_dikirim from t_sms order by id_sms desc");
?>
<table class="table table-striped" width="100%" cellpadding="4" cellspacing="4">
<?
while($row = $hasil->fetchArray()){
?>	<tr>
    	<td><a href="?apa=sendsms&id=<?=$row['id_sms']?>"><div><b><?=$row['topik_sms'] ?></b><br><?=$row['isi_sms'] ?></div></a><br>
        </td>
        <td align="right"><a href="?apa=SMS&id=<?=$row['id_sms']?>&do=simpan" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-search"></span> Edit</a>
        	<a href="?apa=<?=$apa?>&delete=<?=$row['id_sms']?>" onClick="return confirm('Yakin mau dihapus?');" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span> Delete</a></td>
    </tr>
<? } ?>
</table>