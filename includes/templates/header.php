<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php getTitle() ?></title>
        <!-- ملف البوتستراب  -->
        <!-- ضمن ملفات المشروح حطيت ملف البوتستراب الغير مضغوط مشان اذا بدي شوفو وعدل عليه  -->
        <link rel="stylesheet" href="http://localhost/shops/<?php echo $css; ?>bootstrap.min.css" />
        <!-- ملف الفونت اوسوم  -->
        <link rel="stylesheet" href="http://localhost/shops/<?php echo $css; ?>font-awesome.min.css" />
        <link rel="stylesheet" href="http://localhost/shops/<?php echo $css; ?>jquery-ui.css" />
        <link rel="stylesheet" href="http://localhost/shops/<?php echo $css; ?>jquery.selectBoxIt.css" />
        <link rel="stylesheet" href="http://localhost/shops/<?php echo $css; ?>bootstrap-tagsinput.css" />
        <link rel="stylesheet" href="<?php echo $root . $css; ?>front.css" />
        <?php
        if(!isset($_COOKIE['layout']) || isset($_COOKIE['layout']) && $_COOKIE['layout'] == 'light'){
        ?>    
        <link rel="stylesheet" href="<?php echo $root . $css; ?>light-front.css" />
        <?php 
        }
        ?>
    </head>
    <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand navbar-img" href="index.php" title="<?php echo lang('HOME_PAGE'); ?>"><img src="admin/uploads/navbar/logo-red.png" alt="website logo"/> </a>

          <!--<a class="navbar-brand" href=""> Shops </a>-->

        </div>
        <div class="collapse navbar-collapse" id="app-nav">
            
            <ul class="nav navbar-nav navbar-right ">
                <!-- begien all categories in md & lg screen -->
                <li id="cat-lg-md" class="dropdown "> 
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                      <i class="fa fa-th-large fa-fw"></i> <?php echo lang('CATEGORIES'); ?> <span class="caret"></span>
                  </a>
                  <ul class="dropdown-menu">

                    <li>
                    <?php

                        $allCats = getAllFrom("ID,Name", "categories", "WHERE Parent = 0", "Name", "ASC");

                        foreach($allCats as $cat){
                            echo '<div class="">
                                    <div class="cat-parent" data-id="#id-'. $cat['ID'] .'">
                                        <a href="categories.php?pageid='. $cat['ID'] .' ">'.$cat['Name'].'</a>
                                        <div class="cat-childs" id="id-'. $cat['ID'] .'">';
                                        $childs = getAllFrom("ID,Name", "categories", "WHERE Parent = {$cat['ID']}", "Name", "ASC");
                                        foreach($childs as $child) {
                                            echo '<a href="categories.php?pageid='. $child['ID'] .' ">'.$child['Name'].'</a>';
                                        }
                                    echo'</div>
                                    </div>
                                </div>';
                        }

                    ?>
                    </li>
                    <li role="separator" class="divider"></li>
                    <li>
                        <div class="cat-parent">
                            <a href="#"><?php echo lang('ALL_CATEGORIES'); ?></a>
                        </div>
                    </li>
                
                  </ul>
                </li> 
                <!-- end all categories in md & lg screen -->
                <!-- link to categories in sm & xs screen -->
                <li class="cat-sm-xs">
                    <a href="#"> <i class="fa fa-th-large fa-fw"></i> <?php echo lang('CATEGORIES'); ?></a>
                </li>
                <li class="">
                    <a href="cart.php" ><i class="fa fa-shopping-cart fa-fw"></i> <?php echo lang('BASKET'); ?> 
                    <?php
                    if(isset($_SESSION['basket'])){
                        $countItems = count($_SESSION['basket']);
                        if($countItems != 0){
                            echo "<span class='items-in-cart'>$countItems</span>";
                        }
                    }
                    ?>
                    </a>
                </li>                 
                
                <?php
                  if(isset($_SESSION['user'])){
                      
                        echo '<li> <a href="profile.php"> <i class="fa fa-user fa-fw"></i> ' . lang('ACCOUNT') . ' </a> </li>';
                        echo '<li> <a href="request.php"> <i class="fa  fa-plus fa-fw"></i> ' . lang('ADD_REQUEST') . ' </a> </li>';
                        echo '<li> <a href="messages.php"> <i class="fa fa-paper-plane" aria-hidden="true"></i> ' . lang('CHATS') . ' </a></li>';
                        echo '<li> <a href="logout.php"> <i class="fa  fa-sign-out fa-fw"></i> ' . lang('LOGOUT') . ' </a> </li>';              
                        
                  }elseif(isset($_SESSION['shopid'])){
                      
                        echo '<li> <a href="myshop.php"> <i class="fa fa-user fa-fw"></i> ' . lang('MY_SHOP') . ' </a> </li>';
                        echo '<li> <a href="newad.php"> <i class="fa  fa-plus fa-fw"></i> ' . lang('ADD_ITEM') . ' </a> </li>';
                        echo '<li> <a href="messages.php"> <i class="fa fa-paper-plane" aria-hidden="true"></i> ' . lang('CHATS') . ' </a></li>';
                        echo '<li> <a href="logout.php"> <i class="fa  fa-sign-out fa-fw"></i> ' . lang('LOGOUT') . ' </a> </li>';

                  }else{
                      
                        echo '<li> <a href="login.php"> <i class="fa fa-unlock-alt fa-fw"></i> ' . lang('LOGIN_SIGNUP') . '</a> </li>';            
                    } 
                ?>
            </ul>
            <form class="navbar-form navbar-left" action="search.php" method="get">

                <div class="input-group">                
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo lang('SELECT_CATEGORY'); ?><span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            
                            <li>
                                <input  type="radio" name="cat" value="0" checked/>
                                <label ><?php echo lang('ALL'); ?></label>                            
                            </li>                            
<?php
                        $cats = getAllFrom("ID, Name", "categories", "WHERE Parent = 0","ID");
                            foreach($cats as $search){
?>
                            <li>
                                <input  type="radio" name="cat" value="<?php echo $search['ID'] ?>" />
                                <label ><?php echo $search['Name'] ?></label>                            
                            </li>
<?php                            
                            }
?>                            
                        </ul>
                    </div><!-- /btn-group -->
                    <input type="text" class="form-control" aria-label="..." placeholder="<?php echo lang('SEARCH_PLACEHOLDER'); ?>" name="search">
                </div><!-- /input-group -->                
                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>     
            </form>

        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
        
    <div class="layout-change">
        <div class="light-mode"><?php echo lang('LIGHT'); ?></div>
        <div class="dark-mode"><?php echo lang('DARK'); ?></div>
    </div>    