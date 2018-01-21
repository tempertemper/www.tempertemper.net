<?php
    if (!defined('PERCH_PATH')) exit;



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
            <h1><?php echo PerchLang::get('Log in'); ?></h1>
        </div>
        <div class="bd">
            <form action="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/" method="post" class="form-simple form-login" <?php if (PERCH_PARANOID) echo 'autocomplete="off"'; ?>>
                
                <?php
					if ($db_error) {
						echo '<div role="alert" class="notification notification-alert">' .PerchUI::icon('core/face-pain'). PerchLang::get('There\'s a problem with connecting to the database. Please check your settings.');
					}
					
                    if (isset($_POST['login']) && @$_POST['username']!='' && isset($_POST['password']) && $_POST['password']!='') {
                        if ($CurrentUser->activation_failed) {
                            echo '<div role="alert" class="notification notification-alert">' .PerchUI::icon('core/face-pain'). PerchLang::get('Sorry, your license key isn\'t valid for this domain.');
                        
                            if (strpos(PERCH_LICENSE_KEY, '-LOCAL-')==2) {
                                echo ' ';
                                echo PerchLang::get('This license key is for local testing only, so can only be used on a non-public domain.');
                            } else {
                                if (!$Settings->get('hideBranding')->settingValue()) {
                                    echo ' ';
                                    echo PerchLang::get('Log into your %sPerch account%s and add the following as your <em>live</em> or <em>testing</em> domain:', '<a href="https://grabaperch.com/account" class="notification-link">', '</a>');
                                    echo ' <code>'.PerchUtil::html($_SERVER['SERVER_NAME']).'</code>';
                                }
                            }
                            
                            echo '</div>';
                        }else{
                            echo '<div role="alert" class="notification notification-alert">' .PerchUI::icon('core/face-pain'). PerchLang::get('Sorry, those details are incorrect. Please try again.') . '</div>';
                        }
                    }
                ?>
                
                <?php
                    $login_attempt = false;
                    if (isset($_POST['login'])) {
                        $login_attempt = true;
                    }
                
                    $username = '';
                    if (isset($_POST['username'])) {
                        $username = $_POST['username'];
                    }
                    $password = '';
                    if (isset($_POST['password'])) {
                        $password = $_POST['password'];
                    }
                ?>

                <div class="field-wrap<?php if ($login_attempt && $username=='') echo ' input-error'; ?>">
                    <label for="username">
                        <?php echo PerchLang::get('Username'); ?>
                        <?php if ($login_attempt && $username=='') echo '<span class="required-value">('.PerchLang::get('required').')</span>'; ?>
                    </label>
                    <div class="form-entry">
                        <input type="text" name="username" required="required" aria-required="true" class="input-simple" value="<?php echo PerchUtil::html($username,1); ?>" autofocus <?php if (PERCH_PARANOID) echo 'autocomplete="off"'; ?>>
                    </div>
                </div>

                <div class="field-wrap<?php if ($login_attempt && $password=='') echo ' input-error'; ?>">
                    <label for="password">
                        <?php echo PerchLang::get('Password'); ?>
                        <?php if ($login_attempt && $password=='') echo '<span class="required-value">('.PerchLang::get('required').')</span>'; ?>
                    </label>
                    <div class="form-entry">
                        <input type="password" name="password" required="required" aria-required="true" class="input-simple" autocomplete="off">
                    </div>
                </div>

                <div class="buttons">
                    <input type="submit" class="button button-simple" value="<?php echo PerchLang::get('Log in'); ?>">
                    <?php
                        if (!$Settings->get('hide_pwd_reset')->val()) {
                    ?>
                    <a class="reset-link" href="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/reset"><?php echo PerchLang::get('Reset password'); ?></a>
                    <?php
                        } // hide pwd reset
                    ?>
                    <input type="hidden" name="login" value="1" />
                    <?php
                        if (isset($_GET['r']) && $_GET['r']!='') {
                            echo '<input type="hidden" name="r" value="'.PerchUtil::html(base64_encode($_GET['r']), true).'" />';
                        }

                        if (isset($_POST['r']) && $_POST['r']!='') {
                            echo '<input type="hidden" name="r" value="'.PerchUtil::html($_POST['r'], true).'" />';
                        }
                    ?>
                </div>

            </form>
        </div>
    </div>
