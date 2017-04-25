<?php
    $auth_page = true;
    include('../inc/pre_config.php');
    include('../../config/config.php');
    include(PERCH_CORE . '/inc/loader.php');
    $Perch  = new Perch;
    $Perch->page_title = 'Database connection error'; 
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link media="all" rel="stylesheet" href="<?php echo PerchUtil::html(PERCH_LOGINPATH, true); ?>/core/assets/css/styles.css">
    <!--[if IE]><link rel="stylesheet" href="<?php echo PerchUtil::html(PERCH_LOGINPATH, true); ?>/core/assets/css/ie9.css"><![endif]-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php 
        echo PerchUtil::html($Perch->page_title);      
    ?></title>
</head>
<body>
<div class="page-setup">

    <div class="setup-box">
        <div class="logo">
            <?php
               if (PERCH_RUNWAY) {
                    echo '<img src="'.PerchUtil::html(PERCH_LOGINPATH, true).'/core/runway/assets/img/logo.png" alt="Perch" width="110">';
                } else {
                    echo '<img src="'.PerchUtil::html(PERCH_LOGINPATH, true).'/core/assets/img/logo.png" alt="Perch" width="110">';
                }
            ?>
        </div>

        <div class="bd">     

            <div role="alert" class="notification-block notification-alert">
                <h2 class="notification-heading">
                    <?php echo PerchUI::icon('core/face-pain'); ?> Error: Could not connect to the database
                </h2>
                <p>Please check that the access details specified in <code><?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/config/config.php</code> file are correct.</p>
            </div>

            <p><a href="<?php echo PerchUtil::html(PERCH_LOGINPATH, true); ?>" class="button button-simple">Try again</a></p>
        
            
        </div>

    <div class="ft">
        
    </div>
</div>

</div>

</body>
</html>