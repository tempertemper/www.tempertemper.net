<?php
    
    echo $HTML->title_panel([
        'heading' => $Lang->get('Viewing diagnostic information'),
        ]);


    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);
    $Smartbar->add_item([
        'active'   => false,
        'title'    => 'Basic',
        'link'     => '/core/settings/diagnostics/',
        'icon'     => 'core/info-alt',
    ]);
    $Smartbar->add_item([
        'active'   => true,
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

    $DB = PerchDB::fetch();

    $messages = array();

?>

    <div class="inner">
		<h3><?php echo PerchLang::get('Perch information'); ?></h3>
        <ul class="diagnostics-list">
            <li><?php 
                echo (PERCH_RUNWAY ? 'Perch Runway' : 'Perch');
                if (strpos(PERCH_LICENSE_KEY, '-LOCAL-')==2) echo ' LTM';
                echo ': ';
                echo PerchUtil::html($Perch->version); ?></li>
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
                            $sql = 'SELECT COUNT(*) FROM '.$val;
                            $count = $DB->get_count($sql);
                            $tables[] =  PerchUtil::html($val . ' ('.$count.')');
                        }
                    }
                    echo '<li>DB tables: '.implode(', ', $tables).'</li>';
                }
            ?>
			<li>Users: <?php echo PerchDB::fetch()->get_value('SELECT COUNT(*) FROM '.PERCH_DB_PREFIX.'users'); ?></li>        
            <li>App runtimes: <pre><?php
                $file = PerchUtil::file_path(PERCH_PATH.'/config/apps.php');
                echo PerchUtil::html(trim(file_get_contents($file)));
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
            <li>F1: <?php echo md5(file_get_contents(PerchUtil::file_path(PERCH_CORE.'/lib/PerchAuthenticatedUser.class.php'))); ?>
            </li>
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
                $ignore = array('PERCH_LICENSE_KEY', 'PERCH_DB_PASSWORD', 'PERCH_EMAIL_PASSWORD');
                if (PerchUtil::count($constants['user'])) {
                    foreach($constants['user'] as $key=>$val) {
                        if (!in_array($key, $ignore) && substr($key, 0, 5)=='PERCH') echo '<li>'.PerchUtil::html($key.': '.$val).'</li>';
                    }
                }
                
            ?>
		</ul>
		<h3><?php echo PerchLang::get('Hosting settings'); ?></h3>
		<ul class="diagnostics-list">
            <li>PHP: <?php echo PerchUtil::html(phpversion()); ?></li>
            <li>Zend: <?php echo PerchUtil::html(zend_version()); ?></li>
            <li>OS: <?php echo PerchUtil::html(PHP_OS); ?></li>
            <li>SAPI: <?php echo PerchUtil::html(PHP_SAPI); ?></li>
            <li>Safe mode: <?php echo (ini_get('safe_mode') ? 'detected' : 'not detected'); ?></li>
            <li>MySQL client: <?php echo PerchUtil::html($DB->get_client_info()); ?></li>
            <li>MySQL server: <?php echo PerchUtil::html($DB->get_server_info()); ?></li>
            <li>Free disk space: 
            <?php
                $bytes     = disk_free_space("."); 
                $si_prefix = array( 'B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB' );
                $base      = 1024;
                $class     = min((int)log($bytes , $base) , count($si_prefix) - 1);
                echo sprintf('%1.2f' , $bytes / pow($base,$class)) . ' ' . $si_prefix[$class];
            ?>
            </li>
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
