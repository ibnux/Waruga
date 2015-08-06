<?php
// nama file

$namaFile = "bulananWarga.xls";

// Function penanda awal file (Begin Of File) Excel

function xlsBOF() {
	echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
	return;
}

// Function penanda akhir file (End Of File) Excel

function xlsEOF() {
	echo pack("ss", 0x0A, 0x00);
	return;
}

// Function untuk menulis data (angka) ke cell excel

function xlsWriteNumber($Row, $Col, $Value) {
	echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
	echo pack("d", $Value);
	return;
}

// Function untuk menulis data (text) ke cell excel

function xlsWriteLabel($Row, $Col, $Value ) {
	$L = strlen($Value);
	echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
	echo $Value;
	return;
}

// header file excel

//header("Pragma: public");
//header("Expires: 0");
//header("Cache-Control: must-revalidate, post-check=0,
//        pre-check=0");
//header("Content-Type: application/force-download");
//header("Content-Type: application/octet-stream");
//header("Content-Type: application/download");

// header untuk nama file
//header("Content-Disposition: attachment; filename=".$namaFile."");
header('Content-disposition: attachment; filename='.$namaFile);
header('Content-type: application/octet-stream');
//header("Content-Transfer-Encoding: binary ");

// memanggil function penanda awal file excel
xlsBOF();

// ------ membuat kolom pada excel --- //

// mengisi pada cell A1 (baris ke-0, kolom ke-0)
xlsWriteLabel(0,0,"NO");               
xlsWriteLabel(0,1,"Nama");              
xlsWriteLabel(0,2,"No HP");
xlsWriteLabel(0,3,"Jan");
xlsWriteLabel(0,4,"Peb");
xlsWriteLabel(0,5,"Mar");   
xlsWriteLabel(0,6,"Apr"); 
xlsWriteLabel(0,7,"Mei"); 
xlsWriteLabel(0,8,"Jun"); 
xlsWriteLabel(0,9,"Jul"); 
xlsWriteLabel(0,10,"Ags");
xlsWriteLabel(0,11,"Sept");
xlsWriteLabel(0,12,"Okt");
xlsWriteLabel(0,13,"Nov");
xlsWriteLabel(0,14,"Des");
xlsWriteLabel(0,15,"Keterangan");

// -------- menampilkan data --------- //

// nilai awal untuk baris cell
$noBarisCell = 1;

// nilai awal untuk nomor urut data
$noData = 1;

$hasil = $db->query("Select `blok_rumah`, `nomor_rumah`, `nama_suami`,
				telepon_suami, `nama_istri`,telepon_istri from t_warga where tgl_keluar=0 ORDER BY `blok_rumah`, CAST( nomor_rumah AS INTEGER) ASC");

while($data = $hasil->fetchArray()){
   xlsWriteLabel($noBarisCell,0,$data['blok_rumah']."-".$data['nomor_rumah']);
   if(!empty($data['nama_suami']) && $data['nama_suami']!='belum'){
		xlsWriteLabel($noBarisCell,1,$data['nama_suami']);
		xlsWriteLabel($noBarisCell,2,$data['telepon_suami']);
   }else if(!empty($data['nama_istri'])){
		xlsWriteLabel($noBarisCell,1,$data['nama_istri']);
		xlsWriteLabel($noBarisCell,2,$data['telepon_istri']);
   }else{
		xlsWriteLabel($noBarisCell,1,'');
		xlsWriteLabel($noBarisCell,2,'');
   }
   xlsWriteLabel($noBarisCell,3,'1');
   xlsWriteLabel($noBarisCell,4,'2');
   xlsWriteLabel($noBarisCell,5,'3');
   xlsWriteLabel($noBarisCell,6,'4');
   xlsWriteLabel($noBarisCell,7,'5');
   xlsWriteLabel($noBarisCell,8,'6');
   xlsWriteLabel($noBarisCell,9,'7');
   xlsWriteLabel($noBarisCell,10,'8');
   xlsWriteLabel($noBarisCell,11,'9');
   xlsWriteLabel($noBarisCell,12,'10');
   xlsWriteLabel($noBarisCell,13,'11');
   xlsWriteLabel($noBarisCell,14,'12');
   xlsWriteLabel($noBarisCell,15,'');
   
   $noBarisCell++;
   $noData++;
}

// memanggil function penanda akhir file excel
xlsEOF();
exit();

?>