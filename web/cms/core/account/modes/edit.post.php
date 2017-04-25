<?php 

        echo $HTML->title_panel([
            'heading' => $Lang->get('Editing My Account'),
        ]);

        $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);
        if ($CurrentUser->has_priv('perch.users.manage')) {
            $Smartbar->add_item([
                'active'   => false,
                'title'    => 'Users',
                'link'     => '/core/users/',
                'icon'     => 'core/users',
            ]);
        }
        
        $Smartbar->add_item([
            'active'   => true,
            'title'    => 'My Account',
            'link'     => '/core/account/',
            'icon'     => 'core/user',
        ]);
        echo $Smartbar->render();

?>
<form action="<?php echo PerchUtil::html($Form->action()); ?>" method="post" class="form-simple">

	<h2 class="divider"><div><?php echo PerchUtil::html($Lang->get('Details')); ?></div></h2>

        <div class="field-wrap <?php echo $Form->error('userGivenName', false);?>">
            <?php echo $Form->label('userGivenName', 'First name'); ?>
            <div class="form-entry">
            <?php echo $Form->text('userGivenName', $Form->get($details, 'userGivenName')); ?>
            </div>
        </div>

    	<div class="field-wrap <?php echo $Form->error('userFamilyName', false);?>">
    		<?php echo $Form->label('userFamilyName', 'Last name'); ?>
            <div class="form-entry">
    		<?php echo $Form->text('userFamilyName', $Form->get($details, 'userFamilyName')); ?>
            </div>
    	</div>

    	<div class="field-wrap <?php echo $Form->error('userEmail', false);?>">
    		<?php echo $Form->label('userEmail', 'Email'); ?>
            <div class="form-entry">
    		<?php echo $Form->email('userEmail', $Form->get($details, 'userEmail')); ?>
            </div>
    	</div>

    	<div class="field-wrap last">
            <?php echo $Form->label('lang', 'Language'); ?>
            <div class="form-entry">
            <?php
                $langs = PerchLang::get_lang_options();
                $opts = array();
                if (PerchUtil::count($langs)) {
                    foreach($langs as $lang) {
                        $opts[] = array('label'=>$lang, 'value'=>$lang);
                    }
                }
                echo $Form->select('lang', $opts, $Form->get($settings, 'lang', 'en-gb'));
            ?>
            </div>
        </div>


    <h2 class="divider"><div><?php echo PerchUtil::html($Lang->get('Change password')); ?></div></h2>


    <?php
        if (PERCH_PARANOID) {
    ?>
        <div class="field-wrap <?php echo $Form->error('currentPassword', false);?>">
            <?php echo $Form->label('currentPassword', 'Current password'); ?>
            <div class="form-entry">
            <?php echo $Form->password('currentPassword', ''); ?>
            </div>
        </div>
    <?php
        }
    ?>

        <div class="field-wrap <?php echo $Form->error('userPassword', false);?>">
            <?php echo $Form->label('userPassword', 'New password'); ?>
            <div class="form-entry">
            <?php echo $Form->password('userPassword', ''); ?>
            </div>
        </div>
        <div class="field-wrap <?php echo $Form->error('userPassword2', false);?>">
            <?php echo $Form->label('userPassword2', 'Repeat new password'); ?>
            <div class="form-entry">
            <?php echo $Form->password('userPassword2', ''); ?>
            </div>
        </div>

	<div class="submit-bar">
        <div class="submit-bar-actions">
		<?php
			echo $Form->submit('submit', 'Save changes', 'button');
		    echo ' ' . $Lang->get('or') . ' <a href="'.PERCH_LOGINPATH.'">' . $Lang->get('Cancel'). '</a>';
		?>
        </div>
	</div>
</form>
