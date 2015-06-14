<?php

use Rah\Danpu\Dump;
use Rah\Danpu\Config;
use Rah\Danpu\Export;
use Rah\Danpu\Import;

class PerchBackupRun extends PerchBase
{
    protected $table        = 'backup_runs';
    protected $pk           = 'runID';
    protected $event_prefix = 'backup_run';

    public $message = false;

    public function restore($Bucket=false)
    {
        if ($Bucket===false) {
            $Plans = new PerchBackupPlans;
            $Plan  = $Plans->find($this->planID());
            $Bucket  = PerchResourceBuckets::get($Plan->planBucket());
        }

    	$conf   = PerchConfig::get('env');

    	$file  = $Bucket->get_file_path().'/'.$this->runDbFile();

    	if (file_exists($file)) {

    		$target = $conf['temp_folder'].'/restore_'.$this->runDbFile();

    		if (copy($file, $target)) {
    			return $this->restore_db($conf['temp_folder'], $target);
    		}

    		

    	}
    	return false;
    }

    private function restore_db($tmp_folder, $file)
    {
    	try {
    	    $DB = PerchDB::fetch();
    	    
    	    $dump = new Dump;
    	    $dump
    	        ->file($file)
    	        ->dsn($DB->dsn)
    	        ->user(PERCH_DB_USERNAME)
    	        ->pass(PERCH_DB_PASSWORD)
    	        ->tmp($tmp_folder);

    	    new Import($dump);

    	    unlink($file);

    	    return true;
    	} catch (\Exception $e) {
    	    $this->message = $e->getMessage();
    	    $this->db_file = false;
    	    return false;
    	}
    }

}