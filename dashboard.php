<?php
    include 'classes/User.php';
    include 'classes/Categories.php';
    include 'classes/Post.php';
    Session::init();
    Session::checkSession();
    $user = new User();
    $cats = new Categories();
    $post = new Post();
    $do = isset($_GET['do']) ? $_GET['do'] : 'manage';
    if($do == 'logout'){
        Session::destroy();
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    </head>
    <body>
        <nav>
            <div class="logo">CMS</div>
            <div>Hello, <?php echo Session::get('fullname'); ?> <i class="fa fa-angle-down"></i></div>
            <ul class="setting">
                <li><a href="?do=account"><i class="fa fa-cog"></i> Account settings</a></li>
                <li><a href="?do=changepass"><i class="fa fa-key"></i> Change password</a></li>
                <li><a href="?do=logout"><i class="fa fa-sign-out"></i> Logout</a></li>
            </ul>
        </nav>
        <div class="accordion">
            <h3 class="dash"><a href="?do=manage"><i class="fa fa-dashboard"></i> Dashboard</a></h3>
            <h3><i class="fa fa-tags"></i> Post <i class="fa fa-angle-right"></i></h3>
            <ul>
                <li><a href="?do=showposts">Show posts</a></li>
                <li><a href="?do=addpost">Add post</a></li>
            </ul>
            <h3><i class="fa fa-user"></i> User <i class="fa fa-angle-right"></i></h3>
            <ul>
                <li><a href="?do=searchuser">Search about user</a></li>
            </ul>
            <h3><i class="fa fa-cogs"></i> Settings <i class="fa fa-angle-right"></i></h3>
            <ul>
                <li><a href="?do=pendings">Pending users</a></li>
                <li><a href="#">Blog settings</a></li>
                <li><a href="#">Blog settings</a></li>
                <li><a href="#">Blog settings</a></li>
            </ul>
        </div>
        <?php
        if($do == 'manage'){
        ?>
            <div class="boxes-bord">
                <div class="box users">
                    <h3>The Total Users</h3>
                    <i class="fa fa-users"></i>
                    <span><?php echo $user->countUsers(); ?></span>
                </div>
                <div class="box posts">
                    <h3>The Total Posts</h3>
                    <i class="fa fa-tags"></i>
                    <span><?php echo $post->countPosts() ?></span>
                </div>
                <div class="box pendings">
                    <h3>The Pending Users</h3>
                    <i class="fa fa-user-plus"></i>
                    <span><?php echo isset($user->getPendingUsers()->num_rows) ? $user->getPendingUsers()->num_rows : 0; ?></span>
                </div>
            </div>

            <!-- Show all posts by using pagination -->
            <div class="table-paginate">
                
            </div>
            
            

            <ul class="paginator">
            </ul>

        <?php }elseif($do == 'addpost'){ ?>
            <div class="add-post">
                <h1>Add New Post</h1>
                <form action="?do=insert" method="POST" enctype="multipart/form-data">
                    <div class="flex-container">
                        <label>Title</label>
                        <input type="text" name="title">
                    </div>
                    <div class="flex-container">
                        <label>Content</label>
                        <textarea name="content"></textarea>
                    </div>
                    <div class="flex-container">
                        <label>Choose category</label>
                        <select name="category_id">
                            <option></option>
                            <?php 
                                $categories = $cats->getAllCategories(); 
                                while($cat  = $categories->fetch_assoc()) {
                                    echo "<option value='{$cat['id']}'>".$cat['category_name']."</option>";
                                }
                             ?>
                        </select>                        
                    </div>
                    <div class="flex-container">
                        <input type="file" name="image">
                    </div>
                    <div class="flex-container">
                        <input type="submit" name="share" value="Add Post">
                    </div>
                </form>
            </div>
        <?php }elseif($do == 'insert'){ 

            if($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST['share'])){
                echo $post->addPost($_POST,$_FILES['image']);
            }

        }elseif ($do == 'showposts') {?>
            <?php 
                /*$order= 'DESC';
                $sorted_array = array('DESC','ASC');
                if(isset($_GET['order']) && in_array($_GET['order'], $sorted_array)){
                    $order = $_GET['order'];
                }*/
            ?>
            <table class="table-posts paginate" border=1>
                <tr>
                    <th>#ID
                        <a class="sort">
                            <i class="fa fa-angle-down"></i>
                        </a>
                    </th>
                    <th>Title</th>
                    <th>Published by</th>
                    <th>Created at</th>
                    <th>Updated at</th>
                    <th>Action</th>
                </tr>
            <?php 
                $posts = $post->getPosts();
                $id = 1;
                while($post = $posts->fetch_assoc()){
                    echo "<tr>";
                        echo "<td>".$id++."</td>";
                        echo "<td>".$post['title']."</td>";
                        $fullname = $user->getUserById(intval($post['user_id']));
                        echo "<td>".$fullname['full_name']."</td>";
                        echo "<td>".$post['created_at']."</td>";
                        echo "<td>".$post['updated_at']."</td>";
                        echo "<td><a href='?do=edit&id={$post['id']}' class='edit'>Edit</a> <span data-postid={$post['id']} class='confirm'>Delete</span></td>";
                    echo "</tr>";
                }
            ?>
            </table>
            <div class="link">
                <i class="fa fa-angle-left"></i>
                <i class="fa fa-angle-right"></i>
            </div>

        <?php }elseif ($do == 'edit') {

            $id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
            $data = $post->getPostById($id);
            ?>
            <div class="add-post edit-post">
                <h1>Edit Post</h1>
                <form action="?do=update" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="postid" value="<?php echo $id; ?>">
                    <div class="flex-container">
                        <label>Title</label>
                        <input type="text" name="title" value="<?php echo $data['title']; ?>">
                    </div>
                    <div class="flex-container">
                        <label>Content</label>
                        <textarea name="content"><?php echo $data['content']; ?>"</textarea>
                    </div>
                    <div class="flex-container">
                        <label>Choose category</label>
                        <select name="category_id">
                            <option></option>
                            <?php 
                                $categories = $cats->getAllCategories(); 
                                while($cat  = $categories->fetch_assoc()) {
                                    if($cat['id'] === $data['category_id']){
                                        echo "<option value='{$cat['id']}' selected>".$cat['category_name']."</option>";

                                    }else{
                                        echo "<option value='{$cat['id']}'>".$cat['category_name']."</option>";
                                    }
                                    
                                }
                             ?>
                        </select>                        
                    </div>
                    <div class="flex-container">
                        <input type="file" name="image" value="<?php echo $data['image']; ?>">
                    </div>
                    <div class="flex-container">
                        <input type="submit" name="update" value="Update Post">
                    </div>
                </form>
            </div>    
        <?php }elseif($do == 'update'){

                if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])){
                    echo $post->updatePost($_POST, $_FILES['image']); 
                }
        }elseif ($do == 'delete') {
            $id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
            $res = $post->deletePost($id);
            if($res !== false){
                echo $res;
            }
        }elseif ($do == 'changepass') { 

            if($_SERVER['REQUEST_METHOD']== 'POST' && isset($_POST['change'])){
                echo $user->changePassword($_POST['oldpassword'],$_POST['newpassword']);
                exit();
            }

            ?>
                <div class="change-pass add-post">
                    <h1>Change Password</h1>
                    <form action="?do=changepass" method="POST">
                        <div class="flex-container">
                            <label>Old password</label>
                            <input type="password" name="oldpassword">
                        </div>
                        <div class="flex-container">
                            <label>New password</label>
                            <input type="password" name="newpassword">
                        </div>
                        <div class="flex-container">
                            <input type="submit" name="change" value="Change Password">
                        </div>
                    </form>
                </div>
        <?php }elseif ($do == 'account') {

                $data = $user->getUserById(intval(Session::get('id')));
                if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change'])){
                    die($user->updateAccountSettings($_POST));
                }
            ?>
                <div class="account add-post">
                    <h1>Account Settings</h1>
                    <form action="?do=account" method="POST">
                        <div class="flex-container">
                            <label>Full name</label>
                            <input type="text" name="full_name" value="<?php echo $data['full_name']; ?>">
                        </div>
                        <div class="flex-container">
                            <label>User Name</label>
                            <input type="text" name="username" value="<?php echo $data['username']; ?>">
                        </div>
                        <div class="flex-container">
                            <label>E-mail</label>
                            <input type="email" name="email" value="<?php echo $data['email']; ?>">
                        </div>
                        <div class="flex-container">
                            <input type="submit" name="change" value="Update Account Settings">
                        </div>
                    </form>
                </div>
        <?php  }elseif ($do == 'pendings'){
                $pendings = $user->getPendingUsers();
                ?>
                <h1 class='pending'>Pending Users</h1>
                <table class="table-posts" border="1">
                    <tr>
                        <th>#ID</th>
                        <th>Username</th>
                        <th>Full name</th>
                        <th>E-mail</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    while($pending = $pendings->fetch_assoc()){
                        echo "<tr>";
                            echo "<td>".$pending['id']."</td>";
                            echo "<td>".$pending['username']."</td>";
                            echo "<td>".$pending['full_name']."</td>";
                            echo "<td>".$pending['email']."</td>";
                            echo "<td><button class='approve' data-userid={$pending['id']}>Approve</button></td>";
                        echo "</tr>";
                    }

                    ?>
                </table>

        <?php }elseif ($do == 'searchuser') { ?>
                
                <div class="flex-container add-post">
                    <label>Find User: </label>
                    <input type="text" name="finduser" id="finduser">
                </div>
                <table class='table-posts founduser' border="1">
                </table>
        <?php } ?>


        <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
        <script src="js/plugins.js"></script>
    </body>
</html>