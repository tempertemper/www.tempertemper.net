<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
    <p>
        <?php echo PerchLang::get("Delete this route?"); ?>
    </p>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ($app_path.'/modes/_subnav.php'); ?>



        <h1><?php echo PerchLang::get('Delete route'); ?></h1>


    <p class="alert alert-notice"><?php 
        printf(PerchLang::get('Are you sure you wish to delete this route?')); ?>
    </p>

    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" class="sectioned">

        <p class="submit">
            <?php echo $Form->submit('btnsubmit', 'Delete', 'button'), ' ', PerchLang::get('or'), ' <a href="',PERCH_LOGINPATH . '/core/apps/content/routes/">', PerchLang::get('Cancel'), '</a>'; ?>
        </p>
        
    </form>
    
<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>