<?php
    # Title panel
    echo $HTML->title_panel_start();
    echo $HTML->heading1('Backup');
    echo $HTML->title_panel_end();
    
    
    # Side panel
    echo $HTML->side_panel_start();
    
    echo $HTML->side_panel_end();
    
    
    # Main panel
    echo $HTML->main_panel_start();
    echo $HTML->heading2('Backup folder not writable');
    echo '<div id="template-help">';
    echo $HTML->para('Perch Backup needs to be able to write and delete files from the folder backup inside perch_backup.');
	echo '</div>';
    echo $HTML->main_panel_end();


?>