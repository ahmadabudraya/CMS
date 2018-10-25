<?php
class Post{
	private $db;
	public function __construct(){
		$this->db = new Database();
	}

	public function filterData($val){
		$val = trim($val);
		//$val = htmlentities($val);
		$val = stripslashes($val);
		$val = htmlspecialchars($val);
		//$val = strtolower($val);
		$val = $this->db->link->real_escape_string($val);
		return $val;
	} 
	public function uploadImage($file){
		
		$fileName = $file['name'];
		$fileSize = $file['size'];
		$fileTmp  = $file['tmp_name'];
		$fielType = $file['type'];
		$fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

		// List Of Allowed File Typed To Upload
		$filesAllowedExtension = array("png","jpg","gif","jpeg");

		if(!empty($fileName) && !in_array($fileExtension, $filesAllowedExtension)){
			return false;
		}
		$file = mt_rand().'_'.$fileName;
		move_uploaded_file($fileTmp, "uploads\\".$file);
		return $file;
	}
	public function updatePost($data,$_file){
		$title   	 = $this->filterData($data['title']);
		$content 	 = $this->filterData($data['content']);
		$category_id = intval($this->filterData($data['category_id']));
		$id 		 = intval($data['postid']);
		$image 		 = $this->uploadImage($_file);
		$updated_at  = date('Y-m-d H:i:s',time());

		if($title == "" || $content == "" || $category_id == ""){
			return "You must fill all fields";
		}
		if(!$image){
			return 'This Extension Is Not <strong>Allowed</strong>';
		}
		$sql = "UPDATE posts 
				SET 
				title = '$title',
				content = '$content',
				category_id = '$category_id',
				updated_at = '$updated_at',
				image 	= '$image'
				WHERE id = '$id'";
		$res = $this->db->update($sql);
		if($res){
			return "Post updated Successful !";
		}
		return false;
	}
	public function countPosts(){
		$sql = "SELECT * FROM posts";
		$cnt = $this->db->select($sql);
		return $cnt->num_rows;
	}
	public function getPostById($id){
		$sql = "SELECT posts.*,full_name,username, category_name
				FROM users, posts, categories
				WHERE users.id = posts.user_id 
				AND categories.id = posts.category_id 
				AND posts.id = '$id'";
		$res = $this->db->select($sql);
		return ($res) ? $res->fetch_assoc() : false;
	}
	public function addPost($data, $_file){
		
		$title 		 = $this->filterData($data['title']);
		$content     = $this->filterData($data['content']);
		$category_id = $this->filterData($data['category_id']);
		$image 		 = $this->uploadImage($_file);
		$user_id 	 = Session::get('id');
		$created_at  = date('Y-m-d H:i:s',time()); 
		if($title == "" or $content == "" or $category_id == ""){
			return "You must fill all fields";
		}
		if(!$image){
			return 'This Extension Is Not <strong>Allowed</strong>';
		}

		$sql = "INSERT INTO posts values('','$title', '$content', '$user_id', '$category_id','$image','0', '$created_at', '$created_at')";
		$res = $this->db->insert($sql);
		if($res)return 'The post is added successfully';
		return false;
	}
	
	public function getPosts($order = 'DESC'){
		$sql  = "SELECT full_name,username, posts.*
		 		 FROM users, posts
		 		 WHERE users.id = posts.user_id ORDER BY posts.id $order";

		//$sql = "SELECT * FROM posts ORDER BY id $order";
		$res = $this->db->select($sql);
		return $res;
	}

	public function deletePost($id){
		$sql = "DELETE FROM posts WHERE id =$id";
		$res = $this->db->delete($sql);
		if($res){
			echo "The post deleted Successfully!";
		}
		return false;
	}
	
	public function updateViews($post_id){
		$query = "UPDATE posts 
				  SET views = views + 1
				  WHERE id = '$post_id'";
		$res = $this->db->update($query);
		return ($res) ? true : false;
	}
	public function numberOfViews($post_id){
		$query = "SELECT views FROM posts WHERE id = '$post_id'";
		$count = $this->db->select($query); 
		return $count->fetch_assoc()['views'];
	}
	public function getPopularPosts($limit = 5){
		$query  = "SELECT * FROM posts ORDER BY views DESC LIMIT $limit";
		$result = $this->db->select($query);
		if($result){
			return $result;
		}
		return false;
	}
	public function pagination($_page = 1){
		$record_per_page = 5;
		$page = $_page;
		$output = '';
		$start = ($page - 1) * $record_per_page;
		$sql  = "SELECT full_name, username,posts.*
				 FROM users, posts 
				 WHERE users.id = posts.user_id ORDER BY posts.id DESC LIMIT $start , $record_per_page";
		
		$result = $this->db->select($sql);
		return ($result) ? $result : false;
	}
	public function links($_page){

		if(!is_numeric($_page))$_page = 1;
		
		$record_per_page = 5;

		$posts_query  = "SELECT * FROM posts";
		$posts_result = $this->db->select($posts_query);
		$total_records= isset($posts_result->num_rows) ? $posts_result->num_rows : 0;   
		$total_pages  = ceil($total_records / $record_per_page);
		$page  = $_page; 
		$links = '';
		$prev  = $page-1; 
		
		$links .= '<nav aria-label="Page navigation">
		  <ul class="pagination pagination-lg">';
		    
		$links .= ($page > 1) ? "<li>
		      <a href='?page=$prev' aria-label='Previous'>
		        <span aria-hidden='true'>&laquo;</span>
		      </a>
		    </li>" : "";

		for($i = 1; $i<=$total_pages; $i++){
			$class = ($i == $page) ? 'class="active"' : ''; 
			$links .= "<li $class ><a href='?page=$i'>$i</a></li>";
		}
		$next = $page+1;
		$links .= ($page < $total_pages) ? "<li><a href='?page=$next' aria-label='Next'><span aria-hidden='true'>&raquo;</span></a>
		    </li>" : "";
		$links .= "</ul></nav>";
		return ($total_records > 0) ? $links : false;
	}

}
