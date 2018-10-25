<?php
class Categories{
	private $db;
	public function __construct(){
		$this->db = new Database();
	} 

	public function getAllCategories(){
		$sql = "SELECT * FROM categories";
		$res = $this->db->select($sql);
		return $res;
	}
	public function getCategory($category_name){

		$categoriesAllowed = array("programming","sport","culture","news");
		if(in_array(strtolower($category_name), $categoriesAllowed)){
			$sql  = "SELECT full_name,username, posts.*,category_name
		 		 FROM users, posts, categories
		 		 WHERE users.id = posts.user_id AND categories.id = posts.category_id AND category_name = '$category_name' ORDER BY posts.id";

			$res = $this->db->select($sql);
			return ($res) ? $res : false;
		}
		return false;
		
	}

	public function numCategories(){
		$query = "SELECT category_name, COUNT(posts.category_id) count 
				  FROM categories, posts
				  WHERE categories.id = posts.category_id
				  GROUP BY posts.category_id";
		$result = $this->db->select($query);
		return ($result) ? $result : false;
		
	}
}


?>