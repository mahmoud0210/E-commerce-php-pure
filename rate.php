<?php
    
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        include "admin/connect.php";
        include "includes/functions/functions.php";
        
        $rate     = filter_var($_POST['rate'], FILTER_SANITIZE_NUMBER_INT);
        $itemid   = filter_var($_POST['itemid'], FILTER_SANITIZE_NUMBER_INT);
        
        if(checkRate($itemid)){
                header('Location:index.php');
                exit();
        }else{
            
            if($rate == 5) {
                $number = "five";
            }elseif($rate == 4) {
                $number = "four";
            }elseif($rate == 3){
                $number = "three";
            }elseif($rate == 2){
                $number = "two";
            }elseif($rate == 1){
                $number = "one";
            }else {
                header('Location:index.php');
                exit();
            }

            /*
            $stmtSelect = $con->prepare("SELECT $number FROM items WHERE Item_ID = ?");
            $stmtSelect->execute(array($itemid));
            $item = $stmtSelect->fetch();
                !empty($item)*/
            if(true){
                //$newRate = $item[$number] + 1;


                $stmtUpdate = $con->prepare("UPDATE 
                                            items
                                        SET
                                            $number = $number + 1
                                        WHERE
                                            Item_ID = ?");
                $stmtUpdate->execute(array($itemid));

                if(isset($_COOKIE['rate'])){
                    $cookieOldValue = $_COOKIE['rate'];
                    $cookieNewValue = $cookieOldValue . '-' . $itemid;
                    setcookie('rate', $cookieNewValue, time() + 60 * 60 * 24 * 30 * 3, '/shops', 'localhost', false , true);
                    //echo $_COOKIE['rate'] ." | ". $cookieNewValue ." | ".$cookieOldValue;
                }else {
                    setcookie('rate', $itemid, time() + 60 * 60 * 24 * 30 * 3, '/shops', 'localhost', false, true);
                }

                    header("location: items.php?itemid=$itemid#rate");
                    exit(); 

            }else {
                header('Location:index.php');
                exit();         
            }            
            
        }        

    }

?>