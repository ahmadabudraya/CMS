<?php
class Comment{
	private $db;
	public function __construct(){
		$this->db = new Database();
	}

	public function getTheNumberOfComments($post_id){
		$query = "SELECT COUNT(post_id) count FROM comments WHERE post_id = '$post_id'
				  GROUP BY post_id";
		$res   = $this->db->select($query);
		return ($res) ? $res->fetch_assoc()['count'] : 0;
		
	}

}

?>