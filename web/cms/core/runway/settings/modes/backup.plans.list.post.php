<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
   


<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>

<?php include ($app_path.'/modes/_subnav.php'); ?>

	<?php if (!$errors && $CurrentUser->has_priv('perch.backups.manage')) { ?>
	<a class="add button" href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/settings/backup/edit/'); ?>"><?php echo PerchLang::get('Add plan'); ?></a>
	<?php } //perch.backups.manage ?>


	<h1><?php echo PerchLang::get('Configuring Backup Plans'); ?></h1>
    
    <?php
	/* ----------------------------------------- SMART BAR ----------------------------------------- */
       
    if (!$errors) {
           
        echo $HTML->smartbar(
            $HTML->smartbar_link(true, 
                    array( 
                        'link'=> PERCH_LOGINPATH.'/core/settings/backup/',
                        'label' => PerchLang::get('Plans'),
                    )
            ),

            $HTML->smartbar_link(false, 
                    array( 
                        'link'=> PERCH_LOGINPATH.'/core/settings/backup/restore/general/',
                        'label' => PerchLang::get('Restore'),
                        'class' => 'icon download'
                    ), true
            )
        );

    }


	/* ----------------------------------------- /SMART BAR ----------------------------------------- */
    $Alert->output();

    echo $HTML->listing($plans, 
    		array('Title', 'Frequency', 'Last run', 'Active'), 
    		array('planTitle', 'planFrequency', 'last_run_date_for_display', 'planActive'), 
            array(
                    'edit' => '../backup',
                    'delete' => 'delete',
                ),
            array(
                'user' => $CurrentUser,
                'edit' => 'categories.manage',
                'delete' => 'categories.sets.delete',
                )
            );



    
   ?>
<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>