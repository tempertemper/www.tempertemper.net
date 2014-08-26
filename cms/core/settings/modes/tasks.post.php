<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
    
   
    <p><?php echo PerchLang::get('Some tasks can be automatically run on a schedule. In order to do this, you need to configure your server to periodically run a script.'); ?></p>
    <p><?php echo PerchLang::get('This page lists the most recently run scheduled tasks, along with their output.'); ?></p>

    


<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>

<?php include ('_subnav.php'); ?>


	<h1><?php echo PerchLang::get('Viewing Scheduled Tasks'); ?></h1>
<?php
	if (PerchUtil::count($tasks)) {
?>		
		<table>
        <thead>
            <tr>
            	<th class="action"><?php echo PerchLang::get('Status'); ?></th>
            	<th class="action"><?php echo PerchLang::get('App'); ?></th>                
				<th class="nohoper"><?php echo PerchLang::get('Task'); ?></th>   
                
				               
				             
                <th><?php echo PerchLang::get('Result'); ?></th>
                <th class="alsoran"><?php echo PerchLang::get('Date'); ?></th>
                <th class="action"><?php echo PerchLang::get('Duration'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php          
            foreach($tasks as $Task) {
                echo '<tr class="'.PerchUtil::flip('odd').'">';
                	echo '<td><span class="icon ' . PerchUtil::html(strtolower($Task->taskResult())) . '">' . PerchUtil::html($Task->taskResult()) . '</span></td>';
                	echo '<td>' . (isset($app_lookup[$Task->taskApp()]) ? PerchUtil::html($app_lookup[$Task->taskApp()]) : $Task->taskApp()) . '</td>';
                    echo '<td>' . PerchUtil::html($Task->taskKey()) . '</td>';
                    
                    
                    echo '<td>' . PerchUtil::html($Task->taskMessage()) . '</td>';
					echo '<td>' . strftime(PERCH_DATE_SHORT.' '.PERCH_TIME_LONG, strtotime($Task->taskStartTime())) . '</td>';
                    $duration = strtotime($Task->taskEndTime()) - strtotime($Task->taskStartTime());

                    if ($duration>=0) {
                    	echo '<td>' . $duration . 's</td>';
                    }else{
                    	echo '<td>-</td>';
                    }

                    
                echo '</tr>';
            }
        ?>
        </tbody>
    </table>
<?php
	}

?>


    
	<h2><?php echo PerchLang::get('Configuring Scheduled Tasks'); ?></h2>

<?php
	echo '<div class="helptext">';

	if (!defined('PERCH_SCHEDULE_SECRET')) {

		
		echo '<p>'.PerchLang::get('To configure tasks to run, you first need to set a %ssecret%s. This is just a code word to stop tasks being run accidentally. Choose a secret (e.g. %scarbonara%s) and add it to your %sconfig/config.php%s file like this:', '<em>', '</em>', '<em>', '</em>', '<code>'.PERCH_LOGINPATH.'/', '</code>').'</p>';

		echo "<p><code class=\"example\">define('PERCH_SCHEDULE_SECRET', 'carbonara');</code></p>";

		echo '<p>'.PerchLang::get('Do that now, then reload this page. Don\'t use %scarbonara%s.', '<em>', '</em>').'</p>';

	}else{

		echo '<p>'.PerchLang::get('Scheduled tasks can be run from the command line (or via a cron job) with the following command:').'</p>';

		echo '<p><code class="example">php '.PERCH_PATH.'/core/scheduled/run.php '.PERCH_SCHEDULE_SECRET.'</code></p>';

		echo '<p>'.PerchLang::get('or via a web browser or remote HTTP request with the following URL:').'</p>';

		$url = 'http://'.PerchUtil::html($_SERVER['HTTP_HOST']).PERCH_LOGINPATH.'/core/scheduled/run.php?secret='.PERCH_SCHEDULE_SECRET;

		echo '<p><code class="example">'.$url.'</code></p>';

	}

	echo '</div>';
?>


<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>