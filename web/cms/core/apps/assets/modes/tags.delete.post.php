<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
<p><?php //echo PerchLang::get(); ?></p>

<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>


    <h1><?php 
            printf(PerchLang::get('Delete Tag %s'), PerchUtil::html($Tag->tagTitle())); 
        ?></h1>

    
    <?php echo $Alert->output(); ?>

    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" class="sectioned">

        <p class="alert notice"><?php 
            printf(PerchLang::get('Are you sure you wish to delete the %s tag?'), '<strong>'. PerchUtil::html($Tag->tagTitle()). '</strong>'); ?>
        </p>
        
        <p class="submit">
            <?php echo $Form->submit('btnsubmit', 'Delete', 'button'), ' ', PerchLang::get('or'), ' <a href="',PERCH_LOGINPATH . '/core/apps/assets/tags/', '">', PerchLang::get('Cancel'), '</a>'; ?>
        </p>
        
    </form>


<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>