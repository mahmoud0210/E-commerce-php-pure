<?php

    $dsn = 'mysql:host=localhost;dbname=test';
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
<!DOCTYPE html>
<html>
    <head>
        <title>Gibran</title>
        <style>
      
        </style>
    </head>
    <body>
       <img src="/shops/picture.jpg" alt="Mountain" style="width:100%">
</body>
</html>