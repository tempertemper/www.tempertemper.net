<?php
    echo $HTML->title_panel([
            'heading' => $Lang->get('Delete Asset'),
        ]);


    echo $HTML->warning_block('Are you sure?', $Lang->get('Are you sure you wish to delete this asset? %sIt may be in use on your site. Deleting will result in the asset no longer being available.%s'), '<strong>','</strong>');
?>
<form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" class="form-simple">

    <div class="submit-bar">
        <div class="submit-bar-actions">
        <?php echo $Form->submit('btnsubmit', 'Delete', 'button'), ' ', PerchLang::get('or'), ' <a href="',PERCH_LOGINPATH . '/core/apps/assets/edit/?id='.$Asset->id(), '">', PerchLang::get('Cancel'), '</a>'; ?>
        </div>
    </div>
    
</form>
