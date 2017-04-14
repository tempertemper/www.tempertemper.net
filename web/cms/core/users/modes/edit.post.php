<?php
	    echo $HTML->title_panel([
        	'heading' => $Lang->get('Editing %s’s User Account', $details['userGivenName'].' '.$details['userFamilyName']),
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

   
    <form action="<?php echo PerchUtil::html($Form->action()); ?>" method="post" class="form-simple">
		
        <div class="field-wrap <?php echo $Form->error('userUsername', false);?>">
            <?php echo $Form->label('userUsername', 'Username'); ?>
            <div class="form-entry">
            <?php echo $Form->text('userUsername', $Form->get($details, 'userUsername'), ''); 

            if (PERCH_PARANOID) {
            	echo $Form->hint(PerchLang::get('Usernames are case-sensitive'));
            }

            ?>
            </div>
        </div>
        
        <div class="field-wrap <?php echo $Form->error('userGivenName', false);?>">
            <?php echo $Form->label('userGivenName', 'First name'); ?>
            <div class="form-entry">
            <?php echo $Form->text('userGivenName', $Form->get($details, 'userGivenName'), ''); ?>
            </div>
        </div>
		
		<div class="field-wrap <?php echo $Form->error('userFamilyName', false);?>">
			<?php echo $Form->label('userFamilyName', 'Last name'); ?>
			<div class="form-entry">
			<?php echo $Form->text('userFamilyName', $Form->get($details, 'userFamilyName'), ''); ?>
			</div>
		</div>
		
		<div class="field-wrap <?php echo $Form->error('userEmail', false);?>">
			<?php echo $Form->label('userEmail', 'Email'); ?>
			<div class="form-entry">
			<?php echo $Form->email('userEmail', $Form->get($details, 'userEmail'), ''); ?>
			</div>
		</div>
		
		<?php if ($User->id() != $CurrentUser->id()){ ?>		
		<div class="field-wrap <?php echo $Form->error('roleID', false);?>">
			<?php echo $Form->label('roleID', 'Role'); ?>
			<div class="form-entry">
			<?php 
			    $opts = array();
			    
			    if (PerchUtil::count($roles)) {
                    foreach($roles as $Role) {
                        $opts[] = array('label'=>$Role->roleTitle(), 'value'=>$Role->id());
                    }
                }
			    
			    echo $Form->select('roleID', $opts, $Form->get($details, 'roleID'), ''); ?>
			</div>
		</div>
        <?php } ?>

		<div class="field-wrap">
			<?php echo $Form->label('resetPwd', 'Send new password instructions'); ?>
			<div class="form-entry">
			<?php echo $Form->checkbox('resetPwd', '1', '0'); ?>
			</div>
		</div>

		<?php
			if (PERCH_PARANOID) {
		?>
		<h2><?php echo PerchLang::get('Authenticate'); ?></h2>
		<div class="field-wrap <?php echo $Form->error('currentPassword', false);?>">
			<?php echo $Form->label('currentPassword', 'Your password'); ?>
			<div class="form-entry">
			<?php echo $Form->password('currentPassword', $Form->get(false, 'currentPassword'), ''); ?>
			</div>
		</div>

		<?php } // PARANOID ?>

		
		<?php
			echo $HTML->submit_bar([
					'button' => $Form->submit('submit', 'Save changes', 'button'),
					'cancel_link' => '/core/users'
				]);
		?>
	</form>
