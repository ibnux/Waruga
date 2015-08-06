<?php
class SQLITE{
	var $versi = 3;
	var $db = NULL;
	var $db_file = "";
	var $error = "";

	function __construct( $db_file ,$versi = 3){
		$this->versi = $versi;
		$this->db_file = $db_file;
		if($versi==3)
			$this->db = new SQLite3($db_file);
		else
			$this->db = sqlite_open($db_file,0666,$this->error);
		
	}
	
	function exec($query){
		$this->error = "";
		if($this->versi==3)
			return $this->db->exec($query);
		else
			return sqlite_exec($this->db,$query,$this->error);
	}
	
	function query($query){
		$this->error = "";
		if($this->versi==3)
			return $this->db->query($query);
		else
			return sqlite_query($this->$db,$query,SQLITE_ASSOC,$this->error);
	}
	
	function querySingle($query,$all=false){
		$this->error = "";
		if($this->versi==3)
			return $this->db->querySingle($query,$all);
		else
			return sqlite_single_query($this->db,$query,$all);
	}
	
	function fetchArray($sql){
		$this->error = "";
		if($this->versi==3)
			return $sql->fetchArray();
		else
			return sqlite_fetch_array($sql,SQLITE_ASSOC);
			
	}
	
	function lastErrorMsg(){
		if($this->versi==3)
			return $this->db->lastErrorMsg();
		else
			return $this->error;
			
	}
}