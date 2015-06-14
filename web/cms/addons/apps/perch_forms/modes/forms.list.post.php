<?php
    # Side panel
    echo $HTML->side_panel_start();
    echo $HTML->para('The forms in your site are listed here. If a form is configured to store data, you can click through to view the responses.');
    echo $HTML->side_panel_end();
    
    
    # Main panel
    echo $HTML->main_panel_start();
        include('_subnav.php');

    echo $HTML->heading1('Listing Forms');
    
    if (PerchUtil::count($forms)) {
?>
    <table class="d">
        <thead>
            <tr>
                <th><?php echo $Lang->get('Form'); ?></th>
                <th><?php echo $Lang->get('Responses'); ?></th>
                <th><?php echo $Lang->get('Most recent'); ?></th>
                <th class="action"></th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($forms as $Form) {
?>
            <tr>
                <td class="primary"><a href="<?php echo $API->app_path(); ?>/responses/?id=<?php echo $HTML->encode(urlencode($Form->id())); ?>"><?php echo $HTML->encode($Form->formTitle()); ?></a></td>
                <td><a href="<?php echo $API->app_path(); ?>/responses/?id=<?php echo $HTML->encode(urlencode($Form->id())); ?>"><?php echo $HTML->encode($Form->number_of_responses());?></a></td>
                <td><?php echo $HTML->encode(strftime('%d %b %Y %H:%M', strtotime($Form->most_recent_response_date())));?></td>

                <td>
                    <?php if ($CurrentUser->has_priv('perch_forms.delete')) { ?>
                    <a href="<?php echo $API->app_path(); ?>/delete/?id=<?php echo $HTML->encode(urlencode($Form->id())); ?>" class="delete inline-delete" data-msg="<?php echo $Lang->get('Delete this form?'); ?>"><?php echo $Lang->get('Delete'); ?></a>
                    <?php } ?>
                </td>
            </tr>

<?php   
    }
?>
        </tbody>
    </table>
    

    
<?php    
    }else{
        echo $HTML->warning_message('No forms have been submitted yet. Submit a new form to have it show up here.');
    }
    
     
    echo $HTML->main_panel_end();


?>