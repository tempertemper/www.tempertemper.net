<?php 
    include (PERCH_PATH.'/core/inc/sidebar_start.php');
 
    echo $HTML->para('Listing backup runs');
 
    include (PERCH_PATH.'/core/inc/sidebar_end.php'); 
    include (PERCH_PATH.'/core/inc/main_start.php'); 
    include ($app_path.'/modes/_subnav.php'); 
 
    echo '<form method="post" action="'.PerchUtil::html($Form->action(), true).'">
            <div>'.$Form->submit('backup', 'Backup now', 'add button topadd').'</div>
         </form>';


    echo $HTML->heading1('Viewing ‘%s’ Backup Plan', $HTML->encode($Plan->planTitle()));
    

    echo $HTML->smartbar(
        $HTML->smartbar_link(true, 
                array( 
                    'link'=> PERCH_LOGINPATH.'/core/settings/backup/?id='.$Plan->id(),
                    'label' => $Plan->planTitle(),
                )
        ),

        $HTML->smartbar_link(false, 
                array( 
                    'link'=> PERCH_LOGINPATH.'/core/settings/backup/edit/?id='.$Plan->id(),
                    'label' => PerchLang::get('Plan Options'),
                )
        )

    );


    // If a success or failure message has been set, output that here


    $Alert->output();

    $rows = $runs;

    $headings = ['Date', 'Result', 'Message'];

    $s = '';
    if (PerchUtil::count($rows)) {

        $first = true;

        $s .= '<table class="d">
                <thead>
                    <tr>';
        foreach($headings as $heading) {
            $s .= '<th'.($first?' class="compact"':'').'>'.PerchLang::get($heading).'</th>';
            $first = false;
        }
        $s .= '     <th class="action last"></th>
                    </tr>
                </thead>
                <tbody>';
        echo $s;
      

        foreach($rows as $row) {

        ?>
        <tr>
            <td class="action"><?php 
                    switch($row->runResult()) {
                        case 'OK':
                            echo '<span class="icon ok">'.PerchLang::get('OK').'</span>';
                            break;
                        case 'FAILED':
                            echo '<span class="icon failed">'.PerchLang::get('Failed').'</span>';
                            break;
                        default:
                            echo '<span class="icon warning">'.PerchUtil::html($row->runResult()).'</span>';
                            break;
                    }
                ?></td>
            <td><?php echo strftime(PERCH_DATE_SHORT.' '.PERCH_TIME_SHORT, strtotime($row->runDateTime())); ?></td>

            <td><?php
                    if ($row->runType()=='db') {
                        if ($row->runDbFile()!='') {
                            echo PerchLang::get('Database backed up.');
                        }else{
                            echo PerchUtil::html($row->runMessage());
                        }
                    }else{
                        echo PerchUtil::html($row->runMessage());
                    }
                ?></td>
            <td><?php
                if ($row->runDbFile()!='') {
                    echo '<a href="'.PERCH_LOGINPATH.'/core/settings/backup/restore/?id='.$row->id().'" class="caution">'.PerchLang::get('Restore').'</a>';
                }

            ?></td>
        </tr>
        <?php     
        }     

        $s = '   </tbody>
                </table>';
        echo $s;

        echo $HTML->paging($Paging);
    }

    
    
    // Footer
    include (PERCH_PATH.'/core/inc/main_end.php');
