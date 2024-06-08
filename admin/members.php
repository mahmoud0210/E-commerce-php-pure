<?php
ob_start();

session_start();

if( isset($_SESSION['username']) ){
    
    $getTitle = 'Members';
    
    include 'init.php';
    
    $do =isset($_GET['do'])?$_GET['do'] : 'Manage';
    
    if($do == 'Manage') {
        
        $orderArray = array('UserID', 'Username', 'Email', 'Fullname');
        $order = isset($_GET['order']) && in_array($_GET['order'],$orderArray)?$_GET['order']:'UserID';
        
        $sort = 'DESC';
        $sortArray = array( 'ASC', 'DESC' );
        if(isset($_GET['sort']) && in_array($_GET['sort'] , $sortArray )){
            $sort =$_GET['sort'];
        }        
        
        $query = '';
        if(isset($_GET['page']) && $_GET['page'] == 'Pending'){
            $query = 'AND RegStatus = 0';
        }elseif(isset($_GET['page']) && $_GET['page'] == 'All'){
            $query = '';
        }
        
        $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 AND UserID != 1 $query ORDER BY $order $sort");
        $stmt->execute();
        $rows = $stmt->fetchAll();
?>


<?php if(!empty($rows)){ ?>
<div class="container">
    <div class="xx">
    <h1 class="text-center custom-h1"><i class="fa fa-users"></i><?php echo lang('MANAGE_MEMBERS'); ?></h1>
    <!--<div class="add-cat">
            <a href="members.php?do=Add" >
                <span><i class="fa fa-plus"></i> Add new member </span>
            </a>
        </div>-->        
    <div class="table-responsive">
        <div class="table-shadow">
        <table class="main-table text-center table table-bordered">
            <tr>
                <td><?php echo lang('#ID'); ?>
                    <div class="order-sort">
                    <a href="members.php?order=UserID&sort=ASC"><i class="fa fa-arrow-up"></i></a>
                    <a href="members.php?order=UserID&sort=DESC"><i class="fa fa-arrow-down"></i></a>
                    </div>                 
                </td>
                <td><?php echo lang('IDENTIFIER_NAME'); ?>
                    <div class="order-sort">
                    <a href="members.php?order=Username&sort=ASC"><i class="fa fa-arrow-up"></i></a>
                    <a href="members.php?order=Username&sort=DESC"><i class="fa fa-arrow-down"></i></a>
                    </div>                
                </td>
                <td><?php echo lang('EMAIL'); ?>
                    <div class="order-sort">
                    <a href="members.php?order=Email&sort=ASC"><i class="fa fa-arrow-up"></i></a>
                    <a href="members.php?order=Email&sort=DESC"><i class="fa fa-arrow-down"></i></a>
                    </div> 
                </td>
                <td><?php echo lang('MEMBER_NAME'); ?>
                    <div class="order-sort">
                    <a href="members.php?order=	Fullname&sort=ASC"><i class="fa fa-arrow-up"></i></a>
                    <a href="members.php?order=	Fullname&sort=DESC"><i class="fa fa-arrow-down"></i></a>
                    </div>                 
                </td>
                <td><?php echo lang('REGISTERD_DATE'); ?>
                    <div class="order-sort">
                    <a href="members.php?order=UserID&sort=ASC"><i class="fa fa-arrow-up"></i></a>
                    <a href="members.php?order=UserID&sort=DESC"><i class="fa fa-arrow-down"></i></a>
                    </div>                  
                </td>
                <td><?php echo lang('CONTROL'); ?>
                    <div class="order-sort">
                    <a href="members.php?page=Pending"><?php echo lang('ACTIVATE'); ?></a>
                    <span>-----</span>
                    <a href="members.php?page=All"><?php echo lang('ALL'); ?></a>
                    </div>
                </td>
            </tr>
            <?php
        foreach($rows as $row){
            echo"<tr>";
                echo"<td>".$row['UserID']."</td>";
                echo"<td>".$row['Username']."</td>";
                echo"<td>".$row['Email']."</td>";
                echo"<td>".$row['Fullname']."</td>";
                echo"<td>".$row['addDate']."</td>";
                echo"<td>
                        <a href='members.php?do=Delete&userid=" .$row['UserID']. "' class='confirm btn btn-danger'><i class='table-btn-icon-position fa fa-close'></i>". lang('DELETE') ."</a>";            
                if($row['RegStatus'] == 0) {
                    echo"<a href='members.php?do=Activate&userid=" .$row['UserID']. "' class=' btn btn-info activate'><i class='table-btn-icon-position fa fa-check'></i>". lang('ACTIVATE') ."</a>";
                }
// <a href='members.php?do=Edit&userid=" .$row['UserID']. "' class='btn btn-success'><i class='table-btn-icon-position fa fa-edit'></i>Edit</a>            
                echo"</td>";
           echo"</tr>";   
        };
            ?>
        </table>
        </div>
    </div>
    <!--<div class="add-cat">
            <a href="members.php?do=Add" >
                <span><i class="fa fa-plus"></i> Add new member </span>
            </a>
        </div>-->
    </div>
</div>
  
    <?php 
          }//end if(!empty($rows))
           else { ?>
                <div class="container">
                <div class="xx">
                    <h1 class="text-center custom-h1"><?php echo lang('MANAGE_MEMBERS') ?></h1> 
                    <div class="alert alert-success"> There's not records to show  </div>
                    <!--<div class="add-cat">
                                <a href="members.php?do=Add" >
                                    <span><i class="fa fa-plus"></i> Add new members</span>
                                </a>
                        </div>-->
                    </div>
               </div>
               
         <?php  }
    ?>
    
 <?php } // end if($do == 'Manage')
    elseif($do == 'Add') { ?>
            <div class="container">
                <div class="xx">
                <h1 class="text-center custom-h1"><i class="fa fa-plus"></i> Add members </h1>

                <form class="form-horizontal" action="?do=Insert" method="post" enctype="multipart/form-data">

    <!--form-horizontal bootstrap class : label in left and input in right -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" name="username" class="form-control" autocomplete="off" required="required" placeholder="Username To Login Into Shop" />
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="password" name="password" class="password form-control" autocomplete="new-password" required="required" placeholder=""/>
                            <i class="show-pass fa fa-eye fa-2x"></i>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="email" name="email" class="form-control" required="required" placeholder=""/>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" name="full" class="form-control" required="required"  placeholder="Full Name Appear In Your Profile Page"/>
                        </div>
                    </div>
                    
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Image</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="file" name="avatar" class="form-control" />
                        </div>
                    </div>                    

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add Member" class="btn btn-primary btn-lg" />
                        </div>
                    </div>

                </form>
            </div>        
      </div>
    <?php }//end if($do == 'Add')
    
    elseif($do == 'Insert') {
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            echo '<div class="container">';
            echo '<div class="xx">';
            echo '<h1 class="text-center custom-h1"> Insert members </h1>';

            $avatarName =  $_FILES['avatar']['name'];
            $avatarType =  $_FILES['avatar']['type'];
            $avatarTmp  =  $_FILES['avatar']['tmp_name'];
            $avatarSize =  $_FILES['avatar']['size'];
            
            $avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");
            
            $avatarNameExplode = explode('.', $avatarName);
            
            $avatarExtention = strtolower(end($avatarNameExplode));
            
            $user    = $_POST['username'];
            $pass    = $_POST['password'];
            $email   = $_POST['email'];
            $name    = $_POST['full'];
            
            $hashPass = sha1($_POST['password']);
            
            //تشكيل مصفوفة لفحص الأخطاء
            $formErrors = array();
            
            if(strlen($user) < 4){
                $formErrors[] ='Username cant be less <strong> than 4 characters </strong>';
            }
            if(strlen($user) > 20){
                $formErrors[] ='Username cant be more <strong> than 20 characters </strong>';
            }
            if(empty($user)){
                $formErrors[] = 'Username cant be <strong>Empty</strong>';
            }
            if(empty($pass)){
                $formErrors[] = 'Password cant be <strong>Empty</strong>';
            }
            if(empty($email)){
                $formErrors[] = 'Email cant be <strong>Empty</strong>';
            }
            if(empty($name)){
                $formErrors[] = 'Full Name cant be <strong>Empty</strong>';
            }
            if(!empty($avatarName) && strlen($avatarName) > 240) {
                $formErrors[] ='Avatar name cant be more <strong> than 240 characters </strong>';
            }              
            if(!empty($avatarName) && !in_array($avatarExtention, $avatarAllowedExtension)){
                $formErrors[] = 'This extension is not <strong>Allowed</strong>';
            }
            if($avatarSize > 4194304){
                $formErrors[] = 'Size must be larger than <strong> 4MB </strong>';
            }

            //إذا ما كان في أخطاء بينففذ الأستعلام 
            if(empty($formErrors)){
                
                
                // check if user exist in database
                $check = checkItem("Username", "users", $user);
                if ($check == 1){
                    
                     echo "<div class='alert alert-danger'>This Username is exist , try with other name</div>";
                    redirectFunction('back');
                    
                }else{
                    if (!empty($avatarName)){
                        
                        $avatar = rand(0,1000000000) . '_' . $avatarName;
                    
                        move_uploaded_file($avatarTmp, 'uploads/avatars/' . $avatar);
                        
                    }else{
                        $avatar = "default_user_avatar.jpg";
                    }
                   
                    
                    $stmt = $con->prepare(" INSERT INTO
                                            users(Username, password, Email, Fullname, RegStatus, addDate, Avatar)
                                            VALUES(:user, :pass, :email, :name, 1, now(), :zavatar)");

                    $stmt->execute(array(
                        'user'  => $user,
                        'pass'  => $hashPass,
                        'email' => $email,
                        'name'  => $name,
                        'zavatar' => $avatar
                    ));

                    echo '<div class="alert alert-success">' . $stmt->rowCount() . ' recourd inserted' . '</div>';                   
                }
                
               
            }//end if(empty($formErrors))
            else{
                foreach($formErrors as $error){
                    echo '<div class="alert alert-danger">' . $error . '</div>';
                    
                }
                redirectFunction('back');
            }
                echo '</div>';
            echo '</div>';
        } //end if($_SERVER['REQUEST_METHOD'] == 'POST')        
        else {
            echo '<div class="container">';
                echo '<div class="xx">';           
                echo '<h1 class="custom-h1 text-center"><i class="fa fa-warning"></i> Access Denied</h1>';
                echo "<div class='alert alert-danger'>you can't browse this page directly</div>";
                redirectFunction('home');
                echo '</div>';
            echo '</div>';
        }
                
    }//end if($do == 'Insert')
    
    elseif($do == 'Edit') {
        
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        
        $userid = $userid == 1? 0 : $userid; 
        $stmt = $con->prepare("SELECT * 
                               FROM users 
                               WHERE UserID = ? 
                               LIMIT 1 ");
        $stmt->execute(array($userid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        
        if($count > 0){ ?>       
    
            <div class="container">
                <div class="xx">
                <h1 class=" custom-h1 text-center"><i class="fa fa-wrench"></i><?php echo lang('EDIT_MEMBER'); ?></h1>                
                
                <form class="form-horizontal" action="?do=Update" method="post" enctype="multipart/form-data">
                    <div class="row">
                    <div class="col-md-3">
                        <div class="thumbnail item-box">
                            <img class="img-responsive" src="uploads/avatars/<?php echo $row['Avatar']; ?>" alt="" />
                        </div>
                        
                    </div>

                    <div class="col-md-9">
        <!--form-horizontal bootstrap class : label in left and input in right -->
                        <input type="hidden" name="userid" value="<?php echo $userid ?>"/>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label"><?php echo lang('IDENTIFIER_NAME'); ?></label>
                            <div class="col-sm-10 col-md-8">
                                <input type="text" name="username" class="form-control" autocomplete="off" value="<?php echo $row['Username'] ?>" required="required"/>
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label"><?php echo lang('PASSWORD'); ?></label>
                            <div class="col-sm-10 col-md-8">
                                <input type="hidden" name="oldpassword" value="<?php echo $row['password'] ?>" />
                                <input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="<?php echo lang('PLACEHOLDER_PASSWORD_MEMBER'); ?>"/>
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label"><?php echo lang('EMAIL'); ?></label>
                            <div class="col-sm-10 col-md-8">
                                <input type="email" name="email" class="form-control" value="<?php echo $row['Email'] ?>" required="required"/>
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label"><?php echo lang('MEMBER_NAME'); ?></label>
                            <div class="col-sm-10 col-md-8">
                                <input type="text" name="full" class="form-control" value="<?php echo $row['Fullname'] ?>" required="required"/>
                            </div>
                        </div>
                        
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label"><?php echo lang('IMAGE'); ?></label>
                            <div class="col-sm-10 col-md-8">
                                <input type="hidden" name="oldavatar" value="<?php echo $row['Avatar'] ?>" />
                                <input type="file" name="newavatar" class="form-control" />
                            </div>
                        </div>                          

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="<?php echo lang('UPDATE'); ?>" class="btn btn-primary btn-lg" />
                            </div>
                        </div>
                    </div>
                        </div>
                </form>
            </div>
        </div>

        <?php 
            } //end if ($count > 0)
            else { 
            echo '<div class="container">';
                echo '<div class="xx">';           
                echo '<h1 class="custom-h1 text-center"><i class="fa fa-warning"></i> Access Denied</h1>';
                echo "<div class='alert alert-danger'>There is not member with this value</div>";
                redirectFunction('home');
                echo '</div>';
            echo '</div>';              
        }
        
    } //end if($do == "Edit")
    elseif ($do == 'Update'){
      
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            
            echo '<div class="container">';
                echo '<div class="xx">';
                    echo '<h1 class="text-center custom-h1">'. lang('UPDATE_MEMBER') .'</h1>';
            
            $id      = $_POST['userid'];
            $user    = $_POST['username'];
            $email   = $_POST['email'];
            $name    = $_POST['full'];
            
            
            $avatarName = $_FILES['newavatar']['name'];
            $avatarSize = $_FILES['newavatar']['size'];
            $avatarTmp  = $_FILES['newavatar']['tmp_name'];
            $avatarType = $_FILES['newavatar']['type'];
                        
            $avatarAllowedExtension = array('jpg', 'png', 'gif', 'jpeg');
            $extensionName = explode('.', $avatarName);
            $avatarExtention = strtolower(end($extensionName));
            
            //يفحص حقل كلمة المرور
            
            $pass = empty( $_POST['newpassword'] ) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);
            
            $stmtCheck = $con->prepare("SELECT
                                            Username
                                        FROM 
                                            users
                                        WHERE
                                            Username = ? AND UserID != ?");
            $stmtCheck->execute(array($user, $id));
            $count =$stmtCheck->rowCount();
            
            //تشكيل مصفوفة لفحص الأخطاء
            $formErrors = array();
            
            if(strlen($user) < 4){
                $formErrors[] ='Username cant be less <strong> than 4 characters </strong>';
            }
            if(strlen($user) > 20){
                $formErrors[] ='Username cant be more <strong> than 20 characters </strong>';
            }
            if(empty($user)){
                $formErrors[] = 'Username cant be <strong>Empty</strong>';
            }
            if(empty($email)){
                $formErrors[] = 'Email cant be <strong>Empty</strong>';
            }
            if(empty($name)){
                $formErrors[] = 'Full Name cant be <strong>Empty</strong>';
            }
            if(!empty($avatarName) && !in_array($avatarExtention, $avatarAllowedExtension)){
                $formErrors[] = 'This extension is not <strong>Allowed</strong>';
            }
            if($avatarSize > 4194304){
                $formErrors[] = 'Size must\'nt be larger than <strong> 4MB </strong>';
            }
            if($count == 1){
                $formErrors[] = 'This user is exist , try with other <strong> username </strong>';
            }
            if(!empty($avatarName) && strlen($avatarName) > 240) {
                $formErrors[] ='Image name cant be more <strong> than 240 characters </strong>';
            }             
            
            
            //إذا ما كان في أخطاء بينففذ الأستعلام 
            if(empty($formErrors)){
                
                if (!empty($avatarName)){

                    $avatar = rand(0,1000000000) . '_' . $avatarName;

                    move_uploaded_file($avatarTmp, 'uploads/avatars/' . $avatar);
                    if($_POST['oldavatar'] != 'default_user_avatar.jpg'){
                        unlink('uploads/avatars/' . $_POST['oldavatar']);
                    }
                }else{
                    $avatar = $_POST['oldavatar'];
                }                
                    
                $stmt = $con->prepare(" UPDATE users
                                        SET Username = ?, Email = ?, Fullname =?, password =?, Avatar =?
                                        WHERE UserID = ? ") ;
                $stmt->execute(array($user ,$email ,$name ,$pass, $avatar, $id));
                echo '<div class="alert alert-success">' . $stmt->rowCount() . ' recourd updated</div>';  
                redirectFunction('back');
            } // if(empty($formErrors))
            else{
                foreach($formErrors as $error){
                    echo '<div class="alert alert-danger">' . $error . '</div>';
                    
                } 
                redirectFunction('back');
            }
                echo '</div>';
            echo '</div>';
           
        } //end if($_SERVER['REQUEST_METHOD'] == 'POST')        
        else {
             echo '<div class="container">';
                echo '<div class="xx">';           
                echo '<h1 class="custom-h1 text-center"><i class="fa fa-warning"></i> Access Denied</h1>';
                echo "<div class='alert alert-danger'><p>you can't browse this page directly</p></div>";
                redirectFunction('home');
                echo '</div>';
            echo '</div>';
        }
        
    }//end if($do == 'Update')
    elseif($do == 'Delete'){
        
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

            $stmt = $con->prepare("SELECT * 
                                   FROM users 
                                   WHERE UserID = ? 
                                   LIMIT 1 ");
            $stmt->execute(array($userid));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();

            if($count > 0){
                echo '<div class="container">';
                echo '<div class="xx">';
                echo '<h1 class="text-center custom-h1">'. lang('DELETE_MEMBER') .'</h1>';                

                $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");
                $stmt->bindParam(":zuser", $userid);
                $stmt->execute();
                
                if($row['Avatar'] == 'default_user_avatar.jpg') {

                }else {
                    unlink('uploads/avatars/' . $row['Avatar']);
                }
                
                echo '<div class="alert alert-success">' . $stmt->rowCount() . ' recourd deleted' . '</div>';
                redirectFunction('back');
                echo '</div>';
                echo '</div>';
            }else{
            echo '<div class="container">';
                echo '<div class="xx">';           
                echo '<h1 class="custom-h1 text-center"><i class="fa fa-warning"></i> Access Denied</h1>';
                echo "<div class='alert alert-danger'>There is't user with this value</div>";
                redirectFunction('home');
                echo '</div>';
            echo '</div>';
            }
    }//end if($do == 'Delete')

    elseif($do == 'Activate'){

        
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

            $stmt = $con->prepare("SELECT * 
                                   FROM users 
                                   WHERE UserID = ? 
                                   LIMIT 1 ");
            $stmt->execute(array($userid));
            $count = $stmt->rowCount();

            if($count > 0){
                echo '<div class="container">';
                echo '<div class="xx">';
                echo '<h1 class="text-center custom-h1">'. lang('ACTIVATE_MEMBER') .'</h1>';                

                $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");    
                $stmt->execute(array($userid));
                echo '<div class="alert alert-success">' . $stmt->rowCount() . ' recourd updated' . '</div>';
                redirectFunction('back');
                echo '</div>';
                echo '</div>';
            }else{
            echo '<div class="container">';
                echo '<div class="xx">';           
                echo '<h1 class="custom-h1 text-center"><i class="fa fa-warning"></i> Access Denied</h1>';
                echo "<div class='alert alert-danger'>There is't user with this value</div>";
                redirectFunction('home');
                echo '</div>';
            echo '</div>';
            }
    }//end if($do == 'Delete')
    
    include $tpl . 'footer.php';
    
} else { // end  if (isset($_SESSION['username']) )    
    header('location: index.php');
    exit();
}
ob_end_flush(); // release the output
?>