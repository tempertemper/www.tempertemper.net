<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
	<p><?php echo PerchLang::get('Check the box for each privilege this role should have.'); ?></p>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>

    <h1><?php 
		if (is_object($Role)) {
			echo PerchLang::get('Editing %s Role', PerchUtil::html($Role->roleTitle()));
		}else{
			echo PerchLang::get('Adding a New Role');
		}
		 ?></h1>
	

    
    <?php echo $Alert->output(); ?>


<?php


    /* ----------------------------------------- SMART BAR ----------------------------------------- */
    if (is_object($Role)) {
    ?>
    <ul class="smartbar">
        <li class="selected"><a href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/users/roles/edit/?id='.$Role->id()); ?>"><?php echo PerchLang::get('Privileges'); ?></a></li>
        <li class=""><a href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/users/roles/actions/?id='.$Role->id()); ?>"><?php echo PerchLang::get('Actions'); ?></a></li>

    </ul>
    <?php
    } // if Role
    /* ----------------------------------------- /SMART BAR ----------------------------------------- */

?>


    
    <form action="<?php echo PerchUtil::html($Form->action()); ?>" method="post" class="sectioned magnetic-save-bar">
		
		<h2><?php echo PerchLang::get('Role details'); ?></h2>
		
        <div class="field last <?php echo $Form->error('roleTitle', false);?>">
            <?php echo $Form->label('roleTitle', 'Title'); ?>
            <?php echo $Form->text('roleTitle', $Form->get($details, 'roleTitle'), ''); ?>
        </div>
        
        <h2><?php echo PerchLang::get('Privileges'); ?></h2>

        <?php
            if (PerchUtil::count($privs)) {
                
                $previous = false;
                
                foreach($privs as $Priv) {
                    if ($Priv->app() != $previous && $previous !==false) {
                        
                        if ($previous !== false) {
                            echo '<div class="field">';
                            echo $Form->checkbox_set('privs-'.$previous, $Perch->app_name($previous), $opts, $existing_privs, false);
                            echo '</div>';
                        }
                        
                        $opts = array();

                        $API   = new PerchAPI(1, $Priv->app());
                        $Lang  = $API->get('Lang');
                        
                    }

                    if (is_object($Role)) {
                        $disabled = $Role->roleMasterAdmin();
                    }else{
                        $disabled = false;
                    }
                    
                    switch($Priv->app()) {

                        case 'perch':
                        case 'content':
                            $title = PerchLang::get($Priv->privTitle());
                            break;

                        default:
                            $title = $Lang->get($Priv->privTitle());
                            break;

                    }

                    $opts[] = array('label'=>$title, 'value'=>$Priv->id(), 'disabled'=>$disabled);
                    
                    $previous = $Priv->app();
                }
                
                if (PerchUtil::count($opts)) {
                    echo '<div class="field">';
                    echo $Form->checkbox_set('privs-'.$previous, $Perch->app_name($previous), $opts, $existing_privs);
                    echo '</div>';
                }
                
                
            }
            
            
        
        
        ?>


		<p class="submit">
			<?php 		
				echo $Form->submit('submit', 'Save changes', 'button');
				echo ' ' . PerchLang::get('or') . ' <a href="'.PERCH_LOGINPATH.'/core/users/roles/">' . PerchLang::get('Cancel'). '</a>'; 
				
			?>
		</p>
	</form>

<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>