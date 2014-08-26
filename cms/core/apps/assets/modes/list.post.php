<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
<p><?php echo PerchLang::get('This page lists assets in use within the site. Use the smart bar to filter or search, and click on an asset to edit.'); ?></p>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>

    <?php if ($CurrentUser->has_priv('assets.create')) { ?>
    <a class="add button" href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/apps/assets/edit/'); ?>"><?php echo PerchLang::get('Add asset'); ?></a>
    <?php } // assets.create ?>
    
    <h1><?php echo PerchLang::get('Listing all assets'); ?></h1>

	<?php
	/* ----------------------------------------- SMART BAR ----------------------------------------- */
	   $base_path = PERCH_LOGINPATH.'/core/apps/assets/';
       include('_smart_bar.php');
       
	/* ----------------------------------------- /SMART BAR ----------------------------------------- */


        if (PerchUtil::count($assets)) {

            if ($view == 'list') {
                include('_asset_list.php');
            }else{
                include('_asset_grid.php');
            }

            if ($Paging->enabled()) {
                echo '<div class="paging-cont">';
                echo $HTML->paging($Paging);
                echo '</div>';
            }

        }
    ?>

<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>