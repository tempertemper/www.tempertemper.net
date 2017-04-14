<?php
    $auth_page = true;
    $done      = false;
    $mode      = 'request_link';
    $error     = false;

    $new_user_mode = false;

    if (isset($_GET['new'])) $new_user_mode = true;

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
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/');
    }

    $Perch->page_title = ($new_user_mode ? PerchLang::get('Create your password') : PerchLang::get('Reset your password'));

    if (PerchUtil::get('token')) {
        $mode = 'enter_token';

        $Users = new PerchUsers();
        $User = $Users->get_by_password_recovery_token(PerchUtil::get('token'));
        if (!$User) {
            $mode = 'token_expired';
        }else{

            if (PerchUtil::post('username') && PerchUtil::post('new_password')) {
                if (PerchUtil::post('username')==$User->userUsername()) {
                    PerchUtil::debug('Username matches');

                    if (PerchUtil::post('new_password') == PerchUtil::post('new_password2')) {

                        if ($User->password_meets_requirements(PerchUtil::post('new_password'))) {
                            $User->set_new_password(PerchUtil::post('new_password'));
                            $mode = 'password_set';
                        }else{
                            PerchUtil::debug($User->msg, 'notice');
                            $error = 'weak_password';
                        }
                        
                    }else {
                        $error = 'non_matching_passwords';
                    }
                }else{
                    $error = 'non_matching_username';
                }
            }
        }

    }


    if (isset($_POST['reset']) && $_POST['reset']=='1' && isset($_POST['email']) && $_POST['email']!='') {
        $email = $_POST['email'];
        if (PerchUtil::is_valid_email($email)) {
            $Users = new PerchUsers();
            $User = $Users->find_by_email($email);
            if (is_object($User)) {
                $User->send_password_recovery_link();
            }
        }
        $done = true;
    }
    include(PERCH_CORE . '/templates/login/top.php');
?>
   <div class="logincont <?php echo $Settings->get('headerScheme')->settingValue();?>">
    <div class="logo">
        <?php
            $logo = $Settings->get('logoPath')->settingValue();
            if ($logo) {
                echo '<img class="customer-logo" src="'.PerchUtil::html($logo).'" alt="" />';
            }else{
                if (PERCH_RUNWAY) {
                    echo '<img class="customer-logo" src="'.PERCH_LOGINPATH.'/core/runway/assets/img/logo.png" width="180" alt="Perch Runway">';
                }else{
                    echo '<img class="customer-logo" src="'.PERCH_LOGINPATH.'/core/assets/img/logo.png" width="110" class="logo" alt="Perch">';
                }
            }
        ?>
    </div>

    <div class="login-box">
        <div class="hd topbar <?php echo $topbar_class; ?>">
            <h1><?php echo ($new_user_mode ? PerchLang::get('Create password') : PerchLang::get('Reset password')); ?></h1>
        </div>
        <div class="bd">

<?php
    if ($mode == 'request_link') {

        if ($done) {
?>
            <form action="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/reset/" method="post" class="reset form-simple form-login">
                <p class="login-notes"><?php echo PerchLang::get('Thank you. Now check your email for the link.');
                     echo ' '.PerchLang::get('If you do not receive an email, look in your spam folder and also check that the email address you have used is the one we have for you.'); ?></p>
                <div class="buttons">
                    <?php echo PerchLang::get('%sLog in%s %sTry again%s', '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'" class="button button-simple">', '</a>', '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/reset/">', '</a>'); ?>
                    
                </div>
            </form>
<?php
        }else{
?>
            <form action="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/reset/<?php if ($new_user_mode) echo '?new=1'; ?>" method="post" class="reset form-simple form-login">
                <p class="login-notes"><?php 
                if ($new_user_mode) {
                    echo PerchLang::get('In order to set up your new account, enter your email address and you will be sent a link.'); 
                }else{
                    echo PerchLang::get('If you have forgotten your password, enter your email address and you will be sent a recovery link.'); 
                }

                ?></p>
                <div class="field-wrap<?php if (isset($_POST['email']) && @$_POST['email']=='') echo ' input-error'; ?>">
                    <label for="email"><?php echo PerchLang::get('Email'); ?></label>
                    <div class="form-entry">
                    <input required type="email" name="email" value="<?php echo PerchUtil::html((isset($_POST['email']) ? $_POST['email'] : ''),1); ?>" id="email" class="input-simple" <?php echo (PERCH_PARANOID ? 'autocomplete="new-password"' : ''); ?>/>
                    <?php if (isset($_POST['email']) && @$_POST['email']=='') echo '<span class="error">'.PerchLang::get('Required').'</span>'; ?>
                    </div>
                </div>

                <div class="buttons">
                    <input type="submit" class="button button-simple" value="<?php echo ($new_user_mode ? PerchLang::get('Request link') : PerchLang::get('Reset password')); ?>">
                    <input type="hidden" name="reset" value="1" />
                </div>
            </form>

<?php
        }
    } // request link

    if ($mode == 'enter_token') {
?>
        <form action="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/reset/?token=<?php echo PerchUtil::html(PerchUtil::get('token'), true); ?>" method="post" class="reset recover form-simple form-login">
            <p class="login-notes"><?php echo PerchLang::get('Please confirm your username and then choose a new password.'); ?></p>

            <?php if ($error && $error=='non_matching_username') { ?>
                <p class="notification notification-alert"><?php echo PerchLang::get('Sorry, that username is not correct.'); ?></p>
            <?php } ?>

            <?php if ($error && $error=='non_matching_passwords') { ?>
                <p class="notification notification-alert"><?php echo PerchLang::get('Sorry, your new passwords did not match. Try typing them again.'); ?></p>
            <?php } ?>

            <?php if ($error && $error=='weak_password') { ?>
                <p class="notification notification-alert"><?php echo PerchLang::get('Sorry, your new password has been used before or is too simple.'); ?></p>
            <?php } ?>


            <div class="field-wrap <?php if (isset($_POST['username']) && @$_POST['username']=='') echo ' input-error'; ?>">
                <label for="username"><?php echo PerchLang::get('Username'); ?></label>
                <div class="form-entry">
                <input required type="username" name="username" value="<?php echo PerchUtil::html((isset($_POST['username']) ? $_POST['username'] : ''),1); ?>" id="username" class="input-simple" />
                <?php if (isset($_POST['username']) && @$_POST['username']=='') echo '<span class="error">'.PerchLang::get('Required').'</span>'; ?>
                </div>
            </div>

            <div class="field-wrap <?php if (isset($_POST['new_password']) && @$_POST['new_password']=='') echo ' input-error'; ?>">
                <label for="new_password"><?php echo PerchLang::get('New password'); ?></label>
                <div class="form-entry">
                <input required type="password" autocomplete="off" name="new_password" value="" id="new_password" class="input-simple" />
                <?php if (isset($_POST['password']) && @$_POST['password']=='') echo '<span class="error">'.PerchLang::get('Required').'</span>'; ?>
                </div>
            </div>

            <div class="field-wrap <?php 
                        if ((isset($_POST['new_password2']) && @$_POST['new_password2']=='') 
                        || isset($_POST['new_password']) && @$_POST['new_password2']!=@$_POST['new_password']) echo ' input-error'; ?>">
                <label for="new_password2"><?php echo PerchLang::get('Repeat new password'); ?></label>
                <div class="form-entry">
                <input required type="password" autocomplete="off" name="new_password2" value="" id="new_password2" class="input-simple" />
                <?php if (isset($_POST['password']) && @$_POST['password']=='') echo '<span class="error">'.PerchLang::get('Required').'</span>'; ?>
                </div>
            </div>

            <div class="buttons">
                <input type="submit" class="button button-simple" value="<?php echo ($new_user_mode ? PerchLang::get('Create password') : PerchLang::get('Reset password')); ?>">
                <input type="hidden" name="reset" value="1" />
            </div>
        </form>
<?php
    } // enter_token

    if ($mode == 'token_expired') {
?>
            <form action="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/reset/" method="post" class="reset form-simple form-login">
            <?php if ($new_user_mode) { ?>
                <p class="login-notes"><?php echo PerchLang::get('Sorry, that account link has expired. For security reasons, you need to %srequest a new link%s', '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/reset/?new=1">', '</a>'); ?></p>
            <?php } else { ?>
                <div role="alert" class="notification notification-alert"><?php echo PerchUI::icon('core/face-pain').PerchLang::get('Sorry, that recovery link has expired'); ?></div>

                <p class="login-notes"><?php echo PerchLang::get('For security reasons, you need to %srequest a new link%s', '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/reset/" class="notification-link">', '</a>'); ?></p>
                
            <?php } ?>
            </form>
<?php
    } // token_expired


    if ($mode == 'password_set') {
?>
            <form action="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/" method="post" class="reset form-simple form-login">
                <div class="notification notification-success"><?php echo PerchLang::get('Thank you. Your new password has been set and you can now log in using it.'); ?></div>
                <div class="buttons"><?php echo PerchLang::get('%sLog in%s', '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'" class="button button-simple">', '</a>'); ?></div>
            </form>
<?php
    } // password_set
?>

        </div>

    </div>


<?php
    include(PERCH_CORE . '/templates/login/btm.php');
