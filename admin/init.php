<?php

    include "connect.php";

    $tpl  = 'includes/templates/';
    $lang = 'includes/languages/';
    $func = 'includes/functions/';
    $css  = 'layout/css/';
    $js   = 'layout/js/';
    

    include $func . "functions.php";
    if(isset($_COOKIE['lang']) && $_COOKIE['lang'] == 'arabic' ){
        include $lang . "arabic.php";
    }else{
        include $lang . "english.php";
    }
    include $tpl . "header.php";

    if( !isset($nonavbar) ) {
        include $tpl . "navbar.php";
    }

    //$test  = 45;