<?php 

        echo $HTML->title_panel([
            'heading' => $Lang->get('Delete Tag ‘%s’', $HTML->encode($Tag->tagTitle())),
        ]);

?>
<form method="post" action="<?php echo $HTML->encode($Form->action(), true); ?>" class="form-simple">

    <div role="alert" class="notification notification-warning">
    <?php 
        printf($Lang->get('Are you sure you wish to delete the ‘%s’ tag?'), '<strong>'. $HTML->encode($Tag->tagTitle()). '</strong>'); ?>
    
    </div>
    
    <div class="submit-bar">
        <div class="submit-bar-actions">
        <?php echo $Form->submit('btnsubmit', 'Delete', 'button'), ' ', PerchLang::get('or'), ' <a href="',PERCH_LOGINPATH . '/core/apps/assets/tags/', '">', PerchLang::get('Cancel'), '</a>'; ?>
        </div>
    </div>
    
</form>
