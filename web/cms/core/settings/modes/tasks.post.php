<?php 

    echo $HTML->title_panel([
        'heading' => $Lang->get('Viewing scheduled tasks'),
        ]);

    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    $Smartbar->add_item([
        'active' => true,
        'title'  => 'Tasks',
        'link'   => '/core/settings/tasks/',
        'icon'   => 'core/clock',
    ]);

    echo $Smartbar->render();

	if (PerchUtil::count($tasks)) {


        $Listing = new PerchAdminListing($CurrentUser, $HTML, $Lang, $Paging);
        $Listing->add_col([
                'title'     => 'Status',
                'value'     => 'taskResult',
                'icon'      => function($Task) {
                    switch($Task->taskResult()) {
                        case 'OK':
                            return PerchUI::icon('core/circle-check', 16, null, 'icon-status-success');
                            break;

                        case 'FAILED':
                            return PerchUI::icon('core/circle-delete', 16, null, 'icon-status-alert');
                            break;

                        case 'WARNING':
                            return PerchUI::icon('core/alert', 16, null, 'icon-status-warning');
                            break;

                        default:
                            return PerchUI::icon('core/info-alt', 16, null, 'icon-status-info');
                            break;
                    }
                    
                }
            ]);
        $Listing->add_col([
                'title'     => 'App',
                'value'     => function($Task) {
                    return (isset($app_lookup[$Task->taskApp()]) ? PerchUtil::html($app_lookup[$Task->taskApp()]) : $Task->taskApp());
                },
            ]);
        $Listing->add_col([
                'title'     => 'Task',
                'value'     => 'taskKey',
            ]);
        $Listing->add_col([
                'title'     => 'Result',
                'value'     => 'taskMessage',
            ]);
        $Listing->add_col([
                'title'     => 'Date',
                'value'     => function($Task){
                    return strftime(PERCH_DATE_SHORT.' '.PERCH_TIME_LONG, strtotime($Task->taskStartTime()));
                },
            ]);
        $Listing->add_col([
                'title'     => 'Duration',
                'value'     => function($Task){
                    $duration = strtotime($Task->taskEndTime()) - strtotime($Task->taskStartTime());
                    if ($duration>=0) {
                        return $duration.'s';
                    } 

                    return '-';
                },
            ]);


        echo $Listing->render($tasks);
	}

    echo $HTML->heading2('Configuring Scheduled Tasks');


	echo '<div class="instructions">';

	if (!defined('PERCH_SCHEDULE_SECRET')) {

		
		echo '<p>'.PerchLang::get('To configure tasks to run, you first need to set a %ssecret%s. This is just a code word to stop tasks being run accidentally. Choose a secret (e.g. %scarbonara%s) and add it to your %sconfig/config.php%s file like this:', '<em>', '</em>', '<em>', '</em>', '<code>'.PERCH_LOGINPATH.'/', '</code>').'</p>';

		echo "<p class=\"markup-sample\"><code>define('PERCH_SCHEDULE_SECRET', 'carbonara');</code></p>";

		echo '<p>'.PerchLang::get('Do that now, then reload this page. Don\'t use %scarbonara%s.', '<em>', '</em>').'</p>';

	}else{

		echo '<p>'.PerchLang::get('Scheduled tasks can be run from the command line (or via a cron job) with the following command:').'</p>';

		echo '<p class="markup-sample"><code>php '.PERCH_PATH.'/core/scheduled/run.php '.PERCH_SCHEDULE_SECRET.'</code></p>';

		echo '<p>'.PerchLang::get('or via a web browser or remote HTTP request with the following URL:').'</p>';

		$url = 'http://'.PerchUtil::html($_SERVER['HTTP_HOST']).PERCH_LOGINPATH.'/core/scheduled/run.php?secret='.PERCH_SCHEDULE_SECRET;

		echo '<p class="markup-sample"><code>'.$url.'</code></p>';

	}

	echo '</div>';
