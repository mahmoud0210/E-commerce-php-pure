<?php
ob_start();
session_start();
$getTitle = "Contact";
include "init.php";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $name    = trim(filter_var($_POST['name'],FILTER_SANITIZE_STRING));
    $email   = trim(filter_var($_POST['email'],FILTER_SANITIZE_EMAIL));
    $subject = trim(filter_var($_POST['subject'],FILTER_SANITIZE_STRING));
    $content = trim(filter_var($_POST['content'],FILTER_SANITIZE_STRING));
    
    $formErrors = array();
    
    if(empty($name)){
        $formErrors[] = lang('CONTACT_ERROR_NAME');
    }
    if(empty($email)){
        $formErrors[] = lang('CONTACT_ERROR_EMAIL_EMPTY');
    }elseif(filter_var($email, FILTER_VALIDATE_EMAIL) != true){
        $formErrors[] = lang('CONTACT_ERROR_EMAIL_VALIDATE');
    }
    if(empty($subject)){
        $formErrors[] = lang('CONTACT_ERROR_SUBJECT');
    }
    if(empty($content)){
        $formErrors[] = lang('CONTACT_ERROR_CONTENT');
    }    
    
    if(empty($formErrors)){
        
        $stmt = $con->prepare("INSERT INTO 
                                    contact(Name,Email,Subject,Content)
                                    values(:zname, :zemail, :zsubject, :zcontent)");
        $stmt->execute(array(
            "zname" => $name,
            "zemail" => $email,
            "zsubject" => $subject,
            "zcontent" => $content
        ));
        
        $success = lang('CONTACT_SUCCESS');
    }
    
}
?>

<div class="contact">
    <div class="contact-container">
        <div class="col-md-6">
            <div class="contact-more-info">
                <span class="contact-header text-center"><?php echo lang('FOR_MORE_INFORMATION'); ?></span>
                <div class="more-info">
                    <i class="fa fa-phone"></i>
                    <p><?php echo lang('CONTACT_TELEPHONE'); ?></p>
                    +1 555 123456
                </div>
                <div class="more-info">
                    <i class="fa fa-envelope"></i>
                    <p><?php echo lang('CONTACT_EMAIL'); ?></p>
                    tartoushop@gmail.com
                </div>
                <div class="more-info">
                    <i class="fa fa-map-marker"></i>
                    <p><?php echo lang('CONTACT_LOCATION'); ?></p>
                    21 Hanano Street Tartous, Syria
                </div>             
            </div>           
        </div>
        <div class="col-md-6">
            <div class="contact-form">
                <span class="contact-header text-center"><?php echo lang('CONTACT_US_FORM'); ?></span>
                <form method="post" action="">
                    <input type="text" placeholder="<?php echo lang('CONTACT_US_FORM_NAME'); ?>" name="name" autocomplete="off"/>
                    <input type="email" placeholder="<?php echo lang('CONTACT_US_FORM_EMAIL'); ?>" name="email"/>
                    <input type="text" placeholder="<?php echo lang('CONTACT_US_FORM_SUBJECT'); ?>" name="subject" autocomplete="off"/>
                    <textarea placeholder="<?php echo lang('CONTACT_US_FORM_MESSAGE'); ?>" name="content" rows="7"></textarea>
                    <input type="submit" value="<?php echo lang('CONTACT_US_FORM_SEND'); ?>"/>
                </form>
                <?php if(isset($formErrors) && !empty($formErrors)) {
                        
                        foreach($formErrors as $error){
                ?>
                <div class="well well-sm aler alert-danger"><?php echo $error; ?></div>
                <?php
                        }                    
    
                        }elseif(isset($success)) {
                ?>
                <div class="well well-sm alert alert-success"><?php echo $success; ?></div>
                <?php
                        } ?>
            </div>
        </div>
    </div>
</div>

<?php
include $tpl . 'footer.php';
ob_end_flush();
?>
