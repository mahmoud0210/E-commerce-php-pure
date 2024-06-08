<?php

/**********************************
************ Copy Page ************
***********************************
*/
ob_start(); // output buffering start

session_start();

$pageTitle = '';

if (isset($_SESSION['username'])){
    
    include "init.php";
    
    $do =isset($_GET['do'])?$_GET['do'] : 'Manage';
    
    if($do == "Manage") {
        
    }elseif($do == 'Add') {
        
    }elseif($do == 'Insert') {
        
    }elseif($do == 'Edit') {
        
    }elseif($do == 'Update') {
        
    }elseif($do == 'Delete') {
        
    }
    
    echo "hello";
    
    include $tpl . 'footer.php';
    
} //end if (isset($_SESSION['username']))
else{
    header('location:index.php');
    exit();
}

ob_end_flush(); // release the output

?>