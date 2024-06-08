<?php

/**********************************
************ Copy Page ************
***********************************
*/
ob_start(); // output buffering start

session_start();


        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;
        
        require 'includes/libraries/PHPMailer/src/Exception.php';
        require 'includes/libraries/PHPMailer/src/PHPMailer.php';
        require 'includes/libraries/PHPMailer/src/SMTP.php';


$getTitle = 'Contact';

if (isset($_SESSION['username'])){
    
    include "init.php";
    
    $do =isset($_GET['do'])?$_GET['do'] : 'Manage';
    
    if($do == "Manage") {
        
        $stmt = $con->prepare("Select * From contact ORDER BY Contact_ID DESC");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        
        if(!empty($rows)){         
?>

<div class="container">
    <div class="xx">
    <h1 class="text-center custom-h1"><i class="fa  fa-envelope"></i><?php echo lang('CONTACT_US_MESSAGES'); ?></h1>
    <div class="table-responsive">
        <div class="table-shadow">
        <table class="main-table text-center table table-bordered">
            <tr>
                <td><?php  echo lang('STATUS');?></td>
                <td><?php  echo lang('SENDER_NAME');?></td>
                <td><?php  echo lang('EMAIL');?></td>
                <td><?php  echo lang('SUBJECT');?></td>
                <td><?php  echo lang('CONTENT');?></td>
            </tr>
            <?php
        foreach($rows as $row){
            echo"<tr>";
                echo"<td class='contact-status'>";
                    if($row['Status'] == 0) {
                        echo '<a class="replay-now" href="?do=replay&contactid='.$row['Contact_ID'].'"><i class="fa fa-warning"></i>'. lang('REPLAY_NOW') .'</a>';
                    }else{
                        echo '<a class="replied" href="?do=replay&contactid='.$row['Contact_ID'].'"><i class="fa fa-check-square-o"></i>'. lang('REPLIED') .'</a>';
                    }
                echo"</td>";
                echo"<td>".$row['Name']."</td>";
                echo"<td>".$row['Email']."</td>";
                echo"<td>".$row['Subject']."</td>";
                echo"<td>".$row['Content']."</td>";
           echo"</tr>";   
        };
            ?>
        </table>
        </div>
    </div>
    </div>
</div>
    <?php 
          }//end if(!empty($rows))
           else { ?>
                <div class="container">
                <div class="xx">
                    <h1 class="text-center custom-h1"><i class="fa  fa-envelope"></i> Contact Us Messages </h1>
                    <div class="alert alert-success"> There's not records to show  </div>
                    </div>
               </div>
               
         <?php  }
    ?>
<?php        
    }//end if($do == "Manage")
    elseif($do == 'replay') {
        
        $Contact_ID = isset($_GET['contactid']) && is_numeric($_GET['contactid']) ? $_GET['contactid']:0;
        $stmt = $con->prepare("SELECT * FROM contact WHERE Contact_ID = ?");
        $stmt->execute(array($Contact_ID));
        $row = $stmt->fetch();
        
        
        $message = isset($_GET['message'])?$_GET['message']:'';
        if($stmt->rowCount() == 1){
           
        
?>
<div class="container">
    <div class="xx">
        <h1 class="text-center custom-h1"><i class="fa  fa-envelope"></i><?php echo lang('CONTACT_US_MESSAGES'); ?></h1>
        <?php if($message == 'Success'){ ?>
        
        <div class="alert alert-success"><?php echo lang('SEND_SUCCESS'); ?></div>
            
        <?php }elseif($message == 'Error'){ ?>
            
        <div class="alert alert-danger"><?php echo lang('SEND_ERROR'); ?></div>    
            
        <?php } ?>
        <div class="contact-container">
            <div class="row">
                <div class="col-sm-3 left"><?php echo lang('SENDER_NAME'); ?></div>
                <div class="col-sm-9 right"><?php echo $row['Name'] ?><hr /></div>
            </div>
            <div class="row">
                <div class="col-sm-3 left"><?php echo lang('EMAIL'); ?></div>
                <div class="col-sm-9 right"><?php echo $row['Email'] ?><hr /></div>
            </div>
            <div class="row">
                <div class="col-sm-3 left"><?php echo lang('SUBJECT'); ?></div>
                <div class="col-sm-9 right"><?php echo $row['Subject'] ?><hr /></div>
            </div>
            <div class="row">
                <div class="col-sm-3 left"><?php echo lang('CONTENT'); ?></div>
                <div class="col-sm-9 right"><?php echo $row['Content'] ?></div>
            </div>            
        </div>
        <div class="contact-container">
            <h3 class="text-center"><?php echo lang('ADMIN_REPLAY'); ?></h3>
            <?php if($row['Status'] == 0){ ?>
            <form action="?do=insert" method="post">
                <div class="form-group">
                    <textarea class="form-control" name="replay"></textarea>
                </div>
                <div class="form-group text-center">
                    <input type="hidden" name="contactid" value="<?php echo $row['Contact_ID'] ?>" />
                    <input type="hidden" name="name"      value="<?php echo $row['Name'] ?>" />
                    <input type="hidden" name="email"     value="<?php echo $row['Email'] ?>" />
                    <input type="hidden" name="subject"   value="<?php echo $row['Subject'] ?>" />
                    <input type="hidden" name="content"   value="<?php echo $row['Content'] ?>" />
                    <input type="submit" class="btn btn-primary " value="<?php echo lang('REPLAY'); ?>"/>                         
                </div>
            </form>   
            <?php }else{ echo "<hr /><p class='lead'>" . $row['Replay'] . "</p>"; } ?>
        </div>
    </div>
</div>  


<?php
        }else {
            header('location:contact.php');
            exit();
        }
        
    }//end elseif($do == 'replay') 
    elseif($do == 'insert') {
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $Replay     = nl2br($_POST['replay']);
            $altReplay  = $_POST['replay'];
            
            $Contact_ID = isset($_POST['contactid']) & is_numeric($_POST['contactid']) ? $_POST['contactid'] : 0; 
            $Name       = $_POST['name'];
            $Email      = $_POST['email'];
            $Subject    = $_POST['subject'];
            //$Content    = $_POST['content'];
            
            $mail = new PHPMailer(true);                    //passing 'true' enables exceptions
            
            try {

                //server setting

                $mail->SMTPDebug = 0;                       //enable verbose debug output
                $mail->isSMTP();                            //set mailer to use smtp
                $mail->Host = 'smtp.gmail.com';             //specify main and backup smtp servers
                $mail->SMTPAuth = true ;                    //enable smtp authentication
                $mail->Username = 'tartoushop@gmail.com';   //smtp username
                $mail->Password = 'ojrneuhxwgefdyjf';       //smtp password
                $mail->SMTPSecure = 'tls';                  //enable tls encryption, 'ssl' also accepted
                $mail->Port = 587;                          //tcp port to connect to

                //recipients

                $mail->setFrom('tartoushop@gmail.com', 'Tartous Shop');
                $mail->addAddress($Email, $Name);

                //content

                $mail->isHTML(true);
                $mail->Subject = $Subject;
                $mail->Body    = $Replay;
                $mail->AltBody = $altReplay;

                if($mail->send()){
                    
                    //echo 'Message has been sent';
                    $stmt= $con->prepare("UPDATE
                                        contact
                                    SET
                                        Replay = ?,
                                        Status = ?
                                    WHERE
                                        Contact_ID = ?");
                    $stmt->execute(array($Replay , 1,$Contact_ID));
                        
                    header('location:contact.php?do=replay&contactid='. $Contact_ID.'&message=Success');    
                    exit();
                    
                }



            } catch (Exception $e) {
                //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
                header('location:contact.php?do=replay&contactid='. $Contact_ID.'&message=Error');    
                exit();            
            }            
            
        } // end if($_SERVER['REQUEST_METHOD'] == 'POST')
        else {
            header('location:contact.php');
            exit();
        }        
        
    } // end elseif($do == 'insert')
    elseif($do == 'Edit') {
        
    }elseif($do == 'Update') {
        
    }elseif($do == 'Delete') {
        
    }
    
    include $tpl . 'footer.php';
    
} //end if (isset($_SESSION['username']))
else{
    header('location:index.php');
    exit();
}

ob_end_flush(); // release the output

?>