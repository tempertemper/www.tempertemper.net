<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
    
    <p><?php echo PerchLang::get('Are you sure you wish to delete this user?'); ?></p>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>


    <h1><?php echo PerchLang::get('Deleting User %s', PerchUtil::html($User->userGivenName() . ' ' . $User->userFamilyName())); ?></h1>

    
    <?php echo $Alert->output(); ?>

    <h2><?php printf(PerchLang::get('Are you sure you wish to delete %s?'), PerchUtil::html($User->userGivenName() . ' ' . $User->userFamilyName())); ?></h2>

    <form action="<?php echo PerchUtil::html($Form->action()); ?>" method="post" class="sectioned">
		
		<p class="submit">
			<?php 		
				echo $Form->submit('submit', 'Delete user', 'button');
			
			    echo ' ' . PerchLang::get('or') . ' <a href="'.PERCH_LOGINPATH.'/core/users">' . PerchLang::get('Cancel'). '</a>'; 
			?>
			
		</p>
	</form>

<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>