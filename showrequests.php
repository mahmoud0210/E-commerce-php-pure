<?php
ob_start();
session_start();
$getTitle = 'Requests';
include "init.php";

$numRequests = 10;
$pn = isset($_GET['pn']) && is_numeric($_GET['pn']) ? intval($_GET['pn']) : 1;
$pagination = pagination(
                'Request_ID',
                'requests',
                'Status = 0',
                $numRequests,
                $pn,
                '');
$requests = $pagination[0];
$control = $pagination[3];
?>

<div class="container">
<div class="xx"> 
    <h1 class="custom-h1 text-center">Requests</h1>
    <?php if(empty($requests)){ ?>
    <div class="no-requests">
        There Are Not Any Requests !!!
    </div>
    <?php }else{ ?>
    
    <?php if(!isset($_SESSION['shopid'])){ ?>
        <h4 class="text-center"><i class="fa fa-warning"></i><strong> Login As Shop Owrner To Accept Requests !!</strong></h4><br />       
    <?php } ?>
    
    <div class="shop-cart">
        <?php
        foreach($requests as $request){
            $name = getOneFrom('Name', 'categories', "WHERE ID = {$request['Cat_ID']}", 'Name');
        ?>
            <div class="item-cart row">
                <?php if(isset($_SESSION['shopid'])){ ?>
                    <span class="request-control accept" data-request="<?php echo $request['Request_ID']; ?>">Accept</span>
                <?php } ?>
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
                    <div class="request-show">
                        
                        <div class="request-showmore">
                            <strong>Show More Information</strong>
                        </div>
                        
                        <div class="request-details">
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
                </div>
            </div>
            <hr/> 
        <?php } ?>
    </div>
    <ul class="pagination">
        <?php echo $control; ?>
    </ul>
    <?php } ?>    
</div>
</div>

<?php
include $tpl."footer.php";
ob_end_flush();
?>