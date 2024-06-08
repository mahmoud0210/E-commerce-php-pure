<?php
ob_start(); // output buffering start
    session_start();

    $getTitle = 'Item';

    include "init.php";

    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid'])? intval($_GET['itemid']) : 0;

    $stmtItem = $con->prepare("
                            SELECT 
                                items.*,
                                shops.Shop_ID,
                                shops.Shop_Name,
                                categories.Name AS Cat_Name,
                                categories.Parent
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
        
        // اضافة المنتج الى الكوكيز الخاص بامنجات التي تم مشاهدتها
        addRecentlyViewed($row['Item_ID']);  
        $recently = recentlyViewed();
        
        if($row['Approve'] == 1) {
    $cartComponent = checkCart();            
?>
<div class="container">

<div class="xx">
    <h1 class="text-center custom-h1 ">        
        <?php echo $row['Name'] ?>

    </h1>   
    <div class="row">
        
        <div class="item-image">
            <div class="col-md-3">
                <img class="img-responsive img-thumbnail center-block" src="admin/uploads/items/<?php echo $row['Image'] ?>" alt="" />
                <div class="big-image">
                    <div class="close-image">
                        <i class="fa fa-close"></i>
                    </div>    
                    <div class="image-big-container ">
                        <img src="admin/uploads/items/<?php echo $row['Image'] ?>" />
                    </div>
                </div>
                <div class="cart">
                <?php 
                    if($cartComponent == -1 or !in_array($row['Item_ID'],$cartComponent)){
                ?>
                                <form action="cart.php?cart=Add" method="post" class="center-block">
                                    <input 
                                           type="hidden" 
                                           name="item_id" 
                                           value="<?php echo $row['Item_ID'] ?>" />
                                    <input 
                                           type="text"
                                           class="form-control"
                                           name="item_quantity"/>
                                    <input
                                           type="submit"
                                           class="btn btn-primary"
                                           value = "Add"/>
                                </form>
                <?php
                    }else{ echo "<span class='center-block '><i class='fa fa-check'></i>Added</span>";}
                ?>  
                </div>    
        </div>
        </div>
    
        <div class="item-details">
            <div class="col-md-9">
                <span class="item-date">
                    Added in : <?php  echo $row['Add_Date'];

                    ?>
                </span>
                <h2> <?php echo $row['Name'] ?></h2>
                <ul class="list-unstyled">
                    <li>
                        <i class="fa fa-money fa-fw"></i>
                        <span> Price </span>:<?php echo ' $'.$row['Price'] ?>
                    </li>
                    <li>
                        <i class="fa fa-th fa-fw"></i>
                        <span> Quantity </span>:<?php echo $row['Quantity'] ?>
                    </li>                    
                    <li>
                        <i class="fa fa-building fa-fw"></i>
                        <span> Made In </span>:<?php echo ' '.$row['Country_Made'] ?>
                    </li>
                    <li>
                        <i class="fa fa-clone fa-fw"></i>
                        <span> Category </span>:
                            <a href="categories.php?pageid= <?php echo $row['Cat_ID'] ?> ">
                                <?php echo ' '.$row['Cat_Name'] ?>
                            </a>
                    </li>
                    <li>
                        <i class="fa fa-user fa-fw"></i>
                        <span> Added By </span>:
                            <a href="shopinfo.php?shopid=<?php echo $row['Shop_ID']?>">
                                <?php echo ' '.$row['Shop_Name'] ?>
                            </a>
                    </li>
                    <li class="tags">
                        <i class="fa fa-tags fa-fw"></i>
                        <span> Tags </span>:
                        <?php
            
                        $allTags = explode(",", $row['Tags']);
                        
                        foreach($allTags as $tag){
                            $tag = str_replace(' ', '', $tag);
                            $lowertag = strtolower($tag);
                            if(!empty($lowertag)){
                                echo "<a class='preventDefaultTags' href='tags.php?name=$lowertag'>". $tag . ' </a>';
                            }
                        }
                        ?>
                    </li>
                </ul>
                <?php if(!empty($row['Description'])) {?>
                <hr>
                <?php }?>
                <p> <?php echo ' '. nl2br($row['Description']) ?> </p>
                
            </div>
        </div>
    </div>
<?php if(!empty($row['Details'])){ ?>

    <div class=" details">
        <div class="panel panel-default">
            <div class="panel-heading">
                Details
            </div>
            <div class="panel-body">
                <ul class="list-unstyled">
                        <?php

                        $allDetails = explode("|", $row['Details']);
                        
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


    <div class="row rate-comment">
        
        <div id="rate" class=" col-md-3">
        <div class="rate">  
        
        <?php
            if(!checkRate($row['Item_ID'])){
        ?>
            
        <form  action="rate.php" method="post">     
                    <ul class="list-unstyled">
                        <li>
                            <div>
                                <input id="vis-five" type="radio" name="rate" value="5" checked />
                                <label class="badge" ><?php echo ' '.$row['five'] ?></label>
                                <span>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </span>
                            </div>
                        </li>                     
                        <li>
                            <div>
                                <input id="vis-two" type="radio" name="rate" value="4"  />
                                <label  class="badge" ><?php echo ' '.$row['four'] ?></label>
                                <span>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </span>
                            </div>
                        </li>                     
                        <li>
                            <div>
                                <input id="vis-two" type="radio" name="rate" value="3"  />
                                <label  class="badge" ><?php echo ' '.$row['three'] ?></label>
                                <span>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </span>
                            </div>
                        </li>                     
                         <li>
                            <div>
                                <input id="vis-two" type="radio" name="rate" value="2"  />
                                <label  class="badge" ><?php echo ' '.$row['two'] ?></label>
                                <span>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </span>
                            </div>
                        </li> 
                        <li>
                            <div>
                                <input id="vis-one" type="radio" name="rate" value="1"  />
                                <label  class="badge badge-danger" ><?php echo ' '.$row['one'] ?></label>
                                <span> 
                                    <i class="fa fa-star"></i>
                                </span>
                            </div>
                        </li>
                    </ul>
                        <input type="hidden" name="itemid" value="<?php echo $row['Item_ID'] ?>"/>   
                    <div class="sub">
                            <input type="submit" value="Rate" class="btn" />
                    </div>
        </form>
            <?php
            
            }else{
                
            ?>
                    <ul class="list-unstyled">
                        <li>
                            <div>
                                <span>
                                    <label  class="badge badge-danger" ><?php echo ' '.$row['five'] ?></label>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </span>
                            </div>
                        </li>                     
                        <li>
                            <div>
                                <span>
                                    <label  class="badge badge-danger" ><?php echo ' '.$row['four'] ?></label>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </span>
                            </div>
                        </li>                     
                        <li>
                            <div>
                                <span>
                                    <label  class="badge badge-danger" ><?php echo ' '.$row['three'] ?></label>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </span>
                            </div>
                        </li>                     
                         <li>
                            <div>
                                <span>
                                    <label  class="badge badge-danger" ><?php echo ' '.$row['two'] ?></label>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </span>
                            </div>
                        </li> 
                        <li>
                            <div>
                                <span>
                                    <label  class="badge badge-danger" ><?php echo ' '.$row['one'] ?></label>
                                    <i class="fa fa-star"></i>
                                </span>
                            </div>
                        </li>
                    </ul>   
            
            <?php
            }
            ?>
            
            </div>
        </div>
        
        <div class="col-md-9">
            <?php if(isset($_SESSION['user'])) { ?> 
            <div class="add-comment">
                <h3>Add your comment</h3>
                <form action="<?php $_SERVER['PHP_SELF'] . '?itemid=' . $row['Item_ID'] ?> "  method="post" >
                <textarea class="formcontrol" name="comment" required></textarea>
                <input class="btn btn-primary" type="submit" value="Add comment" />
                </form>
                
            <?php 
                if($_SERVER['REQUEST_METHOD'] == 'POST') {            
                    
                    $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                    $itemid = $row['Item_ID'];
                    $userid = $_SESSION['uid'];
                    
                    
                    if(!empty($comment)){
                        
                        $stmt = $con->prepare("INSERT INTO
                        comments(comment, status, comment_date, item_id, user_id)
                        VALUES(:zcomment, 0, NOW(), :zitem, :zuser)");
                        
                        $stmt->execute(array(
                            
                            'zcomment'  => $comment,
                            'zitem'     => $itemid,
                            'zuser'     => $_SESSION['uid']
                        
                        ));
                        
                        if($stmt){
                            echo"<div class='alert alert-success'>Comment Added <br/>Your comment is now hidden when admin approve it the comment well be show</div>";
                        }
                        
                    } // end if(!empty($comment))
                    else{
                        echo"<div class='alert alert-danger'>Comment Can't By Empty</div>";
                    }
                } // end if($_SERVER['REQUEST_METHOD'] == 'POST')
            ?>
            </div>
            <?php
                
                } // end if(isset($_SESSION['user']))
                else{
                echo'<div class="add-comment">';
                    echo "<h4> <a href='login.php'>Login </a> or <a href='login.php'> Register </a> to add comment</h4>";
                echo'</div>';
                }
            ?>
        </div>
    </div>

<?php 
        
        $stmtComment = $con->prepare("SELECT 
                                    comments.*,
                                    users.Fullname AS user_name,
                                    users.Avatar
                                FROM 
                                    comments
                                INNER JOIN users ON users.UserID = comments.user_id
                                WHERE item_id = ? AND status = 1
                                ORDER BY c_id DESC");
        
        $stmtComment->execute(array($row['Item_ID']));
        
        $comments = $stmtComment->fetchAll();
        
        if(!empty($comments)){
            echo '<div class="comments-container">';
            foreach($comments as $comment){
                echo '<div class="comment-box" id="' . $comment['user_id'] . '">';
                echo'<hr class="custom-hr col-sm-offset-2 ">';
                echo'<div class="row">';
                    echo'<div class="col-sm-2 text-center">';
    ?>
    <div class="the-return">
        <ul class="list-unstyled user-<?php echo $comment['user_id']?>">
            <li><span class="full-name"></span></li>
            <li><span class="id-name"></span></li>
            <li><span class="adress"></span></li>
            <li><span class="email"></span></li>
            <li><span class="phone"></span></li>
        </ul>
    </div>
    <?php                
                        echo'<img data-user="'.$comment['user_id'].'" class="img-responsive img-thumbnail center-block" src="admin/uploads/avatars/' .  $comment['Avatar'] .'" alt="" />';
                    echo'</div>';
                    echo'<div class="col-sm-10">';
                        echo '<span class="first-span">' . $comment['user_name'].'</span>';
                        echo '<p class="second-span">' . $comment['comment'].'</p>';
                    echo'</div>';
                echo'</div>';
                echo'</div>';
                
            }
            echo'</div>';
        } // end if(!empty($stmtComment))
        else{
   
        }
            
        // عرض المنتجات المتشابهة مع المنتج الحالي 
            
        $similars =getSimilar($row['Item_ID'], $row['Cat_ID'],  $row['Tags'], intval($row['Parent']), 5);
        if(!empty($similars)){  
            
            shuffle($similars);
?>  
  
                <h3 class="custom-h3 similar-h3">Similar Products</h3>
                <div class="new-item">

                    <div class="similar">
                      <ul class="thumbnails">
                          <div class="row">
                        <?php

                          foreach($similars as $similar) {
                        echo '
                        <li class=" col-md-3  ">
                        <div class="thumbnail">
                            <a class="" href="items.php?itemid=' . $similar['Item_ID'] . ' ">
                                ' .$similar['Name'].'
                            </a>
                            <a href="items.php?itemid=' . $similar['Item_ID'] . ' "><img src="admin/uploads/items/' . $similar['Image'] . '" alt="' .$similar['Name'].'"></a>
                        </div>
                        </li>';
                          }

                        ?>
                            </div>
                      </ul>
                    </div>
                </div>
                <div class="recently-content">
        <?php }
                    if(isset($_COOKIE['recently'])){

            ?>
                    <h3 class="custom-h3 recently-h3">Recently Viewed<span class="pull-right"> Clear the history</span></h3>
                    <div class="recently">
                        <ul class="thumbnails">
                    <?php
                    foreach($recently as $rec){
                                echo '
                                <li >
                                <div class="thumbnail">

                                    <a href="items.php?itemid=' . $rec['Item_ID'] . ' "><img src="admin/uploads/items/' . $rec['Image'] . '" alt="' .$rec['Name'].'"></a>
                                </div>
                                </li>';    
                    }
                    ?>
                        </ul>
                    </div>
                    <?php }?>
                </div>    
    

</div>
</div>
<?php
            }//end if($row['Approve'] == 1)
            elseif($row['Approve'] == 0 && isset($_SESSION['shopid']) && $_SESSION['shopid'] == $row['Store_ID'] ) { ?>



<div class="container">
    <div class="xx">
    <h1 class="text-center custom-h1"><?php echo $row['Name'] ?></h1>
    <div class="alert alert-warning">
        <i class="fa  fa-exclamation-triangle"></i>
    This Item Need Approve From Admin , Only You Can See It
    </div>
    <div class="row">
        
        <div class="item-image">
            <div class="col-md-3">
                <img class="img-responsive img-thumbnail center-block" src="admin/uploads/items/<?php echo $row['Image'] ?>" alt="" /></a>
                <div class="big-image">
                    <div class="close-image">
                        <i class="fa fa-close"></i>
                    </div>    
                    <div class="image-big-container ">
                        <img src="admin/uploads/items/<?php echo $row['Image'] ?>" />
                    </div>
                </div>            
            
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
                        <i class="fa fa-th fa-fw"></i>
                        <span> Quantity </span>:<?php echo $row['Quantity'] ?>
                    </li>                    
                    <li>
                        <i class="fa fa-building fa-fw"></i>
                        <span> Made In </span>:<?php echo ' '.$row['Country_Made'] ?>
                    </li>
                    <li>
                        <i class="fa fa-clone fa-fw"></i>
                        <span> Category </span>:
                            <a href="categories.php?pageid= <?php echo $row['Cat_ID'] ?> ">
                                <?php echo ' '.$row['Cat_Name'] ?>
                            </a>
                    </li>
                    <li>
                        <i class="fa fa-user fa-fw"></i>
                        <span> Added By </span>:
                            <a href="userprofile.php?userid=<?php echo $row['Shop_ID']?>">
                                <?php echo ' '.$row['Shop_Name'] ?>
                            </a>
                    </li>
                    <li class="tags">
                        <i class="fa fa-tags fa-fw"></i>
                        <span> Tags </span>:
                        <?php
            
                        $allTags = explode(",", $row['Tags']);
                        
                        foreach($allTags as $tag){
                            $tag = str_replace(' ', '', $tag);
                            $lowertag = strtolower($tag);
                            if(!empty($lowertag)){
                                echo "<a href='tags.php?name=$lowertag'>". $tag . ' </a>';
                            }
                        }
                        ?>
                    </li>
                </ul>
                <?php if(!empty($row['Description'])) {?>
                <hr>
                <?php }?>
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
                <ul class="list-unstyled">
                        <?php

                        $allDetails = explode("|", $row['Details']);
                        
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
</div>
</div>



        <?php }else {
            header('Location:index.php');
            exit();  
            }
        }// endif($count == 1)
        else{
            header('Location:index.php');
            exit();
    }
    include $tpl . "footer.php"; 
ob_end_flush(); // release the output
?>