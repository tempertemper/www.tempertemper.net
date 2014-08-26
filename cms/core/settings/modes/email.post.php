<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
    
   
    <p><?php echo PerchLang::get('Try your email settings by sending a test email. If you receive the email, your settings are correct.'); ?></p>

	<h3><?php echo PerchLang::get('Current configuration'); ?></h3>
    
    <ul>
        <li><strong><?php echo PerchLang::get('Method'); ?>:</strong> <?php echo PerchUtil::html(PERCH_EMAIL_METHOD); ?></li>
        <?php if (strtolower(PERCH_EMAIL_METHOD)=='smtp') { ?>
        <li><strong><?php echo PerchLang::get('Host'); ?>:</strong> <?php echo PerchUtil::html(PERCH_EMAIL_HOST); ?></li>
        <li><strong><?php echo PerchLang::get('Port'); ?>:</strong> <?php echo PerchUtil::html(PERCH_EMAIL_PORT); ?></li>
        <li><strong><?php echo PerchLang::get('Authenticate'); ?>:</strong> <?php echo PerchLang::get(PERCH_EMAIL_AUTH ? 'Yes' : 'No'); ?></li>
        
        <li><strong><?php echo PerchLang::get('Username'); ?>:</strong> <?php echo PerchUtil::html(PERCH_EMAIL_USERNAME); ?></li>
        <li><strong><?php echo PerchLang::get('Password'); ?>:</strong> <?php echo str_repeat('*', strlen(PERCH_EMAIL_PASSWORD)); ?></li>
            
        <?php } ?>

    </ul>

    


<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>

<?php include ('_subnav.php'); ?>


	<h1><?php echo PerchLang::get('Editing Email Settings'); ?></h1>
    
    <?php echo $Alert->output(); ?>



    <form action="<?php echo PerchUtil::html($Form->action()); ?>" method="post" class="sectioned">
		
	    <h2><?php echo PerchLang::get('Test email settings'); ?></h2>

	    <div class="info-panel">
	    
	    </div>
	
		<div class="field <?php echo $Form->error('email', false);?>">
			<?php echo $Form->label('email', 'Email'); ?>
			<?php echo $Form->email('email', $Form->get(array(), 'email'), ''); ?>
		</div>
		
		<p class="submit">
			<?php 		
				echo $Form->submit('submit', 'Send test email', 'button');
				echo ' ' . PerchLang::get('or') . ' <a href="'.PERCH_LOGINPATH.'/core/settings/">' . PerchLang::get('Cancel'). '</a>'; 
				
			?>
		</p>
	</form>

<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>