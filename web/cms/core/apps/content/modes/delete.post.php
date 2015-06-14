

<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
    
    <p>
        <?php echo PerchLang::get("Delete this item of content from this page."); ?>
    </p>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>



	    <h1><?php echo PerchLang::get('Content'); ?></h1>


    <p><?php 
        $display_page = PerchUtil::html(($Region->regionPage() == '*' ? PerchLang::get('all pages.') : $Page->pageNavText()));
        printf(PerchLang::get('Are you sure you wish to delete this item in the %s region from %s?'), '<strong>'. PerchUtil::html($Region->regionKey()). '</strong>', $display_page); ?>
    </p>

    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" class="sectioned">

        <p class="submit">
            <?php echo $Form->submit('btnsubmit', 'Delete', 'button'), ' ', PerchLang::get('or'), ' <a href="',PERCH_LOGINPATH . '/core/apps/content/edit/?id='.$Region->id(), '">', PerchLang::get('Cancel'), '</a>'; ?>
        </p>
        
    </form>
    
<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>