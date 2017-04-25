<?php
        echo $HTML->title_panel([
        'heading' => $Lang->get('Rolling back the ‘%s’ Region', PerchUtil::html($Region->regionKey())),
        ]);
?>

	

    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" class="form-simple">

        <div role="alert" class="notification notification-warning"><?php 
            echo PerchUI::icon('core/alert');
            printf($Lang->get('Are you sure you wish to roll back the region ‘%s’?'), '<strong>'. PerchUtil::html($Region->regionKey()). '</strong>'); ?>
        </div>
        
        
        
        <div class="submit-bar">
            <div class="submit-bar-actions">
            <?php echo $Form->submit('btnsubmit', 'Delete', 'button'), ' ', $Lang->get('or'), ' <a href="',PERCH_LOGINPATH . '/core/apps/content/revisions/?id='.$Region->id(), '">', $Lang->get('Cancel'), '</a>'; ?>
            </div>
        </div>
        
    </form>
    
