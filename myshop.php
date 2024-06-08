<?php
ob_start(); // output buffering start


    session_start();

    $getTitle = 'Profile';

    include "init.php";
    if(isset($_SESSION['shopid'])){
        
        $getUser = $con->prepare("SELECT * FROM shops WHERE Shop_ID = ?");
        $getUser->execute(array($_SESSION['shopid']));
        $info = $getUser->fetch();
        
        $pn = isset($_GET['pn']) && is_numeric($_GET['pn'])? intval($_GET['pn']) : 1;        
        $numProduct = 12;
        
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
        
        if ($do == 'Manage') {
        
        $countNotifItem = getOneFrom('count(Notif_ID) as number', 'shop_notif_item', "WHERE Shop_ID={$_SESSION['shopid']} AND Readed = 0", 'Notif_ID');    
        $countNotifComm = getOneFrom('count(Notif_ID) as number', 'shop_notif_comment', "WHERE Shop_ID={$_SESSION['shopid']} AND Readed = 0", 'Notif_ID');    
        
        $countNotif['number'] = $countNotifItem['number'] + $countNotifComm['number'];
        
        $subscribers_number = getOneFrom( "count(*) as number", "subscribes", "WHERE Shop_ID = {$_SESSION['shopid']}", 'Shop_ID');
  
?>
<div class="container">
<div class="xx profile-panel-heading-icon">

    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-home fa-fw"></i>Index</a></li>
        <li class="active">Profile</li>
    </ol>    
    
    <div class="information ">
        <div class="panel">
            <div class="panel-heading">My information
                <a href="?do=Edit">
                    <span class="pull-right">Edit<i class="fa fa-wrench"></i></span>
                </a>
                <span class="pull-right separet">|</span>
                <a href="?do=Sell">
                    <span class="pull-right"> Procurement List<i class="fa  fa-bar-chart-o"></i></span>
                </a>
                <span class="pull-right separet">|</span>
                <a href="?do=Request">
                    <span class="pull-right"> My Requests<i class="fa  fa-bars"></i></span>
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
                                <span>Shop Name</span>: <?php echo $info['Shop_Name']; ?> 
                            </li>

                            <li>
                                <i class="fa fa-id-card fa-fw"></i>
                                <span>Identifier Name</span>: <?php echo $info['Identifier_Name']; ?>
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

                            <li>
                                <i class="fa fa-envelope-o  fa-fw"></i>
                                <span>Description </span> : <?php echo '<p>' . $info['Description'] . '</p>'; ?>
                            </li>

                        </ul>
                    </div>
                    <div class="col-md-5">
                        <div class="shop-image">
                            <div class="thumbnail" style="margin-bottom : 0px">
                                <img class="img-responsive" style="max-height:265px" src="admin/uploads/shops/<?php echo $info['Avatar'] ?>" />
                            </div>
                        </div>
                        <div class="subscribers-number">Number Of Subscribers<br />
                            <?php echo $subscribers_number['number']; ?>
                        </div>                
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="my-items">
        <h1 class="text-center">My Items</h1>

                    <?php

    //$allItems= getAllFrom("*", "items", "WHERE Member_ID = {$info['UserID']}", "Item_ID");
        
        $test = pagination(
                "Item_ID", 
                "items", 
                "Store_ID = {$info['Shop_ID']}",
                $numProduct, 
                $pn,
                '#my-items'); 
        
        $allItems =  $test[0];
                    if (!empty($allItems)){
                        echo '<div class="row">';
                        foreach($allItems as $item){
                            
       $customThumbnail = $item['Approve'] == 0 ? ' custom-thumbnail': NULL;
             
                            echo'<div class="col-xs-6 col-md-3">';
                                echo'<div class="item-box">';
                            
    echo'<div class="item-box-control">';
        echo'<a class="item-delete" href="newad.php?do=Delete&itemid='.$item['Item_ID'].'">
                <i class="confirm fa fa-times-circle"></i>
            </a>';
        echo'<a class="item-edit" href="newad.php?do=Edit&itemid='.$item['Item_ID'].'">
                <i class="fa  fa-pencil-square-o"></i>
            </a>';
    echo'</div>';
                            
                                echo"<div class='thumbnail" . $customThumbnail . "' >";
                                    echo'<span class="price-tag">$' .$item['Price'].             '</span>';
                                    if($item['Approve'] == 0){
                                        echo'<span class="approve-status">Weating Approve</span>';
                                    }
                                    echo '<a href="items.php?itemid=' . $item['Item_ID'] . ' "><img class="img-responsive" src="admin/uploads/items/'.$item['Image'].'" alt="" /></a>';
                                    echo'<div class="caption">';
                                    echo'<div class="date">' .$item['Add_Date']. '</div>';
                                        echo'<a href="items.php?itemid=' . $item['Item_ID'] . ' ">
                                                <p>' .$item['Name'].'</p>
                                            </a>';
                                        echo'<p>' .$item['Description']. '</p>';
                                    echo'</div>';
                                echo'</div>';
                                echo'</div>';
                            echo'</div>';
                        }
                        echo '</div>';
                ?>
        <div class="text-center">
            <ul class="pagination">
                <?php echo $test[3]; ?>
            </ul>
        </div>
                <?php
                        
                    } elseif($info['RegStatus'] == 1) {
                        echo'<p class="lead no-items">You have no items ,click to <a href="newad.php">Add Item</a></p>';
                    }
                    ?>

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
            <li><a href="myshop.php"><i class="fa fa-user fa-fw"></i>Profile</a></li>
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
                                        <div class="required">
                                            <label>
                                                <i class="fa fa-user fa-fw"></i>
                                                <span>Shop Name</span>:
                                            </label>
                                            <input 
                                                   type="text" 
                                                   class="form-control" 
                                                   name="shopname"
                                                   value="<?php echo $info['Shop_Name']; ?>"
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
                                                   name="identifiername"
                                                   value="<?php echo $info['Identifier_Name']; ?>"
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
                                                   placeholder="Your Store Location"
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
                                        <div class="required">
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

                                    <li>
                                        <div class="desc">
                                            <label>
                                                <i class="fa fa-kk  fa-fw"></i>
                                                <span>Description </span>:
                                            </label>    
                                                <textarea 
                                                        class="form-control " 
                                                        name="description">
                                                    <?php echo $info['Description'] ?>
                                                </textarea>
                                        </div>
                                    </li>

                                </ul>
                            </div>
                            <div class="col-md-5">
                                <div class="shop-image">
                                    <div class="thumbnail" style="margin-bottom : 0px">
                                        <img class="img-responsive" style="max-height:265px" src="admin/uploads/shops/<?php echo $info['Avatar'] ?>" />
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
                        <ol class="breadcrumb">
                            <li><a href="index.php"><i class="fa fa-home fa-fw"></i>Index</a></li>
                            <li><a href="myshop.php"><i class="fa fa-user fa-fw"></i>Profile</a></li>
                            <li><a href="myshop.php?do=Edit"><i class="fa fa-wrench fa-fw"></i>Edit</a></li>
                            <li class="active">Update</li>
                        </ol>
                        <h1 class="custom-h1 text-center">Update Information</h1>                        
                        <div class="information-alert">
<?php          
            $shopid         = $_SESSION['shopid'];
            $shopname       = $_POST['shopname'];
            $identifiername = $_POST['identifiername'];
            $adress         = $_POST['adress'];
            $newpass        = $_POST['new_pass'];    
            $email          = $_POST['email'];
            $phonenumber    = $_POST['phonenumber'];   
            $description    = trim ($_POST['description']);
                
            $shopNameV      = filter_var($shopname, FILTER_SANITIZE_STRING);
            $idenNameV      = filter_var($identifiername, FILTER_SANITIZE_STRING);
            $adressV        = filter_var($adress, FILTER_SANITIZE_STRING);
            $emailV         = filter_var($email, FILTER_SANITIZE_EMAIL);    
            $phoneNumberV   = filter_var($phonenumber, FILTER_SANITIZE_NUMBER_INT);
            $descV          = filter_var($description, FILTER_SANITIZE_STRING); 
                
            if(!empty($idenNameV)){
                
            $iden           = getOneFrom("Shop_ID,Identifier_Name", "shops","WHERE (Shop_ID != {$_SESSION['shopid']}) AND (Identifier_Name = '{$idenNameV}')", "Shop_ID");    
            
            }    
                
                
            $old            = getOneFrom("*", "shops", "WHERE Shop_ID={$_SESSION['shopid']}", "Shop_ID"); 
            $passhash       = sha1($newpass);            
            $pass           = empty($newpass)?$old['Password']:$passhash;    
                            
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
            
            if(empty($shopNameV)){
                $formErrors[] ='Name can\'t be  <strong> Empty </strong>';
            }
            if(strlen($shopNameV) < 5){
                $formErrors[] ='Shop Name can\'t be  less than <strong> 5 characters </strong>';
            }                
            if(!empty($idenNameV) && strlen($idenNameV) < 5){
                $formErrors[] ='Identifier Name can\'t be  less than <strong> 5 characters </strong>';
            }
            if(isset($idenNameV) && !empty($iden)){
                $formErrors[] ='There is another shop with <strong>Same Identifier Name </strong>';
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
                /*
            if(!empty($phoneNumberV) && filter_var($phoneNumberV ,FILTER_VALIDATE_INT) == false ){
                $formErrors[] = 'Phone number isn\'t <strong>Valide</strong>';
            }*/
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
                    move_uploaded_file($newImageNameTmp, "admin/uploads/shops/" . $image);
                    if($old_image != 'store.png'){
                        unlink('admin/uploads/shops/' . $old_image);
                    }
                    
                }else {
                    $image = $old_image;
                }
                $stmt = $con->prepare(" UPDATE 
                                                shops
                                        SET 
                                                Password = ?,
                                                Identifier_Name = ?,
                                                Email =?,
                                                Shop_Name = ?,
                                                Adress = ?,
                                                Description = ?,
                                                Phone_Number =?,
                                                Avatar =?
                                        WHERE 
                                                Shop_ID = ? ") ;
    $stmt->execute(array($pass, $idenNameV, $emailV, $shopNameV, $adressV, $descV, $phoneNumberV, $image, $shopid) );
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
            header('Location:myshop.php');
            exit();            
        }        
                
        } // end if($do == "Update")
        elseif($do == "Sell"){
                                        
  $allBuy = getAllFrom("*" ,"orders", "WHERE Seller_ID = {$info['Shop_ID']}", "Order_ID", "DESC");
 
?>
<div class="container">
    <div class="xx">
        <h1 class="text-center custom2-h1">All Procurement List</h1>
        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-home fa-fw"></i>Index</a></li>
            <li><a href="myshop.php"><i class="fa fa-user fa-fw"></i>Profile</a></li>
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
                                Buyer Name
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
       if(!empty($allBuy)){        
                    foreach($allBuy as $row){

                        $shop = getOneFrom('UserID, Fullname', 'users', "WHERE UserID ={$row['Buyer_ID']}", "UserID");
                        echo"<tr class='table-body'>";
                            echo"<td>".$row['Item_Name']."</td>";
                            echo"<td><p>".$row['Item_Desc']."</p></td>";
                            echo"<td>".$shop['Fullname']."</td>";
                            echo"<td>".$row['Item_Price']."</td>";
                            echo"<td>".$row['Item_Quantity']."</td>";
                            echo"<td>" . ($row['Item_Price'] * $row['Item_Quantity']) . "</td>";
                            echo"<td>" . $row['Buy_Date'] . "</td>";
                       echo"</tr>";   
                    };
       }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
    
<?php    
        
    //if(!empty($allBuy))        
       
        }//if($do = "Buy")        
        elseif($do == 'Notif') {
   ?>
   <div class="container">
       <div class="xx">
           <h1 class="custom2-h1 text-center">Notifications</h1>
            <ol class="breadcrumb">
                <li><a href="index.php"><i class="fa fa-home fa-fw"></i>Index</a></li>
                <li><a href="myshop.php"><i class="fa fa-user fa-fw"></i>Profile</a></li>
                <li class="active">Notifications</li>
           </ol>
                <div class="notif">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="panel">
                                <div class="panel-heading">Comments Notifications</div>
                                <div class="panel-body">
                                <?php
                                $stmt = $con->prepare("SELECT * FROM shop_notif_comment WHERE Shop_ID = ? ORDER BY Notif_ID DESC");
                                $stmt->execute(array($_SESSION['shopid']));
                                $notifs = $stmt->fetchAll();
                                if($stmt->rowCount() > 0){
                                    echo "<ul class='list-unstyled'>";
                                    foreach($notifs as $notif){
                                        $comment = getOneFrom('user_id, item_id', 'comments', "WHERE c_id ={$notif['Comment_ID']}", 'user_id');
                                        $user = getOneFrom('Fullname', 'users', "WHERE USERID ={$comment['user_id']}", 'Fullname');
                                        $item = getOneFrom('Name', 'items', "WHERE Item_ID ={$comment['item_id']}", 'Name');
                                        
                                        echo "<li>" . $user['Fullname'] ." Commented On Your Item <br />" . $item['Name'] . "<a href='items.php?itemid=". $comment['item_id'] ."'> Show Item</a></li>";
                                        
                                        // تحديث قيمة الاشعار لكي يصبح مقروء
                                        $stmt = $con->prepare("UPDATE shop_notif_comment SET Readed = ? WHERE Notif_ID = ?");
                                        $stmt->execute(array(1, $notif['Notif_ID']));
                                    }
                                    echo "</ul>";
                                }else{
                                    echo '<span class="empty">There Is No Notifications To Show</span>';
                                }
                                ?>                                </div>
                            </div>                        
                        </div> 
                        <div class="col-sm-6">
                            <div class="panel">
                                <div class="panel-heading">Items Notifications (items have been approved by admin)</div>
                                <div class="panel-body">
                                <?php
                                $stmt = $con->prepare("SELECT * FROM shop_notif_item WHERE Shop_ID = ? ORDER BY Notif_ID DESC");
                                $stmt->execute(array($_SESSION['shopid']));
                                $notifs = $stmt->fetchAll();
                                if($stmt->rowCount() > 0){
                                    echo "<ul class='list-unstyled'>";
                                    foreach($notifs as $notif){
                                        $item = getOneFrom('Name', 'items', "WHERE Item_ID ={$notif['Item_ID']}", 'Name');
                                        
                                        echo "<li>" . $item['Name'] . "<a href='items.php?itemid=". $notif['Item_ID'] ."'> Show Item</a></li>";
                                        
                                        // تحديث قيمة الاشعار لكي يصبح مقروء
                                        $stmt = $con->prepare("UPDATE shop_notif_item SET Readed = ? WHERE Notif_ID = ?");
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
            } //end elseif($do == 'Notif')
            elseif($do == 'Request'){
             
                $rows = getAllFrom("*", "requests_status", "WHERE Shop_ID = {$_SESSION['shopid']}", "Request_ID");            
                $in = '(';
                
                foreach($rows as $row){
                    $in .= $row['Request_ID'] . ',';
                }
                
                $in = rtrim($in, ',');
                $in .= ")";
                if($in !== '()'){
                    $requests = getAllFrom("*", "requests", "WHERE Request_ID IN ".$in , "Request_ID");
                }
                
?>
                            
<div class="container">
<div class="xx">
    <h1 class="custom2-h1 text-center">My Requests</h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-home fa-fw"></i>Index</a></li>
        <li><a href="myshop.php"><i class="fa fa-user fa-fw"></i>Profile</a></li>
        <li class="active">My Requests</li>
    </ol>
    
    <?php if(empty($requests)){ ?>
    <div class="no-requests">
        There Are Not Any Requests
        You Can Accept New Request <a href="showrequests.php">FROM HERE</a>        
    </div>
    <?php }else{ ?>
    
    <div class="shop-cart">
        <?php
        foreach($requests as $request){
            $name = getOneFrom('Name', 'categories', "WHERE ID = {$request['Cat_ID']}", 'Name');
        ?>
            <div class="item-cart row">
                <span class="item-information">
                    Add Date : <?php echo $request['Add_Date'] ?>
                </span>
                <div class="col-md-3 text-center">
                    <img src="<?php echo 'admin/uploads/requests/' . $request['Image']; ?>" class="img-request"/>
                </div>
                <div class="col-md-9">
                    <span class="item-name"><?php echo $request['Name']; ?></span>
                    <p>Price Range : <?php echo $request['Price_MIN'] ?> - <?php echo $request['Price_MAX'] ?></p>
                    <p>Quantity : <?php echo $request['Quantity'] ?></p>
                    <p>Category : <?php echo $name['Name'] ?></p>
                    <?php
                    if(!empty($request['Details'])) {
                        
                        echo '<p>Details : </p>';
                        echo '<ul class="list-unstyled details">';
                        
                        $details = explode('|', $request['Details']);
                        foreach($details as $detail){
                            $detailExp = explode(':', $detail);
                    ?>
                    
                    <li>
                        <span class="one"><?php echo  $detailExp[0]; ?></span>
                        <span class="two"><?php echo ': ' . $detailExp[1]; ?></span>
                    </li>
                    
                    <?php
                        }//end foreach($details as $detail)
                        echo '</ul>';
                    } // end if(!empty($request['Details']))
                    ?>
                    <p><?php echo nl2br($request['Description']) ?></p>
                </div>
            </div>
            <hr/> 
        <?php } ?>
    </div>
    
    <?php } ?>
</div>
</div>                            
                            
<?php    
                
            }
    }else{
        header('Location:login.php');
        exit();
    }

    include $tpl . "footer.php"; 
ob_end_flush(); // release the output
?>