<?php
    include 'classes/User.php';
    Session::init();
    Session::checkLogin();
    $user = new User();
?>
<html>
    <head>
        <title>CMS</title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
        <link rel="stylesheet" href="css/logstyle.css">
    </head>
    <body>
        <?php
            if($_SERVER['REQUEST_METHOD']=='POST' and isset($_POST['login'])){
                $ret = $user->userLogin($_POST);
                echo $ret."<br>";
                echo Session::get('loginmsg');
            }

        ?>

        <div class="signin">
            <form method="post" action="">
                <div class="logo">
                    <h2><span>CMS</span></h2>
                </div>
                <div class="frm" id="signin">
                    <input type="text" name="emailuser" id="username" placeholder="Email or username">
                    <input type="password" name="password" id="password" placeholder="Password">
                    <input type="submit" name="login" value="Log In">
                </div>
            </form>
        </div>
        <div class="signup">
            <h1>Create an account</h1>
            <?php
                if($_SERVER['REQUEST_METHOD']=='POST' and isset($_POST['signup'])){
                    $ret = $user->userRegistration($_POST);
                    echo $ret;
                    $fullname = isset($_POST['fullname']) ? $_POST['fullname'] : '';
                    $username = isset($_POST['username']) ? $_POST['username'] : '';
                    $email = isset($_POST['email']) ? $_POST['email'] : '';
                    $fullname = isset($_POST['fullname']) ? $_POST['fullname'] : '';
                }
            ?>
            <form method="post" action="">
                <div class="row">
                    <input type="text" name="fullname" placeholder="Full name" value="<?php echo isset($fullname) ? $fullname : ''; ?>" autocomplete="off">
                </div>
                <div class="row">
                    <input type="text" id="username" onkeyup="checking(this.id)" name="username" value="<?php echo isset($username) ? $username : ''; ?>" placeholder="Username"autocomplete="off">
                </div>
                <div class="row">
                    <input type="email" id="email" onkeyup="checking(this.id)" name="email" value="<?php echo isset($email) ? $email : ''; ?>" placeholder="E-mail address"autocomplete="off">
                </div>
                <div class="row validate">
                    <input type="password" name="password" id="pass" placeholder="Password">
                    <input type="password" name="re-password" id="repass" placeholder="Confirm Password">
                    <i class="fa"></i>
                </div>
                
                <input type="submit" name="signup" id="register" value="Join now!">
            </form>
        </div>
       
        <footer>
            <p>Developed by [Ahmad Abudraya]</p>
        </footer>

        <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
        <script src="js/plugins.js"></script>

    </body>
</html>