<?php
ob_start();
session_start();

if( isset($_SESSION['username']) ){
    
    $getTitle = 'Comments';
    
    include 'init.php';
    
    $do =isset($_GET['do'])?$_GET['do'] : 'Manage';
    
    if($do == 'Manage') {
        
        $orderArray = array('c_id', 'item_name', 'user_name');
        $order = isset($_GET['order']) && in_array($_GET['order'],$orderArray)?$_GET['order']:'c_id';
        
        $sort = 'DESC';
        $sortArray = array( 'ASC', 'DESC' );
        if(isset($_GET['sort']) && in_array($_GET['sort'] , $sortArray )){
            $sort =$_GET['sort'];
        }        
        
        $query = '';
        if(isset($_GET['page']) && $_GET['page'] == 'Approve'){
            $query = 'WHERE comments.status = 0 ';
        }elseif(isset($_GET['page']) && $_GET['page'] == 'All'){
            $query = '';
        }
        
        $stmt = $con->prepare("SELECT 
                                    comments.*,
                                    items.Name AS item_name,
                                    users.Username AS user_name
                                FROM 
                                    comments    
                                INNER JOIN items ON items.Item_ID = comments.item_id
                                INNER JOIN users ON users.UserID = comments.user_id
                                $query
                                ORDER BY $order $sort");
        /*$stmt = $con->prepare("SELECT 
                                    *
                                FROM 
                                    comments
                                WHERE status = 0     
                                ORDER BY $order $sort");*/
        $stmt->execute();
        $rows = $stmt->fetchAll();
?>


<?php if(!empty($rows)){ ?>
<div class="container">
    <div class="xx">
    <h1 class="text-center custom-h1"><i class="fa fa-comments-o"></i><?php echo lang('MANAGE_COMMENTS'); ?></h1>
    <div class="table-responsive">
        <div class="table-shadow">
        <table class="main-table text-center table table-bordered">
            <tr>
                <td><?php echo lang('#ID'); ?>
                    <div class="order-sort">
                    <a href="comments.php?order=c_id&sort=ASC"><i class="fa fa-arrow-up"></i></a>
                    <a href="comments.php?order=c_id&sort=DESC"><i class="fa fa-arrow-down"></i></a>
                    </div>                 
                </td>
                <td><?php echo lang('MEMBER_NAME'); ?>
                    <div class="order-sort">
                    <a href="comments.php?order=user_name&sort=ASC"><i class="fa fa-arrow-up"></i></a>
                    <a href="comments.php?order=user_name&sort=DESC"><i class="fa fa-arrow-down"></i></a>
                    </div>                 
                </td>
                <td><?php echo lang('COMMENT'); ?></td>
                <td><?php echo lang('NAME_ITEM'); ?>
                    <div class="order-sort">
                    <a href="comments.php?order=item_name&sort=ASC"><i class="fa fa-arrow-up"></i></a>
                    <a href="comments.php?order=item_name&sort=DESC"><i class="fa fa-arrow-down"></i></a>
                    </div>   
                </td>
                <td><?php echo lang('ADD_DATE'); ?>
                    <div class="order-sort">
                    <a href="comments.php?order=c_id&sort=ASC"><i class="fa fa-arrow-up"></i></a>
                    <a href="comments.php?order=c_id&sort=DESC"><i class="fa fa-arrow-down"></i></a>
                    </div>  
                </td>
                <td><?php echo lang('CONTROL'); ?>
                    <div class="order-sort">
                    <a href="comments.php?page=Approve"><?php echo lang('APPROVE'); ?></a>
                    <span>-----</span>
                    <a href="comments.php?page=All"><?php echo lang('ALL'); ?></a>
                    </div>                
                </td>
            </tr>
            <?php
        foreach($rows as $row){
            echo"<tr>";
                echo"<td>".$row['c_id']."</td>";
                echo"<td>".$row['user_name']."</td>";
                echo"<td>".$row['comment']."</td>";
                echo"<td>";
                    echo"".$row['item_name']."";
                echo"</td>";
                echo"<td>".$row['comment_date']."</td>";
                echo"<td class = 'object-control'>
                        <a href='comments.php?do=Delete&comid=" .$row['c_id']. "' class='confirm btn btn-danger'><i class='table-btn-icon-position fa fa-close'></i>". lang('DELETE') ."</a>";
            
                if($row['status'] == 0) {
                    echo"<a href='comments.php?do=Approve&comid=" .$row['c_id']. "' class=' btn btn-info activate'><i class='table-btn-icon-position fa fa-check'></i>". lang('APPROVE') ."</a>";
                }
                echo"</td>";
           echo"</tr>";   
        };
            ?>
        </table>
        </div>
    </div>
    </div>
</div>
    <?php 
          }//end if(!empty($rows))
           else { ?>
                <div class="container">
                <div class="xx">
                    <h1 class="text-center custom-h1"><?php echo lang('MANAGE_COMMENTS'); ?></h1> 
                    <div class="alert alert-success"> There's not records to show  </div>
                    </div>
               </div>
               
         <?php  }
    ?>
 <?php } // end if($do == 'Manage')
 
 elseif($do == 'Delete'){

            $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

            $count = checkItem('c_id', 'comments', $comid);

            if($count > 0){
                echo '<div class="container">';
                echo '<div class="xx">';
                echo '<h1 class="text-center custom-h1">'. lang('DELETE_COMMENT') .'</h1>';
                
                $stmt = $con->prepare("DELETE FROM comments WHERE c_id = :zcom");
                $stmt->bindParam(":zcom", $comid);
                $stmt->execute();
                echo '<div class="alert alert-success">' . $stmt->rowCount() . ' recourd deleted' . '</div>';
                redirectFunction('back');
                echo '</div>';
                echo '</div>';
            }else{
            echo '<div class="container">';
                echo '<div class="xx">';           
                echo '<h1 class="custom-h1 text-center"><i class="fa fa-warning"></i> Access Denied</h1>';
                echo "<div class='alert alert-danger'>There is't comment with this value</div>";
                redirectFunction('home');
                echo '</div>';
            echo '</div>';
            }
    }//end if($do == 'Delete')

    elseif($do == 'Approve'){
        
        
            $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

            //$count = checkItem('c_id', 'comments', $comid);
            $stmt = $con->prepare("SELECT item_id, user_id FROM comments WHERE c_id =?");
            $stmt->execute(array($comid));
            $count = $stmt->rowCount();
        
            if($count > 0){
                $comment = $stmt->fetch();
                echo '<div class="container">';
                echo '<div class="xx">';
                echo '<h1 class="text-center custom-h1">'. lang('APPROVE_COMMENT') .'</h1>';
                
                // الموافقة على المنتج 
                $stmtA = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ?");    
                $stmtA->execute(array($comid));
                
                // وصول اشعار الى المستخدم صاحب التعليق بان الأدمن وافق على تعليقه
                $stmt = $con->prepare(" INSERT INTO 
                                        user_notif_comment (User_ID, Comment_ID)
                                        VALUES(:zuser, :zcomment)");
                $stmt->execute(array(
                    'zuser' => $comment['user_id'],
                    'zcomment' => $comid
                ));
                
                // وصول اشعار الى البائع بوجود تعليق جديد على منتجه
                
                // الحصول على الأي دي الخاص بالمتجر الذي نشر المنتج
                $stmt = $con->prepare("SELECT Store_ID FROM items WHERE Item_ID = ?");
                $stmt->execute(array($comment['item_id']));
                $shop = $stmt->fetch();
                
                $stmt = $con->prepare(" INSERT INTO 
                                        shop_notif_comment (Shop_ID, Comment_ID)
                                        VALUES(:zshop, :zcomment)");
                $stmt->execute(array(
                    'zshop' => $shop['Store_ID'],
                    'zcomment' => $comid
                ));
                
                echo '<div class="alert alert-success">' . $stmtA->rowCount() . ' recourd updated' . '</div>';
                redirectFunction('back');
                echo '</div>';
                echo '</div>';
            }else{
            echo '<div class="container">';
                echo '<div class="xx">';           
                echo '<h1 class="custom-h1 text-center"><i class="fa fa-warning"></i> Access Denied</h1>';
                echo "<div class='alert alert-danger'>There is't comment with this value</div>";
                redirectFunction('home');
                echo '</div>';
            echo '</div>';
            }
    }//end if($do == 'Approve')
    
    include $tpl . 'footer.php';
    
} else { // end  if (isset($_SESSION['username']) )
 
    
    header('location: index.php');
    exit();
}
ob_end_flush(); // release the output
?>