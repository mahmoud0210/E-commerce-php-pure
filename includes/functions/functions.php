<?php


/*

get Similar v1

*/

// تابع يقوم بحذف محتويات المجلد بشكل كامل
function delete_dir($dir){
    if (file_exists($dir)){
        
        $files = scandir($dir);// حفظ محتويات المجد في مصفوفة 
        foreach($files as $file){
            $link = $dir . "\\" . $file; 
            /*
            البدء بفحص عناصر المصفوفة عنصر عنصر ولدينا عدة حالات 
            1 - اذا كان العنصر هو عبارة عن ملف فيقوم بحذفه مباشرة 
            2 - if(!rmdir($link)) اذا كان العنصر مجلد فيحاول حذفه وذلك ضمن الشرط 
            وهنا لدينا حالتان 
            2 - 1 - المجلد فارغ فيتم حذفة ولا يتم تنفيذ الشرط 
            2 - 2 - المجلد غير فارغ فلا يتم حذفة ونقوم باعادة استدعاء التابع مرة اخرى وعند الاأنتهاء نقوم بحذفه 
            */
            if(is_dir($link) && $file !== '.' && $file !== '..' ){
                if(!rmdir($link)){
                    // المجلد غير فارغ
                    delete_dir($link);//استدعاء التابع مرة اخرى وحذف محتويات المجلد 
                    rmdir($link);// حذف المجلد بعد ان أصبح فارغ
                }
            }elseif(is_file($link) && is_writeable($link)){
                unlink($link);//حذف العنصر اذا كان ملف 
            }
        }

        rmdir($dir);// حذف المجلد الأساسي بعد حذف جميع محتوياته 
    }    
}

function getSimilar($itemid, $catid, $tags, $parent, $limit = 4){
    global $con;
    $alltag = explode(",", $tags);
    $result = array();
    $getsimilar =  $con->prepare("SELECT 
                                    * 
                                FROM 
                                    items
                                WHERE 
                                    Item_ID != {$itemid}
                                AND    
                                    Cat_ID = {$catid} 
                                AND 
                                    Tags LIKE '%{$tags}%'
                                LIMIT {$limit}");
    $getsimilar->execute();
    $first = $getsimilar->fetchAll();
    
    if(!empty($first)){
        foreach($first as $fir){
            $result[]= $fir;
        }   
    }    
    
    /*
    أول خطوة بيدور على المتجات التي في نفس القسم ولها نفس التاغ تماما
    فإذا كانت التائج أقل من اليمت المسموح به يدخل إلى شرط ايف التالي لكي يبحث عن نتائج اخرى 
    أقل تطايق
    */
    
    if($getsimilar->rowCount() < $limit ){
        
        
        /*
        اذا كان نتيجت الأستعلام الأول أقل من اليمت المسموح به
        */
        $count = $limit - $getsimilar->rowCount();
        
        foreach($alltag as $tag){
            
            $getsimilar =  $con->prepare("SELECT 
                                            * 
                                        FROM 
                                            items
                                        WHERE 
                                            Item_ID != $itemid
                                        AND    
                                            Cat_ID = $catid
                                        AND 
                                            Tags LIKE '%$tag%'
                                        LIMIT $count");
            $getsimilar->execute();
            $first = $getsimilar->fetchAll();
            
            if(!empty($first)){
                foreach($first as $fir){
                    
                    foreach($result as $res){
                        if($res['Item_ID'] == $fir['Item_ID']){
                            $find = "find";
                            break;
                        }
                    }
                    if(isset($find) && $find == 'find'){
                        //لا تضيف المنتج الى النتائج
                        $find = 'notFind';
                    }else{
                        $result[]= $fir;
                        $find = 'notFind';
                    }
                }
                if(count($result) == $limit){
                    break;
                }else{
                    $count = $limit - count($result);
                }    
            }
  
        }         
    }
    
    if(count($result) < $limit) {
        $count = $limit - count($result);
            $getsimilar =  $con->prepare("SELECT 
                                        * 
                                    FROM 
                                        items
                                    WHERE 
                                        Item_ID != $itemid
                                    AND    
                                        Cat_ID = $catid
                                    LIMIT $count");
        $getsimilar->execute();
        $first = $getsimilar->fetchAll();
        
            if(!empty($first)){
                foreach($first as $fir){
                    
                    foreach($result as $res){
                        if($res['Item_ID'] == $fir['Item_ID']){
                            $find = "find";
                            break;
                        }
                    }
                    if(isset($find) && $find == 'find'){
                        //لا تضيف المنتج الى النتائج
                        $find = 'notFind';
                    }else{
                        $result[]= $fir;
                        $find = 'notFind';
                    }
                }    
            }        
        
    }
    
    
    if(count($result) < $limit) {
        
        if($parent !== 0){
         
        $count = $limit - count($result);
            $getsimilar =  $con->prepare(" SELECT 
                                            items.*,
                                            categories.Parent
                                        FROM 
                                            items 
                                        INNER JOIN categories ON items.Cat_ID = categories.ID
                                        WHERE
                                            categories.Parent = {$parent}
                                        AND Item_ID != $itemid    ");
        $getsimilar->execute();
        $first = $getsimilar->fetchAll();
        
            if(!empty($first)){
                foreach($first as $fir){
                    
                    foreach($result as $res){
                        if($res['Item_ID'] == $fir['Item_ID']){
                            $find = "find";
                            break;
                        }
                    }
                    if(isset($find) && $find == 'find'){
                        //لا تضيف المنتج الى النتائج
                        $find = 'notFind';
                    }else{
                        $result[]= $fir;
                        $find = 'notFind';
                        if(count($result) == $limit){
                            break;
                        }
                    }
                }    
            }               
            
        }     
        
    }    
    return $result;
 // AND Tags LIKE '%{$tag}%'
    
}

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

/*

Get one Function v2.0
Function to get record from table 

*/

function getOneFrom($field, $table, $where = NULL, $orderfield, $ordering = "DESC"){
    global $con;
    $getall =$con->prepare("SELECT 
                                $field
                            FROM 
                                $table 
                            $where
                            ORDER BY 
                                $orderfield $ordering");
    $getall->execute();
    $all = $getall->fetch();
    return $all;
}

/*
** getCat() function v1.0
** function to get latest categories from database
*/

function getCat(){
    global $con;
    $getStmt = $con->prepare("SELECT * FROM categories ORDER BY ID ");
    $getStmt->execute();
    $cats = $getStmt->fetchAll();
    return $cats;
}

/*
** getItems() function v1.0
** function to get latest items from specific category in database
*/

function getItems($where, $value, $approve = NULL){
    global $con;
    $sql = $approve == NULL ? 'AND Approve = 1' : NULL;
    $getStmt = $con->prepare("SELECT
                                    * 
                                FROM 
                                    items 
                                WHERE 
                                    $where = ? $sql
                                ORDER BY Item_ID DESC ");
    $getStmt->execute(array($value));
    $items = $getStmt->fetchAll();
    return $items;
}

/*
** checkUserStatus() function v1.0
** function to check user status if he regesered or not 
*/

function checkUserStatus($name){
    global $con;
    $stmtx = $con->prepare('SELECT
                                * 
                            FROM
                                users 
                            WHERE 
                                Username = ? AND RegStatus = 0 ');
    $stmtx->execute(array($name));
    $status = $stmtx->rowCount();
    return $status;
}

function getTitle() {
    
    global $getTitle;
    if(isset($getTitle)){
        echo $getTitle;
    }else {
        echo 'Default';
    }
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
    $statement = $con->prepare("SELECT $select FROM $from WHERE Username = ?");
    $statement->execute(array($value));
    $row = $statement->rowCount();
    return $row;
}

//-----------------------------------------------------------------------------

function checkShop($value) {
    global $con;
    $statement = $con->prepare("SELECT Shop_ID FROM shops WHERE Shop_ID = ?");
    $statement->execute(array($value));
    $row = $statement->rowCount();
    return $row;
}

//-----------------------------------------------------------------------------

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

function redirectHome($errorMsg, $seconds = 3){
    echo "<div class='alert alert-danger' >$errorMsg</div>";
    echo "<div class ='alert alert-info'>You will be redirected to Homepage after <strong>$seconds Seconds</stong></div>";
    header("refresh:$seconds;url=index.php");
    exit();
    
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
function pagination($id, $table, $where, $numProduct, $pn, $urlInfo) {
    global $con;
    $sql = $con->prepare("SELECT COUNT($id) FROM $table WHERE $where ");
    $sql->execute();
    $row = $sql->fetch();
    $rows = $row[0];
    $page_rows = $numProduct;
    $last = ceil($rows/$page_rows);
    if ($last < 1) { $last = 1; }
    $pagenum = $pn;
    $limit = 'LIMIT ' . ($pagenum - 1) * $page_rows . ',' . $page_rows;
    $stmt = $con->prepare("SELECT * FROM $table WHERE $where ORDER BY $id DESC $limit");
    $stmt->execute();
    $products = $stmt->fetchAll();
    $textline1 = "products(<b>$rows</b>)";
    $textline2 = "page<b>$pagenum</b>of<b>$last</b>";
    $paginationCtrls = '';
    if($last != 1) {
        if($pagenum > 1) {
            $previous = $pagenum - 1;
            $paginationCtrls .= '<li class="page-item"><a class="page-link"  href =" ' . $_SERVER['PHP_SELF'] . '?pn='.$previous .''.$urlInfo.'"> previous</a></li>';
            for($i = $pagenum - 4 ; $i < $pagenum ; $i++) {
                if($i > 0) {
                    $paginationCtrls .= '<li class="page-item"><a class="page-link"  href =" ' . $_SERVER['PHP_SELF'] . '?pn=' .$i . ''.$urlInfo.'"> '. $i .'</a></li>';  
                }
            }
        }
        $paginationCtrls .= '<li class="page-item active"><a class="page-link ">' . $pagenum . '</a></li>';
        for($i = $pagenum + 1 ; $i <= $last ; $i++) {
            $paginationCtrls .= '<li class="page-item"><a class="page-link"  href =" ' . $_SERVER['PHP_SELF'] . '?pn=' .$i . ''.$urlInfo.'"> '. $i .'</a></li>';
            if ($i > $pagenum+4) { break ;}
        }
        if($pagenum != $last) {
            $next = $pagenum + 1;
            $paginationCtrls .= '<li class="page-item"><a class="page-link"  href =" ' . $_SERVER['PHP_SELF'] . '?pn=' .$next . ''.$urlInfo.'"> next</a></li>';
        }
    }
    $resultFunc = array($products, $textline1, $textline2, $paginationCtrls);
    return $resultFunc;
}

// *******************
// shop cart functions
// *******************

function addUpdateItem($pid, $qty){
    
    if(!isset($_SESSION['basket'])){
        
        $_SESSION['basket'] = array();
        $_SESSION['basket'][0]['productid'] = $pid;
        $_SESSION['basket'][0]['qty'] = $qty;
        return "ADD";
        
    }else{
        
        if(isexist($pid,$qty)){
            return "UPDATE";
        }else{
            $m = $_SESSION['basket'];
            $max = count($m);
            $_SESSION['basket'][$max]['productid'] = $pid;
            $_SESSION['basket'][$max]['qty'] = $qty;
            return "ADD";
        }
        
    }
}

function isexist($pid, $qty){
    $m = $_SESSION['basket'];
    $max = count($m);
    for($i = 0; $i < $max; $i++){
        if($pid == $_SESSION['basket'][$i]['productid']){
            $_SESSION['basket'][$i]['qty'] = $qty;
            return true;
            break;
        }
    }
    return false;
}

function deleteItem($pid){
    $m = $_SESSION['basket'];
    $max =count($m);
    for($i=0; $i <$max; $i++){
        if($pid == $_SESSION['basket'][$i]['productid']){
            unset($_SESSION['basket'][$i]);
            /*
            unset
            تحذف عنصر من المصفوفة لكن وتحذف دليله أيضا
            لذلك يجب استخدام الدالة التي تليها حتى يتم إعادة ترتيب دليل المصفوفة 
            */
            $_SESSION['basket'] = array_values($_SESSION['basket']);
            break;
        }
    }
}

function shopCartAuto($quantity, $pid){
    
        $qty = filter_var($quantity, FILTER_SANITIZE_STRING);

        if(is_numeric($qty) && $qty >0){
            $qtyInit = intval($qty);
            $notification = addUpdateItem($pid, $qtyInit);
            if($notification == "ADD"){
                return "You add $qtyInit items to your shop cart";
            }elseif($notification == "UPDATE"){
                echo "your shop cart is updated";
            }
        }     
}
    
function checkCart(){
    $itemsID = array();
    if(isset($_SESSION['basket'])) {
        $m = $_SESSION['basket'];
        $max = count($m);
        for($i=0; $i<$max; $i++){
            $itemsID[] = $_SESSION['basket'][$i]['productid'];
        }
        return $itemsID;
    }else return -1;
}
/*
rate check function v1
*/
function checkRate($itemid) {
    if(isset($_COOKIE['rate'])){
        $cookie = $_COOKIE['rate'];
        $cookieArray = explode('-',$cookie);
        if(in_array($itemid, $cookieArray)) {
            return true;
        }else {
            return false;
        }
    }
}


// *********************
// recentlyViewed function
// *********************

function addRecentlyViewed($item) {
    if(isset($_COOKIE['recently'])){
        
        $recently =  $_COOKIE['recently'];
        $recently = explode('-', $recently);
        
        if(!in_array($item, $recently)){
            if(count($recently) >= 7) {
                
                array_pop($recently);
                array_unshift($recently, $item);
                $recently = implode('-', $recently);
                setcookie('recently', $recently, time() + 60*60*24*30*3, '/', 'localhost', false, false);
                
            }else {
                
                array_unshift($recently, $item);
                $recently = implode('-', $recently);
                setcookie('recently', $recently, time() + 60*60*24*30*3, '/', 'localhost', false, false);
                
            }
        }
        
    }else{
        setcookie('recently', $item, time() + 60*60*24*30*3, '/', 'localhost', false, false);
    }
}
function recentlyViewed(){
    global $con;
    if(isset($_COOKIE['recently'])) {
        $recently = str_replace('-', ',', $_COOKIE['recently']);
        $in = "(".$recently.")";
        $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID IN $in");
        $stmt->execute();
        return $stmt;        
    }
}