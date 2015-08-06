<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta charset="UTF-8">
<title><?= $config->Judul_Aplikasi;?></title>
<meta name="mobile-web-app-capable" content="yes">
<link rel="icon" sizes="192x192" href="./?file&apa=images/waruga-icon-2x.png">
<link rel="manifest" href="manifest.json">
<style>
<?
ikutkan("css/bootstrap.min.css");
ikutkan("css/jquery.datetimepicker.css");
?>
.hoverTable tr:hover {
  	background-color: #CC6666;
}
.hoverTable tr{
	cursor:crosshair;
}
a:link {
	text-decoration: none;
	color: #000;
}
a:visited {
	text-decoration: none;
	color: #000;
}
a:hover {
	text-decoration: underline;
	color: #000;
}
a:active {
	text-decoration: none;
	color: #000;
}
.home{
	border: 1px;
	border-color:#000;
	border-style:dotted;
}
.kursor {
	cursor:pointer;
}

@media screen and (max-width: 320px) { 
	#tabelData{
		width: 360px    !important;
	}
	#overflow {
		overflow:scroll;
	}
}
#tabelData {
	width:100%;
}

body,td,th {
	font-size: <?= $config->Font_Size;?>px;
}
body {
	margin-left:2px;
	margin-right:2px;
}
.page-header{
	margin-top: 2px;
	margin-bottom: 5px;
}
.page-header h4{
	margin-bottom: 0px;
}
.garistipis {
	padding:2px; margin:4px
}
.autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
.autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
.autocomplete-selected { background: #F0F0F0; }
.autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
.autocomplete-group { padding: 2px 5px; }
.autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
</style>
<script type="text/javascript" src="./?file&apa=js/jquery.min.js"></script>
<script type="text/javascript" src="./?file&apa=js/bootstrap.min.js"></script>

<script>
function caltel(nomor){
	if(confirm('kirim SMS ke '+nomor+'?\r\n\r\nPilih cancel untuk menelepon.')){
		window.location = 'sms:'+nomor;
		return;
	}
	if(confirm('Telepon ke '+nomor+'?')){
		window.location = 'tel:'+nomor;
	}
}
</script>
</head>
<body>
<div class="container">