<?php 
ob_start(); // output buffering start

    session_start();


    if( isset($_SESSION['user']) || isset($_SESSION['shopid']) ){
        header('Location: index.php');
        exit();
    }

    $getTitle = 'Login';

    include 'init.php';
    

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $displayClass = '';
        
        if(isset($_POST['login'])){
            
            $displayClass = 'login';
            
            $formErrors = array();
            
            $email = $_POST['email'];
            $pass = $_POST['password'];
            
            $filterdLoginEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
            $filterdPass = filter_var($pass, FILTER_SANITIZE_STRING);
            $hashpass = sha1($pass);
            
            if($filterdLoginEmail === "admin@admin.com" && $filterdPass === "admin"){
                header("location:admin/index.php");
                exit();
            }
            
            if(empty($filterdLoginEmail)){
                $formErrors[] = 'Email cant be <strong>Empty</strong>';
            }elseif(filter_var($filterdLoginEmail, FILTER_VALIDATE_EMAIL) != true) {
                $formErrors[] = 'Email isn\'t <strong>Valide</strong>';
            }
            if(empty($filterdPass)){
                $formErrors[] = 'Password cant be <strong>Empty</strong>';
            }                
            
            elseif(isset($_POST['type']) && $_POST['type'] == 0) {
                
                if(empty($formErrors)) {
                    
                    $stmt = $con->prepare('SELECT * FROM users WHERE Email = ? AND password = ? AND GroupID = ?');
                    $stmt->execute(array($filterdLoginEmail ,$hashpass, 0));
                    $count = $stmt->rowCount();

                    if($count > 0){
                        $get = $stmt->fetch();

                        $_SESSION['user'] = $get['Username']; // register session name
                        $_SESSION['uid'] = $get['UserID'];
                        header('Location: index.php'); 
                        exit();
                        
                    }else{                
                        $formErrors['type'] = 'login';
                        $formErrors[] = 'Email or Password is <strong>Error</strong>';
                    }   
                }else {
                    
                    $formErrors['type'] = 'login';
                }
                
            }//  end if($_POST['type'] == 0) --> user
            elseif(isset($_POST['type']) && $_POST['type'] == 1) {

                if(empty($formErrors)) {
                    
                    $stmt = $con->prepare('SELECT * FROM shops WHERE Email = ? AND Password = ?');
                    $stmt->execute(array($filterdLoginEmail ,$hashpass));
                    $count = $stmt->rowCount();

                    if($count > 0){
                        $get = $stmt->fetch();
                        
                        if($get['RegStatus'] == 1 ) {                            
                            $_SESSION['shopid'] = $get['Shop_ID'];
                            header('Location: index.php'); 
                            exit();                            
                        }else{
                            $formErrors['type'] = 'login';
                            $formErrors[] = 'Dear <strong>'. $get['Email'].'</strong> <br />Your Account Is Waiting Approve From Admin <br /> When Admin Approve It You Can Login ';       
                        }
                
                        
                    }else{                
                        $formErrors['type'] = 'login';
                        $formErrors[] = 'Email or Password is <strong>Error</strong>';
                    }   
                }else {
                    
                    $formErrors['type'] = 'login';
                }            
            }
            elseif(isset($_POST['type']) && $_POST['type'] == 2) {

                if(empty($formErrors)) {
                    
                    $stmt = $con->prepare('SELECT * FROM distributos WHERE Email = ? AND Password = ?');
                    $stmt->execute(array($filterdLoginEmail ,$hashpass));
                    $count = $stmt->rowCount();

                    if($count > 0){
                        $get = $stmt->fetch();
                        $formErrors['type'] = 'login';
                        $formErrors[] = ' <strong> SUCCESS</strong>';                        
                        //echo $get['Dist_Name'] . '  ' . $get['Email'] . '  ' . $get['Password'];
                        
                        /*
                        $_SESSION['user'] = $get['Username']; // register session name
                        $_SESSION['uid'] = $get['UserID'];
                        header('Location: index.php'); 
                        exit();
                        */
                        
                    }else{                
                        $formErrors['type'] = 'login';
                        $formErrors[] = 'Email or Password is <strong>Error</strong>';
                    }   
                }else {
                    
                    $formErrors['type'] = 'login';
                }            
            }else {
                // اذا حاول يشي الشيك بوكس 
                $formErrors['type'] = 'login';
                $formErrors[] = 'You Must Chosse <strong>Login Type</strong>';
            }

        }// end isset($_POST['login'])
        elseif(isset($_POST['signup'])){
            
            $success = array();
                
            $displayClass ='signup';
            
            if (isset($_POST['user'])) {
                
                //تسجبل الدخول كمستخدم عادي 
                $nameX = $_POST['username'];
                $full  = $_POST['fullname'];
                $pass  = $_POST['password'];
                $pass2 = $_POST['password2'];
                $email = $_POST['email'];

                $filterdName = filter_var($nameX, FILTER_SANITIZE_STRING);
                $filterdFullName = filter_var($full, FILTER_SANITIZE_STRING);
                // ما عملنا فيلتر للباس ورد لأنو الباس عم أعملا هاش 
                $hashPass = sha1($_POST['password']);
                $hashPass2 = sha1($_POST['password2']);
                $filterdEamil = filter_var($email, FILTER_SANITIZE_EMAIL);

                $formErrors = array();

                $length = strlen($filterdName);
                for($i=0; $i <$length;$i++){
                    if($filterdName[$i] == ' '){
                        $formErrors[] = 'Username cant be include <strong>space</strong>';
                        break;
                    }
                }


                if(empty($filterdName)){
                    $formErrors[] = 'Username cant be <strong>Empty</strong>';
                }elseif(strlen($filterdName) < 4){
                    $formErrors[] ='Username cant be less <strong> than 4 characters </strong>';
                }elseif(strlen($filterdName) > 20){
                    $formErrors[] ='Username cant be more <strong> than 20 characters </strong>';
                }

                if(empty($filterdFullName)){
                    $formErrors[] = 'Full name cant be <strong>Empty</strong>';
                }elseif(strlen($filterdFullName) < 4){
                    $formErrors[] ='Full name cant be less <strong> than 4 characters </strong>';
                }elseif(strlen($filterdFullName) > 20){
                    $formErrors[] ='Full name cant be more <strong> than 20 characters </strong>';
                } 

                if (is_numeric($filterdName)){
                    $formErrors[] ='Username cant be  <strong> number </strong>';
                }
                if (is_numeric($filterdFullName)){
                    $formErrors[] ='Full name cant be  <strong> number </strong>';
                }

                if(empty($pass)){
                    $formErrors[] = 'Password cant be <strong>Empty</strong>';
                }elseif($hashPass !== $hashPass2){
                    $formErrors[] = 'Password isn\'t <strong>Match</strong> ';
                }

                if(empty($filterdEamil)){
                    $formErrors[] = 'Email cant be <strong>Empty</strong>';
                }elseif(filter_var($filterdEamil, FILTER_VALIDATE_EMAIL) != true) {
                    $formErrors[] = 'Email isn\'t <strong>Valide</strong>';
                }

                if(empty($formErrors)){


                    // check if user exist in database
                    $check = checkItem("Username", "users", $filterdName);
                    if ($check == 1){

                        $formErrors[] = " This user is exist , try with other username ";
                        $formErrors['type'] = "user" ;
                    }

                    else{

                        $stmt = $con->prepare(" INSERT INTO
                                        users(Username, password, Email, Fullname, RegStatus, addDate, Avatar)
                                        VALUES(:user, :pass, :email, :full, 0, now(), :avatar)");

                        $stmt->execute(array(
                            'user'  => $filterdName,
                            'pass'  => $hashPass,
                            'email' => $filterdEamil,
                            'full'  => $filterdFullName,
                            'avatar'=> 'default_user_avatar.jpg'
                        ));

                        $success['user'] = '<div class="alert alert-success">You Are Now Registerd User </div>';                    
                    }

                }//end if(empty($formErrors))
                else{
                    $formErrors['type'] = "user" ;                   
                }
                
            }//if (isset($_POST['user']))
            elseif (isset($_POST['store'])) {
                
                $shopName   = $_POST['shopname'];
                $password   = $_POST['password'];
                $password2  = $_POST['password2'];
                $email      = $_POST['email'];
                
                
                $filterdNameShop    = filter_var($shopName, FILTER_SANITIZE_STRING);
                // ما عملنا فيلتر للباس ورد لأنو الباس عم أعملا هاش 
                $hashPass           = sha1($_POST['password']);
                $hashPass2          = sha1($_POST['password2']);
                $filterdEamilShop   = filter_var($email, FILTER_SANITIZE_EMAIL);

                $formErrors = array();


                if(empty($filterdNameShop)){
                    $formErrors[] = 'Shop name cant be <strong>Empty</strong>';
                }elseif(strlen($filterdNameShop) < 4){
                    $formErrors[] ='Shop name cant be less <strong> than 4 characters </strong>';
                }elseif(strlen($filterdNameShop) > 255){
                    $formErrors[] ='Shop name cant be more <strong> than 20 characters </strong>';
                }

                if(empty($password)){
                    $formErrors[] = 'Password cant be <strong>Empty</strong>';
                }elseif($hashPass !== $hashPass2){
                    $formErrors[] = 'Password isn\'t <strong>Match</strong> ';
                }

                if(empty($filterdEamilShop)){
                    $formErrors[] = 'Email cant be <strong>Empty</strong>';
                }elseif(filter_var($filterdEamilShop, FILTER_VALIDATE_EMAIL) != true) {
                    $formErrors[] = 'Email isn\'t <strong>Valide</strong>';
                }

                if(empty($formErrors)){

                    $stmt = $con->prepare(" INSERT INTO
                                    shops(Shop_Name, password, Email, RegStatus, addDate, Avatar)
                                    VALUES(:shop, :pass, :email, 0, now(), :avatar)");

                    $stmt->execute(array(
                        'shop'  => $filterdNameShop,
                        'pass'  => $hashPass,
                        'email' => $filterdEamilShop,
                        'avatar'=> 'store.png'
                    ));

                    $success['store'] = '<div class="alert alert-success">You Are Now Shop Owrner </div>';
                }//end if(empty($formErrors))
                else{
                    $formErrors['type'] = "store" ;                   
                }
                
            }
            // end if (isset($_POST['store']))
            elseif (isset($_POST['dist'])) {
           
                $distName   = $_POST['distname'];
                $password   = $_POST['password'];
                $password2  = $_POST['password2'];
                $email      = $_POST['email'];
                
                
                $filterdNameDist    = filter_var($distName, FILTER_SANITIZE_STRING);
                // ما عملنا فيلتر للباس ورد لأنو الباس عم أعملا هاش 
                $hashPass           = sha1($_POST['password']);
                $hashPass2          = sha1($_POST['password2']);
                $filterdEamilDist  = filter_var($email, FILTER_SANITIZE_EMAIL);

                $formErrors = array();


                if(empty($filterdNameDist)){
                    $formErrors[] = ' Distributor name cant be <strong>Empty</strong>';
                }elseif(strlen($filterdNameDist) < 4){
                    $formErrors[] =' Distributor name cant be less <strong> than 4 characters </strong>';
                }elseif(strlen($filterdNameDist) > 255){
                    $formErrors[] =' Distributor name cant be more <strong> than 20 characters </strong>';
                }

                if(empty($password)){
                    $formErrors[] = 'Password cant be <strong>Empty</strong>';
                }elseif($hashPass !== $hashPass2){
                    $formErrors[] = 'Password isn\'t <strong>Match</strong> ';
                }

                if(empty($filterdEamilDist)){
                    $formErrors[] = 'Email cant be <strong>Empty</strong>';
                }elseif(filter_var($filterdEamilDist, FILTER_VALIDATE_EMAIL) != true) {
                    $formErrors[] = 'Email isn\'t <strong>Valide</strong>';
                }

                if(empty($formErrors)){

                    $stmt = $con->prepare(" INSERT INTO
                                    distributos(Dist_Name, password, Email, RegStatus, addDate, Avatar)
                                    VALUES(:shop, :pass, :email, 0, now(), :avatar)");

                    $stmt->execute(array(
                        'shop'  => $filterdNameDist,
                        'pass'  => $hashPass,
                        'email' => $filterdEamilDist,
                        'avatar'=> 'default_user_avatar.jpg'
                    ));

                    $success['dist'] = '<div class="alert alert-success">You Are Now Distributor </div>';
                }//end if(empty($formErrors))
                else{
                    $formErrors['type'] = "dist" ;                   
                }
                                
            }
        }
        // end if(isset($_POST['signup']))
    }// end if($_SERVER['REQUEST_METHOD'] == 'POST')
$spanClass = '';  
if(isset($displayClass) && $displayClass == 'login'){
    $spanClass = 'login';
}elseif(isset($displayClass) && $displayClass == 'signup'){
    $spanClass = 'signup';
}
?>


<div class="container login-signup text-center">
<div class="xx" <?php if($spanClass=='signup'){echo'style="background-color: #6cbd6c"';} ?> >    
    <h1 class="text-center">
        <span <?php if($spanClass=='' or $spanClass=='login'){echo 'class="show-form"';} ?> data-class="login">Login</span> |
        <span <?php if($spanClass=='signup'){echo 'class="show-form"';} ?> data-class="signup">Signup</span>
    </h1>
    
    <!-- start login form -->
    <div class="row">
 
        <div class="col-md-12 login form" <?php if($spanClass=='signup'){echo'style="display: none"';} ?>>
            <form class=""  action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                <div class="input-position">
                    <input 
                           name="email"
                           type="email" 
                           autocomplete="on" 
                           class="form-control"
                           placeholder="Email to login"
                           required />
                </div>
                <div class="input-position">
                    <input 
                           name="password"
                           type="password"
                           autocomplete="new-password"
                           class="form-control"
                           placeholder="Password"
                           required />
                </div>
                <div class="input-group login-type">
                    <div class="">
                        <input name="type" type="radio" value="0" id="user-login" checked />
                        <label for="user-login"> User </label>
                    </div>
                    <div class="">
                        <input name="type" type="radio" value="1" id="shop-login" />
                        <label for="shop-login"> Shop Owner </label>
                    </div>
                    <div class="">
                        <input name="type" type="radio" value="2" id="dist-login" />
                        <label for="dist-login"> Distributor </label>
                    </div>
                </div>
                <input name="login" type="submit" class="btn btn-primary btn-block" value="Login" />
            </form>
            <div class="the-errors text-center">
                <?php 
                if(isset($formErrors) && !empty($formErrors) && $formErrors['type'] == 'login' ){
                    foreach($formErrors as $error){
                        if($error != 'login') {
                            echo '<div class="alert alert-danger">' . $error . '</div>';
                        }
                    }
                }
                ?>
            </div>        
        </div> 
    </div>


    <!-- end login form -->
    
    <!-- start signup form -->
    <div class="signup form" <?php if($spanClass=='signup'){echo'style="display: block"';} ?> >
        
        <div class="row signup-grey">
            
            <div class="col-md-6">
                <i class="fa fa-user fa-4x fa-fw center-block"></i>
                <h3 class="text-center">Sign Up As User</h3>
                <p class="lead">ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursuDuis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla. Class apten</p>
            </div>            
            <div class="col-md-6">
                <form class="" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                    <div class="input-position">
                        <input 
                               pattern=".{4,20}"
                               title="Username must be between 4 $ 20 characters"
                               name="username"
                               type="text" 
                               autocomplete="off" 
                               class="form-control"
                               placeholder="[User Name] this name is used to login"
                               value="<?php if(isset($filterdName)){echo $filterdName;} ?>"
                               required />
                    </div>
                     <div class="input-position">   
                        <input 

                               name="fullname"
                               type="text" 
                               autocomplete="off" 
                               class="form-control"
                               placeholder="[Full Name] this name will appeare in you profile page"
                               value="<?php if(isset($filterdFullName)){echo $filterdFullName;} ?>"
                               required />
                    </div>
                    <div class="input-position">
                        <input 
                               minlength="4"
                               name="password"
                               type="password"
                               autocomplete="new-password"
                               class="form-control" 
                               placeholder="Password"
                               required />
                    </div>
                    <div class="input-position">
                        <input 
                               minlength="4"
                               name="password2"
                               type="password"
                               autocomplete="new-password"
                               class="form-control" 
                               placeholder="Password"
                               required />
                    </div>
                    <div class="input-position">
                        <input 
                               type="email" 
                               name="email" 
                               autocomplete="on" 
                               class="form-control" 
                               placeholder="Email"
                               value="<?php if(isset($filterdEamil)){echo $filterdEamil;} ?>"
                               required />
                    </div>
                    <input type="hidden" name="user" />
                    <input name="signup" type="submit" class="btn btn-success btn-block" value="Signup As User" />
                </form> 
                <div class="the-errors text-center">
                    <?php 
                    if(isset($formErrors) && !empty($formErrors) && $formErrors['type'] == 'user' ){
                        foreach($formErrors as $error){
                            if($error != 'user') {
                                echo '<div class="alert alert-danger">' . $error . '</div>';
                            }
                        }
                    }

                    if(isset($success['user'])){
                        echo $success['user'];
                    }
                    ?>
                </div>
            </div>

        </div>
        
        <div class="row signup-green">
            
            <div class="col-md-6 col-md-push-6">
                <img src="admin/uploads/login/store.png" alt="store" />
                <h3 class="text-center">Sign Up As Shop Owner</h3>
                <p class="lead">ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursuDuis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla. Class apten</p>
            </div>            
            <div class="col-md-6 col-md-pull-6">
                <form class="" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                    <div class="input-position">
                        <input 
                               name="shopname"
                               type="text" 
                               autocomplete="off" 
                               class="form-control"
                               placeholder="Name Of the Shop"
                               value="<?php if(isset($filterdNameShop)){echo $filterdNameShop;} ?>"
                               required />
                    </div>
                    <div class="input-position">
                        <input 
                               minlength="4"
                               name="password"
                               type="password"
                               autocomplete="new-password"
                               class="form-control" 
                               placeholder="Password"
                               required />
                    </div>
                    <div class="input-position">
                        <input 
                               minlength="4"
                               name="password2"
                               type="password"
                               autocomplete="new-password"
                               class="form-control" 
                               placeholder="Retype Password"
                               required />
                    </div>
                    <div class="input-position">
                        <input 
                               type="email" 
                               name="email" 
                               autocomplete="on" 
                               class="form-control" 
                               placeholder="Email"
                               value="<?php if(isset($filterdEamilShop)){echo $filterdEamilShop;} ?>"
                               required />
                    </div>
                    <input type="hidden" name="store" />
                    <input name="signup" type="submit" class="btn btn-success btn-block" value="Signup As Shop Owner" />
                </form>
                <div class="the-errors text-center">
                    <?php 
                    if(isset($formErrors) && !empty($formErrors) && $formErrors['type'] == 'store' ){
                        foreach($formErrors as $error){
                            if($error != 'store') {
                                echo '<div class="alert alert-danger">' . $error . '</div>';
                            }
                        }
                    }

                    if(isset($success['store'])){
                        echo $success['store'];
                    }
                    ?>
                </div>                
            </div>

        </div>
        
        <div class="row signup-grey">
            
            <div class="col-md-6">
                <i class="fa fa-truck fa-4x fa-fw center-block"></i>
                <h3 class="text-center">Sign Up As Distributor</h3>
                <p class="lead">ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursuDuis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla. Class apten</p>
            </div>            
            <div class="col-md-6">
                <form class="" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                    <div class="input-position">
                        <input 
                               name="distname"
                               type="text" 
                               autocomplete="off" 
                               class="form-control"
                               placeholder="Name Of the Distributor"
                               value="<?php if(isset($filterdNameDist)){echo $filterdNameDist;} ?>"
                               required />
                    </div>
                    <div class="input-position">
                        <input 
                               minlength="4"
                               name="password"
                               type="password"
                               autocomplete="new-password"
                               class="form-control" 
                               placeholder="Password"
                               required />
                    </div>
                    <div class="input-position">
                        <input 
                               minlength="4"
                               name="password2"
                               type="password"
                               autocomplete="new-password"
                               class="form-control" 
                               placeholder="Retype Password"
                               required />
                    </div>
                    <div class="input-position">
                        <input 
                               type="email" 
                               name="email" 
                               autocomplete="on" 
                               class="form-control" 
                               placeholder="Email"
                               value="<?php if(isset($filterdEamilDist)){echo $filterdEamilDist;} ?>"
                               required />
                    </div>
                    <input type="hidden" name="dist" />
                    <input name="signup" type="submit" class="btn btn-success btn-block" value="Signup As Distributor" />
                </form> 
                <div class="the-errors text-center">
                    <?php 
                    if(isset($formErrors) && !empty($formErrors) && $formErrors['type'] == 'dist' ){
                        foreach($formErrors as $error){
                            if($error != 'dist') {
                                echo '<div class="alert alert-danger">' . $error . '</div>';
                            }
                        }
                    }

                    if(isset($success['dist'])){
                        echo $success['dist'];
                    }
                    ?>
                </div>                 
            </div>

        </div>
        
    </div>

    <!-- end signup form -->

</div>   
</div>

<?php
    include $tpl . "footer.php";

ob_end_flush(); // release the output
?>