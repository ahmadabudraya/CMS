<?php
	include 'classes/database.php';
	$db = new Database();

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		
		$data = [];
		$record_per_page = 3;
		$page = $_POST['page'];
		$output = '';
		$start = ($page - 1) * $record_per_page;
		$sql  = "SELECT full_name, posts.*
				 FROM users, posts 
				 WHERE users.id = posts.user_id ORDER BY posts.id DESC LIMIT $start , $record_per_page";
		$rows = $db->select($sql);
		$id = $start+1;
		$output .="
		<table class='table-posts' border=1 style='width:79%'>
                <tr>
                    <th>#ID
                        <a class='sort'>
                            <i class='fa fa-angle-down'></i>
                        </a>
                    </th>
                    <th>Title</th>
                    <th>Published by</th>
                    <th>Created at</th>
                    <th>Updated at</th>
                    <th>Action</th>
                </tr>
		";
		while($row = $rows->fetch_assoc()){
			$output .="<tr>";
				$output .="<td>".$id++ ."</td>";
				$output .="<td>".$row['title']."</td>";
				$output .="<td>".$row['full_name']."</td>";
				$output .="<td>".$row['created_at']."</td>";
				$output .="<td>".$row['updated_at']."</td>";
				$output .="<td><a href='?do=edit&id={$row['id']}' class='edit'>Edit</a> <span data-postid={$row['id']} class='confirm'>Delete</span></td>";
			$output .="</tr>";
		}
		$output .="</table>";
		$data['content'] = $output;
		$posts_query  = "SELECT * FROM posts";
		$posts_result = $db->select($posts_query);
		$total_records= isset($posts_result->num_rows) ? $posts_result->num_rows : 0;   
		$total_pages  = ceil($total_records / $record_per_page);
		$links = '';
		$prev  = $page-1; 
		$links .= ($page > 1) ? "<li class='$prev'>&laquo;</a>" : '';

		for($i = 1; $i<=$total_pages; $i++){
			$class = ($i == $page) ? 'class="active"' : ''; 
			$links .= "<li $class class='$i'>$i</li>";
		}
		$next = $page+1;
		$links .= ($page < $total_pages) ? "<li class='$next'>&raquo;</a>" : ""; 
		$data['links'] = $links;
		echo json_encode($data);
	}else{
		header('location: log.php');
	}

?>