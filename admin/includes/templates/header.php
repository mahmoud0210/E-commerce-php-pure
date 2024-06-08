<!DOCTYPE html>
<html dir="<?php echo isset($_COOKIE['lang']) && $_COOKIE['lang'] == 'arabic' ? 'rtl':ltr; ?>">
    <head>
        <meta charset="UTF-8" />
        <title><?php getTitle() ?></title>
        <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css" />
        <link rel="stylesheet" href="<?php echo $css; ?>font-awesome.min.css" />
        <link rel="stylesheet" href="<?php echo $css; ?>jquery-ui.css" />
        <link rel="stylesheet" href="<?php echo $css; ?>jquery.selectBoxIt.css" />
        <link rel="stylesheet" href="<?php echo $css; ?>bootstrap-tagsinput.css" />
        <link rel="stylesheet" href="<?php echo $css; ?>backend.css" />
        <?php if(isset($_COOKIE['lang']) && $_COOKIE['lang'] == 'arabic' ){ ?>
        <link rel="stylesheet" href="<?php echo $css; ?>right-backend.css" />
        <?php } ?>
        
    </head>
    <body>
