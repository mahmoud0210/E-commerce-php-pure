<?php
ob_start();
    session_start();

    $getTitle = 'Shop Information';

    include "init.php";

$shopid = isset($_GET['shopid']) && is_numeric($_GET['shopid'])? intval($_GET['shopid']) : 0;

        
        $getUser = $con->prepare("SELECT * FROM shops WHERE Shop_ID = ?");
        $getUser->execute(array($shopid));
        $info = $getUser->fetch();
        
        $pn = isset($_GET['pn']) && is_numeric($_GET['pn'])? intval($_GET['pn']) : 1;        
        $numProduct = 12;

if($info['RegStatus'] == 1){
    $subscribers_number = getOneFrom( "count(*) as number", "subscribes", "WHERE Shop_ID = {$shopid}", 'Shop_ID');
?>
<div class="container">
<div class="xx">
<h1 class=" custom-h1 text-center"><?php echo $info['Shop_Name'] ?> </h1>
    
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-home fa-fw"></i>Index</a></li>
        <li><a href=""><i class="fa fa-building fa-fw"></i>Shops</a></li>
        <li class="active"><?php echo $info['Shop_Name'] ?></li>
    </ol> 
    <div class="subscribe">
    <?php 
    if(isset($_SESSION['uid']) && !isset($_SESSION['shopid'])){ 
            $subscribe = $con->prepare('SELECT * FROM subscribes WHERE User_ID = ? AND Shop_ID = ?'); 
            $subscribe->execute(array($_SESSION['uid'], $shopid));
            $rowSub = $subscribe->fetch();
            if($subscribe->rowCount() == 0){
    ?>
        <span class="subscribe-now pull-right" data-shop="<?php echo $shopid;  ?>" data-user="<?php echo $_SESSION['uid'];  ?>"> Subscribe</span>        
    <?php    
            }elseif($subscribe->rowCount() == 1 ){
    ?>
        <span class="subscribed pull-right" data-shop="<?php echo $shopid;  ?>" data-user="<?php echo $_SESSION['uid'];  ?>">
            <i class="fa fa-bell-slash<?php echo $rowSub['Bell'] == 0?' active':'' ?>"></i>
            <i class="fa fa-bell<?php echo $rowSub['Bell'] == 1?' active':'' ?>"></i>
            Subscribed
        </span>        
    <?php    
            }        
        } 
    ?>    
    </div>
    
    <div class="information ">
        <div class="panel">
            <div class="panel-heading">Shop information
                <?php
                if(isset($_SESSION['uid']) && !isset($_SESSION['shopid'])){
                    $stmt = $con->prepare("SELECT Chat_ID FROM chats WHERE Shop_ID = ? AND User_ID = ?");
                    $stmt->execute(array($shopid,$_SESSION['uid']));
                    $chat = $stmt->fetch();
                    $chatid = is_numeric($chat['Chat_ID']) && $chat['Chat_ID'] !== NULL ? $chat['Chat_ID'] : "NEW";
                    //echo $chat['Chat_ID'];
                    //var_dump($cahtid);
                    ?>
                <a class="btn btn-info pull-right" href="messages.php?chatid=<?php echo $chatid; ?>&shopid=<?php echo $shopid; ?>" >
                    <span class="pull-right"><span class="glyphicon glyphicon-send"></span> Send Message </span>
                </a>
                
                    <?php
                   
                }
                ?>
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
                                <span>Description </span>: <?php echo '<p>' . $info['Description'] . '</p>'; ?>
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
        <h1 class="text-center">Shop Items</h1>

                    <?php

    //$allItems= getAllFrom("*", "items", "Store_ID = {$info['Shop_ID']} AND Approve = 1", "Item_ID");
        
        $test = pagination(
                "Item_ID", 
                "items", 
                "Store_ID = {$info['Shop_ID']} AND Approve = 1",
                $numProduct, 
                $pn,
                "&shopid={$shopid}#my-items"); 
        
        $allItems =  $test[0];
                    if (!empty($allItems)){
                        echo '<div class="row">';
                        foreach($allItems as $item){
                                         
                            echo'<div class="col-xs-6 col-md-3">';
                                echo'<div class="item-box">';                            
                                echo"<div class='thumbnail' >";
                                    echo'<span class="price-tag">$' .$item['Price']. '</span>';
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
                    } else {
                        echo' <p class="lead no-items">This store does not have any products to sell !!!</p> ';
                    }
                    ?>

    </div>

</div>
</div>
<?php
}else{
    header("Location:index.php");
    exit();
}
    include $tpl . "footer.php";
ob_end_flush();
?>