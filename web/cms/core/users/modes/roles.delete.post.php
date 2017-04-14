<?php 
    echo $HTML->title_panel([
            'heading' => $Lang->get('Deleting a Role'),
        ]); 

    echo $Form->form_start();

    $Alert->set('warning', $Lang->get('Are you sure you wish to delete the %s role?', PerchUtil::html($Role->roleTitle())));
    echo $Alert->output();

?>
	<div class="field-wrap">
	    <?php echo $Form->label('roleID', 'Choose a different role for users to be transferred to'); ?>
	    <div class="form-entry">
	    <?php
		    $opts = array();
	        if (PerchUtil::count($all_roles)) {
	            foreach($all_roles as $ThisRole) {
	                if ($ThisRole->id()!=$Role->id()) {
	                    $opts[] = array('label'=>$ThisRole->roleTitle(), 'value'=>$ThisRole->id());
	                }
	            }
	        }
	        echo $Form->select('roleID', $opts, $Form->get(false, 'roleID'));
	    
	    ?>
	    </div>
	</div>

<?php

    echo $HTML->submit_bar([
                'button' => $Form->submit('btnsubmit', 'Delete', 'button'),
                'cancel_link' => '/core/users/roles/'
            ]);
    echo $Form->form_end();
