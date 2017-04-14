<?php  
	if ($CurrentUser->logged_in()) { 
		include(PERCH_CORE . '/templates/layout/btm.php');
	} else {
		include(PERCH_CORE . '/templates/login/btm.php');
	}

    if (file_exists(PERCH_PATH.'/addons/plugins/ui/_config.inc')) {
        include PERCH_PATH.'/addons/plugins/ui/_config.inc';
    }    

    if (PERCH_DEBUG) {
        if (isset($mode)) PerchUtil::debug('Mode: '.$mode);
        PerchUtil::debug('Queries: '. PerchDB_MySQL::$queries);
        PerchUtil::debug('Memory: '. round(memory_get_peak_usage()/1024/1024, 4));
        PerchUtil::output_debug(); 
    }
?>
</body>
</html><?php
flush();