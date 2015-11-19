<div class="page-header">
  <h4><a href="./"><?= $config->Judul_Aplikasi;?></a></h4>
</div>
<div class="btn-group btn-group-justified">
	<a href="./" class="btn btn-default btn-block <? if(empty($apa)) echo "active"; ?>"><span class="glyphicon glyphicon-home"></span> home</a>
	<div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle <? if(strpos('.iuran.iuranstats.kas.tamu.ronda.surat.',".$apa.") !== false) echo "active"; ?>" data-toggle="dropdown">
            <span class="glyphicon glyphicon-briefcase"></span> <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><a href="./?apa=iuran" class="<? if($apa == "iuran") echo "active"; ?>"><span class="glyphicon glyphicon-inbox"></span> Iuran bulanan</a></li>
            <li><a href="./?apa=kas" class="<? if($apa == "kas") echo "active"; ?>"><span class="glyphicon glyphicon-stats"></span> Kas</a></li>
            <!--<li><a href="./?apa=surat" class="<? if($apa == "surat") echo "active"; ?>"><span class="glyphicon glyphicon-envelope"></span> Surat Menyurat</a></li>
            <li><a href="./?apa=tamu" class="<? if($apa == "tamu") echo "active"; ?>"><span class="glyphicon glyphicon-list-alt"></span> Buku Tamu</a></li>-->
            <li><a href="./?apa=ronda" class="<? if($apa == "ronda") echo "active"; ?>"><span class="glyphicon glyphicon-dashboard"></span> Ronda</a></li>
            <li><a href="./?apa=grafik" class="<? if($apa == "grafik") echo "active"; ?>"><span class="glyphicon glyphicon-tasks"></span> Grafik</a></li>
        </ul>
    </div>
    <div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle <? if(strpos('.print.setup.sms.listsms.sync.kategori.',".$apa.") !== false) echo "active"; ?>" data-toggle="dropdown">
            <span class="glyphicon glyphicon-cog"></span> <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><a href="./?apa=SMSlist" class="<? if($apa == "SMSlist") echo "active"; ?>"><span class="glyphicon glyphicon-comment"></span> SMS</a></li>
            <li><a href="./?apa=kategori" class="<? if($apa == "kategori") echo "active"; ?>"><span class="glyphicon glyphicon-list"></span> Kategori</a></li>
            <li><a href="./?apa=Vcard" onClick="return confirm('Yakin mau unduh buku Telepon?')"><span class="glyphicon glyphicon-credit-card"></span> VCard All</a></li>
            <li><a href="./?apa=Vcard&filter=bapak" onClick="return confirm('Yakin mau unduh buku Telepon?')"><span class="glyphicon glyphicon-credit-card"></span> VCard Bapak2</a></li>
            <li><a href="./?apa=Vcard&filter=ibu" onClick="return confirm('Yakin mau unduh buku Telepon?')"><span class="glyphicon glyphicon-credit-card"></span> VCard Ibu2</a></li>
            <!--<li><a href="./?apa=printBulanan" target="_blank" class="<? if($apa == "printBulanan") echo "active"; ?>"><span class="glyphicon glyphicon-folder-open"></span> Export Iuran</a></li>
            <li><a href="./?apa=sync" class="<? if($apa == "sync") echo "active"; ?>"><span class="glyphicon glyphicon-transfer"></span> sync</a></li>
            --><li class="divider"></li>
            <li><a href="./?apa=settings" class="<? if($apa == "settings") echo "active"; ?>">
                <span class="glyphicon glyphicon-wrench"></span> Pengaturan
            </a></li>
            <li><a href="./?apa=cekUpdate" class="<? if($apa == "cekUpdate") echo "active"; ?>">
                <span class="glyphicon glyphicon-refresh"></span> Cek versi baru
            </a></li>
            <li class="divider"></li>
            <li><a href="./?apa=feedback" class="<? if($apa == "feedback") echo "active"; ?>">
                <span class="glyphicon glyphicon-retweet"></span> Diskusi
            </a></li>
        </ul>
    </div>
    <? if($config->Gunakan_Denah=='true'){ ?>
    <a href="./?apa=denah" class="btn btn-default btn-block <? if($apa == "denah") echo "active"; ?>"><span class="glyphicon glyphicon-th"></span> denah</a>
	<? }else{ ?>
    <a href="#" class="btn btn-default btn-block disabled"><span class="glyphicon glyphicon-th"></span> denah</a>
    <? } ?>
</div>