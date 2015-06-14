<?php
	require('PerchBackup.class.php');
	$Backup = new PerchBackup($API);
	
    $HTML = $API->get('HTML');
    
    $Form = $API->get('Form');
    
    $Settings = $API->get('Settings');
    $mysqldump_path = $Settings->get('perch_backup_mysqldump_path')->settingValue();
    if(!$mysqldump_path || $mysqldump_path == '') {
    	$mysqldump_path = 'mysqldump';



	    $UserPrivileges = $API->get('UserPrivileges');
	    $UserPrivileges->create_privilege('perch_backup', 'Run backups');
    }
    
    $message = false;
	
	if ($Form->submitted()) {
	    $postvars = array('backup_type');
	    $data = $Form->receive($postvars);
	    $Backup->build($data['backup_type'],$mysqldump_path);
	}
?>