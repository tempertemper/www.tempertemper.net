<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
<p><?php //echo PerchLang::get(''); ?></p>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ($app_path.'/modes/_subnav.php'); ?>

    <?php if ($CurrentUser->has_priv('content.collections.create')) { ?>
    <a class="add button" href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/manage/collections/edit/'); ?>"><?php echo PerchLang::get('Add collection'); ?></a>
    <?php } // content.collections.create ?>
    
    <h1><?php echo PerchLang::get('Listing all collections'); ?></h1>

    <?php
    /* ----------------------------------------- SMART BAR ----------------------------------------- */
       
    /* ----------------------------------------- /SMART BAR ----------------------------------------- */
    $Alert->output();

    echo $HTML->listing($collections, 
            array('Title', 'Items', 'Updated'), 
            array('collectionKey', 'get_item_count', 'collectionUpdated'), 
            array(
                    'edit' => '../../collections',
                    'delete' => 'delete',

                ),
            array(
                'user' => $CurrentUser,
                'edit' => 'content.collections.manage',
                'delete' => 'content.collections.delete',
                'not-inline' => true,
                )
            );

    echo $HTML->paging($Paging);

include (PERCH_PATH.'/core/inc/main_end.php');
    