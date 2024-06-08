<?php
ob_start(); // output buffering start
    session_start();

    $getTitle = 'Create New Item';

    include "init.php";
    
    if(isset($_SESSION['shopid'])) {
        
    $do =isset($_GET['do']) && !empty($_GET['do'])?$_GET['do'] : 'Manage';
    
    $trustStatus = getOneFrom("Shop_ID, Trust_Status", "shops", "WHERE Shop_ID = {$_SESSION['shopid'] }", "Shop_ID");
        
    //echo $trustStatus['Trust_Status'];    
    
    if($do == "Manage") {
        
 
        if($_SERVER['REQUEST_METHOD'] == 'POST'){            
            
            
            $name       = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $desc       = trim(filter_var($_POST['description'], FILTER_SANITIZE_STRING));
            $price      = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
            $quantity   = filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT);
            $country    = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
            $status     = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
            //$category   = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
            $category   = isset($_POST['sub_category'])?filter_var($_POST['sub_category'], FILTER_SANITIZE_NUMBER_INT):0;
            $tags       = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);
            
            $detail_name = $_POST['detail_name'];
            $detail_desc = $_POST['detail_desc'];
            $details = '';

            for($i = 0; $i < count($detail_name); $i++){
                
                $detail_name[$i] = str_replace(array(":","|"), '', filter_var($detail_name[$i], FILTER_SANITIZE_STRING));
                $detail_desc[$i] = str_replace(array(":","|"), '', filter_var($detail_desc[$i], FILTER_SANITIZE_STRING)); 
                
                if(empty($detail_name[$i]) || empty($detail_desc[$i])) {
                    continue;
                }
 
                
                $details .= "|" . $detail_name[$i] . ":" . $detail_desc[$i];
            }
            
            $details = ltrim($details, '|');
            
            
            $imageName      = $_FILES['image']['name'];
            $imageTmpName   = $_FILES['image']['tmp_name'];
            $imageSize      = $_FILES['image']['size'];
            $imageType      = $_FILES['image']['type'];
            
            
            $allowedExtention = array('jpg', 'jpeg', 'gif', 'png');
            
            $iamgeNameExplode = explode('.' , $imageName);
            
            $imageExtention = strtolower(end($iamgeNameExplode));
            
            /* slide iamge of the product */
            
            if(isset( $_FILES['imageslide']['name'] ) ){
             
                
                $imageSlideName     = $_FILES['imageslide']['name'];
                $imageSlideTmpName  = $_FILES['imageslide']['tmp_name'];
                $imageSlideSize     = $_FILES['imageslide']['size'];
                $imageSlideType     = $_FILES['imageslide']['type'];

                $slideNameExplode = explode('.' , $imageSlideName);

                $slideExtention = strtolower(end($slideNameExplode));                
                
            }
            
            
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
            if($price < 0 ){
                $formErrors[] ='<strong>Invalid price</strong>';
            }            
            if(!filter_var($price, FILTER_VALIDATE_INT) ){
                $formErrors[] ='Price must be  <strong> Int </strong>';
            }
            if(empty($quantity)) {
                $formErrors[] ='Quantity can\'t be <strong> Empty </strong>';
            }
            if($quantity < 0){
                $formErrors[] ='Quantity must by <strong> larger than ZERO </strong>';
            }
            if(!filter_var($quantity, FILTER_VALIDATE_INT) ){
                $formErrors[] ='Quantity must be  <strong> Int </strong>';
            }
            if(empty($country)){
                $formErrors[] ='Country can\'t be  <strong> Empty </strong>';
            }
            if(!( $status == 1 || $status == 2 || $status == 3 || $status == 4)){
                $formErrors[] ='You must choose the product <strong> Status </strong>';
            }
            if( $category == 0 ){
                $formErrors[] ='You must choose the product <strong> Category </strong>';
            }
            if(empty($imageName)) {
                $formErrors[] ='You must add <strong> Image </strong>';
            }
            if(!empty($imageName) && strlen($imageName) > 200) {
                $formErrors[] ='Image name cant be more <strong> than 240 characters </strong>';
            }            
            if(isset($imageName) && !empty($imageName) && !in_array($imageExtention, $allowedExtention)) {
                $formErrors[] = 'This extension is not <strong>Allowed</strong>';
            }
            if(!empty($imageSize) && $imageSize > 4194304) {
                 $formErrors[] = 'Size must be lower than <strong> 4MB </strong>';
            }
            if(!empty($imageSlideName) && strlen($imageSlideName) > 200) {
                $formErrors[] ='Image name cant be more <strong> than 240 characters </strong>';
            }            
            if(isset($imageSlideName) && !empty($imageSlideName) && !in_array($slideExtention, $allowedExtention)) {
                $formErrors[] = 'This extension is not <strong>Allowed</strong>';
            }
            if(!empty($imageSlideSize) && $imageSlideSize > 4194304) {
                 $formErrors[] = 'Size must be lower than <strong> 4MB </strong>';
            } 
            
            if(empty($formErrors)){
                
                
                if(!empty($imageName)){
                    
                    $imageNameDataBase = rand(0,1000000000) .'-'.$imageName;
                    move_uploaded_file($imageTmpName, 'admin/uploads/items/'. $imageNameDataBase);
            
                } else {
                    $imageNameDataBase = "default_item_image.png";
                }                

                if (!empty($imageSlideName)){
                    
                    $slide = rand(0,1000000000) . '_' . $imageSlideName;
                    move_uploaded_file($imageSlideTmpName, "admin/uploads/slide/" . $slide);
                    
                }else {
                        $slide = NULL;
                }   
                
                    $stmt = $con->prepare( " INSERT INTO
                        items(Name, Description, Price, Country_Made, Status, Store_ID, Cat_ID, Add_Date, Tags, Details, Image, Quantity, Ads_Item)
                        VALUES( :zname, :zdesc, :zprice, :zcountry, :zstatus, :zmember, :zcategory, now(), :ztags, :zdetails, :zimage, :zquantity, :zads_Item )" );

                    $stmt->execute(array(
                        'zname'     => $name,
                        'zdesc'     => $desc,
                        'zprice'    => $price,
                        'zcountry'  => $country,
                        'zstatus'   => $status,
                        'zmember'   => $_SESSION['shopid'],
                        'zcategory' => $category ,
                        'ztags'     => $tags,
                        'zdetails'  => $details,
                        'zimage'    => $imageNameDataBase,
                        'zquantity' => $quantity,
                        'zads_Item' => $slide
                    ));

                    if($stmt){
                        $success = 'Success Opration , You Add On Item';
                    }
                
              
            }//end if(empty($formErrors))
                
        } //end if($_SERVER['REQUEST_METHOD'] == 'POST')
        
?>
<div class="container">
    <div class="xx">
        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-home fa-fw"></i>Index</a></li>
            <li><a href="myshop.php"><i class="fa fa-user fa-fw"></i>Profile</a></li>
            <li class="active">Add Item</li>
        </ol> 

        <div class="">
        <div class="new-ad block">

                <div class="panel panel-primary">
                    <div class="panel-heading"><?php echo $getTitle; ?></div>
                    <div class="panel-body">
                        <form class="form-horizontal" action="?do=Manage" method="post" enctype="multipart/form-data">
                        
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-2 col-md-3 control-label">Name</label>
                                        <div class="col-sm-10 col-md-8">
                                            <input 
                                                   type="text" 
                                                   name="name" 
                                                   class="form-control live" 
                                                   placeholder=" Name of the item "
                                                   autocomplete="off"
                                                   data-class=".live-title" 
                                                    />
                                        </div>
                                    </div>

                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-2 col-md-3 control-label">Price</label>
                                        <div class="col-sm-10 col-md-8">
                                            <input 
                                                   type="number" 
                                                   name="price" 
                                                   class="form-control live" 
                                                   placeholder=" Price of the item "
                                                   autocomplete="off"
                                                   data-class=".live-price" 
                                                    />
                                        </div>
                                    </div>
                                    
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-2 col-md-3 control-label">Quantity</label>
                                        <div class="col-sm-10 col-md-8">
                                            <input 
                                                   type="number" 
                                                   name="quantity" 
                                                   class="form-control live" 
                                                   placeholder="The initial quantity you own from the product"
                                                   autocomplete="off"
                                                   data-class=".live-price" 
                                                    />
                                        </div>
                                    </div>                                    

                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-2 col-md-3 control-label"> Country </label>
                                        <div class="col-sm-10 col-md-8">
                                            <input 
                                                   type="text" 
                                                   name="country" 
                                                   class="form-control" 
                                                   placeholder=" Country made of the item "
                                                   autocomplete="off"
                                                    />
                                        </div>
                                    </div>

                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-2 col-md-3 control-label">Status</label>
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

                                    <div class="form-group form-group-lg categories">
                                        <label class="col-sm-2 col-md-3 control-label">Categories</label>
                                        <div class="col-sm-10 col-md-8">
                                            <div class="delete-dot">
                                                <select name="category">
                                                    <option value="0" >...</option>
                                          <?php

                    $stmt2 = $con->prepare("SELECT * FROM categories WHERE Parent = 0 ORDER BY Name ");
                    $stmt2->execute();
                    $cats = $stmt2->fetchAll();

                    foreach($cats as $cat){
                        echo "<option value= ' " . $cat['ID'] . " ' > ";
                        echo $cat['Name'];
                        echo '</option>';
                        /*
                        $allChild=getAllFrom("*", "categories", "WHERE Parent={$cat['ID']}", "Name", "Asc");
                        foreach($allChild as $child){
                        echo "<option value= ' " . $child['ID'] . " ' > ";
                        echo " --- > " . $child['Name'];
                        echo '</option>';  
                        }*/

                    }
                     ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <div class="interactive-content">
                                        <div id="subcategory"></div>
                                        <div id="general"></div>
                                    </div>
                                    
                                    
                                    <div class="form-group form-group-lg tags">
                                        <label class="col-sm-2 col-md-3 control-label">Tags</label>
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
                                    <label class="col-sm-2 col-md-3 control-label">Description</label>
                                    <div class="col-sm-10 col-md-8 item-admin">
                                        <textarea 
                                                class="form-control live " 
                                                name="description"
                                                placeholder=" Describe the item " 
                                                data-class=".live-desc"
                                                >
                                        </textarea>
                                    </div>
                                </div>
                                    <!--
                                    <div class="form-group form-group-lg tags">
                                        <label class="col-sm-2 col-md-3 control-label">Details </label>
                                        <div class="col-sm-10 col-md-8">
                                            <input 
                                                   type="text" 
                                                   name="details" 
                                                   class="form-control" 
                                                   placeholder=" detail1:desc,detail2:desc  "
                                                   data-role="tagsinput" />
                                        </div>
                                    </div> 
                                    -->
                                    <hr />
                                    <div class="details-details">
                                        <span class="text-center">Details</span>
                                        <div class="all-detail">
                                            <div class="each-detail">
                                                <div class="form-group form-group-lg">
                                                    <div class="col-sm-2 col-md-3 control-label ">
                                                        <label>Detail 1 </label>
                                                    </div>
                                                    <div class="col-sm-10 col-md-8">
                                                            <div class="">
                                                                <input 
                                                                       type="text" 
                                                                       name="detail_name[]" 
                                                                       class="form-control" 
                                                                       placeholder="name of the detail "
                                                                       />
                                                            </div>
                                                            <div class="">
                                                                <input 
                                                                       type="text" 
                                                                       name="detail_desc[]" 
                                                                       class="form-control" 
                                                                       placeholder="description of the detail"
                                                                       />
                                                            </div>
                                                    </div>
                                                </div>                                             
                                            </div>
                                        </div>
                                        <div class="add-detail texr-center">
                                            <i class="fa fa-plus">ADD MORE DETAIL</i>
                                        </div>
                                    </div>
                                    <hr />
                                    
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-10">
                                            <input type="submit" value="Add New Item" class="btn btn-primary btn-lg btn-add-new-item" />
                                        </div>
                                    </div>

                                
                            </div>
                            <div class="col-md-4">
                                <div class="thumbnail item-box">
                                    <span class="price-tag">$
                                        <span class=" live-price" >0</span>
                                    </span>
                                    <img class="img-responsive" src="product-icon.png" alt="" />
                                    <div class="caption">
                                        <h3 class="live-title">Title</h3>
                                        <p class="live-desc">Description</p>
                                    </div>
                                </div>
                                
                                <div class="well well-success">
                                    <p>This is the basic image of the product </p>
                                    <input type="file" name="image" class="form-control" />
                                </div>  
<?php
        if($trustStatus['Trust_Status'] == 1 ) {            
?>
                                <div class="well well-warning">
                                    <p>This image is showen in home page </p>
                                    <input type="file" name="imageslide" class="form-control" />
                                </div>                                 
<?php            
        }
?>                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>
                        <?php

                        if(!empty($formErrors)){
                            foreach($formErrors as $error){
                                echo '<div class="alert alert-danger">' . $error . '</div>';
                            }
                        }
                        if(isset($success)){
                            echo'<div class="alert alert-success">' . $success . '</div>';
                        }

                        ?>
        </div>
        </div>  

    </div>
</div>
<?php
    } // end if($do == "Manage")
    elseif($do == "Edit") {
        
        $itemid = isset($_GET['itemid'])&&is_numeric($_GET['itemid'])?intval($_GET['itemid']):0;

            $stmt3 = $con->prepare("SELECT * FROM items WHERE Item_ID = ?");
        
            $stmt3->execute(array($itemid));
            $row = $stmt3->fetch();
            $count = $stmt3->rowCount();
            
    if( $count == 1  && $_SESSION['shopid'] == $row['Store_ID'] ) {
                
   ?>
<div class="container">
<div class="xx">
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-home fa-fw"></i>Index</a></li>
        <li><a href="myshop.php"><i class="fa fa-user fa-fw"></i>Profile</a></li>
        <li class="active">Edit Item</li>
    </ol>
    <h1 class="text-center custom-h1"><?php echo $row['Name'] ?> </h1>

    <div class="new-ad block">
    
        <div class="panel panel-primary">
            <div class="panel-heading"><i class="fa fa-cog"></i><?php echo " Edit Item"; ?></div>
            <div class="panel-body">
                <form class="form-horizontal" action="?do=Update" method="post" enctype="multipart/form-data">                
                    <div class="row">
                        <div class="col-md-8">                            
                        <input type="hidden" name="itemid" value="<?php echo $row['Item_ID'] ?>"/>
            <!--form-horizontal bootstrap class : label in left and input in right  required="required"-->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 col-md-3 control-label">Name</label>
                                <div class="col-sm-10 col-md-8">
                                    <input 
                                           type="text" 
                                           name="name" 
                                           class="form-control live disabled" 
                                           placeholder=" Name of the item "
                                           autocomplete="off"
                                           value="<?php echo $row['Name'] ?>"
                                           data-class=".live-title" 
                                            />
                                </div>
                            </div>

                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 col-md-3 control-label">Price</label>
                                <div class="col-sm-10 col-md-8">
                                    <input 
                                           type="number" 
                                           name="price" 
                                           class="form-control live" 
                                           placeholder=" Price of the item "
                                           autocomplete="off"
                                           value="<?php echo $row['Price'] ?>"
                                           data-class=".live-price" 
                                            />
                                </div>
                            </div>
                            
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 col-md-3 control-label">Quantity</label>
                                <div class="col-sm-10 col-md-8">
                                    <input 
                                           type="number" 
                                           name="quantity" 
                                           class="form-control " 
                                           placeholder=" Price of the item "
                                           autocomplete="off"
                                           value="<?php echo $row['Quantity'] ?>"
                                            
                                            />
                                </div>
                            </div>                            

                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 col-md-3 control-label"> Country </label>
                                <div class="col-sm-10 col-md-8">
                                    <input 
                                           type="text" 
                                           name="country" 
                                           class="form-control" 
                                           placeholder=" Country made of the item "
                                           autocomplete="off"
                                           value="<?php echo $row['Country_Made'] ?>"
                                            />
                                </div>
                            </div>

                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 col-md-3 control-label">Status</label>
                                <div class="">
                                    <div class="col-sm-10 col-md-8">
                                        <select name="status">
    <option value="1" <?php if($row['Status'] == 1){ echo"selected"; }?> >New</option>
    <option value="2" <?php if($row['Status'] == 2){ echo"selected"; }?> >Like New</option>
    <option value="3" <?php if($row['Status'] == 3){ echo"selected"; }?> >Used</option>
    <option value="4" <?php if($row['Status'] == 4){ echo"selected"; }?> >Old</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 col-md-3 control-label">Categories</label>
                                <div class="col-sm-10 col-md-8">
                                  <?php

            $stmt2 = $con->prepare("SELECT Name FROM categories  WHERE id = ( SELECT Parent FROM categories WHERE id = ?)");
            $stmt2->execute(array($row['Cat_ID']));
            $parentCat = $stmt2->fetch();

            echo "<div class='input-disabled'>" . $parentCat['Name'] . "</div>";
             ?>
                                </div>
                            </div>
                            
                                  <?php
        
            $stmt4 = $con->prepare("SELECT Name FROM categories  WHERE id = ?");
            $stmt4->execute(array($row['Cat_ID']));
            $subCatName = $stmt4->fetch();        

            $stmt2 = $con->prepare("SELECT ID,Name FROM categories  WHERE Parent = ( SELECT Parent FROM categories WHERE id = ?)");
            $stmt2->execute(array($row['Cat_ID']));
            $subCats = $stmt2->fetchAll();

                                ?>
                            <div class="interactive-content">
                                <div class="form-group form-group-lg ">
                                    <label class="col-sm-2 col-md-3 control-label">Subcategories</label>
                                    <div class="col-sm-10 col-md-8">
                                        <div class="input-group-btn">
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <?php echo $subCatName['Name']; ?><span class="caret"></span></button>
                                            <ul class="dropdown-menu">
                                    <?php  foreach($subCats as $subCat){  ?>

                                            <li>
                                                <input  type="radio" name="sub_category" value=" <?php echo $subCat['ID']; ?> " <?php echo $subCat['ID'] == $row['Cat_ID']?'checked':''; ?>/>
                                                <label > <?php echo $subCat['Name']; ?> </label>                            
                                            </li>

                                    <?php } ?>

                                            </ul>
                                        </div>
                                    </div>
                                </div>                            
                            </div>
                            
                            <div class="form-group form-group-lg tags">
                                <label class="col-sm-2 col-md-3 control-label">Tags</label>
                                <div class="col-sm-10 col-md-8">
                                    <input 
                                           type="text" 
                                           name="tags" 
                                           class="form-control" 
                                           placeholder=" Example : tag1,tag2  "
                                           value="<?php echo $row['Tags'] ?>"
                                           data-role="tagsinput" />
                                </div>
                            </div> 
                            
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 col-md-3 control-label">Description</label>
                            <div class="col-sm-10 col-md-8 item-admin">
                                <textarea
                                        rows="7"  
                                        class="form-control live " 
                                        name="description"
                                        placeholder=" Describe the item " 
                                        data-class=".live-desc"
                                        >
                                    <?php echo $row['Description'] ?>
                                </textarea>
                            </div>
                        </div>
                        <!--
                        <div class="form-group form-group-lg tags">
                            <label class="col-sm-2 col-md-3 control-label">Details </label>
                            <div class="col-sm-10 col-md-8">
                                <input 
                                       type="text" 
                                       name="details" 
                                       class="form-control" 
                                       placeholder=" detail1:desc,detail2:desc  "
                                       value="<?php //echo $row['Details'] ?>"
                                       data-role="tagsinput" />
                            </div>
                        </div> 
                        -->
                            
                        <hr />
                        <div class="details-details">
                            <span class="text-center">Details</span>
                            <div class="all-detail">
                                
                        <?php

                        $allDetails = explode("|", $row['Details']);
                        $deatail_num = 1;
        
                        foreach($allDetails as $detail){
                            if(!empty($detail)){
                            $detailRow = explode(":", $detail);
                        ?> 
                                
                                <div class="each-detail">
                                    <div class="form-group form-group-lg">
                                        <div class="col-sm-2 col-md-3 control-label ">
                                            <label>Detail<?php echo " " . $deatail_num++ . " " ?></label>
                                        </div>
                                        <div class="col-sm-10 col-md-8">
                                                <div class="">
                                                    <input 
                                                           type="text" 
                                                           name="detail_name[]" 
                                                           class="form-control" 
                                                           placeholder="name of the detail "
                                                           value="<?php echo $detailRow[0] ?>"
                                                           />
                                                </div>
                                                <div class="">
                                                    <input 
                                                           type="text" 
                                                           name="detail_desc[]" 
                                                           class="form-control" 
                                                           placeholder="description of the detail"
                                                           value="<?php echo $detailRow[1] ?>"
                                                           />
                                                </div>
                                        </div>
                                    </div>                                             
                                </div>

                        <?php    
                            }else{
                        ?>
                            
                                <div class="each-detail">
                                    <div class="form-group form-group-lg">
                                        <div class="col-sm-2 col-md-3 control-label ">
                                            <label>Detail 1</label>
                                        </div>
                                        <div class="col-sm-10 col-md-8">
                                                <div class="">
                                                    <input 
                                                           type="text" 
                                                           name="detail_name[]" 
                                                           class="form-control" 
                                                           placeholder="name of the detail "
                                                           value=""
                                                           />
                                                </div>
                                                <div class="">
                                                    <input 
                                                           type="text" 
                                                           name="detail_desc[]" 
                                                           class="form-control" 
                                                           placeholder="description of the detail"
                                                           value=""
                                                           />
                                                </div>
                                        </div>
                                    </div>                                             
                                </div>                                
                                
                        <?php        
                            }    
                        }

                        ?>                            

                            </div>
                            <div class="add-detail texr-center">
                                <i class="fa fa-plus">ADD MORE DETAIL</i>
                            </div>
                        </div>
                        <hr />                            
                            
                            
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-10">
                                <input type="submit" value="Update" class="btn btn-primary btn-lg" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="thumbnail item-box">
                            <span class="price-tag">$ 
                                <span class=" live-price" ><?php echo $row['Price'] ?></span>
                            </span>
                            <img class="img-responsive" src="admin/uploads/items/<?php echo $row['Image']; ?>" alt="" />
                            <div class="caption">
                                <h3 class="live-title"><?php echo $row['Name'] ?></h3>
                                <p class="live-desc"><?php echo $row['Description'] ?></p>
                            </div>
                        </div>
                        
                        <input type="hidden" name="old_image" value="<?php echo $row['Image']; ?>" />
                        
                        <div class="well well-success">
                            <p>This is the basic image of the product </p>
                            <input type="file" name="image" class="form-control" />
                        </div> 
<?php
        if($trustStatus['Trust_Status'] == 1 ) {            
?>
                        <input type="hidden" name="old_slide_image" value="<?php echo $row['Ads_Item']; ?>" />
                        
                        <div class="well well-warning">
                            <p>This is the slide image of the product </p>
                            <p>Image DIMENSIONS : 1300 x 350 Pixels</p>
                            <input type="file" name="imageslide" class="form-control" />
                        </div>                                 
<?php            
        }
?>                          
                    </div>
                </div>
                </form>    
            </div>
        </div>
    
    </div>
</div>
</div>
<?php
            }// end if( $count == 1  && $_SESSION['shopid'] == $row['Store_ID'] )
            else{
                header('Location:myshop.php');
                exit();  
            }
    } //if($do = "Edit")
    elseif($do == "Update") {

        
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                
                $itemid    = $_POST['itemid'];
                $old_image = $_POST['old_image'];
                if(isset($_POST['old_slide_image'])){
                    $old_slide = $_POST['old_slide_image'];
                }
                
 ?>               
                <div class="container">
                    <div class="xx">
                        <ol class="breadcrumb">
                            <li><a href="index.php"><i class="fa fa-home fa-fw"></i>Index</a></li>
                            <li><a href="myshop.php"><i class="fa fa-user fa-fw"></i>Profile</a></li>
                            <li><a href="newad.php?do=Edit&itemid=<?php echo $itemid;  ?>"><i class="fa fa-wrench fa-fw"></i>Edit Item</a></li>
                            <li class="active">Update Item</li>
                        </ol>
                        <h1 class="custom-h1 text-center">Update Item</h1>                        
                        <div class="information-alert">
<?php  
            
            
            $name       = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $desc       = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
            $price      = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
            $quantity   = filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT);    
            $country    = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
            $status     = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
            //$category   = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
            $category   = filter_var($_POST['sub_category'], FILTER_SANITIZE_NUMBER_INT);
            $tags       = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);

            $detail_name = $_POST['detail_name'];
            $detail_desc = $_POST['detail_desc'];
            $details = '';

            
            for($i = 0; $i < count($detail_name); $i++){
                
                $detail_name[$i] = str_replace(array(":","|"), '', filter_var($detail_name[$i], FILTER_SANITIZE_STRING));
                $detail_desc[$i] = str_replace(array(":","|"), '', filter_var($detail_desc[$i], FILTER_SANITIZE_STRING)); 
                
                if(empty($detail_name[$i]) || empty($detail_desc[$i])) {
                    continue;
                }
 
                
                $details .= "|" . $detail_name[$i] . ":" . $detail_desc[$i];
            }                
            $details = ltrim($details, '|');
                
            /* basic image of the product */
                
            $imageName      = $_FILES['image']['name'];
            $imageTmpName   = $_FILES['image']['tmp_name'];
            $imageSize      = $_FILES['image']['size'];
            $imageType      = $_FILES['image']['type'];
            
            
            $allowedExtention = array('jpg', 'jpeg', 'gif', 'png');
            
            $iamgeNameExplode = explode('.' , $imageName);
            
            $imageExtention = strtolower(end($iamgeNameExplode));
                
            /* slide iamge of the product */
                
            if(isset( $_FILES['imageslide']['name'] ) ){
             
                
                $imageSlideName     = $_FILES['imageslide']['name'];
                $imageSlideTmpName  = $_FILES['imageslide']['tmp_name'];
                $imageSlideSize     = $_FILES['imageslide']['size'];
                $imageSlideType     = $_FILES['imageslide']['type'];

                $slideNameExplode = explode('.' , $imageSlideName);

                $slideExtention = strtolower(end($slideNameExplode));                
                
            }             
                        
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
            if($price < 0 ){
                $formErrors[] ='Price must by <strong> larger than ZERO </strong>';
            }    
            if(!filter_var($price, FILTER_VALIDATE_INT) && $price != 0){
                $formErrors[] ='Price must be  <strong> Int </strong>';
            }
            if(empty($quantity)) {
                $formErrors[] ='Quantity can\'t be <strong> Empty </strong>';
            }
            if($quantity < 0){
                $formErrors[] ='Quantity must by <strong> larger than ZERO </strong>';
            }
            if(!filter_var($quantity, FILTER_VALIDATE_INT) && $quantity != 0){
                $formErrors[] ='Quantity must be  <strong> Int </strong>';
            }                
            if(empty($country)){
                $formErrors[] ='Country can\'t be  <strong> Empty </strong>';
            }
            if(!( $status == 1 || $status == 2 || $status == 3 || $status == 4)){
                $formErrors[] ='You must choose the product <strong> Status </strong>';
            }
            if( $category == 0 ){
                $formErrors[] ='You must choose the product <strong> Category </strong>';
            }
            if(!empty($imageName) && strlen($imageName) > 200) {
                $formErrors[] ='Image name cant be more <strong> than 240 characters </strong>';
            }            
            if(isset($imageName) && !empty($imageName) && !in_array($imageExtention, $allowedExtention)) {
                $formErrors[] = 'This extension is not <strong>Allowed</strong>';
            }
            if(!empty($imageSize) && $imageSize > 4194304) {
                 $formErrors[] = 'Size must be lower than <strong> 4MB </strong>';
            }
            if(!empty($imageSlideName) && strlen($imageSlideName) > 200) {
                $formErrors[] ='Image name cant be more <strong> than 240 characters </strong>';
            }            
            if(isset($imageSlideName) && !empty($imageSlideName) && !in_array($slideExtention, $allowedExtention)) {
                $formErrors[] = 'This extension is not <strong>Allowed</strong>';
            }
            if(!empty($imageSlideSize) && $imageSlideSize > 4194304) {
                 $formErrors[] = 'Size must be lower than <strong> 4MB </strong>';
            }            
            //إذا ما كان في أخطاء بينففذ الأستعلام 
            if(empty($formErrors)){
                
                if (!empty($imageName)){
                    
                    $image = rand(0,1000000000) . '_' . $imageName;
                    move_uploaded_file($imageTmpName, "admin/uploads/items/" . $image);
                    if($old_image != 'default_item_image.png'){
                        unlink('admin/uploads/items/' . $old_image);
                    }
                    
                }else {
                    $image = $old_image;
                }
                
                if (!empty($imageSlideName)){
                    
                    $slide = rand(0,1000000000) . '_' . $imageSlideName;
                    move_uploaded_file($imageSlideTmpName, "admin/uploads/slide/" . $slide);
                    if(!empty($old_slide)){
                        unlink('admin/uploads/slide/' . $old_slide);
                    }
                    
                }else {
                    if(empty($old_slide)){
                        $slide = NULL;
                    }else{
                     $slide = $old_slide;
                    }
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
                                                Tags =?,
                                                Details =?,
                                                Image =?,
                                                Quantity =?,
                                                Ads_Item =?,
                                                Approve = ?
                                        WHERE 
                                                Item_ID = ? ") ;
    $stmt->execute(array($name, $desc, $price, $country, $status, $category, $tags, $details, $image, $quantity, $slide, 0, $itemid) );
    echo '<div class="alert alert-success"> Your Item Is Updated</div>';
                
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
        
        echo '</div>';
        
    } // if($do = "Update")
        elseif($do == 'Delete') {
            
            
        
 ?>               
            <div class="container">
                <div class="xx">
                    <ol class="breadcrumb">
                        <li><a href="index.php"><i class="fa fa-home fa-fw"></i>Index</a></li>
                        <li><a href="myshop.php"><i class="fa fa-user fa-fw"></i>Profile</a></li>
                        <li class="active">Delete Item</li>
                    </ol>
                    <h1 class="custom-h1 text-center">Delete Item</h1>                        
                    <div class="information-alert">
            
<?php  
            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
            
            // التاكد من أن المنتج ليس موجود ضمن المنتجات في الصفحة الرئسية
            
            $statement = $con->prepare("SELECT Username FROM users WHERE UserID = 1");
            $statement->execute();
            $info = $statement->fetch();
            $itemsArray = explode('-', $info['Username']);
            
            if(in_array($itemid, $itemsArray)) {
                
                // اذا كان المنتج موجود ضمن المنتجات في الصفحة الرئيسة لا يتم الحذف
                
                                echo '<div class="alert alert-danger"><strong>You Can\'t delete this item</strong><br /> because this item is showen in home page <br /> only <strong>ADMIN</strong> can delete it</div>';
                            echo '</div>';
                    echo '</div>';
                echo '</div>';       
                
            }else{
             
                $item = getOneFrom("*", "items", "WHERE Item_ID={$itemid}", "Item_ID"); 



                if(!empty($item) && $item['Store_ID'] == $_SESSION['shopid'] ){
                    /*
                        التأكد من أن المنتج ليس مودجود في السلايد في الصفحة الرئيسية
                    */

                    if($item['Ads_Slide'] == 0) {

                        $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :zitemid");
                        $stmt->bindParam(":zitemid", $item['Item_ID']);
                        $stmt->execute();

                        unlink('admin/uploads/items/'.$item['Image']);

                                        echo '<div class="alert alert-danger"><strong>Your Item Is Deleted</strong></div>';
                                    echo '</div>';
                            echo '</div>';
                        echo '</div>';                

                        include $tpl . "footer.php";

                        header('REFRESH:3;url=myshop.php');
                        exit; 

                    }else {
                        // اذا كان المنتج موجود ضمن السلايد في الصفحة الرئيسة لا يتم الحذف 

                                        echo '<div class="alert alert-danger"><strong>You Can\'t delete this item</strong><br /> because this item is showen in home page <br /> only <strong>ADMIN</strong> can delete it</div>';
                                    echo '</div>';
                            echo '</div>';
                        echo '</div>';   

                    }

                }else{
                    header('Location:myshop.php');
                    exit();
                }                
                
            }


    }//end if($do == 'Delete')
    }else {
        header('Location:login.php');
        exit();
    }
    include $tpl . "footer.php"; 
ob_end_flush(); // release the output
?>