<?php
	include 'classes/database.php';
	$db = new Database();

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_POST['postid'])){
			$postid = $_POST['postid'];
			$sql = "DELETE FROM posts WHERE id = '$postid'";
			$res = $db->delete($sql);
		}
	}else{
		header('location: log.php');
	}

?>