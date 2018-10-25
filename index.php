<?php
    include 'classes/User.php';
    include 'classes/Categories.php';
    include 'classes/Post.php';
    include 'classes/Comment.php';
    //$user = new User();
    $cats = new Categories();
    $post = new Post();
    $comment = new Comment();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>CMS - Blog</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400,700,800"> 
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        
        <link rel="stylesheet" href="css/blogstyle.css">
    </head>
    <body>
        <!-- Start Section Header -->
        <header>
            <div class="container">
                <div class="logo">
                   <h1>MyBLOG</h1>
                </div>
                <!-- Start Section navBar -->
                <nav>
                    <ul>
                        <li><a class="<?php echo !isset($_GET['category_name']) ? 'active' : '' ?>" href="index.php">Home</a></li>
                        <li><a class="<?php echo isset($_GET['category_name']) && $_GET['category_name'] == 'programming'? 'active' : '' ?>" href="?category_name=programming">Programming</a></li>
                        <li><a class="<?php echo isset($_GET['category_name']) && $_GET['category_name'] == 'culture'? 'active' : '' ?>" href="?category_name=culture">Culture</a></li>
                        <li><a class="<?php echo isset($_GET['category_name']) && $_GET['category_name'] == 'sport'? 'active' : '' ?>" href="?category_name=sport">Sport</a></li>
                        <li><a class="<?php echo isset($_GET['category_name']) && $_GET['category_name'] == 'news'? 'active' : '' ?>" href="?category_name=news">NEWS</a></li>
                    </ul>
                </nav>
                <!-- End Section navBar -->
            </div>
        </header>
        <!-- End Section Header -->
        
        
        
        <div class="container">

            <!-- Start Section SideBar -->
            <aside>
                <div class="social-media">
                    <h2>Follow Us</h2>
                    <i class="fab fa-facebook-f"></i>
                    <i class="fab fa-twitter"></i>
                    <i class="fab fa-youtube"></i>
                    <i class="fab fa-linkedin-in"></i>
                </div>
                <hr>
                <!-- Start Section Popular Posts --> 
                <div class="popular-posts">
                    <h2>popular posts</h2>
                    <?php 
                        $populars =  $post->getPopularPosts(); 
                        while($popular = $populars->fetch_assoc()) { ?>
                            
                    <!-- Start Section Post -->
                    <div class="post">
                        <img src="uploads/<?php echo empty($popular['image']) ? 'default-image.png' : $popular['image']; ?>">
                        <a class="title" href="?post_id=<?php echo $popular['id'] ?>"><h3><?php echo $popular['title']; ?></h3></a>
                        <span class="date"><?php echo $popular['created_at']; ?></span>
                    </div>
                    <!-- End Section Post -->
                   <?php }  ?>
                </div>
                <!-- End Section Popular Posts -->
                <hr>
                <!-- Start Section Categories -->    
                <div class="categories">
                    <h2>Categories</h2>
                    <ul>
                        <?php 
                        $categories = $cats->numCategories();
                        while($category = $categories->fetch_assoc()){ ?>
                        
                        <li>
                            <a href="?category_name=<?php echo $category['category_name'] ?>"><?php echo $category['category_name'] ?></a>
                            <span class="num-cates"><?php echo $category['count']; ?></span>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                <!-- End Section Categories -->    
                
            </aside>
            <!-- End Section SideBar -->
            

            <!-- Start Section Post Content -->
            <?php
            if(isset($_GET['post_id']) && is_numeric($_GET['post_id'])){
                $id = intval($_GET['post_id']);
                $content = $post->getPostById($id);
                $post->updateViews($id);
            ?>    

            <div class="content">
                <div class="breadcrumb">
                    Home / <?php echo $content['category_name'].' / '.$content['title']; ?>
                </div>

                <img src="uploads/<?php echo $content['image']; ?>">
                <h2><?php echo $content['title']?></h2>
                <ul class="main">
                    <li>
                        <i class="fa fa-user"></i> <?php echo $content['full_name'];?>
                    </li>
                    <li>
                        <i class="fa fa-clock"></i> <?php echo $content['created_at'];?>
                    </li>
                    <li>
                        <i class="fa fa-folder"></i> <?php echo $content['category_name'];?>
                    </li>
                    <li>
                        <i class="fa fa-comments"></i> <?php echo $comment->getTheNumberOfComments($id); ?> comments
                    </li>
                    <li>
                        <i class="fa fa-eye"></i> <?php echo $post->numberOfViews($id); ?> Views
                    </li>
                </ul>
                <p>
                    <?php echo $content['content'];?>
                </p>
            </div>


            <!-- Start Comments Section -->

            <div class="comments">
                <form method="post" id="comment_form">
                    <div class="form-group">
                        <input type="text" name="comment_name" id="comment_name" class="form-control" placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                        <textarea name="comment_content" id="comment_content" class="form-control" placeholder="Enter Name" rows="5"></textarea>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="comment_id" id="comment_id" value="0" />
                        <input type="hidden" name="post_id" value="<?php echo $content['id']; ?>">
                        <input type="submit" name="submit" id="submit" class="btn btn-info" value="Add Comment">
                    </div>
                    
                </form>
                <div id="comment_message">
                    
                </div>
                <div id="display_comment" data-postid="<?php echo $_GET['post_id'] ?>">
                    
                </div>
            </div>

            <!-- End Comments Section -->

           <?php }else{ ?>    
            
            <div class="contents">
                <?php
                $articles = null;
                if(isset($_GET['category_name']) && $cats->getCategory($_GET['category_name']) !== false){
                    $articles = $cats->getCategory($_GET['category_name']);
                }else{
                    if(isset($_GET['page']) && is_numeric($_GET['page'])){
                        $articles = $post->pagination(intval($_GET['page']));    
                    }else{
                        $articles = $post->pagination(); 
                    }
                }
                
                
                while($post = $articles->fetch_assoc()){
                ?>
                <article>
                    <?php
                    if(empty($post['image'])){
                        echo "<img src='http://placehold.it/400/100'>";
                    }else{
                        ?>
                         <img src="uploads/<?php echo $post['image']; ?>">
                        <?php
                    }
                    ?>
                    
                    <div class="border">
                    <h1 class="title"><?php echo $post['title']; ?></h1>
                    <div class="info">
                        <i class="fa fa-calendar-alt"></i> <?php echo $post['created_at']; ?>
                        <i class="fa fa-user"></i> <?php echo $post['username']; ?>
                    </div>
                    <p class="brief">
                        <?php echo $post['content']; ?>
                    </p>
                    <a href="?post_id=<?php echo $post['id']; ?>" class="btn btn-danger">Read More</a>
                    </div>
                </article>
                <?php } ?>

                <?php
                $page = 1;
                if(isset($_GET['page'])){
                    $page = intval($_GET['page']);
                }
                $poster = new Post();
                echo $poster->links($page);
                ?>

            <!--
                <article>
                    <img src="https://placeholder.com/wp-content/uploads/2018/04/home-office-2452806_640.jpg">
                    <div class="border">
                    <h1 class="title">What’s The Story Behind Lorem Ipsum?</h1>
                    <div class="info">
                        <i class="fa fa-calendar-alt"></i> September 27, 2018
                        <i class="fa fa-user"></i> Sprintive
                    </div>
                    <p class="brief">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </p>
                    <a href="#" class="btn btn-danger">Read More</a>
                    </div>
                </article>
            -->


            </div>
        <?php } ?>
            <!-- End Section Post Content -->
            
            
            
            
        </div>
        
        <!-- Start Section Footer -->
        <footer>
            <div class="copyright">
                &copy; 2018 myBlog.com, subsidiary of Sprintive. All rights reserved.
            </div>
        </footer>
        <!-- End Section Footer -->
        
        <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
        <script src="js/plugins.js"></script>
    </body>
</html>