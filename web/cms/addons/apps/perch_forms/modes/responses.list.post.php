<?php
    
    # Side panel
    echo $HTML->side_panel_start();
        echo $HTML->para('Click on a response to see the full detail, or you can download the responses as a CSV file.');
    echo $HTML->side_panel_end();
    
    
    # Main panel
    echo $HTML->main_panel_start();
    include('_subnav.php');

    if ($spam) {
        echo $HTML->heading1('Listing Spam Responses');
    }else{
        echo $HTML->heading1('Listing Responses');
    }
    

    /* ----------------------------------------- SMART BAR ----------------------------------------- */
    ?>
    <ul class="smartbar">
        <li class="<?php echo ($filter=='all'?'selected':''); ?>"><a href="<?php echo PerchUtil::html($API->app_path().'/responses/?id='.$Form->id()); ?>"><?php echo $Lang->get('All Responses'); ?></a></li>
        <li class="new <?php echo ($filter=='spam'?'selected':''); ?>"><a href="<?php echo PerchUtil::html($API->app_path().'/responses/'.'?id='.$Form->id().'&spam=1'); ?>"><?php echo $Lang->get('Spam'); ?></a></li>
        <?php if ($CurrentUser->has_priv('perch_forms.configure')) { ?>
        <li class="<?php echo ($filter=='options'?'selected':''); ?>"><a href="<?php echo PerchUtil::html($API->app_path().'/settings/?id='.$Form->id()); ?>"><?php echo $Lang->get('Form Options'); ?></a></li>
        <?php } ?>
        <li class="fin"><a class="download icon" href="<?php echo $HTML->encode($API->app_path().'/responses/export/?id='.$Form->id()); ?>"><?php echo $Lang->get('Download CSV'); ?></a></li>
    </ul>
    <?php
    /* ----------------------------------------- /SMART BAR ----------------------------------------- */

    
    


    
    if (PerchUtil::count($responses)) {
?>
    <table class="d">
        <thead>
            <tr>
                <th class="nohoper"><?php echo $Lang->get('Date'); ?></th>
                <th><?php echo $Lang->get('Detail'); ?></th>
                <th class="action"></th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($responses as $Response) {
?>
            <tr>
                <td class="primary"><a href="<?php echo $API->app_path(); ?>/responses/detail/?id=<?php echo $HTML->encode(urlencode($Response->id())); ?>"><?php echo $HTML->encode(strftime('%d %b %Y %H:%M', strtotime($Response->responseCreated()))); ?></a></td>
                <td>
                    <?php
                        $details = PerchUtil::json_safe_decode($Response->responseJSON(), true);
                        $out = array();
                        if (PerchUtil::count($details)) {
                            foreach($details['fields'] as $item) {
                                if (isset($item['attributes']['label'])) {
                                    $out[] = '<strong>'.$HTML->encode($item['attributes']['label']).':</strong> '.$HTML->encode(PerchUtil::excerpt($item['value'], 10));
                                }
                            }

                            echo implode('<br />', $out);
                        }
                    ?>
                </td>
                <td><a href="<?php echo $API->app_path(); ?>/responses/delete/?id=<?php echo $HTML->encode(urlencode($Response->id())); ?>" class="delete inline-delete"><?php echo $Lang->get('Delete'); ?></a></td>
            </tr>

<?php   
    }
?>
        </tbody>
    </table>


    
<?php    
        if ($Paging->enabled()) {
            echo $HTML->paging($Paging);
        }


    } // if responses
    
     
    echo $HTML->main_panel_end();


?>