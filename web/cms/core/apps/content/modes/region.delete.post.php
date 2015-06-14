

<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
    
    <p>
        <?php echo PerchLang::get("Delete the region from this page. Note that unless the tag is also removed from your page, the option to edit this region will reappear."); ?>
    </p>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>
<?php
$display_page = PerchUtil::html(($Region->regionPage() == '*' ? PerchLang::get('all pages.') : $Page->pageNavText()));

?>

    <h1><?php echo PerchLang::get('Deleting the %s Region', PerchUtil::html($Region->regionKey())); ?></h1>


    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" class="sectioned">

        <p class="alert notice"><?php 
            
            printf(PerchLang::get('Are you sure you wish to delete the region %s from %s?'), '<strong>'. PerchUtil::html($Region->regionKey()). '</strong>', $display_page); ?>
        </p>
        
        
        
        <p class="submit">
            <?php echo $Form->submit('btnsubmit', 'Delete', 'button'), ' ', PerchLang::get('or'), ' <a href="',PERCH_LOGINPATH . '/core/apps/content', '">', PerchLang::get('Cancel'), '</a>'; ?>
        </p>
        
    </form>
    
<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>