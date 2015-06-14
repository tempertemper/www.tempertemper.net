<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
    
    <p>
        <?php echo PerchLang::get("Delete this asset from your site."); ?>
    </p>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>



	    <h1><?php echo PerchLang::get('Asset'); ?></h1>


    <p class="alert alert-error error"><?php 
        printf(PerchLang::get('Are you sure you wish to delete this asset? %sIt may be in use on your site. Deleting will result in the asset no longer being available.%s'), '<strong>','</strong>'); ?>
    </p>

    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" class="sectioned">

        <p class="submit">
            <?php echo $Form->submit('btnsubmit', 'Delete', 'button'), ' ', PerchLang::get('or'), ' <a href="',PERCH_LOGINPATH . '/core/apps/assets/edit/?id='.$Asset->id(), '">', PerchLang::get('Cancel'), '</a>'; ?>
        </p>
        
    </form>
    
<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>