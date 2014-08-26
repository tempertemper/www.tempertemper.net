<?php
    $auth_page = true;
    include('../inc/pre_config.php');
    include('../../config/config.php');
    include(PERCH_CORE . '/inc/loader.php');
    $Perch  = new Perch;
    $Perch->page_title = 'Database connection error'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title><?php 
        echo PerchUtil::html($Perch->page_title);      
    ?></title>
    <link rel="stylesheet" href="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/assets/css/login.css?v=<?php echo PerchUtil::html($Perch->version); ?>" type="text/css" />   
</head>
<body class="login">
    <div class="logincont light">
        <div class="logo">
            <?php
                echo '<img src="'.PERCH_LOGINPATH.'/core/assets/img/logo.png" width="110" class="logo" alt="Perch" />';
            ?>
        </div>

        <div id="login">
            <div id="hd" class="topbar">
                <h1>Error</h1>
            </div>
            <div class="bd">
                <form class="error">
                    <p class="error alert alert-failure">Perch could not connect to the database</p>

                    <p>Please check that the access details specified in <code>config.php</code> are correct.</p>

                    <p><a href="<?php echo PERCH_LOGINPATH; ?>">Try again</a></p>
                </form>
            </div>
        </div>

        <div class="footer">        
            <div class="credit">
                    <p><a href="http://grabaperch.com"><img src="/perch/core/assets/img/perch.png" width="35" height="12" alt="Perch" /></a>
            by <a href="http://edgeofmyseat.com">edgeofmyseat.com</a></p>
            </div>
        </div>
    </div>
</body>
</html>