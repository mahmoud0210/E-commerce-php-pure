<?php
ob_start();
session_start();

$getTitle = 'Shop Cart';
include "init.php";
//include "includes/functions/functions.php";


    $formErrors = array();
    $idErrors = array();
    $beforeBuy =array();
    $formSuccess = array();

if($_SERVER['REQUEST_METHOD'] == "POST") {
    
    $do = isset($_GET['cart'])?$_GET['cart']:"Do";
    
    if($do == "Add"){
        // غضافة المنتجات الى سلة التسوق
        $pid = $_POST['item_id'];
        $quantity = $_POST['item_quantity'];
        $success = shopCartAuto($quantity, $pid);
        header("location:".$_SERVER['HTTP_REFERER']);
        exit();
    }
    elseif(isset($_POST['update'])){
        // تحديث محتويات السلة الشرائية 
        $max =  $_POST['counter'];              
        $newBasket = array();
        for($i = 0;$i < $max; $i++){
            $counter = $i+1;
            $productid = $_POST['productid_' . $counter];
            $input = $_POST['input_'. $counter];
            // للتاكد من ان حقول الأدخال نظامية ولا تحوي على أكواد 
            $pid = filter_var($productid,FILTER_SANITIZE_STRING);
            $quantity = filter_var($input,FILTER_SANITIZE_STRING);
            // التاكد من أن حقول الإدخال أرقام وليست محارف
            if(is_numeric($pid) && is_numeric($quantity) && $quantity >0 && $pid >0){
                $qtyInit = intval($quantity);
                $newBasket[$i]['productid'] = $pid;
                $newBasket[$i]['qty'] = $qtyInit;               
            }           
        } // end for loop
        // تحديث محتويات السلة الشرائية
        foreach($newBasket as $new){
            addUpdateItem($new['productid'], $new['qty']);
        }
    }else{
        // أذا ضغط على زر الحذف حتى يحذف جميع محتويات السلة الشرائية 
        unset($_SESSION['basket']); // delete variables from session basket
        // حطيت الهيدر لانو بس تجذف الكوكي وتفوت عالصفحة رح يطلع رقم المنتجات في السلة بالرغم من انها محذوفة
        // وما بيروح الرقم لحتا تفون عصفحة تانية او تعمل تحديث للصفحة
        header('location:cart.php');
        exit();
    }
}//if($_SERVER['REQUEST_METHOD'] == "POST")
elseif(isset($_GET['delete'])) {
    $pid = is_numeric($_GET['delete'])?intval($_GET['delete']):-1;
    if($pid != -1){
        deleteItem($pid);
    }
}elseif(isset($_GET['action'])) {
    $number = 0;
    $products = $_SESSION['basket'];
    //print_r($buy);
    foreach($products as $product){
        
        // التاكد من ان كمية المنتج التي نريد شرائها اصغر من الكمية االمنتج التي لدى البائع 
        
        $check = getOneFrom("Item_ID, Quantity, Name", "items", "WHERE Item_ID = {$product['productid'] }", "Item_ID");
        
        $beforeBuy[$number]['productid'] = $check['Item_ID'];
        $beforeBuy[$number]['qty'] = $check['Quantity'];
        
        if($product['qty'] > $check['Quantity']) {
                $formErrors[] = lang('ERROR_QUANTITY') .'<strong>'. $check['Name'] . '</strong>'. lang('ERROR_IS') .'<strong>' . $check['Quantity'] . '</strong>';
                $idErrors[] = $check['Item_ID'];
        }
        
        $number += 1;
    }
    if(empty($formErrors)){
        //print_r($beforeBuy);
        $num = 0;
        foreach($products as $product){
            
            $oldQty = $beforeBuy[$num]['qty'];
            $newQty = $oldQty - $product['qty'];
            //echo 'old = ' . $oldQty . ' | new = ' . $newQty . '<br />';
            $approve = $newQty == 0 ? 0 : 1; 
            $stmt = $con->prepare(" UPDATE 
                                            items
                                    SET 
                                            Quantity = ?,
                                            Approve  = ?
                                    WHERE 
                                            Item_ID = ? ") ;
            $stmt->execute(array($newQty, $approve, $product['productid']) );
                
            $num += 1;
            
            $stmt2 = getOneFrom("*", "items", "WHERE Item_ID = {$product['productid'] }" ,"Item_ID");
            
            $stmt3 = $con->prepare("INSERT INTO
                                    orders(Buyer_ID, Seller_ID, Item_Name, Item_Desc, Item_Price, Item_Quantity, Buy_Date)
                                    VALUES(:zbuyer, :zseller, :zname, :zdesc, :zprice, :zquantity, now())");
        
            $stmt3->execute(array(
                'zbuyer'    => $_SESSION['uid'],
                'zseller'   => $stmt2['Store_ID'],
                'zname'     => $stmt2['Name'],
                'zdesc'     => $stmt2['Description'],
                'zprice'    => $stmt2['Price'],
                'zquantity' => $product['qty']
            ));
            
            if($stmt3) {
                $formSuccess[] = lang('SUCCESS_1') .  $product['qty'] . lang('SUCCESS_2') .'<strong>' . $stmt2['Name'] . '</strong>';
            }
        }
        
        // اذا حدث محتويات جدول الأيتم وضاف على جدول الطلبات بيحذف السلة 
        unset($_SESSION['basket']); // delete variables from session basket          
        
    } 
  
}
   // include "init2.php";
    // أذا دخل المستخدم الى الصفحة مباشرة او عن طريق رابط 
        ?>
<div class="container">
    <div class="xx">
        <h1 class=" text-center custom-h1"><?php echo lang('SHOP_CART'); ?></h1>
        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-home fa-fw"></i><?php echo lang('HOME_PAGE'); ?></a></li>
            <li class="active"><?php echo lang('SHOP_CART'); ?></li>
        </ol>                  
     <?php       
    if(isset($_SESSION['basket']) && !empty($_SESSION['basket'])){
       // print_r($_SESSION['basket']);
        ?>
                
                <div class="shop-cart ">
                    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                <?php
                    $counter = 0;
                    $priceAll = 0;
                foreach($_SESSION['basket'] as $basket){
                    
                    $counter += 1;
                    
                    $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ?");
                    $stmt->execute(array($basket['productid']));
                    $row = $stmt->fetch();
                    $priceOne = $row['Price'];// price of one product
                    $priceQty = $priceOne * $basket['qty']; // price of selected qty
                    $priceAll += $priceQty // total price of all selectes products
                ?>
                    <div class="item-cart row">
                        <a
                           href='cart.php?delete=<?php echo $basket['productid'];?>' 
                           class="delete-from-cart confirm"><?php echo lang('DELETE'); ?>
                        </a>
                        <span class="item-information">
                            <?php echo lang('PRICE_ONE_ITEM_IS_:') . $priceOne . lang('|_PRICE') ; ?>
                            <?php echo $basket['qty']; ?>
                            <?php echo lang('ITEMS_IS_:') . $priceQty; ?>
                        </span>
                        <div class="cart-img col-md-3 text-center <?php if(in_array($row['Item_ID'], $idErrors)){ echo " warning";} ?>">
                            <a href="items.php?itemid=<?php echo $row['Item_ID'] ?>" >
                                <img src="<?php echo 'admin/uploads/items/' . $row['Image']; ?>" class=""/>
                            </a>
                            <input 
                                    type='hidden' 
                                    value="<?php echo $basket['productid'];?>"
                                    name='productid_<?php echo $counter;?>'  />

                            <input 
                                    type='hidden' 
                                    value="<?php echo $basket['qty'];?>"                            
                                    name='qty_<?php echo $counter;?>'  />                

                            <input 
                                   type='text'
                                   class="form-control text-center"
                                   value="<?php echo $basket['qty']?>"
                                   name='input_<?php echo $counter;?>' />
                        </div>
                        <div class="col-md-9">
                            <span class="item-name"><?php echo $row['Name']; ?></span>
                            
                            <p><?php echo $row['Description'] ?></p>
                        </div>
                    </div>
                    <hr/>    
                <?php        
                }
                ?>
                        <input type="hidden" value="<?php echo $counter; ?>" name="counter"/>
                        <div class="text-center">
                        <input type="submit" value="<?php echo lang('UPDATE'); ?>" name="update" class="btn btn-primary"/>
                        <input type="submit" value="<?php echo lang('DELETE_ALL'); ?>" name="delete" class="btn btn-danger"/>
                        </div>

                    </form>
                    <?php 
        
                    if(!empty($formErrors)){

                        echo"<div class='error'>";
                                foreach($formErrors as $error){
                                    echo '<div class="alert alert-danger">' . $error . '</div>';
                                   // redirectFunction('back');
                                }
                            echo"</div>";
                            }
                        
                    ?>
                </div>
                <?php 
                if(!isset($_SESSION['user']) && !isset($_SESSION['shopid']) ){
                    // اذا كان يوجد سيشن لسلة التسوق والشخص الذي انشا السلة ليس مسجل كبائع او مستخدم عادي
                ?>
                <div class="buy-product">
                    <span>
                        <?php echo $priceAll; ?>
                    </span>
                    <hr />
                    <span>
                        <a href="login.php"><?php echo lang('LOGIN_OR_SIGNUP'); ?></a>
                    </span>                            
                </div>
                <?php }
                elseif(isset($_SESSION['shopid'])){
                    // اذا كان يوجد سيشن ولكن الشخص مسجل كبائع عندها يجب عليه تسجيل الخروج ثم تسجيل الدخول كمستخدم عادي
                    ?>
                
                <div class="buy-product">
                    <span>
                        <?php echo $priceAll; ?>
                    </span>
                    <hr />
                    <span>
                        <a href="logout.php"><?php echo lang('LOGOUT_FIRST'); ?></a>
                    </span>                            
                </div>
                
                <?php    
                }else{
                // اذا كان يوجد سلة تسوق ولشخص مسجل دخول كمستخدم عادي عندها يمكنه شراء المنتجات
                ?>
                <div class="buy-product">
                    <span>
                        <?php echo $priceAll; ?>
                    </span>
                    <hr />
                    <span>
                        <a href="?action=buy"><?php echo lang('CLICK_TO_BUY'); ?></a>
                    </span>                            
                </div>                
                <?php
                }
    }// إذا دخل الى الصفحة ولم يكن حدد منتجات شرائها
    else{
        if(!empty($formSuccess)){
            echo "<div class='shop-cart'>";            
                echo"<div class='error'>";
                        foreach($formSuccess as $success){
                            echo '<div class="alert alert-success">' . $success . '</div>';
                        }
                echo"</div>";            
            echo"</div>";
        }
        ?>
                    <div class="shop-cart-empty row">
                        <div class="col-md-4 text-center">
                            <i class="fa fa-warning"></i>
                            <p><?php echo lang('P_1'); ?></p>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="fa fa-shopping-cart"></i>
                            <p><?php echo lang('P_2'); ?></p>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="fa fa-truck"></i>
                            <p><?php echo lang('P_3'); ?></p>
                        </div>
                    </div>
        <?php
    }
                ?>
                
            </div>
        </div>

        <?php
include $tpl . "footer.php";
ob_end_flush();
?>