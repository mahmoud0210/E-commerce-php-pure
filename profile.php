<?php
ob_start();
    session_start();

    $getTitle = 'Profile';

    include "init.php";
    if(isset($_SESSION['uid'])){
        
        $getUser = $con->prepare("SELECT * FROM users WHERE UserID = ?");
        $getUser->execute(array($_SESSION['uid']));
        $info = $getUser->fetch();
        
        $pn = isset($_GET['pn']) && is_numeric($_GET['pn'])? intval($_GET['pn']) : 1;        
        $numProduct = 4;
        
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
        
        if($do == 'Manage') {
            $countNotifItem = getOneFrom('count(Notif_ID) as number', 'user_notif_item', "WHERE User_ID={$_SESSION['uid']} AND Readed = 0", 'Notif_ID');    
            $countNotifComm = getOneFrom('count(Notif_ID) as number', 'user_notif_comment', "WHERE User_ID={$_SESSION['uid']} AND Readed = 0", 'Notif_ID'); 
            
            $countNotif['number'] = $countNotifItem['number'] + $countNotifComm['number'];
        
?>
<div class="container">
<div class="xx profile-panel-heading-icon">
    
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-home fa-fw"></i>Index</a></li>
        <li class="active">Profile</li>
    </ol>        
    
    <div class="information block">
            <div class="panel panel-primary">
                <div class="panel-heading">My information
                    <a href="?do=Edit">
                        <span class="pull-right">Edit<i class="fa fa-wrench"></i></span>
                    </a>    
                        <span class="pull-right separet">|</span>
                    <a href="?do=Notif">
                        <span class="pull-right">
                            Notification<i class="fa  fa-bell"></i>
                            <?php echo $countNotif['number'] > 0 ? '<span class="number-notif">' . $countNotif['number'] . '</span>' : '' ?>
                        </span>
                    </a>        
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-7">
                            <ul class="list-unstyled">
                                
                                <li>
                                    <i class="fa fa-user fa-fw"></i>
                                    <span>Full Name </span>: <?php echo $info['Fullname']; ?>
                                </li>
                                
                                <li>
                                    <i class="fa fa-id-card fa-fw"></i>
                                    <span>Identifier Name</span>: <?php echo $info['Username']; ?> 
                                </li>

                                <li>
                                    <i class="fa fa-map-marker  fa-fw"></i>
                                    <span>Adress </span>: <?php echo $info['Adress']; ?>
                                </li>                            

                                <li>
                                    <i class="fa fa-envelope-o  fa-fw"></i>
                                    <span>Email </span>: <?php echo $info['Email']; ?>
                                </li>

                                <li>
                                    <i class="fa fa-phone fa-fw"></i>
                                    <span>Phone Number </span>: 
                                    <?php 
                                            if($info['Phone_Number'] != 0) {
                                                echo $info['Phone_Number'];   
                                            }
                                    ?>
                                </li>                            

                                <li>
                                    <i class="fa fa-calendar fa-fw"></i>
                                    <span>Regester Date </span>: <?php echo $info['addDate']; ?> 
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-5">
                            <div class="user-image">
                                <div class="thumbnail" style="margin-bottom : 0px">
                                    <img class="img-responsive" style="max-height:265px" src="admin/uploads/avatars/<?php echo $info['Avatar'] ?>" />                                
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
    </div>
<?php 
                                        
  $allBuy = getAllFrom("*" ,"orders", "WHERE Buyer_ID = {$info['UserID']}", "Order_ID", "DESC LIMIT 7");
    if(!empty($allBuy)){
?>
    
<div class="my-buys">
    <h1 class="text-center "> Procurement table </h1>
    <div class="show-more">
        <a class="rotate-link" title="Show All Procurement List" href="?do=Buy"> <i class="fa fa-angle-double-down"></i></a >
    </div>    
    <div class="table-responsive">
        <div class="table-shadow">
            <table class="main-table text-center table ">
                <tr class="table-head">
                    <td>
                        Item Name
                    </td>
                    <td>
                        Item Desciption
                    </td>
                    <td>
                        Shop Name
                    </td>
                    <td>
                        Price
                    </td>
                    <td>
                        Quantity
                    </td>
                    <td>
                        Total Price
                    </td>
                    <td>
                        Buy Date
                    </td>
                </tr>
                <?php
            foreach($allBuy as $row){

                $shop = getOneFrom('Shop_ID, Shop_Name', 'shops', "WHERE Shop_ID ={$row['Seller_ID']}", "Shop_ID");
                echo"<tr class='table-body'>";
                    echo"<td>".$row['Item_Name']."</td>";
                    echo"<td><p>".$row['Item_Desc']."</p></td>";
                    echo"<td>".$shop['Shop_Name']."</td>";
                    echo"<td>".$row['Item_Price']."</td>";
                    echo"<td>".$row['Item_Quantity']."</td>";
                    echo"<td>" . ($row['Item_Price'] * $row['Item_Quantity']) . "</td>";
                    echo"<td>" . $row['Buy_Date'] . "</td>";
               echo"</tr>";   
            };
                ?>
            </table>
        </div>
    </div>
    

</div>
    
<?php    
        
    }        
    
?>                                        


    <div class="my-comments block ">
        <h1 class="text-center">My Comment</h1>
        <div class="comments-container">
        <div class="row">

            <?php

    $allComments = getAllFrom("*", "comments", "WHERE user_id ={$info['UserID']} AND status = 1", "c_id", "DESC ");
                if(!empty($allComments)){
                echo '<div class="col-md-6">';
                    echo '<span class="approve comment-status text-center"><i class="fa fa-check"></i> Approved</span>';
                foreach($allComments as $comment){
            ?>
                    <div class="comment-box " >
                        <div class="">
                            <p class="second-span">
                                <a href="items.php?itemid=<?php echo $comment['item_id']?>#<?php echo $comment['user_id']?>"><?php echo $comment['comment'] ?> </a>
                            </p>
                        </div>
                    </div>
                    <hr class="custom-hr">
            <?php }
                echo'</div>';
                }else{
                    echo '<div class="col-md-6">';
                    echo"<span class='approve comment-status text-center'><i class='fa fa-check'></i> Approved  </span>";

                    echo"<span class='approve text-center'>  There isn't comment to show </span>";
                    echo'</div>';                
                }
                ?>

                <?php

    $allComments = getAllFrom("*", "comments", "WHERE user_id ={$info['UserID']} AND status = 0", " c_id ", "DESC LIMIT 7");
                if(!empty($allComments)){
                echo '<div class="col-md-6">';
                    echo '<span class="waiting-approve comment-status text-center"><i class="fa fa-close"></i> Waiting Approve</span>';
                foreach($allComments as $comment){ ?>
                    <div class="comment-box " >
                        <div class="">
            <p class="second-span"><a href="items.php?itemid=<?php echo $comment['item_id']?>"><?php echo $comment['comment'] ?> </a></p>
                        </div>
                    </div>
                    <hr class="custom-hr">
            <?php }
                echo'</div>';
                }else{
                    echo '<div class="col-md-6">';
                    echo"<span class='waiting-approve comment-status text-center'><i class='fa fa-close'></i> Waiting Approve  </span>";

                    echo"<span class='waiting-approve text-center'>  There isn't comment to show </span>";
                    echo'</div>';
                }
                ?>                

        </div>
        </div>
    </div>
</div>
</div>
<?php
            }// end if($do == "Manage")
            elseif($do == "Edit") {
 
?>      

<div class="container">
    <div class="xx profile-panel-heading-icon">
        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-home fa-fw"></i>Index</a></li>
            <li><a href="profile.php"><i class="fa fa-user fa-fw"></i>Profile</a></li>
            <li class="active">Edit</li>
        </ol> 
        <form action="?do=Update" method="post" enctype="multipart/form-data">
            <div class="information ">
                <div class="panel">
                    <div class="panel-heading">Change My information    
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-7">
                                <ul class="list-unstyled">
                                    <li>
                                        <div class="">
                                            <label>
                                                <i class="fa fa-user fa-fw"></i>
                                                <span>Full Name</span>:
                                            </label>
                                            <input 
                                                   type="text" 
                                                   class="form-control" 
                                                   name="fullname"
                                                   value="<?php echo $info['Fullname']; ?>"
                                                    />
                                        </div>
                                    </li>

                                    <li>
                                        <div class="">
                                            <label>
                                                <i class="fa fa-id-card fa-fw"></i>
                                                <span>Identifier Name</span>:
                                            </label>
                                            <input 
                                                   type="text" 
                                                   class="form-control"
                                                   name="loginname"
                                                   value="<?php echo $info['Username']; ?>"
                                                   placeholder="customer can find you with this unrepeated name"
                                                   />
                                        </div>                                        
                                    </li>

                                    <li>
                                        <div class="">
                                            <label>
                                                <i class="fa fa-map-marker  fa-fw"></i>
                                                <span>Adress </span>:
                                            </label>
                                            <input 
                                                   type="text" 
                                                   class="form-control"
                                                   name="adress"
                                                   value="<?php echo $info['Adress']; ?>"
                                                   placeholder="Your Adress"
                                                   />
                                        </div>
                                    </li>
                                    
                                    <li>
                                        <div class="">
                                            <label>
                                                <i class="fa fa-key  fa-fw"></i>
                                                <span>Password </span>:
                                            </label>
                                            <input 
                                                   type="text" 
                                                   class="form-control"
                                                   name="new_pass"
                                                   value=""
                                                   placeholder="Your New Password"
                                                   />                                            
                                        </div>
                                    </li>                                    

                                    <li>
                                        <div class="">
                                            <label>
                                                <i class="fa fa-envelope-o  fa-fw"></i>
                                                <span>Email </span>:
                                            </label>
                                            <input
                                                   type="email"
                                                   class="form-control"
                                                   name="email"
                                                   value=" <?php echo $info['Email']; ?>"
                                                    />
                                        </div>
                                    </li>

                                    <li>
                                        <div class="">
                                            <label>
                                                <i class="fa fa-phone  fa-fw"></i>
                                                <span>Phone Number </span>:
                                            </label>
                                            <input
                                                   type="text"
                                                   class="form-control"
                                                   name="phonenumber"
                                                   value=" <?php echo $info['Phone_Number']; ?>"
                                                   placeholder="Exm : 09 xxxx xxxx"
                                                   />
                                        </div>
                                    </li>

                                </ul>
                            </div>
                            <div class="col-md-5">
                                <div class="shop-image">
                                    <div class="thumbnail" style="margin-bottom : 0px">
                                        <img class="img-responsive" style="max-height:265px" src="admin/uploads/avatars/<?php echo $info['Avatar'] ?>" />
                                    </div>
                                    <input type="file" name="image" class="form-control" />
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
              
            <input type="submit" value="Update Information" class="btn form-cotrol update-shop-info"/>
            
        </form>    
    </div>
</div>


<?php 
            
            } // if($do == "Edit")
            elseif($do == "Update") {
                    
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
 ?>               
                <div class="container">
                    <div class="xx">
                        <h1 class="custom2-h1 text-center">Update Information</h1>                        
                        <ol class="breadcrumb">
                            <li><a href="index.php"><i class="fa fa-home fa-fw"></i>Index</a></li>
                            <li><a href="profile.php"><i class="fa fa-user fa-fw"></i>Profile</a></li>
                            <li><a href="profile.php?do=Edit"><i class="fa fa-wrench fa-fw"></i>Edit</a></li>
                            <li class="active">Update</li>
                        </ol>
                        <div class="information-alert">
<?php          
            $userid         = $_SESSION['uid'];
            $fullname       = $_POST['fullname'];
            $loginname      = $_POST['loginname'];
            $adress         = $_POST['adress'];
            $newpass        = $_POST['new_pass'];    
            $email          = $_POST['email'];
            $phonenumber    = $_POST['phonenumber'];   
                
            $fullNameV      = filter_var($fullname, FILTER_SANITIZE_STRING);
            $loginNameV     = filter_var($loginname, FILTER_SANITIZE_STRING);
            $adressV        = filter_var($adress, FILTER_SANITIZE_STRING);
            $emailV         = filter_var($email, FILTER_SANITIZE_EMAIL);    
            $phoneNumberV   = filter_var($phonenumber, FILTER_SANITIZE_NUMBER_INT);
            
            $iden           = getOneFrom("UserID,Username", "users","WHERE (UserID != {$_SESSION['uid']}) AND (Username = '{$loginname}')", "UserID");
                
            $old            = getOneFrom("*", "users", "WHERE UserID={$_SESSION['uid']}", "UserID"); 
            $passhash       = sha1($newpass);            
            $pass           = empty($newpass)?$old['password']:$passhash;    
                            
            $old_image       = $old['Avatar'];
                
            $newImageName    = $_FILES['image']['name'];
            $newImageNameTmp = $_FILES['image']['tmp_name'];
            $newImageSize    = $_FILES['image']['size'];
            $newImageType    = $_FILES['image']['type'];
                
            $allowedImageExtention = array('jpg', 'jpeg', 'png', 'gif');
            
            $imageNameExploded = explode('.', $newImageName);
                
            $imageExtention = strtolower(end($imageNameExploded));
                
            //echo strlen($shopNameV) . '<br />'. $idenNameV .'<br />'. $adressV .'<br />'. $emailV .'<br />'. $phoneNumberV .'<br />'.$descV .' <br />' .$pass . '<br />'.$newImageSize .'<br/>'.$newImageName;                
                
            //تشكيل مصفوفة لفحص الأخطاء
            $formErrors = array();
            
            if(empty($fullNameV)){
                $formErrors[] ='Full Name can\'t be  <strong> Empty </strong>';
            }
            if(strlen($fullNameV) < 5){
                $formErrors[] ='Full Name can\'t be  less than <strong> 5 characters </strong>';
            }                
            if(!empty($loginNameV) && strlen($loginNameV) < 4){
                $formErrors[] ='Identifier Name can\'t be  less than <strong> 5 characters </strong>';
            }
            if(!empty($iden)){
                $formErrors[] ='There is another user with <strong>Same Identifier Name </strong>';
            }                
            if(empty($emailV)) {
                $formErrors[] = 'Email cant be <strong>Empty</strong>';
            }    
            if(!empty($emailV) && filter_var($emailV ,FILTER_VALIDATE_EMAIL) == false){
                $formErrors[] = 'Email isn\'t <strong>Valide</strong>';
            }
            if(!empty($phoneNumberV) && strlen($phoneNumberV) !== 10 ){
                $formErrors[] ='Phone number must include <strong> 10 numbers </strong>';
            }                
            if(!empty($newImageName) && !in_array($imageExtention, $allowedImageExtention)){
                $formErrors[] = 'This extension is not <strong>Allowed</strong>';
            }
            if($newImageSize > 4194304 ){
                $formErrors[] = 'Size must\'nt be larger than <strong> 4MB </strong>';
            }
            if(!empty($newImageName) && strlen($newImageName) > 240) {
                $formErrors[] ='Image name cant be more <strong> than 240 characters </strong>';
            }
                   
            
            //إذا ما كان في أخطاء بينففذ الأستعلام 
            if(empty($formErrors)){
                //echo "<hr /> good";
                
                if (!empty($newImageName)){
                    
                    $image = rand(0,1000000000) . '_' . $newImageName;
                    move_uploaded_file($newImageNameTmp, "admin/uploads/avatars/" . $image);
                    if($old_image != 'default_user_avatar.jpg'){
                        unlink('admin/uploads/avatars/' . $old_image);
                    }
                    
                }else {
                    $image = $old_image;
                }
                $stmt = $con->prepare(" UPDATE 
                                                users
                                        SET 
                                                password = ?,
                                                Username = ?,
                                                Email =?,
                                                Fullname = ?,
                                                Adress = ?,
                                                Phone_Number =?,
                                                Avatar =?
                                        WHERE 
                                                UserID = ? ") ;
    $stmt->execute(array($pass, $loginNameV, $emailV, $fullNameV, $adressV, $phoneNumberV, $image, $userid) );
    echo '<div class="alert alert-success"> Your Information Are Updated</div>';
                
                        echo '</div>';
                    echo '</div>';
                echo '</div>';                
                
                include $tpl . "footer.php";
                
                redirectFunction('back', 3);
                
            }else{
                
                foreach($formErrors as $error){
                    echo '<div class="alert alert-danger">' . $error . '</div>';
                   // redirectFunction('back');
                }
                        echo '</div>';
                    echo '</div>';
                echo '</div>';               
            }
    
        } //end if($_SERVER['REQUEST_METHOD'] == 'POST') 
        else {
            header('Location:profile.php');
            exit();            
        }        
                
        } // end if($do == "Update")
        elseif($do == "Buy"){
                                        
  $allBuy = getAllFrom("*" ,"orders", "WHERE Buyer_ID = {$info['UserID']}", "Order_ID", "DESC");
    if(!empty($allBuy)){
?>
<div class="container">
    <div class="xx">
        <h1 class="text-center custom2-h1">All Procurement List</h1>
        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-home fa-fw"></i>Index</a></li>
            <li><a href="profile.php"><i class="fa fa-user fa-fw"></i>Profile</a></li>
            <li class="active">All Procurement List</li>
        </ol>    
        <div class="my-buys">
            <div class="table-responsive">
                <div class="table-shadow">
                    <table class="main-table text-center table ">
                        <tr class="table-head">
                            <td>
                                Item Name
                            </td>
                            <td>
                                Item Desciption
                            </td>
                            <td>
                                Shop Name
                            </td>
                            <td>
                                Price
                            </td>
                            <td>
                                Quantity
                            </td>
                            <td>
                                Total Price
                            </td>
                            <td>
                                Buy Date
                            </td>
                        </tr>
                        <?php
                    foreach($allBuy as $row){

                        $shop = getOneFrom('Shop_ID, Shop_Name', 'shops', "WHERE Shop_ID ={$row['Seller_ID']}", "Shop_ID");
                        echo"<tr class='table-body'>";
                            echo"<td>".$row['Item_Name']."</td>";
                            echo"<td><p>".$row['Item_Desc']."</p></td>";
                            echo"<td>".$shop['Shop_Name']."</td>";
                            echo"<td>".$row['Item_Price']."</td>";
                            echo"<td>".$row['Item_Quantity']."</td>";
                            echo"<td>" . ($row['Item_Price'] * $row['Item_Quantity']) . "</td>";
                            echo"<td>" . $row['Buy_Date'] . "</td>";
                       echo"</tr>";   
                    };
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
    
<?php    
        
    }//if(!empty($allBuy))        
       
        }//if($do = "Buy")
        elseif($do == 'Notif') {
   ?>
   <div class="container">
       <div class="xx">
           <h1 class="custom2-h1 text-center">Notifications</h1>
            <ol class="breadcrumb">
                <li><a href="index.php"><i class="fa fa-home fa-fw"></i>Index</a></li>
                <li><a href="profile.php"><i class="fa fa-user fa-fw"></i>Profile</a></li>
                <li class="active">Notifications</li>
           </ol>
                <div class="notif">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="panel">
                                <div class="panel-heading">Comments Notifications ( have been approved by admin)</div>
                                <div class="panel-body">
                                <?php
                                $stmt = $con->prepare("SELECT * FROM user_notif_comment WHERE User_ID = ? ORDER BY Notif_ID DESC");
                                $stmt->execute(array($_SESSION['uid']));
                                $notifs = $stmt->fetchAll();
                                if($stmt->rowCount() > 0){
                                    echo "<ul class='list-unstyled'>";
                                    foreach($notifs as $notif){
                                        $comment = getOneFrom('comment , item_id', 'comments', "WHERE c_id ={$notif['Comment_ID']}", 'comment');
                                        $item = getOneFrom('Name', 'items', "WHERE Item_ID ={$comment['item_id']}", 'Name');
                                        
                                        echo "<li>" . $item['Name'] . "<a href='items.php?itemid=". $comment['item_id'] ."'> Show Item</a><br/>Your Comment :".$comment['comment'] ."</li>";
                                        
                                        // تحديث قيمة الاشعار لكي يصبح مقروء
                                        $stmt = $con->prepare("UPDATE user_notif_comment SET Readed = ? WHERE Notif_ID = ?");
                                        $stmt->execute(array(1, $notif['Notif_ID']));
                                    }
                                    echo "</ul>";
                                }else{
                                    echo '<span class="empty">There Is No Notifications To Show</span>';
                                }
                                ?>                                 </div>
                            </div>                        
                        </div> 
                        <div class="col-sm-6">
                            <div class="panel">
                                <div class="panel-heading">Items Notifications</div>
                                <div class="panel-body">
                                <?php
                                $stmt = $con->prepare("SELECT * FROM user_notif_item WHERE User_ID = ? ORDER BY Notif_ID DESC");
                                $stmt->execute(array($_SESSION['uid']));
                                $notifs = $stmt->fetchAll();
                                if($stmt->rowCount() > 0){
                                    echo "<ul class='list-unstyled'>";
                                    foreach($notifs as $notif){
                                        $item = getOneFrom('Name, Store_ID', 'items', "WHERE Item_ID ={$notif['Item_ID']}", 'Name');
                                        $shop = getOneFrom('Shop_Name', 'shops', "WHERE Shop_ID ={$item['Store_ID']}", 'Shop_Name');
                                        
                                        echo "<li><span>". $shop['Shop_Name'] ." has been puplished one item</span><br/>" . $item['Name'] . "<a href='items.php?itemid=". $notif['Item_ID'] ."'> Show Item</a></li>";
                                        
                                        // تحديث قيمة الاشعار لكي يصبح مقروء
                                        $stmt = $con->prepare("UPDATE user_notif_item SET Readed = ? WHERE Notif_ID = ?");
                                        $stmt->execute(array(1, $notif['Notif_ID']));
                                    }
                                    echo "</ul>";
                                }else{
                                    echo '<span class="empty">There Is No Notifications To Show</span>';
                                }
                                ?>                                    
                                </div>
                            </div>                        
                        </div>
                    </div>
                </div>
       </div>
   </div>                         
   <?php
            }
    }else{
        header('Location:login.php');
        exit();
    }

    include $tpl . "footer.php";
ob_end_flush();
?>