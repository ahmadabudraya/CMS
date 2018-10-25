<?php
	include 'classes/database.php';
	$db = new Database();

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_POST['id'])){
			$id = $_POST['id'];
			$sql = "UPDATE users SET RegStatus = 1 WHERE id = '$id'";
			$res = $db->update($sql);
		}
	}else{
		header('location: log.php');
	}


?>