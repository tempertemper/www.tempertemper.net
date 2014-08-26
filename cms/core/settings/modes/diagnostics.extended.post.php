<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
    
    <p><?php echo PerchLang::get('When raising a support ticket you should copy and paste the information on this page.'); ?></p>
   
    <h3><p><?php echo PerchLang::get('Understanding this report'); ?></p></h3>
    
    <p><?php echo PerchLang::get('The Diagnostics Report gives you useful advice about your set-up and also your hosting environment.'); ?></p>
    
    <p><?php echo PerchLang::get('The Health Check gives you a quick overview of the state of critical items like software versions and hosting configuration.'); ?></p>
    
    <p><?php echo PerchLang::get('Settings listed under Perch Information are part of Perch and generally things you can change.'); ?></p>
    
    <p><?php echo PerchLang::get('Settings listed under Hosting Settings are part of your hosting environment. Making a change to any of these - for example increasing the maximum allowable file upload size - is something that you would need to ask your hosting company about.'); ?></p>

<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>
    
    <h1><?php echo PerchLang::get('Viewing Diagnostic Information'); ?></h1>
	


    <?php echo $Alert->output(); ?>
    
    <h2><?php echo PerchLang::get('Diagnostics report'); ?></h2>
    
    <?php
        $max_upload   = (int)(ini_get('upload_max_filesize'));
        $max_post     = (int)(ini_get('post_max_size'));
        $memory_limit = (int)(ini_get('memory_limit'));
        $upload_mb    = min($max_upload, $max_post, $memory_limit);

        $DB = PerchDB::fetch();

        $messages = array();

    ?>

    <div class="info">
		<h3><?php echo PerchLang::get('Perch information'); ?></h3>
        <ul>
            <li>Perch: <?php echo PerchUtil::html($Perch->version); ?></li>
            <li>Production mode: <?php 
                switch(PERCH_PRODUCTION_MODE) {
                    case PERCH_DEVELOPMENT:
                        echo 'Development';
                        break;
                    case PERCH_STAGING:
                        echo 'Staging';
                        break;
                    case PERCH_PRODUCTION:
                        echo 'Production';
                        break;
                }
                echo ' ('.PERCH_PRODUCTION_MODE.')';
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
			<?php
                
                echo '<li>DB driver: '.PerchDB::$driver.'</li>';
                $sql = 'SHOW TABLES';
                $rows = $DB->get_rows($sql);
                if (PerchUtil::count($rows)) {
                    $tables = array();
                    
                    foreach($rows as $row) {
                        foreach($row as $key=>$val) {
                            $tables[] =  PerchUtil::html($val);
                        }
                    }
                    echo '<li>DB tables: '.implode(', ', $tables).'</li>';
                }
            ?>
			<li>Users: <?php echo PerchDB::fetch()->get_value('SELECT COUNT(*) FROM '.PERCH_DB_PREFIX.'users'); ?></li>        
            <li>App runtimes: <pre><?php
                $file = PerchUtil::file_path(PERCH_PATH.'/config/apps.php');
                echo PerchUtil::html(file_get_contents($file));
            ?></pre></li>
            <?php
                $ScheduledTasks = new PerchScheduledTasks;
                $apps = $ScheduledTasks->get_scheduled();
                if (PerchUtil::count($apps)) {
                    foreach($apps as $appID=>$tasks) {
                        $task_list = array();
                        echo '<li>Scheduled tasks for '.$appID.': ';
                            foreach($tasks as $task) {
                                //print_r($task);
                                $task_list[] = $task['taskKey'].' ('.($task['frequency']/60).' mins)';
                            }
                            echo implode(', ', $task_list);
                        echo '</li>';
                    }
                    
                }
                
            
            ?>
            <?php
                echo '<li>Editor plug-ins: '.implode(', ', PerchUtil::get_dir_contents(PerchUtil::file_path(PERCH_PATH.'/addons/plugins/editors/', true))).'</li>';

            ?>
            <li>H1: <?php echo PerchUtil::html(md5($_SERVER['SERVER_NAME'])); ?></li>
            <li>L1: <?php echo PerchUtil::html(md5(PERCH_LICENSE_KEY)); ?></li>
            
            <?php
                $settings = $Settings->get_as_array();
                if (PerchUtil::count($settings)) {
                    foreach($settings as $key=>$val) {
                        echo '<li>'.PerchUtil::html($key.': '.$val).'</li>';
                    }
                }
            
            ?>
			<?php
                $constants = get_defined_constants(true);
                $ignore = array('PERCH_LICENSE_KEY', 'PERCH_DB_PASSWORD');
                if (PerchUtil::count($constants['user'])) {
                    foreach($constants['user'] as $key=>$val) {
                        if (!in_array($key, $ignore) && substr($key, 0, 5)=='PERCH') echo '<li>'.PerchUtil::html($key.': '.$val).'</li>';
                    }
                }
            ?>
		</ul>
		<h3><?php echo PerchLang::get('Hosting settings'); ?></h3>
		<ul>
            <li>PHP: <?php echo PerchUtil::html(phpversion()); ?></li>
            <li>Zend: <?php echo PerchUtil::html(zend_version()); ?></li>
            <li>OS: <?php echo PerchUtil::html(PHP_OS); ?></li>
            <li>SAPI: <?php echo PerchUtil::html(PHP_SAPI); ?></li>
            <li>Safe mode: <?php echo (ini_get('safe_mode') ? 'detected' : 'not detected'); ?></li>
            <li>MySQL client: <?php echo PerchUtil::html($DB->get_client_info()); ?></li>
            <li>MySQL server: <?php echo PerchUtil::html($DB->get_server_info()); ?></li>
            <li>Extensions: <?php echo PerchUtil::html(implode(', ', get_loaded_extensions())); ?></li>
            <li class="section">GD: <?php echo PerchUtil::html((extension_loaded('gd')? 'Yes' : 'No')); ?>
            <?php 
                if (version_compare(PHP_VERSION, '5.5.9')===0 || version_compare(PHP_VERSION, '5.5.10')===0) {
                    echo ' (image sharpening disabled, <a href="https://bugs.php.net/bug.php?id=66714">#66714</a>)';
                }
            ?></li>
            <li>ImageMagick: <?php echo PerchUtil::html((extension_loaded('imagick')? 'Yes' : 'No')); ?></li>
            <li>PHP max upload size: <?php echo $max_upload; ?>M</li>
            <li>PHP max form post size: <?php echo $max_post; ?>M</li>
            <li>PHP memory limit: <?php echo $memory_limit; ?>M</li>
            <li>Total max uploadable file size: <?php echo $upload_mb; ?>M</li>
            <li>Resource folder writeable: <?php echo is_writable(PERCH_RESFILEPATH)?'Yes':'No'; ?></li>
            
            <li class="section">Session timeout: <?php echo ini_get('session.gc_maxlifetime')/60; ?> minutes</li>
            <li>Native JSON: <?php echo function_exists('json_encode')?'Yes':'No'; ?></li>
            <li>Filter functions: <?php echo function_exists('filter_var')?'Yes':'No (Required for form field type validation)'; ?></li>
            <li>Transliteration functions: <?php echo function_exists('transliterator_transliterate')?'Yes':'No'; ?></li>
            
            <?php
                $first = true;
                foreach($_SERVER as $key=>$val) {
                    if ($key && $val){
                        if (!is_array($val))
                        echo '<li'.($first?' class="section"':'').'>' . PerchUtil::html($key) . ': ' . PerchUtil::html($val).'</li>';
                            $first = false;
                    }
                    
                }
            ?>       
        </ul>
        
    </div>
    

<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>