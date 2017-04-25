<?php 

    echo $HTML->title_panel([
        'heading' => $Lang->get('Viewing diagnostic information'),
        ]);


    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);
    $Smartbar->add_item([
        'active'   => true,
        'title'    => 'Basic',
        'link'     => '/core/settings/diagnostics/',
        'icon'     => 'core/info-alt',
    ]);
    $Smartbar->add_item([
        'active'   => false,
        'title'    => 'Extended',
        'link'     => '/core/settings/diagnostics/?extended',
        'icon'     => 'core/gear',
    ]);
    $Smartbar->add_item([
        'active'   => false,
        'title'    => 'Add-ons',
        'link'     => '/core/settings/diagnostics/add-ons/',
        'icon'     => 'blocks/pencil-paintbrush-pen',
    ]);
    $Smartbar->add_item([
        'active'   => false,
        'title'    => 'Update',
        'link'     => '/core/settings/update/',
        'icon'     => 'ext/o-sync',
        'position' => 'end',
    ]);
    echo $Smartbar->render();


 
    $max_upload   = PerchUtil::shorthand_to_megabytes(ini_get('upload_max_filesize'));
    $max_post     = PerchUtil::shorthand_to_megabytes(ini_get('post_max_size'));
    $memory_limit = PerchUtil::shorthand_to_megabytes(ini_get('memory_limit'));

    $upload_mb    = min($max_upload, $max_post, $memory_limit);
?>

    <div class="inner">
        <h3><?php echo PerchLang::get('Health check'); ?></h3>
        <ul class="progress-list">
            <?php 
                $product = 'Perch';
                if (PERCH_RUNWAY) $product = 'Perch Runway';

                $DB = PerchDB::fetch();

                $messages = array();

                if (file_exists(PerchUtil::file_path(PERCH_PATH.'/setup'))) {
                    $messages[] = array('type'=>'alert', 'text'=>PerchLang::get('%sSetup folder is present and should be deleted%s', '', ''));
                }

                $newest_perch = $Settings->get('on_sale_version')->val();

                if ($newest_perch) {

                    if (version_compare($newest_perch, $Perch->version, '>')) {
                        $messages[] = array('type'=>'warning', 'text'=>PerchLang::get('%sPerch is out of date.%s You are running %s and the latest is %s. %sUpdate instructions%s', '', '', $product.' '.$Perch->version, $newest_perch, '<a href="http://grabaperch.com/update/" class="action">', '</a>'));
                    }

                    if (version_compare($newest_perch, $Perch->version, '<')) {
                        $messages[] = array('type'=>'success', 'text'=>PerchLang::get('%sAhead of the curve!%s You are running a pre-release version of %s.', '', '', $product));
                    }

                    if (version_compare($newest_perch, $Perch->version, '=')) {
                        $messages[] = array('type'=>'success', 'text'=>PerchLang::get('%s%s is up to date%s', '', $product, ''));
                    }
                }

                if (version_compare(PHP_VERSION, '5.4', '<')) {
                    $messages[] = array('type'=>'warning', 'text'=>PerchLang::get('%sPHP %s is very out of date.%s %sMore info%s', '', PHP_VERSION, '', '<a href="http://docs.grabaperch.com/docs/installing-perch/php" class="action">', '</a>'));
                }else if (version_compare(PHP_VERSION, '5.5', '<')){
                    $messages[] = array('type'=>'success', 'text'=>PerchLang::get('%sPHP %s version is okay, but a little out of date.%s Consider updating soon.', '', PHP_VERSION, ''));
                }else{
                    $messages[] = array('type'=>'success', 'text'=>PerchLang::get('%sPHP %s is up to date%s', '', PHP_VERSION, ''));
                }


                $mysql_version = $DB->get_server_info();

                if (version_compare($mysql_version, '5.0', '<')) {
                    $messages[] = array('type'=>'warning', 'text'=>PerchLang::get('%sMySQL %s is out of date.%s Please upgrade to at least 5.0', '', $mysql_version, ''));
                }else{
                    $messages[] = array('type'=>'success', 'text'=>PerchLang::get('%sMySQL %s is up to date%s', '', $mysql_version, ''));
                }

                $gd = extension_loaded('gd');
                $im = extension_loaded('imagick');

                if ($gd || $im) {
                    $messages[] = array('type'=>'success', 'text'=>PerchLang::get('%sImage processing available%s', '', ''));
                }else{
                    $messages[] = array('type'=>'warning', 'text'=>PerchLang::get('%sNo image processing library.%s Consider installing GD or ImageMagick for resizing images.', '', ''));
                }

                if (!function_exists('json_encode')) {
                    $messages[] = array('type'=>'warning', 'text'=>PerchLang::get('%sNo native JSON library.%s Consider installing the PHP JSON library if possible.', '', ''));
                }

                $DefaultBucket = PerchResourceBuckets::get();

                if (!$DefaultBucket->ready_to_write()) {
                    $messages[] = array('type'=>'alert', 'text'=>PerchLang::get('%sResources folder is not writable%s', '', ''));
                }

                if ($max_upload<8) {
                    $messages[] = array('type'=>'warning', 'text'=>PerchLang::get('%sFile upload size is low.%s You can only upload files up to %sM.', '', '', $max_upload));
                }

                if ($memory_limit<64) {
                    $messages[] = array('type'=>'warning', 'text'=>PerchLang::get('%sMemory limit is low.%s Memory use is limited to %sM, which could cause problems manipulating large images.', '', '', $memory_limit));
                }

                if (PerchUtil::find_executable_files_in_resources()) {
                    $messages[] = array('type'=>'alert', 'text'=>PerchLang::get('%sThere are PHP files in your resources folder.%s These could be dangerous and a sign of a security breach.', '', ''));   
                }
                


                if (PERCH_RUNWAY) {

                    $env_config = PerchConfig::get('env');

                    if (!isset($env_config['temp_folder']) || !is_writable($env_config['temp_folder'])) {
                        $messages[] = array('type'=>'warning', 'text'=>PerchLang::get('%sTemp folder is not writable.%s Check the path to your temp folder in your %srunway.php%s file and check permissions are set for PHP to write to it.', '', '','<code>', '</code>'));
                        $Alert->set('notice', PerchLang::get('Your backup temp folder is not set, or is not writable.'));
                        $errors = true;
                    }

                }

                if (strpos(PERCH_LICENSE_KEY, '-LOCAL-')>2) $product .= ' LTM';

                foreach($messages as $message) {
                    echo '<li class="progress-item progress-'.$message['type'].'">';
                    switch($message['type']) {
                        case 'success':
                            echo PerchUI::icon('core/circle-check');
                            break;
                        case 'alert':
                            echo PerchUI::icon('core/circle-delete');
                            break;
                        case 'warning':
                            echo PerchUI::icon('core/alert');
                            break;
                    }
                    echo ' '.$message['text'].'</li>';
                }
            ?>
        </ul>

		<h3><?php echo PerchLang::get('Summary information'); ?></h3>
        <ul class="diagnostics-list">
            <li><?php  echo $product.': ';
                $vitals = array();

                $vitals[] = $Perch->version; 
                $vitals[] = 'PHP: '.   PerchUtil::html(phpversion());
                $vitals[] = 'MySQL: '. PerchUtil::html($DB->get_client_info());
                $vitals[] = 'with '.   PerchUtil::html(PerchDB::$driver);

                echo implode(', ', $vitals);
            ?></li>
            <li>Server OS: <?php 
                echo PerchUtil::html(PHP_OS); 
                echo ', '.PerchUtil::html(PHP_SAPI); 
            ?></li>
            <?php
                $apps_list = $Perch->get_apps();
                $apps = array();
                echo '<li>Installed apps: ';
                if (PerchUtil::count($apps_list)) {
                    foreach($apps_list as $app) {
                        $apps[] = PerchUtil::html($app['id'].($app['version'] ? ' ('.$app['version'].')':''));
                    }
                    echo implode(', ', $apps);
                }else{
                    echo 'none.';
                }
                echo '</li>';          
            ?>
            <li>App runtimes: <?php
                $file = PerchUtil::file_path(PERCH_PATH.'/config/apps.php');
                echo PerchUtil::html(file_get_contents($file));
            ?></li>
			<?php
                $constants = get_defined_constants(true);
                $include = array('PERCH_LOGINPATH', 'PERCH_PATH', 'PERCH_CORE', 'PERCH_RESFILEPATH');
                if (PerchUtil::count($constants['user'])) {
                    foreach($constants['user'] as $key=>$val) {
                        if (in_array($key, $include)) echo '<li>'.PerchUtil::html($key.': '.$val).'</li>';
                    }
                }
            ?>
            <li>Image manipulation: <?php echo PerchUtil::html((extension_loaded('gd')? 'GD ' : '')); ?>
            <?php 
                if (version_compare(PHP_VERSION, '5.5.9')===0 || version_compare(PHP_VERSION, '5.5.10')===0) {
                    echo ' (image sharpening disabled, <a href="https://bugs.php.net/bug.php?id=66714">#66714</a>)';
                }

                echo PerchUtil::html((extension_loaded('imagick')? 'Imagick ' : ''));
            ?>
            </li>
            <li>PHP limits: 
                Max upload <?php echo $max_upload; ?>M, 
                Max POST <?php echo $max_post; ?>M,
                Memory: <?php echo $memory_limit; ?>M,
                Total max file upload: <?php echo $upload_mb; ?>M</li>
            <li>F1: <?php echo md5(file_get_contents(PerchUtil::file_path(PERCH_CORE.'/lib/PerchAuthenticatedUser.class.php'))); ?></li>
            <li>Resource folder writeable: <?php echo is_writable(PERCH_RESFILEPATH)?'Yes':'No'; ?></li>
            <?php
                $constants = $_SERVER;
                $include = array('HTTP_HOST', 'DOCUMENT_ROOT', 'REQUEST_URI', 'SCRIPT_NAME');
                if (PerchUtil::count($constants)) {
                    foreach($constants as $key=>$val) {
                        if (in_array($key, $include)) echo '<li>'.PerchUtil::html($key.': '.$val).'</li>';
                    }
                }
            ?>
    	</ul>
        
    </div>