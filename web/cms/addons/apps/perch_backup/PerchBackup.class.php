<?php

class PerchBackup
{
    
	/**
	 * 
	 * Check to see if we can run mysqldump
	 */
	function can_mysqldump($mysqldump_path)
    { 	
    	if (function_exists('exec') && strlen(exec($mysqldump_path.' --help')) >30) {
            return true;
        }
        return false;
    }
	
    /**
     * 
     * Check that we can write and delete our files
     * @param string $folder
     */
    function can_write_temp_file($folder)
    {
        if (is_dir($folder) && is_readable($folder) && is_writable($folder)) {
            
            // try and write a file
            $file = $folder.DIRECTORY_SEPARATOR.'perch_backup_test_'.rand(10000,99999).'.txt';
            file_put_contents($file, 'test');
            
            // did it work?
            if (file_exists($file)) {
                
                // can we delete it?
                unlink($file);
                
                // has it gone?
                if (!file_exists($file)) {
                    // yes! we're good to go
                    return true;
                }
            }
        }
        return false;
    }
	

    /**
     * 
     * Creates a backup file
     * @param bool $backup_all whether to include customizations to Perch
     * @return a zip file
     */
	public function build($backup_type='all',$mysqldump_path=false)
	{
	   	include('pclzip.lib.php');
	   	
	   	$zipfolder = 'backup';
	   	$zipfile = strtolower('website-backup-'.date('d-M-Y').'.zip');
	   	$file = $zipfolder.DIRECTORY_SEPARATOR.$zipfile;
	   	
		//create zip
		$zip = new PclZip($file);
		
		$files = array();
		
		if($this->can_mysqldump($mysqldump_path)) {
			//generate db dump
			exec($mysqldump_path.' --opt --host='.PERCH_DB_SERVER.' --user='.PERCH_DB_USERNAME.' --password='.PERCH_DB_PASSWORD.' '.PERCH_DB_DATABASE.' > backup/backup.sql');
			//add to zip
			$files[]= 'backup/backup.sql';
		}

		if($backup_type == 'resources') {
		   	//get resources folder and add to zip
		   	$files[]= PERCH_PATH.'/resources';
		
	   	
		//if backupAll
		}elseif($backup_type == 'custom') {
			//get resources folder and add to zip
		   	$files[]= PERCH_PATH.'/resources';
			//get templates
			$files[]= PERCH_PATH.'/templates';
			//get addons
			$files = $this->_get_addons($files);
			//get config
			$files[]= PERCH_PATH.'/config';
		}elseif($backup_type == 'all') {
			$files[]= PERCH_PATH;
		}

		//die('<pre>'.print_r($files, true).'</pre>');
		
		//return zip
		if ($zip->create($files,PCLZIP_OPT_REMOVE_PATH,PERCH_PATH,PCLZIP_OPT_ADD_PATH,'perch_backup') == 0) {
			
			if (file_exists('backup/backup.sql')) {
				unlink('backup/backup.sql');
			}
			echo $zip->errorInfo();
			exit;
		}else{
			header("Content-type:application/x-zip-compressed");
			header("Content-disposition:attachment;filename=".$zipfile);
			readfile($file);
			//delete zip file
			unlink($file);
			//remove the sql file if it exists
			if (file_exists('backup/backup.sql')) {
				unlink('backup/backup.sql');
			}
			exit;	
		} 
		
		
	}
	
	private function _get_addons($files)
	{
		$items = PerchUtil::get_dir_contents(PERCH_PATH.'/addons');

		if (PerchUtil::count($items)) {
			$out = array();

			foreach ($items as $item) {
				if (strpos($item, 'apps')===false) {
					$files[] = PERCH_PATH.'/addons/'.$item;
				}else{
					$apps = PerchUtil::get_dir_contents(PERCH_PATH.'/addons/apps');
					if (PerchUtil::count($apps)) {
						foreach($apps as $app) {
							if (!strpos($app, 'backup')) {
								$files[] = PERCH_PATH.'/addons/apps/'.$app;
							}
						}
					}
				}
			}
		}

		return $files;
	}
	
    
}

?>