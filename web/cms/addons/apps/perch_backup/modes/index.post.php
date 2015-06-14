<?php
    
    # Side panel
    echo $HTML->side_panel_start();
    echo $HTML->heading3('The following can be backed up');
    
    echo $HTML->success_message('Files');
    
    if($Backup->can_mysqldump($mysqldump_path)) {
    	echo $HTML->success_message('MySQL Database');
    }else{
    	echo $HTML->failure_message('MySQL Database');
    }
    echo $HTML->side_panel_end();
    
    
    # Main panel
    echo $HTML->main_panel_start();
    include('_subnav.php');

    echo $HTML->heading1('Making a Backup');


    echo $HTML->heading2('Backup your data and customizations');
    echo '<div id="template-help">';
    echo $HTML->para('Use the form below to backup your content, data and customizations or the entire CMS directory.');
	echo $HTML->para('This does not backup your site files - just the database, CMS folder or specified content.');
    echo $HTML->para('The checks on the right will show if your MySQL database can also be backed up. If it cannot, you will need to do this another way as all  site content is stored in the database.');
    echo '</div>';
    echo $HTML->heading2('Select backup type and download backup');
    echo $Form->form_start();
	
    $opts = array();
    if($Backup->can_mysqldump($mysqldump_path)) {
        $opts[] = array('label'=>'Database only', 'value'=>'database');
        $opts[] = array('label'=>'Database and resources', 'value'=>'resources');
        $opts[] = array('label'=>'Database, resources and customizations', 'value'=>'custom');
        $opts[] = array('label'=>'Database and entire CMS folder', 'value'=>'all');
    }else{
        $opts[] = array('label'=>'Resources only', 'value'=>'resources');
        $opts[] = array('label'=>'Resources and customizations', 'value'=>'custom');
        $opts[] = array('label'=>'Entire CMS folder', 'value'=>'all');
    }
	
	

	echo $Form->select_field('backup_type', 'Backup Type', $opts);
    
	echo $Form->submit_field('btnSubmit', 'Create backup');
	echo $Form->form_end();
    echo $HTML->main_panel_end();


?>