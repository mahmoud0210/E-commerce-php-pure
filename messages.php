<?php
ob_start();
session_start();

$getTitle = 'Messages';
include "init.php";

if(isset($_SESSION['shopid']) || isset($_SESSION['uid'])){

if(isset($_SESSION['shopid'])){
    
    $stmt = $con->prepare("Select 
                                chats.Chat_ID,
                                users.UserID,
                                users.Fullname AS senderName,
                                users.avatar
                            From 
                                chats
                            INNER JOIN users on users.userid = chats.User_ID    
                            Where 
                                chats.Shop_ID = ? 
                            ORDER BY 
                                chats.Chat_ID 
                            DESC");
    $stmt->execute(array($_SESSION['shopid']));
    $rows = $stmt->fetchAll(); 
    
    $status = 1;
    
}elseif(isset($_SESSION['uid'])){
    
    
    
    if(isset($_GET['chatid']) && is_numeric($_GET['chatid'])){
        $chatid = intval($_GET['chatid']);        
    }elseif(isset($_GET['chatid']) && $_GET['chatid'] == 'NEW'){
        // هنا يتم اشاء سجل جديد لمحادثة في جدول المحادثات
        //$chatid = "NEW";
        $shopid = isset($_GET['shopid']) && is_numeric($_GET['shopid'])? intval($_GET['shopid']) : 0;
        // التاكد من أن رقم المتجر صحيح و موجود ضمن قاعدة البيانات 
        $check = checkShop($shopid);
        //اذا قام المستخدم بعمل تحديث للصفحة يتم التاكد من عدم غعادة ادخال سجل جديد لنفس المحادثة
        
        $stmt = $con->prepare("Select Chat_ID From chats Where User_ID = ? AND Shop_ID = ?");
        $stmt->execute(array($_SESSION['uid'], $shopid));
        $row = $stmt->fetch();            
        $chatid = $row['Chat_ID'];        
        $count = $stmt->rowCount();            
        
        if($check == 1 && $count == 0 ){
            //echo "matherFuckers";  
            $addChat = $con->prepare('
                                INSERT INTO 
                                    chats(User_ID, Shop_ID, Chat_Date)
                                    VALUES(:zuser, :zshop, now())');
            $addChat->execute(array(
                'zuser' => $_SESSION['uid'],
                'zshop' => $shopid
            ));
            
            // الحصول على رقم المحادثة بعض اضافتها لجدول المحادثات

            $stmt = $con->prepare("Select Chat_ID From chats Where User_ID = ? AND Shop_ID = ?");
            $stmt->execute(array($_SESSION['uid'], $shopid));
            $row = $stmt->fetch();            
            $chatid = $row['Chat_ID'];
        }
        
    } // end if(isset($_GET['chatid']) && $_GET['chatid'] == 'NEW')
    
    
    $stmt = $con->prepare("Select 
                                chats.Chat_ID,
                                shops.Shop_ID,
                                shops.Shop_Name AS senderName,
                                shops.Avatar
                            From 
                                chats
                            INNER JOIN shops on shops.Shop_ID = chats.Shop_ID    
                            Where 
                                chats.User_ID = ? 
                            ORDER BY 
                                chats.Chat_ID 
                                DESC");
    $stmt->execute(array($_SESSION['uid']));
    $rows = $stmt->fetchAll();   
    
    $status = 2;    
    
}
       

?>

<div class="container">
    <div class="xx">
        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-home fa-fw"></i>Index</a></li>
            <li><a href="myshop.php"><i class="fa fa-user fa-fw"></i>Profile</a></li>
            <li class="active">Messages</li>
        </ol>
        <div class="messages-panel" data-val="<?php echo isset($_SESSION['uid'])?'0':'1' ?>">
            <div class="new-ad block">
                <div class="panel panel-primary">
                    <div class="panel-heading"><?php echo $getTitle; ?></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="messages-sender-list col-md-3 col-sm-4 hidden-xs">
                                <ul class="list-group">
                                    <?php

                                    foreach ($rows as $row) {
                                        // عدد الرسائل الغير مقروئة بالأعتماد على حالة الرسالة
                                        // اذا كنت فاتح الصفحة كبائع تكون حالة الرسالة مساوية ل واحد وهي المرسلة من المستخدم
                                        // عكس فوق في حالة الفتح كمشتري
                                        $stmt = $con->prepare("SELECT count(Message_ID) AS num FROM messages WHERE Chat_ID = ? AND Readed = 0 AND Status = ?");
                                        $stmt->execute(array($row['Chat_ID'],$status));
                                        $num = $stmt->fetch();
                                        
                                        ?>
                                         <li class="messages-sender list-group-item <?php echo (isset($chatid) && $chatid == $row['Chat_ID'])? " active":"" ?>" data-chat="<?php echo $row['Chat_ID'] ?>">
                                            <img class="img-circle" src="p01.jpg" />
                                            <span>
                                                <?php 
                                                    echo $row['senderName'];
                                                    echo '<span class="badge-container"> ';
                                                    if($num['num'] != 0) {
                                                        echo '<span class="badge badge-primary"> ' . $num['num'] . '</span>'; 
                                                    }
                                                    echo '</span>';
                                                ?>
                                            </span>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                            <div class="messages-chat col-md-9 col-sm-8">
                                <div class="row">
                                    <div class="messages-dialog" 
                                         data-val="<?php 
                                                   if(isset($_SESSION['shopid'])){
                                                       echo "1";
                                                   }
                                                    elseif(isset($_SESSION['uid'])){
                                                       echo "0";
                                                   }
                                                   
                                                   ?>">
                                        <div class="start-chat">
                                            <p >START CHAT</p>
                                        </div>
                                        <div class="show-more-messages" data-mesnum="0">
                                            Show More
                                        </div>                                        
                                        <div class="messages">
                                        </div>
                                    
                                    </div>
                                    <div class="messages-form">
                                        <form>
                                            <div class="form-group">
                                                <textarea class="form-control"></textarea>
                                            </div>
                                            <div id="center-form" class="form-group">
                                                <button type="submit" class="btn btn-danger" >
                                                    <i class="fa fa-location-arrow"></i>Send
                                                </button>
                                            </div>
                                        </form>
                                    </div>                                    
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div> 
        </div>
    </div>
</div>

<?php 
} //end if (isset($_SESSION['shopid']))
else{
    header('location:index.php');
    exit();
}
?>

<?php

include $tpl .  "footer.php";
ob_end_flush();