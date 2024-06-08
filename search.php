<?php
ob_start();
session_start();

$getTitle = 'Search';
include "init.php";
$numProduct = 8;

$pn = isset($_GET['pn']) && is_numeric($_GET['pn'])? intval($_GET['pn']) : 1;

$search = isset($_GET['search'])?$_GET['search']:'';

$replace = "<span  class='search' style='color: #7d0005;'>" . strtoupper($search) . "</span>";

$cat = isset($_GET['cat']) && is_numeric($_GET['cat'])? intval($_GET['cat']) : 0;

$cat = $cat !== 0 ?'AND Cat_ID = ' . $cat: '';

if(!empty($search)){

?>
<div class="container">
    <div class="xx">
        <h1 id="go-to" class="custom-h1 text-center">Search Results</h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-home fa-fw"></i>Index</a></li>
        <li class="active">Search </li>
    </ol>
        <?php
        $test = pagination(
                "Item_ID", 
                "items", 
                " Name LIKE '%{$search}%' {$cat}",
                $numProduct, 
                $pn,
                "&search={$search}&cat={$cat}");
            $allItems =  $test[0];
    if(!empty($allItems)){
        
        echo'<div class="row">';
            foreach($allItems as $item){
                
                
                $name = str_ireplace($search, $replace, $item['Name']);
                
                echo'<div class="col-sm-6 col-md-3">';
                    echo'<div class="thumbnail item-box">';
                        echo'<span class="price-tag">$' .$item['Price'].             '</span>';
                        echo '<a href="items.php?itemid=' . $item['Item_ID'] . ' "><img class="img-responsive" src="admin/uploads/items/'. $item['Image'] .'" alt="" /></a>';
                        echo'<div class="caption">';
                        echo'<div class="date">' .$item['Add_Date']. '</div>';
                            echo'
                                    <a href="items.php?itemid=' . $item['Item_ID'] . ' "><p>' . ucwords($name) .'</p></a>
                                ';
                            echo'<p>' .$item['Description']. '</p>';
                        echo'</div>';
                    echo'</div>';
                echo'</div>';
            } 

        /*
        } else {
            echo'there isn\'t items in this category';
        }
        */
        ?>
        </div>
        <ul class="pagination">
            <?php echo $test[3]; ?>
        </ul>
<?php        
    }else {
?>

    <div class="search-result">
        We did not find any matches,<p><strong>please search using other words</strong></p> 
    </div>
<?php    
    }    
?>
    </div>
</div>


<?php
}else{
        header('Location: index.php');
        exit();
}
include $tpl . "footer.php";
ob_end_flush();
?>