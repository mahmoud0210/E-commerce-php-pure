<?php
ob_start();

session_start();

if( isset($_SESSION['username']) ){
    
    $getTitle = 'Shops';
    
    include 'init.php';
    
    $do =isset($_GET['do'])?$_GET['do'] : 'Manage';
    
    if($do == 'Manage') {
        
        $orderArray = array('Shop_ID', 'Shop_Name', 'Email', 'Identifier_Name');
        $order = isset($_GET['order']) && in_array($_GET['order'],$orderArray)?$_GET['order']:'Shop_ID';
        
        $sort = 'DESC';
        $sortArray = array( 'ASC', 'DESC' );
        if(isset($_GET['sort']) && in_array($_GET['sort'] , $sortArray )){
            $sort =$_GET['sort'];
        }        
        
        $query = '';
        if(isset($_GET['page']) && $_GET['page'] == 'Pending'){
            $query = 'WHERE RegStatus = 0';
        }elseif(isset($_GET['page']) && $_GET['page'] == 'All'){
            $query = '';
        }
        
        $stmt = $con->prepare("SELECT * FROM shops $query ORDER BY $order $sort");
        $stmt->execute();
        $rows = $stmt->fetchAll();
?>


<?php if(!empty($rows)){ ?>
<div class="container">
    <div class="xx">
    <h1 class="text-center custom-h1"><img src="uploads/login/store_admin.png" alt="store" style="max-width: 70px;"/><?php echo lang('MANAGE_SHOPS'); ?></h1>

        
    <div class="table-responsive">
        <div class="table-shadow">
        <table class="main-table text-center table table-bordered">
            <tr>
                <td><?php echo lang('#ID'); ?>
                    <div class="order-sort">
                    <a href="shops.php?order=Shop_ID&sort=ASC"><i class="fa fa-arrow-up"></i></a>
                    <a href="shops.php?order=Shop_ID&sort=DESC"><i class="fa fa-arrow-down"></i></a>
                    </div>                 
                </td>
                <td><?php echo lang('SHOP_NAME'); ?>
                    <div class="order-sort">
                    <a href="shops.php?order=Shop_Name&sort=ASC"><i class="fa fa-arrow-up"></i></a>
                    <a href="shops.php?order=Shop_Name&sort=DESC"><i class="fa fa-arrow-down"></i></a>
                    </div>                
                </td>
                <td><?php echo lang('IDENTIFIER_NAME'); ?>
                    <div class="order-sort">
                    <a href="shops.php?order=Identifier_Name&sort=ASC"><i class="fa fa-arrow-up"></i></a>
                    <a href="shops.php?order=Identifier_Name&sort=DESC"><i class="fa fa-arrow-down"></i></a>
                    </div>                
                </td>                
                <td><?php echo lang('EMAIL'); ?>
                    <div class="order-sort">
                    <a href="shops.php?order=Email&sort=ASC"><i class="fa fa-arrow-up"></i></a>
                    <a href="shops.php?order=Email&sort=DESC"><i class="fa fa-arrow-down"></i></a>
                    </div> 
                </td>
                <td><?php echo lang('ADRESS'); ?>
                    <div class="order-sort">
                    <a href="shops.php?order=Adress&sort=ASC"><i class="fa fa-arrow-up"></i></a>
                    <a href="shops.php?order=Adress&sort=DESC"><i class="fa fa-arrow-down"></i></a>
                    </div>                 
                </td>
                <td><?php echo lang('REGISTERD_DATE'); ?>
                    <div class="order-sort">
                    <a href="shops.php?order=Shop_ID&sort=ASC"><i class="fa fa-arrow-up"></i></a>
                    <a href="shops.php?order=Shop_ID&sort=DESC"><i class="fa fa-arrow-down"></i></a>
                    </div>                  
                </td>
                <td><?php echo lang('CONTROL'); ?>
                    <div class="order-sort">
                    <a href="shops.php?page=Pending"><?php echo lang('ACTIVATE'); ?></a>
                    <span>-----</span>
                    <a href="shops.php?page=All"><?php echo lang('ALL'); ?></a>
                    </div>                
                </td>
            </tr>
            <?php
        foreach($rows as $row){
            echo"<tr>";
                echo"<td>".$row['Shop_ID']."</td>";
                echo"<td>".$row['Shop_Name']."</td>";
                echo"<td>".$row['Identifier_Name']."</td>";
                echo"<td>".$row['Email']."</td>";
                echo"<td>".$row['Adress']."</td>";
                echo"<td>".$row['addDate']."</td>";
                echo"<td>
                        <a href='shops.php?do=Delete&shopid=" .$row['Shop_ID']. "' class='confirm btn btn-danger'><i class='table-btn-icon-position fa fa-close'></i>". lang('DELETE') ."</a>";
            
                if($row['RegStatus'] == 0) {
                    echo"<a href='shops.php?do=Activate&shopid=" .$row['Shop_ID']. "' class=' btn btn-info activate'><i class='table-btn-icon-position fa fa-check'></i>". lang('ACTIVATE') ."</a>";
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
                    <h1 class="text-center custom-h1"><?php echo lang('MANAGE_SHOPS'); ?></h1> 
                    <div class="alert alert-success"> There's not records to show  </div>

                    </div>
               </div>
               
         <?php  }
    ?>
    
 <?php } // end if($do == 'Manage')    

    elseif($do == 'Delete'){
        
            $shopid = isset($_GET['shopid']) && is_numeric($_GET['shopid']) ? intval($_GET['shopid']) : 0;

            $stmt = $con->prepare("SELECT * 
                                   FROM shops 
                                   WHERE Shop_ID = ? 
                                   LIMIT 1 ");
            $stmt->execute(array($shopid));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();

            if($count > 0){
                echo '<div class="container">';
                echo '<div class="xx">';
                echo '<h1 class="text-center custom-h1">'. lang('DELETE_SHOP') .'</h1>';                

                $stmt = $con->prepare("DELETE FROM shops WHERE Shop_ID = :zuser");
                $stmt->bindParam(":zuser", $shopid);
                $stmt->execute();
                
                if($row['Avatar'] == 'store.png') {

                }else {
                    unlink('uploads/Shops/' . $row['Avatar']);
                }
                
                echo '<div class="alert alert-success">' . $stmt->rowCount() . ' recourd deleted' . '</div>';
                redirectFunction('back');
                echo '</div>';
                echo '</div>';
            }else{
            echo '<div class="container">';
                echo '<div class="xx">';           
                echo '<h1 class="custom-h1 text-center"><i class="fa fa-warning"></i> Access Denied</h1>';
                echo "<div class='alert alert-danger'>There is't Shop with this value</div>";
                redirectFunction('home');
                echo '</div>';
            echo '</div>';
            }
        
    }//end if($do == 'Delete')

    elseif($do == 'Activate'){

        
            $shopid = isset($_GET['shopid']) && is_numeric($_GET['shopid']) ? intval($_GET['shopid']) : 0;

            $stmt = $con->prepare("SELECT * 
                                   FROM shops 
                                   WHERE Shop_ID = ? 
                                   LIMIT 1 ");
            $stmt->execute(array($shopid));
            $count = $stmt->rowCount();

            if($count > 0){
                echo '<div class="container">';
                echo '<div class="xx">';
                echo '<h1 class="text-center custom-h1">'. lang('ACTIVATE_SHOP') .'</h1>';                

                $stmt = $con->prepare("UPDATE shops SET RegStatus = 1 WHERE Shop_ID = ?");    
                $stmt->execute(array($shopid));
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