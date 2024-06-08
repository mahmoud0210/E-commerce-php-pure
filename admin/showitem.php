<?php

/**********************************
************ Copy Page ************
***********************************
*/
ob_start(); // output buffering start

session_start();

$pageTitle = 'Show Item';

if (isset($_SESSION['username'])){
    
    include "init.php";


$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid'])? intval($_GET['itemid']) : 0;

    $stmtItem = $con->prepare("
                            SELECT 
                                items.*, 
                                categories.Name AS Cat_Name,
                                categories.Visibility AS Cat_Vis,
                                shops.Shop_Name
                            FROM 
                                items 
                            INNER JOIN categories ON items.Cat_ID = categories.ID
                            INNER JOIN shops ON items.Store_ID = shops.Shop_ID 
                            WHERE
                                Item_ID = ? ");

    $stmtItem->execute(array($itemid));

    $count = $stmtItem->rowCount();
    
    if($count == 1) {
        
        $row = $stmtItem->fetch();
?>


<div class="container">
    <div class="xx">
        <h1 class="text-center custom-h1"><?php echo $row['Name'] ?></h1>
        <?php if($row['Cat_Vis'] == 1 ){?>
        <div class="alert alert-warning">
            <i class="fa  fa-exclamation-triangle"></i>
        The category of this item is <strong>Hidden</strong>, only admin can see it
        </div> 
        <?php }?>
    <div class="item-control"  style=" text-align: -webkit-right; text-align: right; ">

        <a class="item-delete" href="items.php?do=Delete&itemid=<?php echo $row['Item_ID'] ?>">
            <i class="confirm fa fa-times-circle"></i>
        </a>
        <a class="item-edit" href="items.php?do=Edit&itemid=<?php echo $row['Item_ID'] ?>">
            <i class="fa fa-pencil-square"></i>
        </a>
        <?php
        if($row['Approve'] == 0 ){
            echo '<a class="item-approve" href="items.php?do=Approve&itemid=' .                      $row['Item_ID'] . '">';
                echo'<i class="fa fa-check-circle"></i>';
            echo '</a>';
        }
        ?>
      
    </div>

    <div class="row">
        
        <div class="item-image">
            <div class="col-md-3">
                <img class="img-responsive img-thumbnail center-block" src="<?php 
                     echo 'uploads/items/'. $row['Image']?>" alt="" /></a>
            </div>
        </div>
    
        <div class="item-details">
            <div class="col-md-9">
                <span class="item-date">
                    Added in : <?php echo $row['Add_Date'] ?>
                </span>
                <h2> <?php echo $row['Name'] ?></h2>
                <ul class="list-unstyled">
                    <li>
                        <i class="fa fa-money fa-fw"></i>
                        <span> Price </span>:<?php echo ' $'.$row['Price'] ?>
                    </li>
                    <li>
                        <i class="fa fa-building fa-fw"></i>
                        <span> Made In </span>:<?php echo ' '.$row['Country_Made'] ?>
                    </li>
                    <li>
                        <i class="fa fa-clone fa-fw"></i>
                        <span> Category </span>:
                                <?php echo ' '.$row['Cat_Name'] ?>
                    </li>
                    <li>
                        <i class="fa fa-user fa-fw"></i>
                        <span> Added By </span>:
                                <?php echo ' '.$row['User_Name'] ?>
                    </li>
                    <li>
                        <i class="fa fa-tags fa-fw"></i>
                        <span> Tags </span>:
                        <?php
            
                        $allTags = explode(",", $row['Tags']);
                        if(!empty($row['Tags'])){
                            foreach($allTags as $tag){
                                $tag = str_replace(' ', '', $tag);
                                $lowertag = strtolower($tag);
                                echo "<a href='tags.php?name=$lowertag'>". $tag . '</a> |';
                            }
                        }else{
                            echo"There are't tags";
                        }
        
                        ?>
                    </li>
                </ul>
                <p> <?php echo ' '.$row['Description'] ?> </p>
                
            </div>
        </div>
    </div>
<?php if(!empty($row['Details'])){ ?>

    <div class=" details">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-users"></i>Details
            </div>
            <div class="panel-body">
                <ul class="list-unstyled ">
                        <?php

                        $allDetails = explode(",", $row['Details']);
                        
                        foreach($allDetails as $detail){
                            $detailRow = explode(":", $detail);
                            
                            echo "<li>
                                    <span class='one'>".strtoupper($detailRow[0])."</span>:
                                    <span class='two'>".$detailRow[1]. "</span>
                                </li> ";
                        }
                        ?>
                </ul>
            </div>
        </div>
    </div>

<?php  }  ?>


    <hr class="custom-hr">


    
<?php 
        
        $stmtComment = $con->prepare("SELECT 
                                    comments.*,
                                    users.Username AS user_name
                                FROM 
                                    comments
                                INNER JOIN users ON users.UserID = comments.user_id
                                WHERE item_id = ?
                                ORDER BY 	
                                    status,
                                    c_id DESC");
        
        $stmtComment->execute(array($row['Item_ID']));
        
        $comments = $stmtComment->fetchAll();
        
        if(!empty($comments)){
            echo '<div class="comments-container">';
            foreach($comments as $comment) { ?>


                <div class="comment-box">
                    
    <div class="item-control ">
        <a class="item-delete" href=" comments.php?do=Delete&comid=<?php echo $comment['c_id'] ?> " >
            <i class="confirm fa fa-times-circle"></i>
        </a>

        <?php
        if($comment['status'] == 0 ){
            echo '<a class="item-approve" href="comment.php?do=Approve&comid=' .                      $comment['c_id'] . '">';
                echo'<i class="fa fa-check-circle"></i>';
            echo '</a>';
        }
        ?>
      
    </div>                    
                    
                <hr class="custom-hr col-sm-offset-2 ">
                <div class="row <?php if($comment['status'] == 0){echo " low-opacity"; }?>">
                    <div class="col-sm-2 text-center">
                        <a href="#"><img class="img-responsive img-thumbnail center-block img-circle" src="../product-icon.png" alt="" /></a>
                    </div>
                    <div class="col-sm-10">
                        <span class="first-span">
                            <a href="#"><?php echo $comment['user_name']; ?></a>
                        </span>
                        <span class="second-span"><?php echo $comment['comment']; ?></span>
                    </div>
                </div>
                </div>
                
            <?php }
            echo'</div>';
        } // end if(!empty($stmtComment))
        else{
            echo'<div class="col-md-offset-3">';
                echo 'This Item Has No Comment';
            echo'</div>';   
        }
?>
    </div>
</div>
<?php
        
        }else{
             echo '<div class="container">';
                echo '<div class="xx">';           
                echo '<h1 class="custom-h1 text-center"><i class="fa fa-warning"></i> Access Denied</h1>';
                echo "<div class='alert alert-danger'><p>you can't browse this page directly</p></div>";
                redirectFunction('home');
                echo '</div>';
            echo '</div>';
    }
    
    include $tpl . 'footer.php';
    
} //end if (isset($_SESSION['username']))
else{
    header('location:index.php');
    exit();
}

ob_end_flush(); // release the output

?>