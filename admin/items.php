<?php

/**********************************
************ Item Page ************
***********************************
*/
ob_start(); // output buffering start

session_start();

$getTitle = 'Items';

if (isset($_SESSION['username'])){
    
    include "init.php";
    
    $do =isset($_GET['do'])?$_GET['do'] : 'Manage';
    
    if($do == "Manage") {
        
        $orderArray = array('Name', 'Price', 'Item_ID', 'Country_Made', 'Status','Cat_Name', 'Shop_Name');
        $order = isset($_GET['order']) && in_array($_GET['order'],$orderArray)?$_GET['order']:'Item_ID';
        
        // price in database is string so i convert it to integer to sort it correctly
        if($order == 'Price'){
            $order = "CAST(PRICE AS INT)";
        }
        
        $sort = 'DESC';
        $sortArray = array( 'ASC', 'DESC' );
        if(isset($_GET['sort']) && in_array($_GET['sort'] , $sortArray )){
            $sort =$_GET['sort'];
        } 
        
        $query = '';
        if(isset($_GET['page']) && $_GET['page'] == 'Pending'){
            $query = ' WHERE items.Approve = 0 ';
        }elseif(isset($_GET['page']) && $_GET['page'] == 'All'){
            $query = '';
        }        
        $stmt = $con->prepare("
                    SELECT 
                        items.*, 
                        categories.Name AS Cat_Name,
                        shops.Shop_Name
                    FROM 
                        items    
                    INNER JOIN categories ON items.Cat_ID = categories.ID
                    INNER JOIN shops ON items.Store_ID = shops.Shop_ID
                    $query
                    ORDER BY $order $sort");
        $stmt->execute();
        $items = $stmt->fetchAll();
?>


<?php if(!empty($items)){ ?>
<div class="container">
    <div class="xx">
    <h1 class="text-center custom-h1"><i class="fa fa-inbox"></i> <?php echo lang('MANAGE_ITEMS'); ?> </h1>
        <!--
        <div class="add-cat">
            <a href="items.php?do=Add" >
                <span><i class="fa fa-plus"></i><?php echo lang('ADD_NEW_ITEM'); ?></span>
            </a>
        </div> -->
    
    <div class="table-responsive">
        <div class="table-shadow">
        <table class="main-table text-center table table-bordered">
            <tr>
                <td><?php echo lang('#ID'); ?>
                    <div class="order-sort">
                    <a href="items.php?order=Item_ID&sort=ASC"><i class="fa fa-arrow-up"></i></a>
                    <a href="items.php?order=Item_ID&sort=DESC"><i class="fa fa-arrow-down"></i></a>
                    </div>                 
                </td>                
                <td><?php echo lang('NAME_ITEM'); ?>
                    <div class="order-sort">
                    <a href="items.php?order=Name&sort=ASC"><i class="fa fa-arrow-up"></i></a>
                    <a href="items.php?order=Name&sort=DESC"><i class="fa fa-arrow-down"></i></a>
                    </div>                
                </td>
                <td><?php echo lang('PRICE'); ?>
                    <div class="order-sort">
                    <a href="items.php?order=Price&sort=ASC"><i class="fa fa-arrow-up"></i></a>
                    <a href="items.php?order=Price&sort=DESC"><i class="fa fa-arrow-down"></i></a>
                    </div>
                </td>
                <td><?php echo lang('ADD_DATE'); ?>
                    <div class="order-sort">
                    <a href="items.php?order=Item_ID&sort=ASC"><i class="fa fa-arrow-up"></i></a>
                    <a href="items.php?order=Item_ID&sort=DESC"><i class="fa fa-arrow-down"></i></a>
                    </div>               
                </td>
                <td><?php echo lang('COUNTRY_MADE'); ?>
                    <div class="order-sort">
                    <a href="items.php?order=Country_Made&sort=ASC"><i class="fa fa-arrow-up"></i></a>
                    <a href="items.php?order=Country_Made&sort=DESC"><i class="fa fa-arrow-down"></i></a>
                    </div>                
                </td>
                <td><?php echo lang('STATUS'); ?>
                    <div class="order-sort">
                    <a href="items.php?order=Status&sort=ASC"><i class="fa fa-arrow-up"></i></a>
                    <a href="items.php?order=Status&sort=DESC"><i class="fa fa-arrow-down"></i></a>
                    </div>                
                </td>
                <td><?php echo lang('CATEGORY'); ?>
                    <div class="order-sort">
                    <a href="items.php?order=Cat_Name&sort=ASC"><i class="fa fa-arrow-up"></i></a>
                    <a href="items.php?order=Cat_Name&sort=DESC"><i class="fa fa-arrow-down"></i></a>
                    </div>                
                </td>
                <td><?php echo lang('SHOPS'); ?>
                    <div class="order-sort">
                    <a href="items.php?order=Shop_Name&sort=ASC"><i class="fa fa-arrow-up"></i></a>
                    <a href="items.php?order=Shop_Name&sort=DESC"><i class="fa fa-arrow-down"></i></a>
                    </div>                
                </td>
                <td><?php echo lang('CONTROL'); ?>
                    <div class="order-sort">
                    <a href="items.php?page=Pending"><?php echo lang('APPROVE'); ?></a>
                    <span>-----</span>
                    <a href="items.php?page=All"><?php echo lang('ALL'); ?></a>
                    </div>                  
                </td>
            </tr>
            <?php
        foreach($items as $item){
            echo"<tr>";
                echo"<td>".$item['Item_ID']."</td>";                
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
                echo"<td class = 'object-control'>
                        <a href='items.php?do=Delete&itemid=" .$item['Item_ID']. "' class='confirm btn btn-danger'><i class='table-btn-icon-position fa fa-close'></i>". lang('DELETE') ."</a>";            
                if($item['Approve'] == 0) {
                    echo"<a href='items.php?do=Approve&itemid=" .$item['Item_ID']. "' class=' btn btn-info activate'><i class='table-btn-icon-position fa fa-check'></i>". lang('APPROVE') ."</a>";
                }
                echo"</td>";
    //<a href='items.php?do=Edit&itemid=" .$item['Item_ID']. "' class='btn btn-success'><i class='table-btn-icon-position fa fa-edit'></i>". lang('EDIT') ."</a>        
           echo"</tr>";   
        };
            ?>
        </table>
        </div>
    </div>
        <!--
        <div class="add-cat">
            <a href="items.php?do=Add" >
                <span><i class="fa fa-plus"></i><?php echo lang('ADD_NEW_ITEM'); ?></span>
            </a>
        </div> -->
    </div>
</div>
    <?php 
          }//end if(!empty($items))
           else { ?>
                <div class="container">
                <div class="xx">
                    <h1 class="text-center custom-h1"><?php echo lang('MANAGE_ITEMS'); ?></h1> 
                    <div class="alert alert-success"> There's not records to show  </div>
                    <!--
                    <div class="add-cat">
                            <a href="items.php?do=Add" >
                                <span><i class="fa fa-plus"></i><?php echo lang('ADD_NEW-ITEM'); ?></span>
                            </a>
                    </div> -->
                    </div>
               </div>
               
         <?php  }
    ?>
    
 <?php } // end if($do == 'Manage')
        /*
        elseif($do == 'Add') { ?>
            <div class="container">
                <div class="xx">
                <h1 class="text-center custom-h1"><i class="fa fa-plus"></i> Add Item </h1>

            

                <form class="form-horizontal" action="?do=Insert" method="post" enctype="multipart/form-data">

    <!--form-horizontal bootstrap class : label in left and input in right  required="required"-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-8">
                            <input 
                                   type="text" 
                                   name="name" 
                                   class="form-control" 
                                   placeholder=" Name of the item "
                                   required />
                        </div>
                    </div>


                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10 col-md-8">
                            <input 
                                   type="text" 
                                   name="price" 
                                   class="form-control" 
                                   placeholder=" Price of the item "
                                   required />
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Country</label>
                        <div class="col-sm-10 col-md-8">
                            <input 
                                   type="text" 
                                   name="country" 
                                   class="form-control" 
                                   placeholder=" Country made of the item "
                                   required />
                        </div>
                    </div>
                    
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Status</label>
                        <div class="delete-dot">
                            <div class="col-sm-10 col-md-8">
                                <select name="status">
                                    <option value="0" >...</option>
                                    <option value="1" >New</option>
                                    <option value="2" >Like New</option>
                                    <option value="3" >Used</option>
                                    <option value="4" >Old</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Shops</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="delete-dot">
                <!-- delete-dot هاد أنا حطيتو مشان يساعدني أختار اول خيار تبع الفاليو لألو صفر -->
                                <select name="shop">
                                    <option value="0" >...</option>
                                    <?php

            $stmt1 = $con->prepare("SELECT * FROM shops WHERE RegStatus = 1 ORDER BY Shop_Name ");
            $stmt1->execute();
            $shops = $stmt1->fetchAll();

            foreach($shops as $shop){
                echo "<option value= ' " . $shop['Shop_ID'] . " ' > ";
                echo $shop['Shop_Name'] . ' ( ' . $shop['Identifier_Name'] . ' ) ';
                echo '</option>';
            }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Categories</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="delete-dot">
                                <select name="category">
                                    <option value="0" >...</option>
                                    <?php

            $stmt2 = $con->prepare("SELECT 
                                        * 
                                    FROM 
                                        categories 
                                    WHERE 
                                        Parent = 0
                                    ORDER BY Name ");
            $stmt2->execute();
            $cats = $stmt2->fetchAll();

            foreach($cats as $cat){
                if($cat['Allow_Ads'] == 0 ){
                    $hidden = $cat['Visibility'] == 1?'[HIDDEN]':'';
                    echo "<option value= ' " . $cat['ID'] . " ' > ";
                    echo $cat['Name'].' '.$hidden;
                    echo '</option>';
                }
                $allChild=getAllFrom("*", "categories", "WHERE Parent={$cat['ID']}", "Name", "Asc");
                foreach($allChild as $child){
                    $hidden = $child['Visibility'] == 1?'[HIDDEN]':'';
                    if($child['Allow_Ads'] == 0 ){
                        echo "<option value= ' " . $child['ID'] . " ' > ";
                        echo $cat['Name'] ." --- > " . $child['Name'].' '.$hidden;
                        echo '</option>'; 
                    }
                }
                
            }
             ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    

                    <div class="form-group form-group-lg tags">
                        <label class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-10 col-md-8">
                            <input 
                                   type="text" 
                                   name="tags" 
                                   class="form-control" 
                                   placeholder=" Example : tag1,tag2  "
                                   data-role="tagsinput" />
                        </div>
                    </div>
                    
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-8 item-admin">
                            <textarea 
                                    class="form-control  " 
                                    name="description"
                                    placeholder=" Describe the item " 
                                    required >
                            </textarea>
                        </div>
                    </div>
                    
                    <div class="form-group form-group-lg tags">
                        <label class="col-sm-2  control-label">Details </label>
                        <div class="col-sm-10 col-md-8">
                            <input 
                                   type="text" 
                                   name="details" 
                                   class="form-control" 
                                   placeholder=" detail1:desc,detail2:desc  "
                                   data-role="tagsinput" />
                        </div>
                    </div>   
                    
                    <div class="form-group form-group-lg ">
                        <label class="col-sm-2  control-label">Image  </label>
                        <div class="col-sm-10 col-md-8">
                            <input 
                                   type="file" 
                                   name="image" 
                                   class="form-control" 
                                   placeholder=""
                                   />
                        </div>
                    </div>                    
                    
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add Item" class="btn btn-primary btn-lg" />
                        </div>
                    </div>

                </form>
            </div>  
        </div>
        
    <?php 
        
    }// rnd if($do == 'Add')
    
    elseif($do == 'Insert') {
        
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            echo '<div class="container">';
                echo '<div class="xx">';
                    echo '<h1 class="custom-h1 text-center"> Insert Item </h1>';
                
            $name     = $_POST['name'];
            $desc     = $_POST['description'];
            $price    = $_POST['price'];
            $country  = $_POST['country'];
            $status   = $_POST['status'];
            $shop     = $_POST['shop'];
            $category = $_POST['category'];
            $tags     = $_POST['tags'];
            $details  = $_POST['details'];
            
            $imageName      = $_FILES['image']['name'];
            $imageTmpName   = $_FILES['image']['tmp_name'];
            $imageSize      = $_FILES['image']['size'];
            $imageType      = $_FILES['image']['type'];
            
            
            $allowedExtention = array('jpg', 'jpeg', 'gif', 'png');
            
            $iamgeNameExplode = explode('.' , $imageName);
            
            $imageExtention = strtolower(end($iamgeNameExplode));
            

            
            
            // عملت اختيار الستيتوس بهي الطريقة مشان ما يطلعلي غلط من ورا السطر10 بملف الباك إيند بالجيكويري
            //$status = 0;
            //if ( !empty($_POST['status']) ) { $status = $_POST['status']; }
            
            //تشكيل مصفوفة لفحص الأخطاء
            $formErrors = array();
            
            if(empty($name)){
                $formErrors[] ='Name can\'t be  <strong> Empty </strong>';
            }
            if(empty($desc)){
                $formErrors[] ='Description can\'t be  <strong> Empty </strong>';
            }
            if(empty($price)){
                $formErrors[] ='Price can\'t be  <strong> Empty </strong>';
            }
            if(empty($country)){
                $formErrors[] ='Country can\'t be  <strong> Empty </strong>';
            }
            if( $status == 0 ){
                $formErrors[] ='You must choose the product <strong> Status </strong>';
            }
            if( $shop == 0 ){
                $formErrors[] ='You must choose the  <strong> Shop </strong>';
            }
            if( $category == 0 ){
                $formErrors[] ='You must choose the product <strong> Category </strong>';
            }
            if(empty($imageName)) {
                $formErrors[] ='You must add <strong> Image </strong>';
            }
            if(!empty($imageName) && strlen($imageName) > 240) {
                $formErrors[] ='Image name cant be more <strong> than 240 characters </strong>';
            }            
            if(isset($imageName) && !empty($imageName) && !in_array($imageExtention, $allowedExtention)) {
                $formErrors[] = 'This extension is not <strong>Allowed</strong>';
            }
            if(!empty($imageSize) && $imageSize > 4194304) {
                 $formErrors[] = 'Size must be lower than <strong> 4MB </strong>';
            }
            
            
            //إذا ما كان في أخطاء بينففذ الأستعلام 
            if(empty($formErrors)){

   //هوي إجباري لازم حط صورة للمنتج بس قلت بيني وبين حالي فرضا صار شي خطا بيكون في صورة إفتراضية    

                if(!empty($imageName)){
                    
                    $imageNameDataBase = rand(0,1000000000) .'-'.$imageName;
            move_uploaded_file($imageTmpName, 'uploads/items/'. $imageNameDataBase);
            
                } else {
                    $imageNameDataBase = "default_item_image.png";
                }
                   
                    $stmt = $con->prepare( " INSERT INTO
                        items(Name, Description, Price, Country_Made,Image, Status, Approve, Store_ID, Cat_ID, Add_Date, Tags, Details)
                        VALUES( :zname, :zdesc, :zprice, :zcountry, :zimage, :zstatus, :zapprove, :zstore, :zcategory, now(), :ztags, :zdetails )" );

                    $stmt->execute(array(
                        'zname'     => $name,
                        'zdesc'     => $desc,
                        'zprice'    => $price,
                        'zcountry'  => $country,
                        'zimage'    => $imageNameDataBase,
                        'zstatus'   => $status,
                        'zapprove'  => 1,
                        'zstore'    => $shop,
                        'zcategory' => $category,
                        'ztags'     => $tags,
                        'zdetails'  => $details
                    ));

                    echo '<div class="alert alert-success">' . $stmt->rowCount() . ' recourd inserted' . '</div>';                    
                    redirectFunction('back');
               
                
            }//end if(empty($formErrors))
            else{
                
                foreach($formErrors as $error){
                    echo '<div class="alert alert-danger">' . $error . '</div>';
                    redirectFunction('back');
                }                
                
            }
         
                echo '</div>';
            echo '</div>';
           
        } //end if($_SERVER['REQUEST_METHOD'] == 'POST')        
        else {
            echo '<div class="container">';
                echo '<div class="xx">';           
                echo '<h1 class="custom-h1 text-center"><i class="fa fa-warning"></i> Access Denied</h1>';
                echo "<div class='alert alert-danger'>you can't browse this page directly</div>";
                redirectFunction('home');
                echo '</div>';
            echo '</div>';
        }
        
    }// end if($do == 'Insert')
    
    elseif($do == 'Edit') {

            
        $itemid = isset($_GET['itemid'])&&is_numeric($_GET['itemid'])?intval($_GET['itemid']):0;

            $stmt3 = $con->prepare(" SELECT * FROM items WHERE Item_ID = ? ");
            $stmt3->execute(array($itemid));
            $row = $stmt3->fetch();
            $count = $stmt3->rowCount();
            
            if($count > 0){
?>
            <div class="container">
                <div class="xx">
            <h1 class="text-center custom-h1"><i class="fa fa-wrench"></i> Update Item </h1>

                <form class="form-horizontal" action="?do=Update" method="post" enctype="multipart/form-data">
                    
                <input type="hidden" name="itemid" value="<?php echo $row['Item_ID'] ?>"/>
                           
    <!--form-horizontal bootstrap class : label in left and input in right  required="required"-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-8">
                            <input 
                                   type="text" 
                                   name="name" 
                                   class="form-control" 
                                   placeholder=" Name of the item "
                                   value ="<?php echo $row['Name'] ?>" />
                        </div>
                    </div>


                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10 col-md-8">
                            <input 
                                   type="text" 
                                   name="price" 
                                   class="form-control" 
                                   placeholder=" Price of the item "
                                   value ="<?php echo $row['Price'] ?>" />
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Country</label>
                        <div class="col-sm-10 col-md-8">
                            <input 
                                   type="text" 
                                   name="country" 
                                   class="form-control" 
                                   placeholder=" Country made of the item "
                                   value ="<?php echo $row['Country_Made'] ?>" />
                        </div>
                    </div>
                    
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-10 col-md-8">
                                <select name="status">
                                    
    <option value="1" <?php if($row['Status'] == 1){ echo"selected"; }?> >New</option>
    <option value="2" <?php if($row['Status'] == 2){ echo"selected"; }?> >Like New</option>
    <option value="3" <?php if($row['Status'] == 3){ echo"selected"; }?> >Used</option>
    <option value="4" <?php if($row['Status'] == 4){ echo"selected"; }?> >Old</option>
                                
                                </select>
                            </div>
                    </div>
                    
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Shops</label>
                        <div class="col-sm-10 col-md-8">
                                <select name="shop">
                                    <?php

            $stmt1 = $con->prepare("SELECT * FROM shops WHERE RegStatus = 1 ORDER BY Shop_Name");
            $stmt1->execute();
            $shops = $stmt1->fetchAll();

            foreach($shops as $shop){
                echo "<option value=' ". $shop['Shop_ID']." ' ";
                if( $shop['Shop_ID'] == $row['Store_ID']){ echo" selected "; }
                echo " > ";
                echo $shop['Shop_Name'] . ' ( ' . $shop['Identifier_Name'] . ' ) ';
                echo '</option>';
            }
                                    ?>
                                </select>
                        </div>
                    </div>
                    
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Categories</label>
                        <div class="col-sm-10 col-md-8">
                                <select name="category">
                                    <?php

            $stmt2 = $con->prepare("SELECT 
                                        * 
                                    FROM 
                                        categories 
                                    WHERE 
                                        Parent = 0
                                    ORDER BY Name ");
            $stmt2->execute();
            $cats = $stmt2->fetchAll();

            foreach($cats as $cat){
                $hidden = $cat['Visibility'] == 1?'[HIDDEN]':'';
                if($cat['Allow_Ads'] == 0 ){
                    echo "<option value=' ". $cat['ID']." ' ";
                    if( $cat['ID'] == $row['Cat_ID']){ echo" selected "; }
                    echo " > ";
                    echo $cat['Name'].' '.$hidden;
                    echo '</option>';
                }elseif($cat['Allow_Ads'] == 1 && $cat['ID'] == $row['Cat_ID']){
                    echo "<option value=' ". $cat['ID']. " ' selected >";
                    echo $cat['Name'].' '.$hidden;
                    echo '</option>';
                }
                $allChild=getAllFrom("*", "categories", "WHERE Parent={$cat['ID']}", "Name", "Asc");
                foreach($allChild as $child){
                    $hidden = $child['Visibility'] == 1?'[HIDDEN]':'';
                    if($child['Allow_Ads'] == 0 ){
                        echo "<option value=' ". $child['ID']." '";
                        if( $child['ID'] == $row['Cat_ID']){ echo" selected "; }
                        echo " > ";
                        echo $cat['Name'] ." --- > " . $child['Name'].' '.$hidden;
                        echo '</option>'; 
                    }elseif($child['Allow_Ads'] == 1 && $child['ID'] == $row['Cat_ID']){
                        echo "<option value=' ". $child['ID']. " ' selected >";
                        echo $cat['Name'] ." --- > " . $child['Name'].' '.$hidden;
                        echo '</option>';
                    }
                }
                
            }
                                    ?>
                                </select>
                        </div>
                    </div>
                    
                    <div class="form-group form-group-lg tags">
                        <label class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-10 col-md-8">
                            <input 
                                   type="text" 
                                   name="tags" 
                                   class="form-control" 
                                   placeholder=" Example : tag1,tag2  "
                                   data-role="tagsinput"
                                   value ="<?php echo $row['Tags'] ?>" />
                        </div>
                    </div>
                    
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-8 item-admin">
                            <textarea 
                                    class="form-control " 
                                    name="description"
                                    placeholder=" Describe the item "
                                    value ="<?php echo $row['Description'] ?>" >
                                <?php echo $row['Description'] ?>
                            </textarea>
                        </div>
                    </div>
                    
                    <div class="form-group form-group-lg tags">
                        <label class="col-sm-2 control-label">Details</label>
                        <div class="col-sm-10 col-md-8">
                            <input 
                                   type="text" 
                                   name="details" 
                                   class="form-control" 
                                   placeholder=" Example : tag1,tag2  "
                                   data-role="tagsinput"
                                   value ="<?php echo $row['Details'] ?>" />
                        </div>
                    </div>
                    
                    <div class="form-group form-group-lg ">

                        <label class="col-sm-2 control-label">Image</label>
                        <div class="col-sm-10 col-md-8">
                            <input
                                   type="hidden"
                                   name="old_image"
                                   value="<?php echo $row['Image'] ?>" />
                            <input
                                   type="file"
                                   name="new_image"
                                   class="form-control" />

                            <div class="thumbnail item-box" style=" border-radius:  6px;
                                        margin-top: 15px;">
                                <img class="img-responsive" src="uploads/items/<?php echo $row['Image']; ?>" alt="" /> 
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Save" class="btn btn-primary btn-lg" />
                        </div>
                    </div>

                </form>
            </div>
        </div>


<?php
        $stmt = $con->prepare("SELECT 
                                    comments.*,
                                    users.Username AS user_name
                                FROM 
                                    comments
                                INNER JOIN users ON users.UserID = comments.user_id
                                WHERE item_id = ?");
        $stmt->execute(array($itemid));
        $rows = $stmt->fetchAll();
                           
        if (!empty($rows)){
?>
<div class="container">
    
    <div class="xx">
        <hr>
    <h1 class="text-center"> Manage [<?php echo $row['Name'] ?>] Comments </h1>

    <div class="table-responsive">
        <div class="table-shadow">
        <table class="main-table text-center table table-bordered">
            <tr>
                <td>Member</td>
                <td>Comment</td>
                <td>Added Date</td>
                <td>Control</td>
            </tr>
            <?php
        foreach($rows as $row){
            echo"<tr>";
                echo"<td>".$row['user_name']."</td>";
                echo"<td>".$row['comment']."</td>";
                echo"<td>".$row['comment_date']."</td>";
                echo"<td>
                        <a href='comments.php?do=Delete&comid=" .$row['c_id']. "' class='confirm btn btn-danger'><i class='table-btn-icon-position fa fa-close'></i>Delete</a>";
            
                if($row['status'] == 0) {
                    echo"<a href='comments.php?do=Approve&comid=" .$row['c_id']. "' class=' btn btn-info activate'><i class='table-btn-icon-position fa fa-check'></i>Approve</a>";
                }
                echo"</td>";
           echo"</tr>";   
        }
            ?>
        </table>
        </div>
    </div>
    </div>
</div>
        
    <?php
              }//end if(!empty($rows))
               else {
                    echo '<div class="container">'; 
                    echo '<div class="xx">';
                    echo '<div class="alert alert-info"> This item has no comments </div>';
                    echo '</div>';
                    echo '</div>';
               }
    } //end if($count > 0)
    else {
            echo '<div class="container">';
                echo '<div class="xx">';           
                echo '<h1 class="custom-h1 text-center"><i class="fa fa-warning"></i> Access Denied</h1>';
                echo "<div class='alert alert-danger'>There is't item with this value</div>";
                redirectFunction('home');
                echo '</div>';
            echo '</div>';  
    }
        
    }// rnd if($do == 'Edit')
    
    elseif($do == 'Update') {
        
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
            echo '<div class="container">';
                echo '<div class="xx">';
                    echo '<h1 class="text-center custom-h1"> Update Items </h1>';
            
            
            $itemid   = $_POST['itemid'];
            $name     = $_POST['name'];
            $desc     = $_POST['description'];
            $price    = $_POST['price'];
            $country  = $_POST['country'];
            $status   = $_POST['status'];
            $shop     = $_POST['shop'];
            $category = $_POST['category'];
            $tags     = $_POST['tags'];
            $details  = $_POST['details'];
            
            $old_image = $_POST['old_image'];
                
            $newImageName = $_FILES['new_image']['name'];
            $newImageNameTmp = $_FILES['new_image']['tmp_name'];
            $newImageSize = $_FILES['new_image']['size'];
            $newImageType = $_FILES['new_image']['type'];
                
            $allowedImageExtention = array('jpg', 'jpeg', 'png', 'gif');
            
            $imageNameExploded = explode('.', $newImageName);
                
            $imageExtention = strtolower(end($imageNameExploded)); 
                
            //تشكيل مصفوفة لفحص الأخطاء
            $formErrors = array();
            
            if(empty($name)){
                $formErrors[] ='Name can\'t be  <strong> Empty </strong>';
            }
            if(empty($desc)){
                $formErrors[] ='Description can\'t be  <strong> Empty </strong>';
            }
            if(empty($price)){
                $formErrors[] ='Price can\'t be  <strong> Empty </strong>';
            }
            if(empty($country)){
                $formErrors[] ='Country can\'t be  <strong> Empty </strong>';
            }
            if( $status == 0 ){
                $formErrors[] ='You must choose the product <strong> Status </strong>';
            }
            if( $shop == 0 ){
                $formErrors[] ='You must choose the  <strong> shop </strong>';
            }
            if( $category == 0 ){
                $formErrors[] ='You must choose the product <strong> Category </strong>';
            }
            if( $newImageSize > 4194304){
                $formErrors[] = 'Size must\'nt be larger than <strong> 4MB </strong>';
            }
            if(!empty($newImageName) && !in_array($imageExtention, $allowedImageExtention)) {
                $formErrors[] = 'This extension is not <strong>Allowed</strong>';
            }
            if(!empty($newImageName) && strlen($newImageName) > 240) {
                $formErrors[] ='Image name cant be more <strong> than 240 characters </strong>';
            }                   
            
            //إذا ما كان في أخطاء بينففذ الأستعلام 
            if(empty($formErrors)){
                
                if (!empty($newImageName)){
                    
                    $image = rand(0,1000000000) . '_' . $newImageName;
                    move_uploaded_file($newImageNameTmp, "uploads/items/" . $image);
                    if($old_image != 'default_user_avatar.png'){
                        unlink('uploads/items/' . $old_image);
                    }
                    
                }else {
                    $image = $old_image;
                }
                $stmt = $con->prepare(" UPDATE 
                                                items
                                        SET 
                                                Name = ?,
                                                Description = ?,
                                                Price =?,
                                                Country_Made = ?,
                                                Status = ?,
                                                Cat_ID = ?,
                                                Store_ID =?,
                                                Tags =?,
                                                Details =?,
                                                Image =?
                                        WHERE 
                                                Item_ID = ? ") ;
    $stmt->execute(array($name, $desc, $price, $country, $status, $category, $shop, $tags, $details, $image, $itemid) );
    echo '<div class="alert alert-success">' . $stmt->rowCount() . ' recourd updated' . '</div>';
                redirectFunction('back');
            }else{
                foreach($formErrors as $error){
                    echo '<div class="alert alert-danger">' . $error . '</div>';
                    redirectFunction('back');
                }
            }
                echo '</div>';
            echo '</div>';   
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
        
        echo '</div>';
    }//end if($do == 'Update')
    */
    elseif($do == 'Delete') {
        
            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;


            $stmt = $con->prepare("SELECT * 
                                   FROM items 
                                   WHERE Item_ID = ? 
                                   LIMIT 1 ");
            $stmt->execute(array($itemid));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();

            if($count > 0){
                echo '<div class="container">';
                echo '<div class="xx">';
                echo '<h1 class="text-center custom-h1">'. lang('DELETE_ITEM') .'</h1>';

                $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :zitemid");
                $stmt->bindParam(":zitemid", $itemid);
                $stmt->execute(); 
                
                if($row['Image'] == 'default_item_image.png') {

                }else {
                    unlink('uploads/items/' . $row['Image']);
                }
                                
                echo '<div class="alert alert-success">' . $stmt->rowCount() . ' recourd deleted' . '</div>';
                redirectFunction('back');
                echo '</div>';
                echo '</div>';
            }else{
                echo '<div class="container">';
                    echo '<div class="xx">';           
                    echo '<h1 class="custom-h1 text-center"><i class="fa fa-warning"></i> Access Denied</h1>';
                    echo "<div class='alert alert-danger'>There is't item with this value</div>";
                    redirectFunction('home');
                    echo '</div>';
                echo '</div>';
            }
        
    }//end if($do == 'Delete')
    
    elseif($do == "Approve"){
        
            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        
                    //$count = checkItem("Item_id", "items", $itemid);
                    $stmt = $con->prepare('SELECT Item_ID, Store_ID FROM items WHERE Item_ID = ?');
                    $stmt->execute(array($itemid));
                    $count = $stmt->rowCount();

            if($count > 0){
                $item = $stmt->fetch();
                echo '<div class="container">';
                echo '<div class="xx">';
                echo '<h1 class="text-center custom-h1">'. lang('APPROVE_ITEM') .'</h1>';                
                
                // الموافقة على المنتج
                $stmtA = $con->prepare("UPDATE items SET Approve = ? WHERE Item_ID = ?");
                $stmtA->execute(array(1, $itemid));
                
                // وصول اشعار الى البائع بالموافقة على منتجه
                $stmt = $con->prepare(" INSERT INTO
                                        shop_notif_item (Shop_ID, Item_ID)
                                        VALUES(:zshop, :zitem)");
                $stmt->execute(array(
                    'zshop' => $item['Store_ID'],
                    'zitem' => $item['Item_ID']
                ));
                
                //وصول اشعار الى كل المستخدمين بالمنتج الجديد
                $stmt = $con->prepare('SELECT User_ID FROM subscribes WHERE Shop_ID = ? AND Bell = 1');
                $stmt->execute(array($item['Store_ID']));
                $users = $stmt->fetchAll();
                
                foreach($users as $user){
                    $stmt = $con->prepare(" INSERT INTO
                                            user_notif_item (User_ID, Item_ID)
                                            VALUES(:zuser, :zitem)");
                    $stmt->execute(array(
                        'zuser' => $user['User_ID'],
                        'zitem' => $item['Item_ID']
                    )); 
                }
                
                
                echo '<div class="alert alert-success">' . $stmtA->rowCount() . ' recourd updated </div>';
                redirectFunction('back');
                echo '</div>';
                echo '</div>'; 
            }// end if($count > 0)
            else {
                echo '<div class="container">';
                    echo '<div class="xx">';           
                    echo '<h1 class="custom-h1 text-center"><i class="fa fa-warning"></i> Access Denied</h1>';
                    echo "<div class='alert alert-danger'>There is't item with this value</div>";
                    redirectFunction('home');
                    echo '</div>';
                echo '</div>';               
            }
        
    } // end if($do == Approve)
    
    include $tpl . 'footer.php';
    
} //end if (isset($_SESSION['username']))
else{
    header('location:index.php');
    exit();
}

ob_end_flush(); // release the output
?>