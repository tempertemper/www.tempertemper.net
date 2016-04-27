<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
    <p><?php echo PerchLang::get('You may update the user’s personal details, email address and password here. If you wish to send password recovery instructions by email, just check the box.'); ?></p>

	
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>


	   <h1><?php echo PerchLang::get('Editing %s’s User Account', PerchUtil::html($details['userGivenName'].' '.$details['userFamilyName'])); ?></h1>


    
    <?php echo $Alert->output(); ?>

    <h2><?php echo PerchLang::get('User details'); ?></h2>

    

    <form action="<?php echo PerchUtil::html($Form->action()); ?>" method="post" class="sectioned">
		
        <div class="field <?php echo $Form->error('userUsername', false);?>">
            <?php echo $Form->label('userUsername', 'Username'); ?>
            <?php echo $Form->text('userUsername', $Form->get($details, 'userUsername'), ''); 

            if (PERCH_PARANOID) {
            	echo $Form->hint(PerchLang::get('Usernames are case-sensitive'));
            }

            ?>
        </div>
        
        <div class="field <?php echo $Form->error('userGivenName', false);?>">
            <?php echo $Form->label('userGivenName', 'First name'); ?>
            <?php echo $Form->text('userGivenName', $Form->get($details, 'userGivenName'), ''); ?>
        </div>
		
		<div class="field <?php echo $Form->error('userFamilyName', false);?>">
			<?php echo $Form->label('userFamilyName', 'Last name'); ?>
			<?php echo $Form->text('userFamilyName', $Form->get($details, 'userFamilyName'), ''); ?>
		</div>
		
		<div class="field <?php echo $Form->error('userEmail', false);?>">
			<?php echo $Form->label('userEmail', 'Email'); ?>
			<?php echo $Form->email('userEmail', $Form->get($details, 'userEmail'), ''); ?>
		</div>
		
		<?php if ($User->id() != $CurrentUser->id()){ ?>		
		<div class="field <?php echo $Form->error('roleID', false);?>">
			<?php echo $Form->label('roleID', 'Role'); ?>
			<?php 
			    $opts = array();
			    
			    if (PerchUtil::count($roles)) {
                    foreach($roles as $Role) {
                        $opts[] = array('label'=>$Role->roleTitle(), 'value'=>$Role->id());
                    }
                }
			    
			    echo $Form->select('roleID', $opts, $Form->get($details, 'roleID'), ''); ?>
		</div>
        <?php } ?>

		<div class="field">
			<?php echo $Form->label('resetPwd', 'Send new password instructions'); ?>
			<?php echo $Form->checkbox('resetPwd', '1', '0'); ?>
		</div>

		<?php
			if (PERCH_PARANOID) {
		?>
		<h2><?php echo PerchLang::get('Authenticate'); ?></h2>
		<div class="field <?php echo $Form->error('currentPassword', false);?>">
			<?php echo $Form->label('currentPassword', 'Your password'); ?>
			<?php echo $Form->password('currentPassword', $Form->get(false, 'currentPassword'), ''); ?>
		</div>

		<?php } // PARANOID ?>

		<p class="submit">
			<?php 		
				echo $Form->submit('submit', 'Save changes', 'button');
				echo ' ' . PerchLang::get('or') . ' <a href="'.PERCH_LOGINPATH.'/core/users">' . PerchLang::get('Cancel'). '</a>'; 
				
			?>
		</p>
	</form>

<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>