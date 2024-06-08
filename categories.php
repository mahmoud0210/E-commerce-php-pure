<?php
ob_start();
session_start();

$getTitle = 'Categories';
include "init.php";
$numProduct = 8;
$catID = isset($_GET['pageid']) && is_numeric($_GET['pageid'])? intval($_GET['pageid']) : 0;

$pn = isset($_GET['pn']) && is_numeric($_GET['pn'])? intval($_GET['pn']) : 1;

    $stmtname = $con->prepare("SELECT Name FROM categories WHERE ID =?");
    $stmtname->execute(array($catID));
    $name =$stmtname->fetch();

    $stmt = $con->prepare("SELECT ID FROM categories WHERE Parent =?");
    $stmt->execute(array($catID));
    $ids =$stmt->fetchAll();

    $in = " IN ({$catID}";

    foreach($ids as $id){
        $in = $in .','.$id['ID'];
    }
    $in = $in . ')';

    $cartComponent = checkCart();

/* = {$catID}*/

?>
<div class="container">
    <div class="xx">
<h1 id="go-to" class="custom-h1 text-center"><?php echo $name['Name']; ?></h1>

    <div class="row">
        <?php
        $test = pagination(
                "Item_ID", 
                "items", 
                " Cat_ID {$in} AND Approve = 1",
                $numProduct, 
                $pn,
                "&pageid={$catID}#go-to");
            $allItems =  $test[0];
        
            foreach($allItems as $item){
?>
     
                <div class="col-md-3 col-sm-6">
                    <div class="thumbnail item-box">
                        <span class="price-tag"><?php echo "$" .$item['Price']; ?> </span>
                        <a href="items.php?itemid=<?php echo $item['Item_ID']; ?>"><img class="img-responsive" src="admin/uploads/items/<?php echo $item['Image']; ?>" alt="" /></a>
                        <div class="caption">
                        <div class="date"><?php echo $item['Add_Date']; ?></div>
                          <a href="items.php?itemid=<?php echo $item['Item_ID']; ?>"><p> <?php echo $item['Name']; ?></p></a>
                            <p><?php echo $item['Description']; ?></p>
                        </div>
                        <div class="cart">
            <?php 
                if($cartComponent == -1 or !in_array($item['Item_ID'],$cartComponent)){
            ?>
                            <form action="cart.php?cart=Add" method="post" class="center-block">
                                <input 
                                       type="hidden" 
                                       name="item_id" 
                                       value="<?php echo $item['Item_ID'] ?>" />
                                <input 
                                       type="text"
                                       class="form-control"
                                       name="item_quantity"/>
                                <input
                                       type="submit"
                                       class="btn btn-primary"
                                       value = "Add"/>
                            </form>
            <?php
                }else{ echo "<span class='center-block '><i class='fa fa-check'></i>Added</span>";}
            ?>
                        </div>    
                    </div>
                </div>        
        
<?php        
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
    </div>
</div>


<?php
include $tpl . "footer.php";
ob_end_flush();
?>