<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
    
    <p><?php echo PerchLang::get('Are you sure you wish to delete this role?'); ?></p>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>

	    <h1><?php echo PerchLang::get('Deleting a Role'); ?></h1>


    
    <?php echo $Alert->output(); ?>

    <p class="alert notice">
        <?php printf(PerchLang::get('Are you sure you wish to delete the %s role?'), PerchUtil::html($Role->roleTitle())); ?>.
        
    </p>

	<h2><?php echo PerchLang::get('Choose a different role for users to be transferred to.'); ?></h2>

    <form action="<?php echo PerchUtil::html($Form->action()); ?>" method="post" class="sectioned">
			
		<div class="field">
		    <?php echo $Form->label('roleID', 'Transfer users to'); ?>
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
		
		<p class="submit">
			<?php 		
				echo $Form->submit('submit', 'Delete role', 'button');
			
			    echo ' ' . PerchLang::get('or') . ' <a href="'.PERCH_LOGINPATH.'/core/users/roles/">' . PerchLang::get('Cancel'). '</a>'; 
			?>
			
		</p>
	</form>

<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>