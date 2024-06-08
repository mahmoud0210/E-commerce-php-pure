<?php
    ob_start();
    session_start();

    $getTitle = 'Home Page';

    include "init.php";

    
    $adsItem = getAllFrom("*", "items", "WHERE Ads_Slide = 1 ", "Item_ID", "DESC LIMIT 5");
    $newItemOne = getAllFrom("*", "items", "WHERE Ads_Item IS NULL AND Approve = 1", "Item_ID", "DESC LIMIT 0, 4");
    $newItemTwo = getAllFrom("*", "items", "WHERE Ads_Item IS NULL AND Approve = 1", "Item_ID", "DESC LIMIT 4, 4");

    $statement = $con->prepare("SELECT Username FROM users WHERE UserID = 1");
    $statement->execute();
    $info = $statement->fetch();
    $itemsArray = explode('-', $info[0]);

    //$leftItem       = $itemsArray[0];
    //$categoryTop    = $itemsArray[1];
    //$one            = $itemsArray[2];
    //$tow            = $itemsArray[3];
    //$three          = $itemsArray[4];
    //$categoryDown   = $itemsArray[5];
    //$shop           = $itemsArray[6];

    $leftItem = getOneFrom("*", "items", "WHERE Item_ID = {$itemsArray[0]}", "Item_ID", "");

    $categoryTopName = getOneFrom("Name", "categories", "WHERE ID = {$itemsArray[1]}", "ID", "");
    $categoryTopOne = getAllFrom("*", "items", "WHERE Cat_ID = {$itemsArray[1]} AND Approve = 1", "Item_ID", "DESC LIMIT 0, 4");
    $categoryTopTow = getAllFrom("*", "items", "WHERE Cat_ID = {$itemsArray[1]} AND Approve = 1", "Item_ID", "DESC LIMIT 4, 4");

    $one = getOneFrom("*", "items", "WHERE Item_ID = {$itemsArray[2]}", "Item_ID", "");
    $tow = getOneFrom("*", "items", "WHERE Item_ID = {$itemsArray[3]}", "Item_ID", "");
    $three = getOneFrom("*", "items", "WHERE Item_ID = {$itemsArray[4]}", "Item_ID", "");

    $categoryDownName = getOneFrom("Name", "categories", "WHERE ID = {$itemsArray[5]}", "ID", "");
    $categoryDownOne = getAllFrom("*", "items", "WHERE Cat_ID = {$itemsArray[5]} AND Approve = 1", "Item_ID", "DESC LIMIT 0, 4");
    $categoryDownTow = getAllFrom("*", "items", "WHERE Cat_ID = {$itemsArray[5]} AND Approve = 1", "Item_ID", "DESC LIMIT 4, 4");

    $newest = getOneFrom("*", "items", "WHERE Approve = 1", "Item_ID", "DESC LIMIT 1");
    $newestShop = getOneFrom("*", "shops", "WHERE Shop_ID = {$itemsArray[6]}", "Shop_ID", "ASC LIMIT 1");
    
    $cartComponent = checkCart();
    
?>


<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
<?php
      $classActive = 0;
      foreach($adsItem as $item) {
        $classActive = ($classActive == 0) ? " active": "";      
?>
    <div class="item <?php echo $classActive;  ?> ">
        <a href="items.php?itemid=<?php echo $item['Item_ID']; ?>"> 
            <img src="<?php echo 'http://localhost/shops/admin/uploads/slide/' . $item['Ads_Item'];  ?>" alt="...">
        </a>
    </div>
<?php 
        $classActive = 1;
      }
?>
  </div>
</div>


<div class="container">    
<div class="xx">    
    <div class="row">
        
            <div class="col-md-3 ">
                <div class="row">
                <div class="col-md-12 col-sm-6">
                    <div class="cat-show-left">
                    <ul class="list-unstyled">  
                <?php
                $allCats = getAllFrom("*", "categories", "WHERE Parent = 0", "ID", "ASC");

                foreach($allCats as $cat){
                    echo '<li>
                            <a href="categories.php?pageid='. $cat['ID'] .' ">
                                <i class="fa fa-arrow-right"></i>'.$cat['Name'].'
                            </a>
                          </li>';

                }?>
                    </ul>
                    </div> 
                </div>

                <div class="col-md-12 col-sm-6">
                    <div class="thumbnail item-box">
                        <span class="price-tag"><?php echo "$" .$leftItem['Price']; ?> </span>
                        <a href="items.php?itemid=<?php echo $leftItem['Item_ID']; ?>"><img class="img-responsive" src="admin/uploads/items/<?php echo $leftItem['Image']; ?>" alt="" /></a>
                        <div class="caption">
                        <div class="date"><?php echo $leftItem['Add_Date']; ?></div>
                          <a href="items.php?itemid=<?php echo $leftItem['Item_ID']; ?>"><p> <?php echo $leftItem['Name']; ?></p></a>
                            <p><?php echo $leftItem['Description']; ?></p>
                        </div>
                        <div class="cart">
            <?php 
                if($cartComponent == -1 or !in_array($leftItem['Item_ID'],$cartComponent)){
            ?>
                            <form action="cart.php?cart=Add" method="post" class="center-block">
                                <input 
                                       type="hidden" 
                                       name="item_id" 
                                       value="<?php echo $leftItem['Item_ID'] ?>" />
                                <input 
                                       type="text"
                                       class="form-control"
                                       name="item_quantity"/>
                                <input
                                       type="submit"
                                       class="btn btn-primary"
                                       value = "<?php echo lang('ADD'); ?>"/>
                            </form>
            <?php
                }else{ echo "<span class='center-block '><i class='fa fa-check'></i>". lang('ADDED') ."</span>";}
            ?>
                        </div>    
                    </div>
                </div>
                    
                <div class="col-md-12 col-sm-6">
                    <div class="thumbnail item-box">
                        <a href="shopinfo.php?shopid=<?php echo $newestShop['Shop_ID']; ?>"><img class="img-responsive" src="admin/uploads/shops/<?php echo $newestShop['Avatar']; ?>" alt="" /></a>
                        <div class="caption">
                          <a href="shopinfo.php?shopid=<?php echo $newestShop['Shop_ID']; ?>"><p> <?php echo $newestShop['Shop_Name']; ?></p></a>
                        </div>   
                    </div>
                </div>                 
                    
                <div class="col-md-12 col-sm-6">
                    <div class="thumbnail item-box">
                        <span class="price-tag"><?php echo "$" .$newest['Price']; ?> </span>
                        <a href="items.php?itemid=<?php echo $newest['Item_ID']; ?>"><img class="img-responsive" src="admin/uploads/items/<?php echo $newest['Image']; ?>" alt="" /></a>
                        <div class="caption">
                        <div class="date"><?php echo $newest['Add_Date']; ?></div>
                          <a href="items.php?itemid=<?php echo $newest['Item_ID']; ?>"><p> <?php echo $newest['Name']; ?></p></a>
                            <p><?php echo $newest['Description']; ?></p>
                        </div>
                        <div class="cart">
            <?php 
                if($cartComponent == -1 or !in_array($newest['Item_ID'],$cartComponent)){
            ?>
                            <form action="cart.php?cart=Add" method="post" class="center-block">
                                <input 
                                       type="hidden" 
                                       name="item_id" 
                                       value="<?php echo $newest['Item_ID'] ?>" />
                                <input 
                                       type="text"
                                       class="form-control"
                                       name="item_quantity"/>
                                <input
                                       type="submit"
                                       class="btn btn-primary"
                                       value = "<?php echo lang('ADD'); ?>"/>
                            </form>
            <?php
                }else{ echo "<span class='center-block '><i class='fa fa-check'></i>". lang('ADDED') ."</span>";}
            ?>
                        </div>    
                    </div>
                </div>    
                    
                    
                </div>
            </div>
            <div class="col-md-9 ">
                <h3 class="custom-h3"> <!--New Items-->
                    <?php echo lang('NEW_ITEMS'); ?>
                </h3>
                <div class="new-item">

                    <div class="new-slide-item show ">
                      <ul class="thumbnails">
                          <div class="row">
                        <?php

                          foreach($newItemOne as $onenew) {
                        echo '
                        <li class=" col-md-3  col-sm-6 col-xs-12">
                        <div class="thumbnail">
                            <a class="" href="items.php?itemid=' . $onenew['Item_ID'] . ' ">
                                ' .$onenew['Name'].'
                            </a>
                            <a href="items.php?itemid=' . $onenew['Item_ID'] . ' "><img src="admin/uploads/items/' . $onenew['Image'] . '" alt="' .$one['Name'].'"></a>
                        </div>
                        </li>';
                          }

                        ?>
                            </div>
                      </ul>
                      </div>

                       <div class="new-slide-item">
                          <ul class="thumbnails">
                              <div class="row">
                            <?php

                              foreach($newItemTwo as $two) {
                            echo '
                            <li class="span3 col-md-3 col-sm-6 col-xs-12">
                            <div class="thumbnail">
                                <a class="" href="items.php?itemid=' . $two['Item_ID'] . ' ">
                                    ' .$two['Name'].'
                                </a>
                                <a href="items.php?itemid=' . $two['Item_ID'] . ' "><img src="admin/uploads/items/' . $two['Image'] . '" alt="' .$two['Name'].'"></a>
                            </div>
                            </li>';
                              }

                            ?>
                              </div>
                          </ul>
                      </div>
                    
                    <div class="left-control" ><i class="fa fa-arrow-left"></i></div>
                    <div class="right-control" ><i class="fa fa-arrow-right"></i></div>
                    
                  </div>
                
             <!-- START CAT ONE SLIDE -->
                <h3 class="custom-h3"><?php echo $categoryTopName['Name'] ?></h3>
                <div class="new-item">

                    <div class="new-slide-item show">
                      <ul class="thumbnails">
                          <div class="row">
                        <?php

                          foreach($categoryTopOne as $firstone) {
                        echo '
                        <li class="span3 col-md-3 col-sm-6 col-xs-12">
                        <div class="thumbnail">
                            <a class="" href="items.php?itemid=' . $firstone['Item_ID'] . ' ">
                                ' .$firstone['Name'].'
                            </a>
                            <a href="items.php?itemid=' . $firstone['Item_ID'] . ' "><img src="admin/uploads/items/' . $firstone['Image'] . '" alt="' .$firstone['Name'].'"></a>
                        </div>
                        </li>';
                          }

                        ?>
                          </div>
                      </ul>
                    </div>

                    <div class="new-slide-item">
                      <ul class="thumbnails">
                          <div class="row">
                        <?php

                          foreach($categoryTopTow as $secondone) {
                        echo '
                        <li class="span3 col-md-3 col-sm-6 col-xs-12">
                        <div class="thumbnail">
                            <a class="" href="items.php?itemid=' . $secondone['Item_ID'] . ' ">
                                ' .$secondone['Name'].'
                            </a>
                            <a href="items.php?itemid=' . $secondone['Item_ID'] . ' "><img src="admin/uploads/items/' . $secondone['Image'] . '" alt="' .$secondone['Name'].'"></a>
                        </div>
                        </li>';
                          }

                        ?>
                          </div>
                      </ul>
                    </div>
                        
                    <div class="left-control" ><i class="fa fa-arrow-left"></i></div>
                    <div class="right-control" ><i class="fa fa-arrow-right"></i></div>
                  </div> 
                
             <!-- END CAT ONE SLIDE -->
                
            <!-- 
                *****************
                *****************
                **Special Items**
                *****************
                *****************
            -->
                <h3 class="custom-h3"><?php echo lang('SPECIAL_ITEMS'); ?></h3>
                <div class="row">
                <div class="col-sm-4 ">
                    <div class="thumbnail item-box">
                        <span class="price-tag"><?php echo "$" .$one['Price']; ?> </span>
                        <a href="items.php?itemid=<?php echo $one['Item_ID']; ?>"><img class="img-responsive" src="admin/uploads/items/<?php echo $one['Image'] ?>" alt="" /></a>
                        <div class="caption">
                        <div class="date"><?php echo $one['Add_Date']; ?></div>
                          <a href="items.php?itemid=<?php echo $one['Item_ID']; ?>"><p> <?php echo $one['Name']; ?></p></a>
                            <p><?php echo $one['Description']; ?></p>
                        </div>
                        <div class="cart">
            <?php 
                if($cartComponent == -1 or !in_array($one['Item_ID'],$cartComponent)){
            ?>
                            <form action="cart.php?cart=Add" method="post" class='center-block '>
                                <input 
                                       type="hidden" 
                                       name="item_id" 
                                       value="<?php echo $one['Item_ID'] ?>" />
                                <input 
                                       class="form-control"
                                       type="text"
                                       name="item_quantity"/>
                                <input
                                       type="submit"
                                       class="btn btn-primary"
                                       value="<?php echo lang('ADD'); ?>"/>
                            </form>
            <?php
                }else{ echo "<span class='center-block '><i class='fa fa-check'></i>". lang('ADDED') ."</span>";}
            ?>                       
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-4">
                    <div class="thumbnail item-box">
                        <span class="price-tag"><?php echo "$" .$tow['Price']; ?> </span>
                        <a href="items.php?itemid=<?php echo $tow['Item_ID']; ?>"><img class="img-responsive" src="admin/uploads/items/<?php echo $tow['Image'] ?>" alt="" /></a>
                        <div class="caption">
                        <div class="date"><?php echo $tow['Add_Date']; ?></div>
                          <a href="items.php?itemid=<?php echo $tow['Item_ID']; ?>"><p> <?php echo $tow['Name']; ?></p></a>
                            <p><?php echo $tow['Description']; ?></p>
                        </div>
                        <div class="cart">
            <?php 
                if($cartComponent == -1 or !in_array($tow['Item_ID'],$cartComponent)){
            ?>
                            <form action="cart.php?cart=Add" method="post" class='center-block '>
                                <input 
                                       type="hidden" 
                                       name="item_id" 
                                       value="<?php echo $tow['Item_ID'] ?>" />
                                <input 
                                       type="text"
                                       class="form-control"
                                       name="item_quantity"/>
                                <input
                                       type="submit"
                                       class="btn btn-primary"
                                       value="<?php echo lang('ADD'); ?>"/>
                            </form>
            <?php
                }else{ echo "<span class='center-block '><i class='fa fa-check'></i>". lang('ADDED') ."</span>";}
            ?>                        
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-4">
                    <div class="thumbnail item-box">
                        <span class="price-tag"><?php echo "$" .$three['Price']; ?> </span>
                        <a href="items.php?itemid=<?php echo $three['Item_ID']; ?>"><img class="img-responsive" src="admin/uploads/items/<?php echo $three['Image'] ?>" alt="" /></a>
                        <div class="caption">
                        <div class="date"><?php echo $three['Add_Date']; ?></div>
                          <a href="items.php?itemid=<?php echo $three['Item_ID']; ?>"><p> <?php echo $three['Name']; ?></p></a>
                            <p><?php echo $three['Description']; ?></p>
                        </div>
                        <div class="cart">                        
            <?php 
                if($cartComponent == -1 or !in_array($three['Item_ID'],$cartComponent)){
            ?>
                            <form action="cart.php?cart=Add" method="post" class='center-block '>
                                <input 
                                       type="hidden" 
                                       name="item_id" 
                                       value="<?php echo $three['Item_ID'] ?>" />
                                <input 
                                       type="text"
                                       class="form-control"
                                       name="item_quantity"/>
                                <input
                                       type="submit"
                                       class="btn btn-primary"
                                       value="<?php echo lang('ADD'); ?>"/>
                            </form>
            <?php
                }else{ echo "<span class='center-block '><i class='fa fa-check'></i>". lang('ADDED') ."</span>";}
            ?>                        
                        </div>                   
                    </div>
                </div>  
                </div>
             <!-- START CAT TOW SLIDE -->
                <h3 class="custom-h3"><?php echo $categoryDownName['Name'] ?></h3>
                <div class=" new-item">

                    <div class="new-slide-item show">
                      <ul class="thumbnails">
                          <div class="row">
                        <?php

                          foreach($categoryDownOne as $firsttow) {
                        echo '
                        <li class="span3 col-md-3 col-sm-6 col-xs-12">
                        <div class="thumbnail">
                            <a class="" href="items.php?itemid=' . $firsttow['Item_ID'] . ' ">
                                ' .$firsttow['Name'].'
                            </a>
                            <a href="items.php?itemid=' . $firsttow['Item_ID'] . ' "><img src="admin/uploads/items/'. $firsttow['Image'] .'" alt="' .$firsttow['Name'].'"></a>
                        </div>
                        </li>';
                          }

                        ?>
                          </div>
                      </ul>
                          
                      </div>

                   <div class="new-slide-item">
                      <ul class="thumbnails">
                          <div class="row">
                        <?php

                          foreach($categoryDownTow as $secondtow) {
                        echo '
                        <li class="span3 col-md-3 col-sm-6 col-xs-12">
                        <div class="thumbnail">
                            <a class="" href="items.php?itemid=' . $secondtow['Item_ID'] . ' ">
                                ' .$secondtow['Name'].'
                            </a>
                            <a href="items.php?itemid=' . $secondtow['Item_ID'] . ' "><img src="admin/uploads/items/'. $secondtow['Image'] .'" alt="' .$secondtow['Name'].'"></a>
                        </div>
                        </li>';
                          }

                        ?>
                          </div>
                      </ul>
                  </div>
                    <div class="left-control" ><i class="fa fa-arrow-left"></i></div>
                    <div class="right-control" ><i class="fa fa-arrow-right"></i></div>
                  </div> 
                
             <!-- END CAT ONE SLIDE -->                
            </div>
    </div>
</div>        
</div>

<?php
    include $tpl . "footer.php";
ob_end_flush();
?>