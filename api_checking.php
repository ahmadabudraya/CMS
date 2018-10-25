<?php
	include 'classes/database.php';
	$db = new Database();
	$result = array();
	if($_SERVER['REQUEST_METHOD'] == 'POST'){

		if(isset($_POST['key']) && $_POST['key'] == 'username'){
			checkRequest('username', $_POST['value']);
		}
		if(isset($_POST['key']) && $_POST['key'] == 'email'){
			checkRequest('email', $_POST['value']);
		}
		
		echo json_encode($result);

	}else{
		header('location: log.php');
	}

	function checkRequest($key, $val){
		global $db;
		global $result;
		$sql = "SELECT * FROM users WHERE $key = '$val'";
		$res = $db->select($sql);
		$result[$key]= true;
		if($res){
			$result[$key]=false;
		}
	}
?>