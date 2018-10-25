<?php
require __DIR__.'/../config.php';
class Database {
	
	public $host = DB_HOST;
	public $user = DB_USER;
	public $pass = DB_PASS;
	public $dbname = DB_NAME;

	public $link;
	public $error;
	public function __construct(){
		$this->connectDB();
	}
	private function connectDB(){
		$this->link = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
		if(!$this->link){
			$this->link = "Connection fail ".$this->link->connect_error;
			return false;
		}
	}

	// Select or read data
	public function select($query){
		$result = $this->link->query($query) or die($this->link->error.__LINE__);
		if($result->num_rows > 0){
			return $result;
		}else return false;
	}
	
	// Insert Data
	public function insert($query){
		$insert_row = $this->link->query($query) or die($this->link->error.__LINE__);
		return $insert_row ? $insert_row : false;
	}

	// Update Data
	public function update($query){
		$update_row = $this->link->query($query) or die($this->link->error.__LINE__);
		return $update_row ? $update_row : false;
	}

	// Delete Data
	public function delete($query){
		$delete_row = $this->link->query($query) or die($this->link->error.__LINE__);
		return $delete_row ? $delete_row : false; 
	}

	public function __destructor(){
		$this->link->close();
	}
}