<?php

    // Error Reporting

    ini_set('display_errors', 'On');
    error_reporting(E_ALL);

    include "admin/connect.php";
    $root = "http://localhost/shops/";

    $tpl  = 'includes/templates/';
    $lang = 'includes/languages/';
    $func = 'includes/functions/';
    $css  = 'layout/css/';
    $js   = 'layout/js/';
    $itemPhoto = 'admin/uploads/items/';
    

    include $func . "functions.php";
    include $lang . "english.php";
    include $tpl . "header.php";
/*
    if(isset($_GET['layout']) && $_GET['layout'] == 'dark'){
        setcookie('layout', 'dark', time() + 60 * 60 * 24 * 30 * 3, '/shops', 'localhost', false , true);
    }elseif(isset($_GET['layout']) && $_GET['layout'] == 'light'){
        setcookie('layout', 'light', time() + 60 * 60 * 24 * 30 * 3, '/shops', 'localhost', false ,true);
    }
*/
