<?php
session_start();

if( isset($_SESSION['username']) ){
    
    $getTitle = 'Dashboard';
    
    include 'init.php';
    
    /* strat Dashboard page */
    
    $usersNum = 8;
    
    $latestUsers = getLatest('*', 'users', 'UserID', $usersNum);
    
    $itemsNum = 8;
    
    $latestItems = getLatest('*', 'items', 'Item_ID', $itemsNum );
    
    $shopsNum = 8;
    
    $latestShops = getLatest('*', 'shops', 'Shop_ID', $shopsNum);
    
    $commentsNum = 5;
    
    $test = 5 ;
?>
<div class="container">
<div class="xx">
<div class="home-stat">
    <div class="home-stat-container text-center">
        <h1 class="custom-h1"><i class="fa fa-dashboard"></i><?php echo lang('DASHBOARD'); ?></h1>
        <div class="row">
            <div class="col-md-3">
                <div class="stat st-members">
                    <i class="fa fa-users"></i>
                    <div class="info">
                        <?php echo lang('TOTAL_MEMBERS'); ?>
                        <span><a href="members.php"><?php echo convertToK(countItems('UserID', 'users'));?></a></span>
                    </div>
                </div>    
            </div>
            <div class="col-md-3">
                <div class="stat st-pending">
                    <i class="fa fa-home"></i>
                    <div class="info">
                        <?php echo lang('PENDING_SHOPS'); ?>
                        <span><a href="shops.php?do=Manage&page=Pending">
                            <?php echo convertToK(checkItem('regStatus', 'shops', 0)); ?></a></span>
                        </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-items">
                    <i class="fa fa-tag"></i>
                    <div class="info">
                    <?php echo lang('TOTAL_ITEMS'); ?>
                    <span><a href="items.php"><?php echo convertToK(countItems('Item_ID', 'items'));?></a></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-comments">
                    <i class="fa fa-comments"></i>
                    <div class="info">
                    <?php echo lang('TOTAL_COMMENTS'); ?>
                    <span><a href="comments.php"><?php echo convertToK(countItems('c_id', 'comments'));?></a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="latest">
    <div class="latest-container">
        <div class="row">
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-users"></i> Latest <?php echo $usersNum; ?> Registered Users
                    </div>
                    <div class="panel-body">
                        <ul class="list-unstyled latest-users">
                        <?php
                        if(!empty($latestUsers)){
                            foreach($latestUsers as $user){
                            echo "<li>".$user['Username'];
                            if($user['RegStatus'] == 0) {
                            echo"<a href='members.php?do=Activate&userid=" .$user['UserID']. "' class=' btn btn-info activate pull-right'><i class='table-btn-icon-position fa fa-check'></i>". lang('ACTIVATE') ."</a>";
                                    }
                            echo '</li>';
                                }
                        }else{
                            echo"There's not records to show";
                        }
                        ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 test-panel">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-tag"></i> Latest <?php echo $usersNum; ?> Items
                    </div>
                    <div class="panel-body">
                        <ul class="list-unstyled latest-users">
                        <?php
                        if(!empty($latestItems)){
                            foreach($latestItems as $item){
                            echo '<li>'.$item['Name'];
                            if($item['Approve'] == 0) {
                            echo"<a href='items.php?do=Approve&itemid=" .$item['Item_ID']. "' class=' btn btn-info activate pull-right'><i class='table-btn-icon-position fa fa-check'></i>" . lang('APPROVE') ."</a>";
                                    }
                            echo '</li>';
                                }
                        }else{
                            echo"There's not records to show";
                        }
                        ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <!-- Start latest comments -->
        <div class="row">
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-comments-o"></i>
                        Latest <?php echo $commentsNum; ?> Comments
                    </div>
                    <div class="panel-body">
                    <?php
                            $stmt = $con->prepare("SELECT 
                                                        comments.*,
                                                        users.Username AS user_name
                                                    FROM 
                                                        comments
                                                    INNER JOIN 
                                                        users 
                                                    ON 
                                                        users.UserID = comments.user_id
                                                    ORDER BY c_id DESC
                                                    LIMIT $commentsNum ");
                            $stmt->execute();
                            $rows = $stmt->fetchAll();
                    if (!empty($rows)){
                            foreach($rows as $row){ 

                echo'<div class="comment-box-dashboard">';
                    echo'<a class="confirm" href="comments.php?do=Delete&comid='.$row['c_id'].'" ><span><i class="fa fa-close"></i></sapn></a>';
                    echo'<span class="member-n">'.$row['user_name'].'</span>';
                    echo'<p class="member-c">'.$row['comment'].'</p>';
                echo'</div>';
                        }
                    }else{
                        echo"There's not records to show";
                    }
                    ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-home"></i> Latest <?php echo $shopsNum; ?> Registered Shops 
                    </div>
                    <div class="panel-body">
                        <ul class="list-unstyled latest-users">
                        <?php
                        if(!empty($latestShops)){
                            foreach($latestShops as $shop){
                            echo "<li>".$shop['Shop_Name'];
                                
                            if($shop['RegStatus'] == 0) {
                            echo"<a href='shops.php?do=Activate&shopid=" .$shop['Shop_ID']. "' class=' btn btn-info activate pull-right'><i class='table-btn-icon-position fa fa-check'></i>". lang('ACTIVATE') ."</a>";
                                    }
                            echo '</li>';
                                }
                        }else{
                            echo"There's not records to show";
                        }
                        ?>
                        </ul>
                    </div>
                </div>
            </div>            
        </div>
    </div>
    <!-- Start latest comments -->
</div>
</div>
</div>

<?php
    /* end Dashboard page */
    include $tpl . 'footer.php';
    
}else{
    
    header('location: index.php');
    exit();
}