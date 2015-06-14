
<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
    
    <p>
        <?php echo PerchLang::get("Delete the navigation group from here."); ?>
    </p>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include('_subnav.php'); ?>
      

	
	    <h1><?php echo PerchLang::get('Deleting the %s Navigation Group', PerchUtil::html($NavGroup->groupTitle())); ?></h1>
	

    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" class="sectioned">

        <p class="alert notice"><?php 
            printf(PerchLang::get('Are you sure you wish to delete the group %s?'), '<strong>'. PerchUtil::html($NavGroup->groupTitle()). '</strong>'); ?>
        </p>
        
        
        
        <p class="submit">
            <?php echo $Form->submit('btnsubmit', 'Delete', 'button'), ' ', PerchLang::get('or'), ' <a href="',PERCH_LOGINPATH . '/core/apps/content/navigation/', '">', PerchLang::get('Cancel'), '</a>'; ?>
        </p>
        
    </form>
    
<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>