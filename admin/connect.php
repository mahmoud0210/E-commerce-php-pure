<?php
/*
phpinfo();

if(defined('PDO::ATTR_DRIVER_NAME')) {
    echo "true";
} else {
    echo "false";
}

    $connection=new mysqli("localhost","root","","shop");

    $con = new PDO('mysql:host=localhost;dbname=shop', 'root', '');

*/
    $dsn = 'mysql:host=localhost;dbname=shop';
    $user = 'root';
    $pass = '';
    $option = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    );

try{
    $con = new PDO($dsn, $user, $pass, $option);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
    echo 'failed to connect' .' '. $e->getMessage();
}
?>