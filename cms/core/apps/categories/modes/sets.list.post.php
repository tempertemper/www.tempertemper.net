<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
<p><?php //echo PerchLang::get(''); ?></p>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>

    <?php if ($CurrentUser->has_priv('categories.sets.create')) { ?>
    <a class="add button" href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/apps/categories/sets/edit/'); ?>"><?php echo PerchLang::get('Add set'); ?></a>
    <?php } // categories.sets.create ?>
    
    <h1><?php echo PerchLang::get('Listing all category sets'); ?></h1>

	<?php
	/* ----------------------------------------- SMART BAR ----------------------------------------- */
       
	/* ----------------------------------------- /SMART BAR ----------------------------------------- */
    $Alert->output();

    echo $HTML->listing($sets, 
    		array('Title', 'Slug'), 
    		array('setTitle', 'setSlug'), 
            array(
                    'edit' => 'sets',
                    'delete' => 'delete/set',
                ),
            array(
                'user' => $CurrentUser,
                'edit' => 'categories.manage',
                'delete' => 'categories.sets.delete',
                )
            );
    ?>

<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>
	