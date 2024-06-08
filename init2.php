<?php

    // Error Reporting
/*
    ini_set('display_errors', 'On');
    error_reporting(E_ALL);
*/
    include "admin/connect.php";
    $root = "http://localhost/shops/";

    $tpl  = 'includes/templates/';
    $lang = 'includes/languages/';
    $func = 'includes/functions/';
    $css  = 'layout/css/';
    $js   = 'layout/js/';
    $itemPhoto = 'admin/uploads/items/';
    

    //include $func . "functions.php";
    include $lang . "english.php";
    include $tpl . "header.php";

