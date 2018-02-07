<?php

	
    echo $HTML->title_panel([
        'heading' => $Lang->get('Editing email settings'),
	]);


    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    $Smartbar->add_item([
        'active' => true,
        'title' => 'Settings',
        'link'  => '/core/settings/email/',
        'icon'  => 'core/mail',
    ]);

    echo $Smartbar->render();

    echo $HTML->heading2('Test email settings');

    echo $Form->form_start();
?>
        <div class="field-wrap <?php echo $Form->error('email', false);?>">
			<?php echo $Form->label('email', 'Email address'); ?>
			<div class="form-entry">
			<?php echo $Form->email('email', $Form->get(array(), 'email')); ?>
			</div>
		</div>     
<?php

    echo $HTML->submit_bar([
                'button' => $Form->submit('btnsubmit', 'Send test email', 'button'),
            ]);
    echo $Form->form_end();
