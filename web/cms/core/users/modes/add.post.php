<?php
	    echo $HTML->title_panel([
        	'heading' => $Lang->get('Adding a New User Account'),
        ]);

        $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);
	    $Smartbar->add_item([
	        'active'   => true,
	        'title'    => 'Users',
	        'link'     => '/core/users/',
	        'icon'     => 'core/users',
	    ]);
	    $Smartbar->add_item([
	        'active'   => false,
	        'title'    => 'My Account',
	        'link'     => '/core/account/',
	        'icon'     => 'core/user',
	    ]);
	    echo $Smartbar->render();
?>
    <h2 class="divider"><div><?php echo PerchLang::get('User details'); ?></div></h2>

    <form action="<?php echo PerchUtil::html($fCreateUser->action()); ?>" method="post" class="form-simple" autocomplete="off">
		
        <div class="field-wrap <?php echo $fCreateUser->error('userUsername', false);?>">
            <?php echo $fCreateUser->label('userUsername', 'Username'); ?>
            <div class="form-entry">
            <?php echo $fCreateUser->text('userUsername', $fCreateUser->get(false, 'userUsername')); 

            if (PERCH_PARANOID) {
            	echo $fCreateUser->hint(PerchLang::get('Usernames are case-sensitive'));
            }

            ?>
            </div>
        </div>
        
        <div class="field-wrap <?php echo $fCreateUser->error('userGivenName', false);?>">
            <?php echo $fCreateUser->label('userGivenName', 'First name'); ?>
            <div class="form-entry">
            <?php echo $fCreateUser->text('userGivenName', $fCreateUser->get(false, 'userGivenName')); ?>
            </div>
        </div>
		
		<div class="field-wrap <?php echo $fCreateUser->error('userFamilyName', false);?>">
			<?php echo $fCreateUser->label('userFamilyName', 'Last name'); ?>
			<div class="form-entry">
			<?php echo $fCreateUser->text('userFamilyName', $fCreateUser->get(false, 'userFamilyName')); ?>
			</div>
		</div>
		
		<div class="field-wrap <?php echo $fCreateUser->error('userEmail', false);?>">
			<?php echo $fCreateUser->label('userEmail', 'Email'); ?>
			<div class="form-entry">
			<?php echo $fCreateUser->email('userEmail', $fCreateUser->get(false, 'userEmail'), 'autocomplete="off"'); ?>
			</div>
		</div>
		<?php
			if (!PERCH_PARANOID) {
		?>
		<div class="field-wrap <?php echo $fCreateUser->error('userPassword', false);?>">
			<?php echo $fCreateUser->label('userPassword', 'Password'); ?>
			<div class="form-entry">
			<?php echo $fCreateUser->password('userPassword', $fCreateUser->get(false, 'userPassword')); ?>
			</div>
		</div>

		<div class="field-wrap <?php echo $fCreateUser->error('userPassword2', false);?>">
			<?php echo $fCreateUser->label('userPassword2', 'Repeat the password'); ?>
			<div class="form-entry">
			<?php echo $fCreateUser->password('userPassword2', $fCreateUser->get(false, 'userPassword2')); ?>
			</div>
		</div>
		<?php } // !PARANOID ?>
		
		<div class="field-wrap <?php echo $fCreateUser->error('roleID', false);?>">
			<?php echo $fCreateUser->label('roleID', 'Role'); ?>
			<div class="form-entry">
			<?php 
			    $opts = array();
			    
				$selection = false;
			
			    if (PerchUtil::count($roles)) {
                    foreach($roles as $Role) {
                        $opts[] = array('label'=>$Role->roleTitle(), 'value'=>$Role->id());
						if (!$selection && !$Role->roleMasterAdmin()) {
							$selection = $Role->id();
						}
                    }
                }
			    
			    echo $fCreateUser->select('roleID', $opts, $fCreateUser->get(false, 'roleID', $selection)); ?>
			</div>
		</div>

		<?php
			if (!PERCH_PARANOID) {
		?>
        <div class="field-wrap">
			<?php echo $fCreateUser->label('sendEmail', 'Send welcome email'); ?>
			<div class="form-entry">
			<?php echo $fCreateUser->checkbox('sendEmail', '1', '1'); ?>
			</div>
		</div>

		<?php } // !PARANOID ?>


		<?php
			if (PERCH_PARANOID) {
		?>
		<h2><?php echo PerchLang::get('Authenticate'); ?></h2>
		<div class="field-wrap <?php echo $fCreateUser->error('currentPassword', false);?>">
			<?php echo $fCreateUser->label('currentPassword', 'Your password'); ?>
			<div class="form-entry">
			<?php echo $fCreateUser->password('currentPassword', $fCreateUser->get(false, 'currentPassword')); ?>
			</div>
		</div>

		<?php } // PARANOID ?>


		<?php
			echo $HTML->submit_bar([
					'button' => $fCreateUser->submit('submit', 'Create user', 'button'),
					'cancel_link' => '/core/users'
				]);
		?>
	</form>
