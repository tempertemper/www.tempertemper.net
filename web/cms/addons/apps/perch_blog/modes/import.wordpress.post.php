<?php 

	echo $HTML->title_panel([
    'heading' => $Lang->get('Importing data'),
    ], $CurrentUser);
        
    
    flush();

    if ($file) {

        $results = $BlogUtil->import_from_wp($file, $format, null, $sectionID);

        if (PerchUtil::count($results)) {
            echo '<div class="inner"><ul class="progress-list">';
            foreach($results as $result) {
                echo '<li class="progress-item progress-'.$result['type'].'">';
                switch($result['type']) {
                    case 'success':
                        echo PerchUI::icon('core/circle-check'). ' ';
                        break;
                    default:
                        echo PerchUI::icon('core/alert'). ' ';
                        break;
                }
                echo implode(' &mdash; ', $result['messages']);
                echo '</li>';
                flush();
            }
            echo '</ul></div>';
        }

    }
    