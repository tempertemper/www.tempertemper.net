<?php
  
    # Side panel
    echo $HTML->side_panel_start();
    echo $HTML->para('This page shows the information collected in full.');
    echo $HTML->side_panel_end();
    
    
    # Main panel
    echo $HTML->main_panel_start(); 
    include('_subnav.php');

    echo $HTML->heading1('Viewing a Response');
    

    if ($message) echo $message;

    echo $HTML->heading2('Response');
    
    echo '<table class="d factsheet">';

    echo '<tr>';
        echo '<th>'.$Lang->get('Received').'</th>';
        echo '<td>'.$HTML->encode(strftime('%d %b %Y %H:%M', strtotime($Response->responseCreated()))).'</td>';
    echo '</tr>';
    
    foreach($Response->fields() as $name=>$details) {
        
        echo '<tr>';
            if (isset($details->attributes->label)) $name = $details->attributes->label;
            echo '<th>'.$HTML->encode($name).'</th>';
            echo '<td>'.nl2br($HTML->encode($details->value)).'&nbsp;</td>';
        echo '</tr>';
    }

    foreach($Response->files() as $name=>$details) {
        
        echo '<tr>';
            $displayname = $name;
            if (isset($details->attributes->label)) $displayname = $details->attributes->label;
            echo '<th>'.$HTML->encode($displayname).'</th>';
            if (file_exists($details->path)) {
                echo '<td><span class="file"></span><a href="'.$API->app_path().'/responses/detail/?id='.$Response->id().'&file='.$name.'">'.$HTML->encode($details->name).'</a></td>';
            }else{
                echo '<td><span class="file"></span>'.$HTML->encode($details->name).' '.$Lang->get('(File not available to download)').'</td>';
            }
            
        echo '</tr>';
    }
    
    echo '</table>';

    $page_details = $Response->page();

    if (is_object($page_details)) {

        echo $HTML->heading2('Page');

        echo '<table class="d factsheet">';

        echo '<tr>';
            echo '<th>'.$Lang->get('Title').'</th>';
            echo '<td><a href="'.PERCH_LOGINPATH.'/core/apps/content/page/?id='.$page_details->id.'">'.$HTML->encode($page_details->title).'</a></td>';
        echo '</tr>';

        echo '<tr>';
            echo '<th>'.$Lang->get('Path').'</th>';
            echo '<td>'.$HTML->encode($page_details->path).'</td>';
        echo '</tr>';

        echo '</table>';
    }


        echo $Form->form_start(false, 'bulk-edit');
            echo '<div class="controls">';
        if ($Response->responseSpam()) {
            
            echo $Form->label('btnSubmit', 'This response was flagged as spam.');
            echo $Form->submit_field('btnSubmit', 'This is not spam');
            
        }else{
            
            echo $Form->label('btnSubmit', 'Is this a junk message?');
            echo $Form->submit_field('btnSubmit', 'Mark as spam');
        }
            echo '</div>';
        echo $Form->form_end();

    echo $HTML->main_panel_end();

?>