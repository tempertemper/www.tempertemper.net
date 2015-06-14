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
    echo $HTML->heading2('No Access');
    echo '<div id="template-help">';
    echo $HTML->para('Your user role does not have access to this functionality.');
	echo '</div>';
    echo $HTML->main_panel_end();


?>