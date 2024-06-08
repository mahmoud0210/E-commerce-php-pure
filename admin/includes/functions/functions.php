<?php

/*

Get All Function v2.0
Function to get records from table 

*/

function getAllFrom($field, $table, $where = NULL, $orderfield, $ordering = "DESC"){
    global $con;
    $getall =$con->prepare("SELECT 
                                $field
                            FROM 
                                $table 
                            $where
                            ORDER BY 
                                $orderfield $ordering");
    $getall->execute();
    $all = $getall->fetchAll();
    return $all;
}

function getTitle() {
    
    global $getTitle;
    if(isset($getTitle)){
        echo $getTitle;
    }else {
        echo 'Default';
    }
}

function redirectFunction($url = "back", $seconds = 5){
    
    $goto;
    $to;
    if($url == 'back'){
        $goto = $_SERVER['HTTP_REFERER'];
        $to ="Previous Page";
    }elseif($url == 'home'){
        $goto = "index.php";
        $to = "Home Page";
    }else{
        $goto = $url;
        $to = "(".$url.")";
    }
    
    echo "<div class ='alert alert-info'>
            You will be redirected to <strong> $to </strong> after <strong>$seconds Seconds</stong>
        </div>";    
    
    header("refresh:$seconds;url=$goto");
    exit();
    
}

/*
** check items function v1.0
** function to check item in database [ function accept parameters ]
** $select = the item to select [ example : user, item, category ]
** $from = the table to select from [ example : users, items, categories ]
** $value = the value of select [ example : osama, box, electronics ]
*/

function checkItem($select, $from, $value) {
    global $con;
    $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
    $statement->execute(array($value));
    $row = $statement->rowCount();
    return $row;
}

/*
** count number of items function  v1.0
** function to count number of items rows in spicific table in database [ accept parameters ]
** $item = the item to count 
** $table = the table to choose from 
*/

function countItems ( $item, $table ) {
    global $con;
    $stmt2 = $con->prepare(" SELECT COUNT($item) FROM $table");
    $stmt2->execute();
    return $stmt2->fetchcolumn();
}


function convertToK ($number){
    if($number >= 1000) {
        return intval($number/1000) . 'K';
    }else {
        return $number;
    }
}
/*
** get latest records function v1.0
** function to get latest items from database [ users, items, comments ]
** $select = field to select
** $table = the table to choose from
** $limit = number of records to get 
*/

function getLatest($select, $table, $order, $limit= 5){
    global $con;
    $getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
    $getStmt->execute();
    $rows = $getStmt->fetchAll();
    return $rows;
}
