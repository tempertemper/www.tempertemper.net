

<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>

<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>

    <h1><?php echo PerchLang::get('Content'); ?></h1>

    <?php echo $Alert->output(); ?>
    <div class="info-panel">
        <p class="alert-notice"><?php echo PerchLang::get('Sorry, your account doesn\'t have access to edit this content.'); ?></p>
    </div>
<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>