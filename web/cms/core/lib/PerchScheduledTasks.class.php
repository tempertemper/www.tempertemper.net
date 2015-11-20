<?php

class PerchScheduledTasks extends PerchFactory
{
	protected $singular_classname = 'PerchScheduledTask';
    protected $table    = 'scheduled_tasks';
    protected $pk       = 'taskID';
    protected $default_sort_column = 'taskStartTime';
    protected $created_date_column = 'taskStartTime';

    static $tasks = array();

    public static function register_task($appID, $taskKey, $freq_minutes, $func)
    {
    	$freq_seconds = (int)$freq_minutes*60;

    	self::$tasks[$appID][] = array('frequency'=>$freq_seconds, 'appID'=>$appID, 'taskKey'=>$taskKey, 'func'=>$func);
    }

    public function get_scheduled()
    {
        $this->_load_app_callbacks();
        return self::$tasks;
    }

    public function run()
    {
    	// find apps and load up their scheduled task scripts to register callbacks
    	$apps = $this->_load_app_callbacks();
    	
   		// if we have apps with scheduled task scripts
    	if (PerchUtil::count($apps)) {

    		// get the dates each task last ran
    		$last_runs = $this->_get_last_runs($apps);

    		// loop through the apps that have schedule task scripts.
    		foreach($apps as $app) {

    			// if we have tasks for this app
    			if (isset(self::$tasks[$app])) {

    				// loop through this app's tasks
    				foreach(self::$tasks[$app] as $task) {

    					// get the date it last ran
    					if (isset($last_runs[$app][$task['taskKey']])) {
    						$last_run = $last_runs[$app][$task['taskKey']];
    					}else{
    						// this task has never run, so run it.
    						$last_run = '2000-01-01 00:00:00';
    					}

    					$last_run_time = strtotime($last_run);
    					$now = time();

    					// check the frequency to see if we should run the task, if not, skip to the next in the loop
  						if ($now-$last_run_time < $task['frequency']) {
  							// the desired frequency hasn't ellapsed, skip this task.
  							continue;
  						}

  						// WE'RE GOING TO RUN THIS TASK.

    					// get the start time for the task
    					$start_time = date('Y-m-d H:i:s');
    					
    					// create the row (in case the task craps out and the script does not complete)
    					$data = array(
    						'taskApp'=>$app,
    						'taskKey'=>$task['taskKey'],
    						'taskStartTime'=>$start_time,
    						'taskResult'=>'FAILED',
    						'taskMessage'=>"Task failed to complete, or is still running."
    					);

    					$Task = $this->create($data);

    					// call the task
    					$result = call_user_func($task['func'], $last_run, $task['taskKey']);

                        // if we didn't get a result, log this as a warning
                        if (!isset($result['result'])) $result['result'] = 'WARNING';
                        if (!isset($result['message'])) $result['message'] = 'Task did not return a result.';
                        
                        // get the end time
                        $end_time = date('Y-m-d H:i:s');

                        // update the task result
                        $data = array(
                            'taskApp'=>$app,
                            'taskKey'=>$task['taskKey'],
                            'taskStartTime'=>$start_time,
                            'taskEndTime'=>$end_time,
                            'taskResult'=>strtoupper($result['result']),
                            'taskMessage'=>$result['message']
                        );

                        $Task->update($data);	
    				}
    			}
    		}
    	}
        $this->_clean_up_log();
    }


    /**
     * Check for the DB table, and if it's not there, run the SQL to create it.
     * @return boolean Success or failure.
     */
    public function attempt_install()
    {
        $sql = 'SHOW TABLES LIKE "'.$this->table.'"';
        $result = $this->db->get_value($sql);
        
        if ($result==false) {
            $sql = $this->_get_install_sql();
            $this->db->execute($sql);

            $sql = 'SHOW TABLES LIKE "'.$this->table.'"';
        	$result = $this->db->get_value($sql);

        	if ($result) return true;

        	return false;
        }
        
        return true; 
    }

    


    private function _load_app_callbacks()
    {
    	$path = PERCH_PATH.'/addons/apps';

    	$apps = PerchUtil::get_dir_contents(PerchUtil::file_path($path), true);

    	$out = array();

    	if (PerchUtil::count($apps)) {
    		
    		foreach($apps as $app) {
    			$file = PerchUtil::file_path($path.'/'.$app.'/scheduled_tasks.php');
    			if (file_exists($file)) {
    				include($file);
    				$out[] = $app;
    			}
    		}
    	}

        if (PERCH_RUNWAY) {
            include(PERCH_CORE.'/runway/scheduled_tasks.php');
        }

    	return $out;
    }

    private function _get_last_runs($apps) 
    {
    	$sql = 'SELECT taskApp, taskKey, MAX(taskStartTime) AS t FROM '.$this->table.' 
    			WHERE taskApp IN ('.$this->db->implode_for_sql_in($apps).') GROUP BY taskApp, taskKey';
    	$rows = $this->db->get_rows($sql);

    	if (PerchUtil::count($rows)) {
    		$out = array();

    		foreach($rows as $row) {
    			$out[$row['taskApp']][$row['taskKey']] = date('Y-m-d H:i:00', strtotime($row['t']));
    		}

    		return $out;
    	}

    	return false;
    }

    /**
     * Just the SQL to create the table.
     */
    private function _get_install_sql()
    {
    	$sql = "CREATE TABLE `".PERCH_DB_PREFIX."scheduled_tasks` (
				  `taskID` int(10) NOT NULL AUTO_INCREMENT,
				  `taskStartTime` datetime NOT NULL,
				  `taskEndTime` datetime DEFAULT NULL,
				  `taskApp` varchar(64) NOT NULL DEFAULT '',
				  `taskKey` varchar(64) DEFAULT NULL,
				  `taskResult` enum('OK','WARNING','FAILED') NOT NULL DEFAULT 'FAILED',
				  `taskMessage` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`taskID`),
				  KEY `idx_app` (`taskApp`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8";
		return $sql;
    }

    private function _clean_up_log()
    {
        $sql = 'SELECT taskApp, taskKey 
                FROM '.$this->table.'
                GROUP BY taskApp, taskKey';
        $tasks = $this->db->get_rows($sql);

        if (PerchUtil::count($tasks)) {
            foreach($tasks as $task) {
                $sql = 'DELETE FROM '.$this->table.' WHERE taskID IN (SELECT * FROM (
                            SELECT taskID
                            FROM '.$this->table.'
                            WHERE taskApp='.$this->db->pdb($task['taskApp']).' AND taskKey='.$this->db->pdb($task['taskKey']).'
                            ORDER BY taskStartTime DESC
                            LIMIT 10, 9999999
                        ) AS tmp)';
                $this->db->execute($sql);
            }

            // optimize index 
            $sql = 'OPTIMIZE TABLE '.$this->table;
            $this->db->get_row($sql);
        }
    }

}
