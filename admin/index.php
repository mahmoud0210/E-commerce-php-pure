<?php

    session_start();
    if (isset($_SESSION['username'])) {
        header('Location: dashboard.php');
        exit();
    }

    $nonavbar = '';
    $getTitle = 'Login';

    include "init.php";
     
    if($_SERVER['REQUEST_METHOD'] == 'POST') {//POST بالحروف الكبيرة حصرا وغير هيك الكود ما يشتغل
        
        $username = $_POST['user'];
        $password = $_POST['pass'];
        $hashedpass = sha1($password);
        
        // check if the user exist in database
        
        $stmt = $con->prepare("SELECT 
                                    userid, username, password 
                               FROM 
                                    users 
                               WHERE 
                                    username = ? 
                               AND 
                                    password = ? 
                               AND 
                                    groupid = 1
                               LIMIT 1");
        $stmt->execute(array($username, $hashedpass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        
        // if count > 0 this mean the database contain record about this username
         
        if ($count > 0) {
            $_SESSION['username'] = $username; // register session name
            $_SESSION['id'] = $row['userid'];
            header('Location: dashboard.php'); // redirect to dashboard page
            exit(); 
        }
    }
?>
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
        
        <h4 class="text-center"><?php echo lang('ADMIN_LOGIN'); ?></h4>
            <hr>
        
            <div class="login-position">
                <span class="login-icon"><i class="fa fa-user"></i></span>
                <input class="form-control" type="text" name="user" placeholder="<?php echo lang('ADMIN_USER_PLACEHOLDER'); ?>" autocomplete="off" />
            </div>

            <div class="login-position">
                <span class="login-icon"><i class="fa fa-lock"></i></span>
                <input class="form-control" type="password" name="pass" placeholder="<?php echo lang('ADMIN_PASS_PLACEHOLDER'); ?>" autocomplete="new-password" />
            </div>
        
        
        <input class="btn btn-primary btn-block" type="submit" value="<?php echo lang('LOGIN'); ?>" />
        <div class="login-visit-shop">
            <hr />
            <span><?php echo lang('OR'); ?></span>
            <a href="../index.php"><?php echo lang('VISIT_SHOP'); ?></a>
        </div>    
        
    </form>

<?php include $tpl . "footer.php"; ?>