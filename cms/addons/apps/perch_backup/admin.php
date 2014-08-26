<?php
	
    if ($CurrentUser->logged_in() && $CurrentUser->has_priv('perch_backup')) {
       $this->register_app('perch_backup', 'Backup', 10, 'Backup your Perch data and customizations', '1.2');
	   $this->add_setting('perch_backup_mysqldump_path', 'Path to mysqldump', 'text');
    }else{
    	$API = new PerchAPI(1.0, 'perch_backup');
    	$UserPrivileges = $API->get('UserPrivileges');
    	$UserPrivileges->create_privilege('perch_backup', 'Run backups');
    }

    
?>