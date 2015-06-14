<?php 
    include (PERCH_PATH.'/core/inc/sidebar_start.php');
 
    echo $HTML->para('Restore from a backup file.');
 
    include (PERCH_PATH.'/core/inc/sidebar_end.php'); 
    include (PERCH_PATH.'/core/inc/main_start.php'); 
    include ($app_path.'/modes/_subnav.php'); 
 

    echo $HTML->heading1('Restore from backup');
    

    echo $HTML->smartbar(
        $HTML->smartbar_link(false, 
                array( 
                    'link'=> PERCH_LOGINPATH.'/core/settings/backup/',
                    'label' => PerchLang::get('Plans'),
                )
        ),

        $HTML->smartbar_link(true, 
                array( 
                    'link'=> PERCH_LOGINPATH.'/core/settings/backup/restore/general/',
                    'label' => PerchLang::get('Restore'),
                    'class' => 'icon download'
                ), true
        )
    );


    echo $message;

    /*
    =================================== PICK A BUCKET ===================================
     */
    if (PerchUtil::count($buckets)) {
        $rows = $buckets;

        $headings = ['Bucket', 'Type', 'Folder'];

        $s = '';
        if (PerchUtil::count($rows)) {

            $s .= '<table class="d"><thead><tr>';
            foreach($headings as $heading) {
                $s .= '<th>'.PerchLang::get($heading).'</th>';
            }
            $s .= '     <th class="action last"></th></tr></thead><tbody>';
            echo $s;
         
            foreach($rows as $Bucket) {
            ?><tr>
                <td class="primary"><?php echo PerchUtil::html(PerchUtil::filename($Bucket->get_name())); ?></td>
                <td><?php echo PerchUtil::html($Bucket->get_type()); ?></td>
                <td><?php echo PerchUtil::html($Bucket->get_file_path()); ?></td>
                <td><?php
                    echo '<a href="'.PERCH_LOGINPATH.'/core/settings/backup/restore/general/?bucket='.PerchUtil::html($Bucket->get_name()).'" class="positive">'.PerchLang::get('Find backups').'</a>';
                ?></td>
            </tr><?php     
            }     
            $s = '   </tbody></table>';
            echo $s;
        }
    }


    
    /*
    =================================== LIST FILES ===================================
     */
    if (PerchUtil::count($db_files)) {
        $rows = $db_files;

        $headings = ['File', 'Date'];

        $s = '';
        if (PerchUtil::count($rows)) {

            $s .= '<table class="d"><thead><tr>';
            foreach($headings as $heading) {
                $s .= '<th>'.PerchLang::get($heading).'</th>';
            }
            $s .= '     <th class="action last"></th></tr></thead><tbody>';
            echo $s;
         
            foreach($rows as $file) {
            ?><tr>
                <td class="primary"><?php echo PerchUtil::html($file); ?></td>
                <td><?php 
                    $parts    = explode('_', $file);
                    $date_ext = array_pop($parts);
                    $parts    = explode('.', $date_ext);
                    $date_str = array_shift($parts);
                    echo strftime(PERCH_DATE_SHORT.' '.PERCH_TIME_SHORT, strtotime($date_str)); ?></td>
                <td><?php
                    echo '<a href="'.PERCH_LOGINPATH.'/core/settings/backup/restore/general/?bucket='.$Bucket->get_name().'&amp;file='.$file.'" class="caution">'.PerchLang::get('Restore').'</a>';
                ?></td>
            </tr><?php     
            }     
            $s = '   </tbody></table>';
            echo $s;
        }
    }

    /*
    =================================== CONFIRM FORM ===================================
     */
    if ($confirm) {
        echo $Form->form_start();

        echo $HTML->warning_message('Restoring will revert your database to the state it was in when this backup was made. There is no undo. Are you sure?');

        echo $Form->submit_field('btnSubmit', 'Restore backup now');
        echo $Form->form_end();
    }

    
    // Footer
    include (PERCH_PATH.'/core/inc/main_end.php');
