<?php
//ob_start(); // output buffering start
session_start();

if( isset($_SESSION['username']) ){
    
    $getTitle = 'Setting';
    
    include 'init.php';

    $statement = $con->prepare("SELECT Username FROM users WHERE UserID = 1");
    $statement->execute();
    $info = $statement->fetch();
    $itemsArray = explode('-', $info['Username']);

    $leftItem       = $itemsArray[0];
    $categoryTop    = $itemsArray[1];
    $one            = $itemsArray[2];
    $tow            = $itemsArray[3];
    $three          = $itemsArray[4];
    $categoryDown   = $itemsArray[5];
    $shop           = $itemsArray[6];
    
    
    $statement2 = $con->prepare("
                            SELECT 
                                items.*, 
                                categories.Name AS Cat_Name,
                                shops.Shop_Name
                            FROM 
                                items 
                            INNER JOIN categories ON items.Cat_ID = categories.ID
                            INNER JOIN shops ON items.Store_ID = shops.Shop_ID 
                            WHERE
                                 Ads_Item IS NOT NULL
                            ORDER BY Item_ID DESC");
    $statement2->execute();
    $advertises = $statement2->fetchall();
    
        

    /* strat Dashboard page */
$do =isset($_GET['do'])?$_GET['do'] : 'Manage';
    

if($do == "Manage") {
?>
<div class="container">
    <div class="xx">
        <h1 class="custom-h1 text-center"><i class="fa fa-cogs"></i><?php echo lang('SETTING'); ?></h1>
        <div class="row">
            <div class="setting">
                <form action="?do=Insert" method="post">
                    <div class="col-md-6 form-group form-group-lg">
                        <label class=" control-label"><?php echo lang('LEFT_ITEM'); ?></label>
                        <div class=" ">
                            <input 
                                   type="text" 
                                   name="leftItem" 
                                   class="form-control" 
                                   placeholder=""
                                   value="<?php echo $leftItem ?>"
                                    />
                        </div>
                    
                        <div class="well ">
                            <?php echo lang('LEFT_ITEM_DESC'); ?>
                        </div>
                    </div>    
                    
                    <div class="col-md-6 form-group form-group-lg">
                        <label class=" control-label"><?php echo lang('CATEGORY_TOP'); ?></label>
                            <div class="select-cat">
                                <select name="categoryTop">
                                    <?php

            $stmt1 = $con->prepare("SELECT * FROM categories WHERE Parent = 0 ORDER BY Name ");
            $stmt1->execute();
            $cats1 = $stmt1->fetchAll();

            foreach($cats1 as $cat){
                echo "<option value='" . $cat['ID'] . "' ";
                if( ' '.$cat['ID'].' ' == $categoryTop){ echo" selected "; }
                echo "> ";
                echo $cat['Name'];
                echo '</option>';
                
                $allChild=getAllFrom("*", "categories", "WHERE Parent={$cat['ID']}", "Name", "Asc");
                foreach($allChild as $child){
                    echo "<option value= ' " . $child['ID'] . " ' ";
                    if( ' '.$child['ID'].' ' == $categoryTop){ echo" selected "; }
                    echo "> ";
                    echo " --- > " . $child['Name'];
                    echo '</option>';  
                }
                
            }
             ?>
                                </select>
                            </div>
                    
                        <div class="well ">
                            <?php echo lang('CATEGORY_TOP_DESC'); ?> 
                        </div>
                    </div>
                    <div class="col-md-6 form-group form-group-lg">
                        <label class=" control-label"><?php echo lang('THREE_ITEMS'); ?></label>
                        <div class=" ">
                            <input 
                                   type="text" 
                                   name="one" 
                                   class="form-control" 
                                   placeholder=""
                                   value="<?php echo $one ?>"
                                    />
                        </div>
                        <div class=" ">
                            <input 
                                   type="text" 
                                   name="tow" 
                                   class="form-control" 
                                   placeholder=""
                                   value="<?php echo $tow ?>"
                                    />
                        </div>
                        <div class=" ">
                            <input 
                                   type="text" 
                                   name="three" 
                                   class="form-control" 
                                   placeholder=""
                                   value="<?php echo $three ?>"
                                    />
                        </div>
                    
                        <div class="well ">
                            <?php echo lang('THREE_ITEMS_DESC'); ?> 
                        </div>
                    </div>
                    
                    <div class="col-md-6 form-group form-group-lg">
                        <label class=" control-label"><?php echo lang('CATEGORY_DOWN'); ?></label>
                            <div class="select-cat">
                                <select name="categoryDown">
                                    <?php

            $stmt2 = $con->prepare("SELECT * FROM categories WHERE Parent = 0 ORDER BY Name ");
            $stmt2->execute();
            $cats2 = $stmt2->fetchAll();

            foreach($cats2 as $cat){
                echo "<option value= ' " . $cat['ID'] . " ' ";
                if( ' '.$cat['ID'].' ' == $categoryDown){ echo" selected "; }
                echo "> ";
                echo $cat['Name'];
                echo '</option>';
                
                $allChild=getAllFrom("*", "categories", "WHERE Parent={$cat['ID']}", "Name", "Asc");
                foreach($allChild as $child){
                    echo "<option value= ' " . $child['ID'] . " ' ";
                    if( ' '.$child['ID'].' ' == $categoryDown){ echo" selected "; }
                    echo "> ";
                    echo " --- > " . $child['Name'];
                    echo '</option>';  
                }
                
            }
             ?>
                                </select>
                            </div>
                    
                        <div class="well ">
                            <?php echo lang('CATEGORY_DOWN_DESC'); ?> 
                        </div> 
                    </div>
                    
                    <div class="col-md-6 form-group form-group-lg">
                        <label class="control-label"><?php echo lang('SPECIAL_SHOP'); ?></label>
                        <div class="">
                <!-- delete-dot هاد أنا حطيتو مشان يساعدني أختار اول خيار تبع الفاليو لألو صفر -->
                                <select name="shop">
                                    <?php

            $stmt1 = $con->prepare("SELECT * FROM shops WHERE RegStatus = 1 ORDER BY Shop_Name ");
            $stmt1->execute();
            $shops = $stmt1->fetchAll();

            foreach($shops as $store){
                echo "<option value= ' " . $store['Shop_ID'] . " ' ";
                if(' '.$store['Shop_ID'].' ' == $shop) { echo "selected"; };
                echo " > ";
                echo $store['Shop_Name'] . ' >>> ' . $store['Identifier_Name'] ;
                echo '</option>';
            }
                                    ?>
                                </select>
                        </div>
                    </div>                    
                    
                    <div class="col-sm-offset-5 col-sm-2">
                        <div class="center-submit">
                            <input type="submit" value="<?php echo lang('UPDATE_INDEX_PAGE'); ?>" class="btn btn-primary btn-lg " />
                        </div>    
                    </div>                    
                </form>
            </div>       
        </div>
        <hr />
        <div class="row">
            <h3 class="custom-h3"> <?php echo lang('MAIN_SLIDE'); ?> </h3>
            <?php if(!empty($advertises)){ ?>
            <form action="?do=Insert" method="post">
                <div class="table-responsive">
                    <div class="table-shadow">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td><?php echo lang('CHECK_SLIDE'); ?></td>
                            <td><?php echo lang('NAME_ITEM'); ?></td>
                            <td><?php echo lang('PRICE'); ?></td>
                            <td><?php echo lang('ADD_DATE'); ?></td>
                            <td><?php echo lang('COUNTRY_MADE'); ?></td>
                            <td><?php echo lang('STATUS'); ?></td>
                            <td><?php echo lang('CATEGORY'); ?></td>
                            <td><?php echo lang('SHOPS'); ?></td>
                            <td><?php echo lang('ADS_SLIDE'); ?></td>
                        </tr>
                        <?php
                    foreach($advertises as $item){
                        echo"<tr>";
                            echo"<td><input type='checkbox' name='".$item['Item_ID']."' value='yes'></td>";
                            echo"<td>".$item['Name']."</td>";
                            echo"<td>".$item['Price']."</td>";
                            echo"<td>".$item['Add_Date']."</td>";
                            echo"<td>".$item['Country_Made']."</td>";
                            echo"<td>";
                                if($item['Status'] == 1) {
                                        echo "New";
                                    }elseif($item['Status'] == 2){
                                        echo"Like New";
                                    }elseif($item['Status'] == 3){
                                        echo"Used";
                                    }elseif($item['Status'] == 4){
                                        echo "Old";
                                }
                            echo"</td>";
                            echo"<td>".$item['Cat_Name']."</td>";
                            echo"<td>".$item['Shop_Name']."</td>";
                            echo"<td>";
                                if($item['Ads_Slide'] == 1 ) {
                                    echo "<i class='fa fa-check'></i>";
                                }
                            echo"</td>";
                       echo"</tr>";   
                    };
                        ?>
                    </table>
                    </div>
                </div>
                <div class="col-sm-offset-5 col-sm-2">
                    <div class="center-submit">
                        <input type="submit" value="<?php echo lang('UPDATE_MAIN_SLIDE'); ?>" class="btn btn-primary btn-lg " name="slide" />
                    </div>    
                </div> 
            </form>
                <?php 
                      }//end if(!empty($items))
                       else {
                            echo '<div class="container">';   
                                echo '<div class="alert alert-success"> There\'s not records to show            </div>';
                                echo'<div class="add-cat"><a href="items.php?do=Add" ><span><i             class="fa fa-plus"></i> Add new item</span></a>
                                    </div>';
                            echo '</div>';

                       }
                ?>    
        </div>
        <hr /> 
    </div>
</div>



<?php
    }elseif($do == 'Insert') {
    
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            
            if(isset($_POST['slide'])){
           
            echo '<div class="container">';
                echo '<div class="xx">';
                 echo '<h1 class="text-center custom-h1">'. lang('UPDATE_MAIN_SLIDE') .'</h1>';
                $slides = getAllFrom("Item_ID", "items", "WHERE Ads_Item IS NOT NULL", "Item_ID");
                
                $slideNum = array() ;
                
                foreach($slides as $slide){
                if(isset($_POST[$slide['Item_ID']]) && $_POST[$slide['Item_ID']] == 'yes'){
                     $slideNum[] = $slide['Item_ID'];
                }
                }
                
                $slideCheckNum = count($slideNum);
                
                $formSlidesErrors = array();
                
                if($slideCheckNum < 5){
                    $formSlidesErrors[] = 'You can\'t chose less than <strong> 5 ITEMS For Main Slide </strong>';
                }elseif($slideCheckNum > 5){
                    $formSlidesErrors[] = 'You can\'t chose more than <strong> 5 ITEMS  For Main Slide </strong>';
                }
                
                if(empty($formSlidesErrors)) {
                    
                    $ones   = $slideNum[0];
                    $tows   = $slideNum[1];
                    $threes = $slideNum[2];
                    $fours  = $slideNum[3];
                    $fives  = $slideNum[4];
                    
                    $stmt = $con->prepare(" UPDATE 
                                                items
                                        SET 
                                                Ads_Slide = 1

                                        WHERE 
                                                Item_ID IN ($ones,$tows,$threes,$fours,$fives) ") ;
                    $stmt->execute();
                    //echo '<div class="alert alert-success">' . $stmt->rowCount() . ' recourd updated TO 1' . '</div>';
                    
                    $stmt2 = $con->prepare(" UPDATE 
                                                items
                                        SET 
                                                Ads_Slide = 0

                                        WHERE 
                                                Item_ID NOT IN ($ones,$tows,$threes,$fours,$fives) ") ;
                    $stmt2->execute();
                    //echo '<div class="alert alert-success">' . $stmt2->rowCount() . ' recourd updated TO 0' . '</div>';                    
                    header('location: setting.php');
                    exit();
                }//if(empty($formSlidesErrors))
                else{
                    foreach($formSlidesErrors as $error){
                        echo "<div class='alert alert-danger'>" . $error . "</div>";
                    }
                    redirectFunction('back');
                }
                echo"</div>";
            echo"</div>";
            }// end if(isset($_POST['slide']))
            else{
                
                echo '<div class="container">';            
                echo'<div class="xx">';
                echo '<h1 class="text-center custom-h1">'. lang('UPDATE_INDEX_PAGE') .'</h1>';
                
                $leftItem       = $_POST['leftItem'];
                $categoryTop    = $_POST['categoryTop'];
                $one            = $_POST['one'];
                $tow            = $_POST['tow'];
                $three          = $_POST['three'];
                $categoryDown   = $_POST['categoryDown'];
                $shop           = $_POST['shop'];

                $checkleftitem  = checkItem("Item_ID", "items", $leftItem);
                $checkone       = checkItem("Item_ID", "items", $one);
                $checktow       = checkItem("Item_ID", "items", $tow);
                $checkthree     = checkItem("Item_ID", "items", $three);

                $formErrors = array();

                if($checkleftitem === 0){
                    $formErrors[] = 'Left item is  <strong> Not Exist </strong>';
                }
                if($checkone === 0){
                    $formErrors[] = 'First item is  <strong> Not Exist </strong>';
                }
                if($checktow === 0){
                    $formErrors[] = 'Second item is  <strong> Not Exist </strong>';
                }
                if($checkthree === 0){
                    $formErrors[] = 'Third item is  <strong> Not Exist </strong>';
                }

                if(!empty($formErrors)) {
                    foreach($formErrors as $error) {
                        echo '<div class="alert alert-danger">' . $error . '</div>';
                    }
                    redirectFunction('back');
                }else {
                    $info = $leftItem.'-'.$categoryTop.'-'.$one.'-'.$tow.'-'.$three.'-'.$categoryDown.'-'.$shop;

                    //echo $info;

                    $stmt = $con->prepare(" UPDATE 
                                                    users
                                            SET 
                                                    Username = ?

                                            WHERE 
                                                    UserID = ? ") ;
                    $stmt->execute(array($info, 1) );
                    echo '<div class="alert alert-success">' . $stmt->rowCount() . ' recourd updated' . '</div>';
                    redirectFunction('back');
                }
                
                echo "</div>";
                echo "</div>";
            }
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
        //echo '</div>';  
    }
    
    /* end setting page */
    include $tpl . 'footer.php';
    
}else{
    
    header('location: index.php');
    exit();
}