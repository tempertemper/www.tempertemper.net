<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
    <p><?php echo PerchLang::get('These actions perform a one-time bulk edit to the site as it currently stands. They have no ongoing effect.'); ?></p>
	<p><?php echo PerchLang::get('This is useful if, for example, you create a new role that you only want to give access to a few regions. You can revoke access to all regions here, and then go to the individual regions concerned and grant access to the ones the role should be able to edit.'); ?></p>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>

    <h1><?php 
		if (is_object($Role)) {
			echo PerchLang::get('Editing %s Role', $Role->roleTitle());
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
        <li class=""><a href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/users/roles/edit/?id='.$Role->id()); ?>"><?php echo PerchLang::get('Privileges'); ?></a></li>
        <li class="selected"><a href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/users/roles/actions/?id='.$Role->id()); ?>"><?php echo PerchLang::get('Actions'); ?></a></li>

    </ul>
    <?php
    } // if Role
    /* ----------------------------------------- /SMART BAR ----------------------------------------- */

?>


    
    <form action="<?php echo PerchUtil::html($Form->action()); ?>" method="post" class="sectioned magnetic-save-bar">

        <h2><?php echo PerchLang::get('Editing Regions'); ?></h2>

        <fieldset class="checkboxes">

            <p class="info"><?php echo PerchLang::get('Modify all existing regions to grant or revoke permission for this role to edit.'); ?></p>

            <strong><?php echo PerchLang::get('Regions'); ?></strong>
            <div class="checkbox">
                <?php echo $Form->radio('regions-noaction', 'regions', 'noaction', true); ?>
                <?php echo $Form->label('regions-noaction', 'Make no changes'); ?>
            </div>
            <div class="checkbox">
                <?php echo $Form->radio('regions-grant', 'regions', 'grant', false); ?>
                <?php echo $Form->label('regions-grant', 'Grant role permission to edit all regions'); ?>
            </div>
            <div class="checkbox">
                <?php echo $Form->radio('regions-revoke', 'regions', 'revoke', false); ?>
                <?php echo $Form->label('regions-revoke', 'Revoke role permissions to edit all regions'); ?>
            </div>
            
        </fieldset>


        <h2><?php echo PerchLang::get('Creating Subpages'); ?></h2>

        <fieldset class="checkboxes">

            <p class="info"><?php echo PerchLang::get('Modify all existing pages to grant or revoke permission for this role to be able to create subpages.'); ?></p>

            <strong><?php echo PerchLang::get('Pages'); ?></strong>
            <div class="checkbox">
                <?php echo $Form->radio('pages-noaction', 'pages', 'noaction', true); ?>
                <?php echo $Form->label('pages-noaction', 'Make no changes'); ?>
            </div>
            <div class="checkbox">
                <?php echo $Form->radio('pages-grant', 'pages', 'grant', false); ?>
                <?php echo $Form->label('pages-grant', 'Grant role permission create new subpages of all current pages'); ?>
            </div>
            <div class="checkbox">
                <?php echo $Form->radio('pages-revoke', 'pages', 'revoke', false); ?>
                <?php echo $Form->label('pages-revoke', 'Revoke role permission to create new subpages'); ?>
            </div>
        </fieldset>
 

        <p class="submit">
            <?php       
                echo $Form->submit('submit', 'Make changes', 'button');
                echo ' ' . PerchLang::get('or') . ' <a href="'.PERCH_LOGINPATH.'/core/users/roles/">' . PerchLang::get('Cancel'). '</a>'; 
                
            ?>
        </p>
    </form>

<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>