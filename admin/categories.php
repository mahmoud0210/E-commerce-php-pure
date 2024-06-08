<?php

/**********************************
************ categories Page ************
***********************************
*/
ob_start(); // output buffering start

session_start();

$getTitle = 'Categories';

if (isset($_SESSION['username'])){
    
    include "init.php";
    
    $do =isset($_GET['do'])?$_GET['do'] : 'Manage';
    
    if($do == "Manage") {
        
        $sort = 'ASC';
        $sortArray = array( 'ASC', 'DESC' );
        if(isset($_GET['sort']) && in_array($_GET['sort'] , $sortArray )){
            $sort =$_GET['sort'];
        }
        
        $stmt2 = $con->prepare("SELECT * FROM categories WHERE Parent = 0 ORDER BY Ordering $sort");
        $stmt2->execute();
        $cats = $stmt2->fetchAll(); ?>
    
    
<?php if(!empty($cats)){ ?>
    <div class="container categories">
        <div class="xx">
        <h1 class="text-center custom-h1"><i class="fa fa-th-large"></i><?php echo lang('MANAGE_CATEGORIES'); ?></h1>             
        <div class="add-cat">
            <a href="categories.php?do=Add" ><span><i class="fa fa-plus"></i><?php echo lang('ADD_NEW_CATEGORY'); ?></span></a>
        </div>
           
        <div class="panel panel-default">
            <div class="panel-heading">
                Manage Categories
                <div class="option pull-right">
                    Ordering :
                    <a class="<?php if($sort == 'ASC') {echo"active";}?>" href="?sort=ASC">Asc</a> |
                    <a class="<?php if($sort == 'DESC') {echo"active";}?>" href="?sort=DESC">Desc</a>
                    View :
                    <span class="active" data-view="full">Full</span> | 
                    <span >Classic</span>
                </div>
            </div>
            <div class="panel-body">
                
        <?php      
        foreach($cats as $cat){
            
            echo"<div class='cat'>";
                echo"<div class='hidden-btn'>";
                    echo"<a href='categories.php?do=Edit&catid=".$cat['ID']."' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i>". lang('EDIT') ."</a>";
                    echo"<a href='categories.php?do=Delete&catid=".$cat['ID']."' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i>". lang('DELETE') ."</a>";
                echo"</div>";
                echo '<h3>'. $cat['Name'] . '</h3>';
                echo "<div class='full-view'>";
                    echo "<p>";
                        if($cat['Description'] == ''){
                            echo "This category has no description";
                        }else{
                            echo $cat['Description'];
                        }
                    echo"</p>";
                    if($cat['Visibility'] == 1){
                        echo "<span class='visibility'>". lang('HIDDEN') ."</span>";}
                    if($cat['Allow_Comment'] == 1){
                        echo"<span class='commenting'>". lang('NO_COMMENT') ."</span>";}
                    if($cat['Allow_Ads'] == 1){
                        echo "<span class='advertises'>". lang('NO_ADS') ."</span>";}
                
$childCat = getAllFrom("*", "categories", "WHERE parent = {$cat['ID']}", "Name", "ASC");
            if(!empty($childCat)) {
                echo "<div class='row'>";
                foreach($childCat as $c){ ?>
                
                    <div class='col-md-2 col-sm-4 text-center child-cat'>
                        <p><?php echo $c['Name']; ?></p>
                        <div class="hidden-child-edit">
                        <a href='categories.php?do=Edit&catid=<?php echo $c['ID'] ?>' class=''><i class='fa fa-edit'></i></a>
                        </div>
                        <div class="hidden-child-close">
                        <a href='categories.php?do=Delete&catid=<?php echo $c['ID'] ?>' class='confirm '><i class='fa fa-close'></i></a>
                        </div>
                    </div>
                
             <?php }
                echo "</div>";
            }
                echo"</div>";
            echo"</div>";
            echo"<hr>";
        }
        ?>
            </div>
        </div>
        <div class="add-cat">
            <a href="categories.php?do=Add" ><span><i class="fa fa-plus"></i><?php echo lang('ADD_NEW_CATEGORY'); ?></span></a>
        </div>
        </div>
    </div>

    <?php 
          }//end if(!empty($cats))
           else { ?>
                <div class="container">
                <div class="xx">
                    <h1 class="text-center custom-h1"><?php echo lang('MANAGE_CATEGORIES'); ?></h1> 
                    <div class="alert alert-success"> There's not records to show  </div>
                    <div class="add-cat">
                            <a href="categories.php?do=Add" >
                                <span><i class="fa fa-plus"></i><?php echo lang('ADD_NEW_CATEGORY'); ?></span>
                            </a>
                    </div>
                    </div>
               </div>
               
         <?php  }
    ?>


<?php
    } // end if($do == "Manage")
    elseif($do == 'Add') { ?>
            <div class="container">
                <div class="xx">
                    <h1 class="text-center custom-h1"><i class="fa fa-plus"></i> <?php echo lang('ADD_NEW_CATEGORY') ?> </h1>

            

                <form class="form-horizontal" action="?do=Insert" method="post">

    <!--form-horizontal bootstrap class : label in left and input in right -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"><?php echo lang('NAME_CAT') ?></label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="<?php echo lang('PLACEHOLDER_NAME_CAT') ?>" />
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"><?php echo lang('DESCRIPTION_CAT') ?> </label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" name="description" class="form-control" placeholder="<?php echo lang('PLACEHOLDER_DESC_CAT') ?>"/>
                        </div>
                    </div>
                    
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"><?php echo lang('PARENT_?') ?></label>
                        <div class="col-sm-10 col-md-8">
                            <select name="parent">
                                <option value="0" >None</option>
                                <?php
        $allCats= getAllFrom("*", "categories", "WHERE Parent = 0", "Name", "ASC");
        foreach($allCats as $cat){
           echo "<option value=' " . $cat['ID'] . " '> " . $cat['Name'] . " </option>"; 
        }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"><?php echo lang('ORDERING') ?> </label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" name="ordering" class="form-control" placeholder="<?php echo lang('PLACEHOLDER_ORDERING_CAT') ?>"/>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> <?php echo lang('VISIBLE') ?> </label>
                        <div class="col-sm-10 col-md-8">
                            <div>
                                <input id="vis-yes" type="radio" name="visibility" value="0" checked />
                                <label for="vis-yes">Yes</label>
                            </div>
                            <div>
                                <input id="vis-no" type="radio" name="visibility" value="1"/>
                                <label for="vis-no">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> <?php echo lang('ALLOW_COMMENTING') ?> </label>
                        <div class="col-sm-10 col-md-8">
                            <div>
                                <input id="com-yes" type="radio" name="commenting" value="0" checked />
                                <label for="com-yes">Yes</label>
                            </div>
                            <div>
                                <input id="com-no" type="radio" name="commenting" value="1"/>
                                <label for="com-no">No</label>
                            </div>
                        </div>
                    </div>


                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> <?php echo lang('ALLOW_ADS') ?> </label>
                        <div class="col-sm-10 col-md-8">
                            <div>
                                <input id="ads-yes" type="radio" name="ads" value="0" checked />
                                <label for="ads-yes">Yes</label>
                            </div>
                            <div>
                                <input id="ads-no" type="radio" name="ads" value="1"/>
                                <label for="ads-no">No</label>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="<?php echo lang('ADD_NEW_CATEGORY') ?>" class="btn btn-primary btn-lg" />
                        </div>
                    </div>

                </form>
            </div>
        </div>

<?php
        
    } //end if($do == 'Add')
    elseif($do == 'Insert') {       
        
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){ 
            
        echo '<div class="container">';
            echo '<div class="xx">';
            echo '<h1 class="text-center custom-h1">'. lang('INSERT_CATEGORY') .'</h1>';             
            
            $name       = $_POST['name'];
            $desc       = $_POST['description'];
            $parent     = !empty($_POST['parent'])?$_POST['parent']:0;
            $order      = $_POST['ordering'];
            $visible    = $_POST['visibility'];
            $comment    = $_POST['commenting'];
            $ads        = $_POST['ads'];
                
                // check if category exist in database
                $check = checkItem("Name", "categories", $name);
            
                if ($check == 1){
                    echo "<div class='alert alert-danger'>This catiegory is exist , try with other name</div>";
                    redirectFunction('back');
                    
                }else{
                    
                    $stmt = $con->prepare(" INSERT INTO
                                            categories(Name, Description, Parent, Ordering, Visibility, Allow_Comment ,Allow_ads)
                                            VALUES(:zname, :zdesc, :zparent, :zorder, :zvisible, :zcomment ,:zads)");

                    $stmt->execute(array(
                        'zname'     => $name,
                        'zdesc'     => $desc,
                        'zparent'   => $parent,
                        'zorder'    => $order,
                        'zvisible'  => $visible,
                        'zcomment'  => $comment,
                        'zads'      => $ads
                    ));

                    echo '<div class="alert alert-success">' . $stmt->rowCount() . ' recourd inserted' . '</div>';
                    redirectFunction('back');
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
        
    } //if($do == 'Insert')
    
    elseif($do == 'Edit') {
        
$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
        
        $stmt = $con->prepare("SELECT * 
                               FROM categories 
                               WHERE ID = ? 
                               LIMIT 1 ");
        $stmt->execute(array($catid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        
        if($count > 0){ ?>       
            <div class="container">
                <div class="xx">    
                <h1 class="text-center custom-h1"><i class="fa fa-wrench "></i> <?php echo lang('EDIT_CATEGORY'); ?> </h1>

                <form class="form-horizontal" action="?do=Update" method="post">

    <!--form-horizontal bootstrap class : label in left and input in right -->
                    <input type="hidden" name="catid" value="<?php echo $catid ?>"/>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"><?php echo lang('NAME_CAT'); ?></label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="<?php echo lang('PLACEHOLDER_NAME_CAT'); ?>" value="<?php echo $row['Name'] ?>"/>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"><?php echo lang('DESCRIPTION_CAT'); ?> </label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" name="description" class="form-control" placeholder="<?php echo lang('PLACEHOLDER_DESC_CAT'); ?>" value="<?php echo $row['Description'] ?>"/>
                        </div>
                    </div>
                    
                    <?php
                $childs= getAllFrom("*", "categories", "WHERE Parent = {$row['ID']}", "Name", "ASC");
                       if(!empty($childs)){ ?>
                           
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"><?php echo lang('PARENT_?'); ?></label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" class="form-control" placeholder="<?php echo lang('CATEGORY_HAS_CHILDS'); ?>" disabled/>
                        </div>
                    </div>
                <?php        
                       }else{
                           
                       
                    ?>   
                    
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"><?php echo lang('PARENT_?'); ?></label>
                        <div class="col-sm-10 col-md-8">
                            <select name="parent">
                                <option value="0" >None</option>
                                <?php
            /*
            االهدف من الشرط التالي هو : اذا كانت الكاتيغوري هي اساسية اي ليست ابن
            لا يظهر اسم هذه الكاتيغوري ضمن الأقسام الممكن أختيارها
            */
            if($row['Parent'] == 0) {
                $allCats= getAllFrom("*", "categories", "WHERE Parent = 0 AND ID != {$row['ID']}", "Name", "ASC");

                foreach($allCats as $cat){
                   echo "<option value=' " . $cat['ID'] . "'>" . $cat['Name'] . " </option>";
                }   
            }else{
                $allCats= getAllFrom("*", "categories", "WHERE Parent = 0", "Name", "ASC");

                foreach($allCats as $cat){
                   echo "<option value=' " . $cat['ID'] . " ' " ;
                    if($cat['ID'] == $row['Parent']){
                        echo " selected";
                    }
                   echo " > " . $cat['Name'] . " </option>";
                }
            }
                                ?>
                            </select>
                        </div>
                    </div>
                    <?php
                       }// end if(!empty($childs)) --> else
                    ?>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"><?php echo lang('ORDERING'); ?></label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" name="ordering" class="form-control" placeholder="<?php echo lang('PLACEHOLDER_ORDERING_CAT') ?>" value="<?php echo $row['Ordering'] ?>"/>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> <?php echo lang('VISIBLE'); ?> </label>
                        <div class="col-sm-10 col-md-8">
                            <div>
                                <input id="vis-yes" type="radio" name="visibility" value="0" <?php if($row['Visibility'] == 0 ){echo "checked"; } ?> />
                                <label for="vis-yes">Yes</label>
                            </div>
                            <div>
                                <input id="vis-no" type="radio" name="visibility" value="1" <?php if($row['Visibility'] == 1 ){echo "checked"; } ?>/>
                                <label for="vis-no">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"><?php echo lang('ALLOW_COMMENTING'); ?></label>
                        <div class="col-sm-10 col-md-8">
                            <div>
                                <input id="com-yes" type="radio" name="commenting" value="0" <?php if($row['Allow_Comment'] == 0 ){echo "checked"; } ?> />
                                <label for="com-yes">Yes</label>
                            </div>
                            <div>
                                <input id="com-no" type="radio" name="commenting" value="1" <?php if($row['Allow_Comment'] == 1 ){echo "checked"; } ?>/>
                                <label for="com-no">No</label>
                            </div>
                        </div>
                    </div>


                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"> <?php echo lang('ALLOW_ADS'); ?> </label>
                        <div class="col-sm-10 col-md-8">
                            <div>
                                <input id="ads-yes" type="radio" name="ads" value="0"
                                <?php if($row['Allow_Ads'] == 0 ){echo "checked"; } ?> />
                                <label for="ads-yes">Yes</label>
                            </div>
                            <div>
                                <input id="ads-no" type="radio" name="ads" value="1" 
                                <?php if($row['Allow_Ads'] == 1 ){echo "checked"; } ?>/>
                                <label for="ads-no">No</label>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="<?php echo lang('UPDATE'); ?>" class="btn btn-primary btn-lg" />
                        </div>
                    </div>

                </form>
            </div>
        </div>
                    


        <?php 
            } //end if ($count > 0)
            else { 
            echo '<div class="container">';
                echo '<div class="xx">';           
                echo '<h1 class="custom-h1 text-center"><i class="fa fa-warning"></i> Access Denied</h1>';
                echo "<div class='alert alert-danger'>There is't category with this value</div>";
                redirectFunction('home');
                echo '</div>';
            echo '</div>';            
        }
        
    } //end if($do == "Edit")
        
    elseif($do == 'Update') {
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            
            echo '<div class="container">';
                echo '<div class="xx">';             
            
                echo '<h1 class="custom-h1 text-center">'. lang('UPDATE_CATEGORY') .'</h1>';
           
            
            $id         = $_POST['catid'];
            $name       = trim($_POST['name']);
            $disc       = $_POST['description'];
            $parent     = !empty($_POST['parent'])?$_POST['parent']:0;
            $order      = $_POST['ordering'];
            $visible    = $_POST['visibility'];
            $comment    = $_POST['commenting'];
            $ads        = $_POST['ads'];
            
            
            $stmtCheck = $con->prepare("SELECT
                                            Name
                                        FROM 
                                            categories
                                        WHERE
                                            Name = ? AND ID != ?");
            $stmtCheck->execute(array($name, $id));
            $count =$stmtCheck->rowCount();
            
            //تشكيل مصفوفة لفحص الأخطاء
            $formErrors = array();
            
            if(empty($name)){
                $formErrors[] ='Username cant be  <strong> Empty </strong>';
            }
            if($count == 1){
                $formErrors[] = 'This category is exist , try with other <strong> Name </strong>';
            }
            /*
            التأكد من أن ال كاتيغوري لا تحوي على قسام فرعية 
            لانه اذا كانت تحوي اقسام غرعية لا يمكن وضعها هي كقسم فرعي ل كاتيغوري أخرى 
            حتى لا يصبح لدينا ثلاث طبقات من الأقسام 
            
            هيدا الحكي كان ضروري أول شي بس بعدين عدلت الكاتيغوري التي تحوي أبناء بأن تصبح 
            disable
            
            $check=getAllFrom ("*", "categories", "WHERE Parent={$id}", "ID");
            
            if(!empty($check) && $parent != 0 ){
             echo '<div class="container">';
                echo '<div class="xx">';           
                echo '<h1 class="custom-h1 text-center"><i class="fa fa-warning"></i> Access Denied</h1>';
                echo "<div class='alert alert-danger'><p>Error</p></div>";
                redirectFunction('home');
                echo '</div>';
            echo '</div>';
            */
            
            if(!empty($formErrors)){
                foreach($formErrors as $error){
                    echo '<div class="alert alert-danger">' . $error . '</div>';
                    redirectFunction('back');
                }
            }else{
            
            $stmt = $con->prepare(" UPDATE 
                                        categories
                                    SET 
                                        Name = ?,
                                        Description =?,
                                        Parent =?,
                                        Ordering =?,
                                        Visibility =?,
                                        Allow_Comment =?,
                                        Allow_Ads =?
                                    WHERE
                                        ID = ? ") ;
            $stmt->execute(array($name ,$disc ,$parent ,$order ,$visible ,$comment ,$ads ,$id));
            echo '<div class="alert alert-success">' . $stmt->rowCount() . ' recourd updated </div>';
            redirectFunction('back');
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
        
        

        
    }//end if($do == "Update")
    
    elseif($do == 'Delete') {
        
$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']): 0;

            $stmt = $con->prepare("SELECT * 
                                   FROM categories 
                                   WHERE ID = ? 
                                   LIMIT 1 ");
            $stmt->execute(array($catid));
            $count = $stmt->rowCount();

            if($count > 0){
                
                echo'<div class="container">';
                    echo'<div class="xx">';    
                    echo'<h1 class="text-center custom-h1"><i class="fa fa-wrench "></i>'. lang('DELETE_CATEGORY') .'</h1>';                

                $stmt = $con->prepare("DELETE FROM categories WHERE ID = :zcat");
                $stmt->bindParam(":zcat", $catid);
                $stmt->execute();
                echo '<div class="alert alert-success">' . $stmt->rowCount() . ' recourd deleted </div>';
                redirectFunction('back');
                    echo '</div>';    
                echo '</div>';    
            }else{
            echo '<div class="container">';
                echo '<div class="xx">';           
                echo '<h1 class="custom-h1 text-center"><i class="fa fa-warning"></i> Access Denied</h1>';
                echo "<div class='alert alert-danger'>There is't category with this value</div>";
                redirectFunction('home');
                echo '</div>';
            echo '</div>';
            }

        
    }//end if($do == "Delete")
    
    include $tpl . 'footer.php';
    
} //end if (isset($_SESSION['username']))
else{
    header('location:index.php');
    exit();
}

ob_end_flush(); // release the output
?>