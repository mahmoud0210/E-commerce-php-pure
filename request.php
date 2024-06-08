<?php 
ob_start();
session_start();
$getTitle = 'Add Request';
include "init.php";

if(isset($_SESSION['uid'])){
    $do = isset($_GET['do']) && !empty($_GET['do']) ? $_GET['do'] : 'Manage'; 
    
    if($do == "Manage") {
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){            
            
            
            $name       = trim(filter_var($_POST['name'], FILTER_SANITIZE_STRING));
            $desc       = trim(filter_var($_POST['description'], FILTER_SANITIZE_STRING));
            $price_min  = filter_var($_POST['price_min'], FILTER_SANITIZE_NUMBER_INT);
            $price_max  = filter_var($_POST['price_max'], FILTER_SANITIZE_NUMBER_INT);
            $quantity   = filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT);
            $category   = isset($_POST['sub_category'])?filter_var($_POST['sub_category'], FILTER_SANITIZE_NUMBER_INT):0;
            
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
            
            
            
            $formErrors = array();
            
            if(empty($name)){
                $formErrors[] ='Name can\'t be  <strong> Empty </strong>';
            }
            if(empty($desc)){
                $formErrors[] ='Description can\'t be  <strong> Empty </strong>';
            } 
            
            if(empty($price_min)){
                $formErrors[] ='Min Price Can\'t be  <strong> Empty </strong>';
            }elseif($price_min < 0 ){
                $formErrors[] ='<strong>Invalid Min Price</strong>';
            }elseif(!filter_var($price_min, FILTER_VALIDATE_INT)){
                $formErrors[] ='Min Price Must Be  <strong> INT </strong>';
            }
            
            if(empty($price_max)){
                $formErrors[] ='Max Price Can\'t be  <strong> Empty </strong>';
            }elseif($price_max < 0 ){
                $formErrors[] ='<strong>Invalid Max Price</strong>';
            }elseif(!filter_var($price_max, FILTER_VALIDATE_INT)){
                $formErrors[] ='Max Price Must Be  <strong> INT </strong>';
            }
            
            if(empty($quantity)) {
                $formErrors[] ='Quantity can\'t be <strong> Empty </strong>';
            }elseif($quantity < 0){
                $formErrors[] ='Quantity must by <strong> larger than ZERO </strong>';
            }elseif(!filter_var($quantity, FILTER_VALIDATE_INT)){
                $formErrors[] ='Quantity must be  <strong> Int </strong>';
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
            
            if(empty($formErrors)){
                
                
                if(!empty($imageName)){
                    
                    $imageNameDataBase = rand(0,1000000000) .'-'.$imageName;
                    move_uploaded_file($imageTmpName, 'admin/uploads/requests/'. $imageNameDataBase);
            
                } else {
                    $imageNameDataBase = "default_item_image.png";
                }                
  
                
                    $stmt = $con->prepare( " INSERT INTO
                        requests(Name, Description, Price_MIN, Price_MAX, User_ID, Cat_ID, Details, Quantity, Image, Add_Date)
                        VALUES( :zname, :zdesc, :zmin, :zmax, :zuser, :zcat, :zdetails, :zquantity, :zimage, current_date())" );

                    $stmt->execute(array(
                        'zname'     => $name,
                        'zdesc'     => $desc,
                        'zmin'      => $price_min,
                        'zmax'      => $price_max,
                        'zuser'     => $_SESSION['uid'],
                        'zcat'      => $category,
                        'zdetails'  => $details,
                        'zquantity' => $quantity,
                        'zimage'    => $imageNameDataBase
                    ));

                    if($stmt){
                        $success = 'Success Opration , You Add On Request';
                    }
                
              
            }//end if(empty($formErrors))
                
        } //end if($_SERVER['REQUEST_METHOD'] == 'POST')        
        
?>    
<div class="container">
    <div class="xx profile-panel-heading-icon">
        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-home fa-fw"></i>Index</a></li>
            <li><a href="profile.php"><i class="fa fa-user fa-fw"></i>Profile</a></li>
            <li class="active">Add Request</li>
        </ol> 

        <div class="">
        <div class="new-ad block ">

                <div class="panel panel-primary">
                    <div class="panel-heading">Add Request
                        <a href="?do=Requests">
                            <span class="pull-right">My Requests</span>
                        </a>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" action="?do=Manage" method="post" enctype="multipart/form-data">
                        
                            <div class="row">
                                <div class="">
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-2 col-md-3 control-label">Name</label>
                                        <div class="col-sm-10 col-md-8">
                                            <input 
                                                   type="text" 
                                                   name="name" 
                                                   class="form-control live" 
                                                   placeholder="Name of the item "
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
                                                   name="price_min" 
                                                   class="form-control" 
                                                   placeholder="Min Price of the item "
                                                   autocomplete="off"
                                                   style="display: inline-block; width: 49%;"
                                                    />
                                           <input 
                                                   type="number" 
                                                   name="price_max" 
                                                   class="form-control" 
                                                   placeholder="Max Price of the item "
                                                   autocomplete="off"
                                                   style="display: inline-block; width: 50%;"
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
                                                   placeholder="The initial quantity you own from the product"
                                                   autocomplete="off"
                                                    />
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
                                        <div class="add-detail texr-center col-sm-offset-3 ">
                                            <i class="fa fa-plus">ADD MORE DETAIL</i>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-2 col-md-3 control-label">Image</label>
                                        <div class="col-sm-10 col-md-8">
                                            <div class="well well-success">
                                                <p>This is the basic image of the product </p>
                                                <input type="file" name="image" class="form-control" />
                                            </div>
                                        </div>
                                    </div> 

                                    <hr />
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 ">
                                            <input type="submit" value="Add New Request" class="btn btn-primary btn-lg btn-add-new-item" />
                                        </div>
                                    </div>

                                
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
    }elseif($do == 'Requests') {
        
        $stmt = $con->prepare("SELECT * FROM requests WHERE User_ID = ?");
        $stmt->execute(array($_SESSION['uid']));
        $requests = $stmt->fetchAll();
        
        $count = $stmt->rowCount();
        
?>
<div class="container">
<div class="xx">
    <h1 class="custom2-h1 text-center">My Requests</h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-home fa-fw"></i>Index</a></li>
        <li><a href="profile.php"><i class="fa fa-user fa-fw"></i>Profile</a></li>
        <li class="active">My Requests</li>
    </ol>
    
    <?php if($count == 0){ ?>
    <div class="no-requests">
        There Are Not Any Requests ,
        You Can Add New Request <a href="request.php">FROM HERE</a>
    </div>
    <?php }else{ ?>
    
    <div class="shop-cart">
        <?php
        foreach($requests as $request){
        $name = getOneFrom('Name', 'categories', "WHERE ID = {$request['Cat_ID']}", 'Name');    
        ?>
            <div class="item-cart row">
                <a
                   href='request.php?do=Delete&Request=<?php echo $request['Request_ID']?>' 
                   class="delete-from-cart confirm"><?php echo lang('DELETE'); ?>
                </a>
                <span class="item-information">
                    Add Date : <?php echo $request['Add_Date'] ?>
                </span>
                <div class="col-md-3 text-center">
                    <img src="<?php echo 'admin/uploads/requests/' . $request['Image']; ?>" class="img-request"/>
                </div>
                <div class="col-md-9">
                    <span class="item-name"><?php echo $request['Name']; ?></span>
                    <p>Price Range : <?php echo $request['Price_MIN'] ?> - <?php echo $request['Price_MAX'] ?></p>
                    <p>Quantity : <?php echo $request['Quantity'] ?></p>
                    <p>Category : <?php echo $name['Name'] ?></p>
                    <?php
                    if(!empty($request['Details'])) {
                        
                        echo '<p>Details : </p>';
                        echo '<ul class="list-unstyled details">';
                        
                        $details = explode('|', $request['Details']);
                        foreach($details as $detail){
                            $detailExp = explode(':', $detail);
                    ?>
                    
                    <li>
                        <span class="one"><?php echo  $detailExp[0]; ?></span>
                        <span class="two"><?php echo ': ' . $detailExp[1]; ?></span>
                    </li>
                    
                    <?php
                        }//end foreach($details as $detail)
                        echo '</ul>';
                    } // end if(!empty($request['Details']))
                    ?>
                    <p><?php echo nl2br($request['Description']) ?></p>
                </div>
            </div>
            <hr/> 
        <?php } ?>
    </div>
    
    <?php } ?>
</div>
</div>

<?php
    } //end if($do == 'Requests')
    elseif($do == 'Delete') {
        $request_ID = isset($_GET['Request']) && is_numeric($_GET['Request']) ? $_GET['Request'] : 0; 
        $request = getOneFrom("*", "requests", "WHERE Request_ID = {$request_ID}", "Request_ID");    
        if(!empty($request) && $request['User_ID'] == $_SESSION['uid']) {

            $stmt = $con->prepare("DELETE FROM requests WHERE Request_ID = :zrequest");
            $stmt->bindParam(":zrequest", $request['Request_ID']);
            $stmt->execute();
            
            if($request['Image'] !== 'default_item_image.png'){
                unlink('admin/uploads/requests/'.$request['Image']);
            }

?>
<div class="container">
<div class="xx">
    <h1 class="custom2-h1 text-center">Delete Request</h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-home fa-fw"></i>Index</a></li>
        <li><a href="profile.php"><i class="fa fa-user fa-fw"></i>Profile</a></li>
        <li class="active">Delete Item</li>
    </ol>
    <div class="new-ad">
        <div class="alert alert-danger"><strong>Your Request Is Deleted</strong></div>
    </div>
</div>
</div>
<?php
            include $tpl."footer.php";   
            header('REFRESH:5;url=request.php?do=Requests');
            exit();
        } // end if(!empty($request) && $request['User_ID'] == $_SESSION['uid'])
        else {
            header('Location:profile.php');
            exit();
        }
    } // end if($do == 'Delete')
} // end if(isset($_SESSION['shopid']))
else{
    header("Location:index.php");
    exit();
}

include $tpl."footer.php";
ob_end_flush();
?>