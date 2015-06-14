<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
<p><?php echo PerchLang::get('Deleting a master page which has pages based on it will break those pages. You should think carefully before deleting a master page.'); ?></p>

<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>


    <h1><?php 
            printf(PerchLang::get('Delete %s Master Page'), PerchUtil::html($Template->templateTitle())); 
        ?></h1>

    
    <?php echo $Alert->output(); ?>

    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" class="sectioned">

        <p class="alert notice"><?php 
            printf(PerchLang::get('Are you sure you wish to delete the %s Master Page?'), '<strong>'. PerchUtil::html($Template->templateTitle()). '</strong>'); ?>
        </p>
        
        <p class="submit">
            <?php echo $Form->submit('btnsubmit', 'Delete', 'button'), ' ', PerchLang::get('or'), ' <a href="',PERCH_LOGINPATH . '/core/apps/content/page/templates/', '">', PerchLang::get('Cancel'), '</a>'; ?>
        </p>
        
    </form>


<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>