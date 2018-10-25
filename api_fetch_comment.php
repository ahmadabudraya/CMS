<?php

	include 'classes/database.php';
	$db = new Database();
	if($_SERVER['REQUEST_METHOD'] == 'POST'){

		$post_id = $_POST['post_id'];

		function get_reply_comment($parent_id = 0, $marginLeft = 0){
			global $db;
			global $post_id;
			$query = "SELECT * FROM comments WHERE parent_comment_id = '$parent_id' AND post_id = '$post_id'";
			$res   = $db->select($query);
			
			if($parent_id == 0){
				$marginLeft = 0;
			}else{
				$marginLeft += 48;
			}
			if($res){
				$output = '';
				while($row = $res->fetch_assoc()) {
					$output .= "
					<div class='panel panel-default' style='margin-left:{$marginLeft}px'>
						<div class='panel-heading'>By <b>".$row['comment_sender_name']."</b> on <i>".$row['date']."</i></div>
						<div class='panel-body'>".$row['comment']."</div>

						<div class='panel-footer' align='right'>
							<button type='button' class='btn btn-default reply' id={$row['comment_id']}>Reply</button></div>
					</div>";

					$output .= get_reply_comment($row['comment_id'],$marginLeft);
				}
				return $output;
			}
			return false;
			
		}

		$query  = "SELECT * FROM comments WHERE parent_comment_id = '0' AND post_id = '$post_id'
		ORDER BY comment_id DESC";
		$res    = $db->select($query);
		$output = '';
		if($res){
			while($row = $res->fetch_assoc()){
				$output .= "
				<div><div class='panel panel-default'>
					<div class='panel-heading'>By <b>".$row['comment_sender_name']."</b> on <i>".$row['date']."</i></div>
					<div class='panel-body'>".$row['comment']."</div>
					<div class='panel-footer' align='right'>
						<button type='button' class='btn btn-default reply' id={$row['comment_id']}>Reply</button>
				</div></div>
				";
				$output .= get_reply_comment($row['comment_id']);
			}
		}	
		echo $output;

		

	}else{
		header('location: log.php');
	}

?>