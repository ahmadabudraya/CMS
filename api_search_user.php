<?php
	include 'classes/database.php';
	$db = new Database();

	if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])){
		$data = array();
		$name = strtolower($_POST['name']);
		$sql  = "SELECT * FROM users WHERE full_name LIKE '{$name}%'";
		$res  = $db->select($sql);
		if($res){
			while($row = $res->fetch_assoc()){
				$data[]=$row['full_name'];
			}
		}
		
		echo json_encode($data);

	}else{
		header('location: log.php');
	}


?>