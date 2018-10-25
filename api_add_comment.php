<?php

	include 'classes/database.php';
	$db = new Database();
	if($_SERVER['REQUEST_METHOD'] == 'POST'){

		$error 			 = '';
		$post_id		 = $_POST['post_id'];
		$comment_id		 = $_POST['comment_id'];
		$comment_name 	 = '';
		$comment_content = '';


		if(empty($_POST['comment_name'])){
			$error .= '<div class="text-danger">Name is required</div>';
		}else{
			$comment_name = $_POST['comment_name'];
		}
		if(empty($_POST['comment_content'])){
			$error .='<div class="text-danger">Comment is required</div>';
		}else{
			$comment_content = $_POST['comment_content'];
		}
		
		if($error == ''){
			$query = "INSERT INTO comments values('','$comment_id','$comment_content','$comment_name','$post_id',NOW())";
			$error = "<label class='text-success'>Comment Added</label>";
			$res   = $db->insert($query);
		}
		$result = array(
			'error' => $error,

		);
		echo json_encode($result);

	}else{
		header('location: log.php');
	}



?>