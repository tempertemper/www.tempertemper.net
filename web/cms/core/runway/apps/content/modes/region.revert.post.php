
<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
    
    <p>
        <?php echo PerchLang::get("Roll back a region to a prior version here."); ?>
    </p>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include($app_path.'/modes/_subnav.php'); ?>
      	
	    <h1><?php echo PerchLang::get('Rolling back the %s Region', PerchUtil::html($Region->regionKey())); ?></h1>
	

    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" class="sectioned">

        <p class="alert notice"><?php 
            printf(PerchLang::get('Are you sure you wish to roll back the region %s?'), '<strong>'. PerchUtil::html($Region->regionKey()). '</strong>'); ?>
        </p>
        
        
        
        <p class="submit">
            <?php echo $Form->submit('btnsubmit', 'Delete', 'button'), ' ', PerchLang::get('or'), ' <a href="',PERCH_LOGINPATH . '/core/apps/content/revisions/?id='.$Region->id(), '">', PerchLang::get('Cancel'), '</a>'; ?>
        </p>
        
    </form>
    
<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>