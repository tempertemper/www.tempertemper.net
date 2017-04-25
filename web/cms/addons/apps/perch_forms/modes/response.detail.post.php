<?php
  
    
    echo $HTML->title_panel([
        'heading' => $Lang->get('Response #%d', $Response->id()),
    ], $CurrentUser);
    

    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    $Smartbar->add_item([
        'active' => true,
        'title' => 'All Responses',
        'link'  => $API->app_nav().'/responses/?id='.$ResponseForm->id(),
        'icon'  => 'core/o-documents',
    ]);

    $Smartbar->add_item([
        'active' => false,
        'title' => 'Spam',
        'link'  => $API->app_nav().'/responses/?id='.$ResponseForm->id().'&spam=1',
        'icon'  => 'ext/o-poop',
    ]);

    $Smartbar->add_item([
        'active' => false,
        'title' => 'Form Options',
        'link'  => $API->app_nav().'/settings/?id='.$ResponseForm->id(),
        'priv'  => 'perch_forms.configure',
        'icon'  => 'core/o-toggles',
    ]);

    $Smartbar->add_item([
        'active' => false,
        'title' => 'Download CSV',
        'link'  => $API->app_nav().'/responses/export/?id='.$ResponseForm->id(),
        'priv'  => 'perch_forms.configure',
        'icon'  => 'ext/o-cloud-download',
        'position' => 'end',
    ]);



    echo $Smartbar->render();

    echo $HTML->heading2('Response');
    
    echo '<div class="inner">';
    echo '<table>';

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
    echo '</div>';

    $page_details = $Response->page();

    if ($page_details) {

        echo $HTML->heading2('Page');

        echo '<div class="inner">';
        echo '<table>';

        if (is_object($page_details)) {
            echo '<tr>';
                echo '<th>'.$Lang->get('Title').'</th>';
                echo '<td><a href="'.PERCH_LOGINPATH.'/core/apps/content/page/?id='.$page_details->id.'">'.$HTML->encode($page_details->title).'</a></td>';
            echo '</tr>';

            echo '<tr>';
                echo '<th>'.$Lang->get('Path').'</th>';
                echo '<td>'.$HTML->encode($page_details->path).'</td>';
            echo '</tr>';
        }else{
            echo '<tr>';
                echo '<th>'.$Lang->get('Path').'</th>';
                echo '<td>'.$HTML->encode($page_details).'</td>';
            echo '</tr>';
        }
 


        echo '</table>';
        echo '</div>';
    }


        echo $Form->form_start(false, 'bulk-edit');

        if ($Response->responseSpam()) {
            echo $Form->submit_field('btnSubmit', 'This is not spam');
            
        }else{
            echo $Form->submit_field('btnSubmit', 'Mark as spam');
        }

        echo $Form->form_end();
