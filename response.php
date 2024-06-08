<?php

/**********************************
************ Copy Page ************
***********************************
*/
ob_start(); // output buffering start
session_start();



    include "admin/connect.php";
    include "includes/functions/functions.php";

    if (is_ajax()) {
        if (isset($_POST["action"]) && !empty($_POST["action"])) { //Checks if action value exists
            $action = $_POST["action"];
            switch($action) { //Switch case for value of action
                case "test": test_function(); break;
                case "user": user_function(); break; 
                case "category" : category_function(); break; 
                case "chat" : chat_function(); break; 
                case "sendMessage" : sendMessage(); break;  
                case "receiveMessage" : receiveMessage(); break;
                //case "sessionNum" : sessionNum();break;    
                case "unreadedMessage" : unreadedMessage();break;
                case "subscribeNow" : subscribeNow();break;    
                case "bell" :bell();break;
                case "requestAdd" : requestAdd();break;    
            }
        }
    }

    //Function to check if the request is an AJAX request
    function is_ajax() {
      return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
    /*
    function test_function(){
      $return = $_POST;
        global $con;

        $stmtItem = $con->prepare("SELECT * FROM users WHERE UserID = 9 ");

        $stmtItem->execute();

        $count = $stmtItem->rowCount();
        $row = $stmtItem->fetch();

            $return["json"] = json_encode($return);
            echo json_encode($row);
    }
    */
//---------------------------------------------------------------------------------- 

    function user_function(){
        global $con;

        $stmtItem = $con->prepare("SELECT * FROM users WHERE UserID =? ");

        $stmtItem->execute(array($_POST['User_ID']));

        $row = $stmtItem->fetch();
        $return = $row;
            echo json_encode($return);
        /*
        تم ارسال البيانات حسب صيغة الجسون وذلك لان المتصفح يتوقع استقبال بيانات بيصيغة الجسون 
        حيث في كود الاجاكس الموجود يوكن نمط البيانات هو جسون
        */
    }
//---------------------------------------------------------------------------------- 
    function category_function(){
        global $con;
        $stmtCat = $con->prepare("SELECT ID, Name FROM categories WHERE Parent =?");
        $stmtCat->execute(array($_POST['Cat_ID']));
        $cats = $stmtCat->fetchAll();
        $return = ' 
            <div class="form-group form-group-lg ">
                <label class="col-sm-2 col-md-3 control-label">Subcategories</label>
                <div class="col-sm-10 col-md-8">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Select Subcategory <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            ' ;

        foreach($cats as $cat){        
            //$return .= "<option value='".$cat['ID']."'>".$cat['Name']."</option>";

            $return .=' 
            <li>
                <input  type="radio" name="sub_category" value=" '.$cat['ID'].' " />
                <label > '.$cat['Name'].' </label>                            
            </li>';
        }

        $return .= "
                        </ul>
                    </div>
                </div>
            </div>";

        echo $return;
    }
//---------------------------------------------------------------------------------- 
    function chat_function(){
        global $con;
        $stmt = $con->prepare("SELECT Shop_ID,User_ID FROM chats WHERE Chat_ID = ?");
        $stmt->execute(array($_POST['Chat_ID']));
        $shop = $stmt->fetch();
        $return = '';
        if(isset($_SESSION['shopid'])){

            // للتاكد من أن هذه المحادثة تخص نفس البائع ولم يقم بتعديل القيمة من صفحة ال اتش تي ام  أل
            if($shop['Shop_ID'] == $_SESSION['shopid']){
                if(!isset($_POST['mesnum'])){
                   //اذا اتى الطلب عند الضفط على اسم المرسل من قائمة المرسلين على اليسار
                   // سوف يتم جعل جميع الرسائل مقروئة 
                    $mesnum = 0;
                    $stmt = $con->prepare("UPDATE
                                                messages
                                            SET
                                                Readed = ?
                                            WHERE
                                                Chat_ID = ?
                                            AND
                                                Readed = 0
                                            AND 
                                                Status = 1");
                    $stmt->execute(array(1, $_POST['Chat_ID']));
                }else{
                    $mesnum = $_POST['mesnum'];
                }

                $stmt = $con->prepare("SELECT * FROM messages WHERE Chat_ID = ? ORDER BY Message_ID DESC LIMIT {$mesnum} , 7");
                $stmt->execute(array($_POST['Chat_ID']));
                $rows = $stmt->fetchAll();
                $rows = array_reverse($rows);

                foreach($rows as $row) {

                    if($row['Status'] == 1) {
                        $return .= '<div class="message">
                                        <div class="income">
                                            <span>' . $row['Content'] . '</span>
                                        </div>
                                    </div>';    
                    }else{
                        $return .= '<div class="message">
                                        <div class="left"></div>
                                        <div class="outcome">
                                            <span>' . $row['Content'] . '</span>
                                        </div>
                                    </div>';     
                    }

                }
                 echo $return;
            }// end if($shop['Shop_ID'] == $_SESSION['shopid'])            
            
        }// end if(isset($_SESSION['shopid']))
        elseif(isset($_SESSION['uid'])){
            
            // للتاكد من أن هذه المحادثة تخص نفس المشتري ولم يقم بتعديل القيمة من صفحة ال اتش تي ام  أل
            if($shop['User_ID'] == $_SESSION['uid']){
                if(!isset($_POST['mesnum'])){
                   //اذا اتى الطلب عند الضفط على اسم المرسل من قائمة المرسلين على اليسار
                   // سوف يتم جعل جميع الرسائل مقروئة 
                    $mesnum = 0;
                    $stmt = $con->prepare("UPDATE
                                                messages
                                            SET
                                                Readed = ?
                                            WHERE
                                                Chat_ID = ?
                                            AND
                                                Readed = 0
                                            AND 
                                                Status = 2");
                    $stmt->execute(array(1, $_POST['Chat_ID']));
                }else{
                    $mesnum = $_POST['mesnum'];
                }

                $stmt = $con->prepare("SELECT * FROM messages WHERE Chat_ID = ? ORDER BY Message_ID DESC LIMIT {$mesnum} , 7");
                $stmt->execute(array($_POST['Chat_ID']));
                $rows = $stmt->fetchAll();
                $rows = array_reverse($rows);

                foreach($rows as $row) {

                    if($row['Status'] == 2) {
                        $return .= '<div class="message">
                                        <div class="income">
                                            <span>' . $row['Content'] . '</span>
                                        </div>
                                    </div>';    
                    }else{
                        $return .= '<div class="message">
                                        <div class="left"></div>
                                        <div class="outcome">
                                            <span>' . $row['Content'] . '</span>
                                        </div>
                                    </div>';     
                    }

                }
                 echo $return;
            }// end if($shop['Shop_ID'] == $_SESSION['shopid'])            
            
        }// end if(isset($_SESSION['uid']))

       
    }// end chat_function
     
 //---------------------------------------------------------------------------------- 

    function sendMessage(){
        global $con;
        $stmt = $con->prepare("SELECT Shop_ID,User_ID FROM chats WHERE Chat_ID = ?");
        $stmt->execute(array($_POST['Chat_ID']));
        $shop = $stmt->fetch();
        $return = '';
        
            if(isset($_SESSION['shopid'])){
                
                // للتاكد من أن هذه المحادثة تخص نفس البائع ولم يقم بتعديل القيمة من صفحة ال اتش تي ام  أل
                if($shop['Shop_ID'] == $_SESSION['shopid']){

                    $message = trim(filter_var($_POST['message'], FILTER_SANITIZE_STRING));

                        if(!empty($message)){

                            $stmt = $con->prepare('INSERT INTO 
                                                        messages (Chat_ID, Content, Message_Date, Status) 
                                                        VALUES(:zchat, :zcontent, now(), 2)');
                            $stmt->execute(array(
                                'zchat' => $_POST['Chat_ID'],
                                'zcontent' => $message
                            ));

                            if($stmt){
                                $return .= '<div class="message">
                                                <div class="left"></div>
                                                <div class="outcome">
                                                    <span>' . nl2br($message)  . '</span>
                                                </div>
                                            </div>';  
                            }// end if($stmt)

                        }// end if(!empty($message))

                    echo $return;

                }// end if($shop['Shop_ID'] == $_SESSION['shopid'])      
                
            }// endif(isset($_SESSION['shopid']))
            elseif(isset($_SESSION['uid'])){
   
                // للتاكد من أن هذه المحادثة تخص نفس البائع ولم يقم بتعديل القيمة من صفحة ال اتش تي ام  أل
                if($shop['User_ID'] == $_SESSION['uid']){

                    $message = trim(filter_var($_POST['message'], FILTER_SANITIZE_STRING));

                        if(!empty($message)){

                            $stmt = $con->prepare('INSERT INTO 
                                                        messages (Chat_ID, Content, Message_Date, Status) 
                                                        VALUES(:zchat, :zcontent, now(), 1)');
                            $stmt->execute(array(
                                'zchat' => $_POST['Chat_ID'],
                                'zcontent' => $message
                            ));

                            if($stmt){
                                $return .= '<div class="message">
                                                <div class="left"></div>
                                                <div class="outcome">
                                                    <span>' . nl2br($message)  . '</span>
                                                </div>
                                            </div>';  
                            }// end if($stmt)

                        }// end if(!empty($message))

                    echo $return;

                }// end if($shop['User_ID'] == $_SESSION['uid'])                
                
            }// end if(isset($_SESSION['uid']))        
        
    }

//----------------------------------------------------------------------------------

    function receiveMessage(){
        global $con;
        $stmt = $con->prepare("SELECT Shop_ID,User_ID FROM chats WHERE Chat_ID = ?");
        $stmt->execute(array($_POST['Chat_ID']));
        $shop = $stmt->fetch();
        $return = '';
        
        if(isset($_SESSION['shopid'])){
            // للتاكد من أن هذه المحادثة تخص نفس البائع ولم يقم بتعديل القيمة من صفحة ال اتش تي ام  أل
            if($shop['Shop_ID'] == $_SESSION['shopid']){
                $stmt = $con->prepare("SELECT Content,Message_Date FROM messages WHERE Chat_ID = ? AND Status = 1 AND Readed = 0 ORDER BY Message_ID DESC");
                $stmt->execute(array($_POST['Chat_ID']));
                $rows = $stmt->fetchAll();
                $rows = array_reverse($rows);

                foreach($rows as $row){
                        $return .= '<div class="message">
                                        <div class="income">
                                            <span>' . $row['Content'] . '</span>
                                        </div>
                                    </div>';   
                }

                $stmt = $con->prepare("UPDATE
                                            messages
                                        SET
                                            Readed = ?
                                        WHERE
                                            Chat_ID = ?
                                        AND
                                            Readed = 0
                                        AND 
                                            Status = 1");
                $stmt->execute(array(1, $_POST['Chat_ID']));

            }// end if($shop['Shop_ID'] == $_SESSION['shopid'])     
            
        }
        elseif(isset($_SESSION['uid'])){
            
            // للتاكد من أن هذه المحادثة تخص نفس البائع ولم يقم بتعديل القيمة من صفحة ال اتش تي ام  أل
            if($shop['User_ID'] == $_SESSION['uid']){
                $stmt = $con->prepare("SELECT Content,Message_Date FROM messages WHERE Chat_ID = ? AND Status = 2 AND Readed = 0");
                $stmt->execute(array($_POST['Chat_ID']));
                $rows = $stmt->fetchAll();
                $rows = array_reverse($rows);

                foreach($rows as $row){
                        $return .= '<div class="message">
                                        <div class="income">
                                            <span>' . $row['Content'] . '</span>
                                        </div>
                                    </div>';   
                }

                $stmt = $con->prepare("UPDATE
                                            messages
                                        SET
                                            Readed = ?
                                        WHERE
                                            Chat_ID = ?
                                        AND
                                            Readed = 0
                                        AND 
                                            Status = 2");
                $stmt->execute(array(1, $_POST['Chat_ID']));

            }// end if($shop['Shop_ID'] == $_SESSION['shopid'])  
        }

        
        echo $return;
    }
//----------------------------------------------------------------------------------
    /*function sessionNum(){
        if($_POST['browser'] == 1){
            $return = $_SESSION['shopid'];
        }elseif($_POST['browser'] == 0){
            $return = $_SESSION['uid'];
        }
            echo json_encode($return);
        //echo $return;
    }*/
//----------------------------------------------------------------------------------
    function unreadedMessage(){
        
        global $con;
        
        if($_POST['browser'] == 1){
            //$return = $_SESSION['shopid'];
            
            $stmt = $con->prepare("Select Chat_ID From chats Where Shop_ID = ? ORDER BY Chat_ID DESC");
            $stmt->execute(array($_SESSION['shopid']));
            $rows = $stmt->fetchAll();   
            $counter = 0;
            foreach($rows as $row){
                $stmt = $con->prepare("SELECT count(Message_ID) AS num FROM messages WHERE Chat_ID = ? AND Readed = 0 AND Status = ?");
                $stmt->execute(array($row['Chat_ID'],1));
                $num = $stmt->fetch(); 
                $return['count_'.$counter] = $num['num']; 
                $counter +=1;
            }
            echo json_encode($return);
        }elseif($_POST['browser'] == 0){
            //$return = $_SESSION['uid'];
            
            $stmt = $con->prepare("Select Chat_ID From chats Where User_ID = ? ORDER BY Chat_ID DESC");
            $stmt->execute(array($_SESSION['uid']));
            $rows = $stmt->fetchAll();   
            $counter = 0;
            foreach($rows as $row){
                $stmt = $con->prepare("SELECT count(Message_ID) AS num FROM messages WHERE Chat_ID = ? AND Readed = 0 AND Status = ?");
                $stmt->execute(array($row['Chat_ID'],2));
                $num = $stmt->fetch(); 
                $return['count_'.$counter] = $num['num']; 
                $counter +=1;
            }
            echo json_encode($return);
            //echo $return;
        }
    }
//----------------------------------------------------------------------------------
    function subscribeNow(){
        global $con;
        $return = '';
        
        $stmt = $con->prepare('INSERT INTO 
                                subscribes (User_ID, Shop_ID) 
                                VALUES(:zuser, :zshop)');
        $stmt->execute(array(
        'zuser' => $_POST['User_ID'],
        'zshop' => $_POST['Shop_ID']
        ));    
        
        if($stmt){
            $return = '<span class="subscribed pull-right"
                             data-shop=" ' . $_POST['Shop_ID'] . ' " 
                             data-user=" ' . $_SESSION['uid'] . ' "> 
                             <i class="fa fa-bell-slash active"></i>
                             <i class="fa fa-bell"></i>
                             Subscribed
                        </span>';
            echo $return;
        }
    }
//----------------------------------------------------------------------------------

    function bell(){
        global $con;
        $return = '';
        
        $stmt = $con->prepare("SELECT Bell FROM subscribes WHERE User_ID = ? AND Shop_ID = ?");
        $stmt->execute(array($_POST['User_ID'], $_POST['Shop_ID']));
        $row = $stmt->fetch();
        if($stmt->rowCount() == 1){
            if($row['Bell'] == 0){
                
                $stmt2 = $con->prepare("UPDATE
                                            subscribes
                                        SET
                                            Bell = 1
                                        WHERE
                                            User_ID = ?
                                        AND
                                            Shop_ID = ?");
                $stmt2->execute(array($_POST['User_ID'], $_POST['Shop_ID']));  
           
                /*$return = '<span class="subscribed pull-right"
                             data-shop=" ' . $_POST['Shop_ID'] . ' " 
                             data-user=" ' . $_SESSION['uid'] . ' "> 
                             <i class="fa fa-bell"></i> Subscribed
                        </span>';*/
                $return = 'unmute';
            }else{
                
                $stmt = $con->prepare("UPDATE
                                            subscribes
                                        SET
                                            Bell = 0
                                        WHERE
                                            User_ID = ?
                                        AND
                                            Shop_ID = ?");
                $stmt->execute(array($_POST['User_ID'], $_POST['Shop_ID']));  
              
                $return = 'mute';
            }
            echo $return;
        }
    }

//----------------------------------------------------------------------------------

    function requestAdd(){
        global $con;
        $return = '';
        $request = $_POST['Request_ID'];
        if(isset($_SESSION['shopid'])){
            $shop = $_SESSION['shopid'];

            $stmt = $con->prepare("UPDATE
                                        requests
                                   SET
                                        Status =?
                                   WHERE
                                        Request_ID =?");
            $stmt->execute(array(1, $request));

            $stmt2= $con->prepare("INSERT INTO 
                                requests_status(Request_ID, Shop_ID)
                                VALUES(:zrequest, :zshop)");
            $stmt2->execute(array(
                        'zrequest' => $request,
                        'zshop'    => $shop
            ));

            $return = "Accepted";
            echo $return;
        } // end if(isset($_SESSION['shopid']))       
    }

//----------------------------------------------------------------------------------

if($_SERVER['REQUEST_METHOD'] != 'POST'){
    header('location:index.php');
    exit();
}

ob_end_flush(); // release the output

?>