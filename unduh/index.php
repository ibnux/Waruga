<?php
/* Waruga installer
aplikasi untuk Install Waruga
http://ibnux.github.io/Waruga/

Untuk PC atau laptop jalankan di webserver PHP

Untuk Android install aplikasi Server for PHP
https://play.google.com/store/apps/details?id=com.esminis.server.php
jalankan aplikasi untuk install server
untuk keamanan, pilih IP 127.0.0.1

simpan file ini di internal memory /www/

Created by @iBNuX

*/

if(file_exists("waruga.phar") && !isset($_GET["upgrade"]))
	require "waruga.phar";
else{
	$manifest = "ew0KICAibmFtZSI6ICJXYXJ1Z2EiLA0KICAiaWNvbnMiOiBbDQogICAgew0KICAgICAgInNyYyI6ICIuLz9maWxlJmFwYT1pbWFnZXMvd2FydWdhLWljb24tMC03NXgucG5nIiwNCiAgICAgICJzaXplcyI6ICIzNngzNiIsDQogICAgICAidHlwZSI6ICJpbWFnZS9wbmciLA0KICAgICAgImRlbnNpdHkiOiAiMC43NSINCiAgICB9LA0KICAgIHsNCiAgICAgICJzcmMiOiAiLi8/ZmlsZSZhcGE9aW1hZ2VzL3dhcnVnYS1pY29uLTF4LnBuZyIsDQogICAgICAic2l6ZXMiOiAiNDh4NDgiLA0KICAgICAgInR5cGUiOiAiaW1hZ2UvcG5nIiwNCiAgICAgICJkZW5zaXR5IjogIjEuMCINCiAgICB9LA0KICAgIHsNCiAgICAgICJzcmMiOiAiLi8/ZmlsZSZhcGE9aW1hZ2VzL3dhcnVnYS1pY29uLTEtNXgucG5nIiwNCiAgICAgICJzaXplcyI6ICI3Mng3MiIsDQogICAgICAidHlwZSI6ICJpbWFnZS9wbmciLA0KICAgICAgImRlbnNpdHkiOiAiMS41Ig0KICAgIH0sDQogICAgew0KICAgICAgInNyYyI6ICIuLz9maWxlJmFwYT1pbWFnZXMvd2FydWdhLWljb24tMngucG5nIiwNCiAgICAgICJzaXplcyI6ICI5Nng5NiIsDQogICAgICAidHlwZSI6ICJpbWFnZS9wbmciLA0KICAgICAgImRlbnNpdHkiOiAiMi4wIg0KICAgIH0sDQogICAgew0KICAgICAgInNyYyI6ICIuLz9maWxlJmFwYT1pbWFnZXMvd2FydWdhLWljb24tM3gucG5nIiwNCiAgICAgICJzaXplcyI6ICIxNDR4MTQ0IiwNCiAgICAgICJ0eXBlIjogImltYWdlL3BuZyIsDQogICAgICAiZGVuc2l0eSI6ICIzLjAiDQogICAgfSwNCiAgICB7DQogICAgICAic3JjIjogIi4vP2ZpbGUmYXBhPWltYWdlcy93YXJ1Z2EtaWNvbi00eC5wbmciLA0KICAgICAgInNpemVzIjogIjE5MngxOTIiLA0KICAgICAgInR5cGUiOiAiaW1hZ2UvcG5nIiwNCiAgICAgICJkZW5zaXR5IjogIjQuMCINCiAgICB9DQogIF0sDQogICJzdGFydF91cmwiOiAiaW5kZXgucGhwIiwNCiAgImRpc3BsYXkiOiAic3RhbmRhbG9uZSIsDQogICJvcmllbnRhdGlvbiI6ICJwb3RyYWl0Ig0KfQ==";		
	$config = "eyJKdWR1bF9BcGxpa2FzaSI6IktvbXBsZWsgV2FydWdhIiwiQmxvayI6IkJsb2siLCJOb21vciI6Ik5vbW9yIiwiSXVyYW5fQnVsYW5hbiI6IjM1MDAwIiwiU3luY19VUkwiOiJodHRwOlwvXC8xOTIuMTY4LjEwLjE1OjM3ODNcLz9hcGE9c3luY1NlcnZlciIsIkZvbnRfU2l6ZSI6IjEyIiwic2VwYXJhdG9yIjoic2VwYXJhdG9yIiwiU01TX1VSTF9QYXJhbWV0ZXIiOiJodHRwOlwvXC8xMjcuMC4wLjE6OTA5MFwvc2VuZHNtcyIsIlNNU19UZXh0X1BhcmFtZXRlciI6InRleHQiLCJTTVNfTnVtYmVyX1BhcmFtZXRlciI6InBob25lIiwiU01TX0FkZGl0aW9uYWxfUGFyYW1ldGVyIjoicGFzc3dvcmQ9IiwiU01TX1N1Y2Nlc3NfUGFyYW1ldGVyIjoiTWVzYWdlIFNFTlQhIiwiU01TX0plZGFfUGVuZ2lyaW1hbiI6IjE1IiwiU01TX0p1bWxhaF9QZW5naXJpbWFuIjoiNSIsInNlcGFyYXRvcjIiOiJzZXBhcmF0b3IiLCJHdW5ha2FuX0Jsb2siOiJ0cnVlIiwiR3VuYWthbl9MYWJlbCI6InRydWUiLCJHdW5ha2FuX0RlbmFoIjoidHJ1ZSIsInNlcGFyYXRvcjMiOiJzZXBhcmF0b3IiLCJTYW5kaV9TaW5rcm9uaXNhc2kiOiJ3YXJ1Z2EiLCJVbmdnYWhfRm90b19LVFBfS0siOiJ0cnVlIiwiQm9sZWhfSGFwdXNfRGF0YV9LYXMiOiJ0cnVlIn0=";
	
	if(!file_exists("manifest.json"))
		file_put_contents("manifest.json",base64_decode($manifest));
	if(!file_exists("config.json"))
		file_put_contents("config.json",base64_decode($config));

	
	set_time_limit(0);
	$fp = fopen (dirname(__FILE__) . '/waruga.phar', 'w+');//This is the file where we save the    information
	$ch = curl_init(str_replace(" ","%20","http://ibnux.github.io/Waruga/unduh/waruga.phar"));//Here is the file we are downloading, replace spaces with %20
	curl_setopt($ch, CURLOPT_TIMEOUT, 300);
	curl_setopt($ch, CURLOPT_FILE, $fp); // write curl response to file
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_exec($ch); // get curl response
	curl_close($ch);
	fclose($fp);
	$fp = fopen (dirname(__FILE__) . '/versi.txt', 'w+');//This is the file where we save the    information
	$ch = curl_init(str_replace(" ","%20","http://ibnux.github.io/Waruga/unduh/versi.txt"));//Here is the file we are downloading, replace spaces with %20
	curl_setopt($ch, CURLOPT_TIMEOUT, 50);
	curl_setopt($ch, CURLOPT_FILE, $fp); // write curl response to file
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_exec($ch); // get curl response
	curl_close($ch);
	fclose($fp);
	if(isset($_GET["upgrade"]))
		header("location: ./?");
	else
		header("location: ./?apa=settings");
}