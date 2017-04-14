<?php 
    echo $HTML->title_panel([
            'heading' => $Lang->get('Delete Category ‘%s’', $Category->catTitle()),
        ]); 
?>

    <div role="alert" class="notification notification-warning"><?php 
        echo $Lang->get('Are you sure you wish to delete this category?'); ?>
    </div>

    <form method="post" action="<?php echo PerchUtil::html($Form->action(), true); ?>" class="form-simple">

        <div class="submit-bar">
            <div class="submit-bar-actions">
            <?php echo $Form->submit('btnsubmit', 'Delete', 'button'), ' ', PerchLang::get('or'), ' <a href="',PERCH_LOGINPATH . '/core/apps/categories/edit/?id='.$Category->id(), '">', PerchLang::get('Cancel'), '</a>'; ?>
            </div>
        </div>
        
    </form>