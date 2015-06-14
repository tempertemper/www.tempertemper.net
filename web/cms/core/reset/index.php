<?php
    $auth_page = true;
    $done = false;

    include('../inc/pre_config.php');
    include('../../config/config.php');
    include(PERCH_CORE . '/inc/loader.php');

    $Perch  = new Perch;
    include(PERCH_CORE . '/inc/auth.php');
    
    // Check for logout
    if ($CurrentUser->logged_in() && isset($_GET['logout']) && is_numeric($_GET['logout'])) {
        $CurrentUser->logout();
    }

    // If the user's logged in, send them to edit content
    if ($CurrentUser->logged_in()) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/apps/content/');
    }

    $Perch->page_title = PerchLang::get('Reset your password');
    
    
    if (isset($_POST['reset']) && $_POST['reset']=='1' && isset($_POST['email']) && $_POST['email']!='') {
        $email = $_POST['email'];
        if (PerchUtil::is_valid_email($email)) {
            $Users = new PerchUsers();
            $User = $Users->find_by_email($email);
            if (is_object($User)) {
                $User->reset_pwd_and_notify();
            }
        }
        $done = true;
    }
    include(PERCH_CORE . '/inc/top.php');
?>
   <div class="logincont <?php echo $Settings->get('headerScheme')->settingValue();?>">
    <div class="logo"><a href="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>">
              <?php
                $logo = $Settings->get('logoPath')->settingValue();
                if ($logo) {
                    echo '<img src="'.PerchUtil::html($logo).'" alt="" />';
                }else{
                    echo '<img src="'.PERCH_LOGINPATH.'/core/assets/img/logo.png" width="110" alt="Perch" />';
                }
            ?></a>
    </div>

    <div id="login">
        <div id="hd" class="topbar">
            <h1><?php echo PerchLang::get('Reset password'); ?></h1>
        </div>
        <div class="bd">
            
<?php
    if ($done) {
?>            
            <form action="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/reset/" method="post" class="reset">
                <p class="instructions"><?php echo PerchLang::get('Thank you. Now check your email for the new password.'); ?></p>
                <p class="instructions"><?php echo PerchLang::get('If you do not receive an email, look in your spam folder and also check that the email address you have used is the one we have for you.'); ?></p>
                <p class="instructions"><?php echo PerchLang::get('%sLog in%s or %stry again%s', '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'">', '</a>', '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/reset/">', '</a>'); ?></p>
            </form>
<?php
    }else{
?>
            <form action="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/reset/" method="post" class="reset">
                <p class="instructions"><?php echo PerchLang::get('If you have forgotten your password, enter your email address and a new password will be sent to you.'); ?></p>
                <div<?php if (isset($_POST['email']) && @$_POST['email']=='') echo ' class="error"'; ?>>
                    <label for="email"><?php echo PerchLang::get('Email'); ?></label>
                    <input type="email" name="email" value="<?php echo PerchUtil::html(@$_POST['email'],1); ?>" id="email" class="text" />
                    <?php if (isset($_POST['email']) && @$_POST['email']=='') echo '<span class="error">'.PerchLang::get('Required').'</span>'; ?>
                </div>

                <p class="submit">
                    <input type="submit" class="button" value="<?php echo PerchLang::get('Reset password'); ?>">
                    <input type="hidden" name="reset" value="1" />
                </p>
            </form>

<?php    
    }
?>
        </div>
        
    </div>


<?php
    include(PERCH_CORE . '/inc/btm.php');
?>