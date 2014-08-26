<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
<p><?php echo PerchLang::get('These are the master pages available for use when creating a new page. You can access their settings from this list.'); ?></p>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>

    <h1><?php echo PerchLang::get('Listing All Master Pages'); ?></h1>
    
    <?php echo $Alert->output(); ?>
    
<?php
    if (PerchUtil::count($templates)) {
?>
    <table class="d">
        <thead>
            <tr>
                <th class="first"><?php echo PerchLang::get('Title'); ?></th>
                <th><?php echo PerchLang::get('Path'); ?></th>
                <th class="action last"></th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($templates as $Template) {
?>
            <tr>
                <td class="primary"><a href="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/apps/content/page/templates/edit/?id=<?php echo PerchUtil::html(urlencode($Template->id())); ?>"><?php echo PerchUtil::html($Template->templateTitle())?></a></td>
                <td><?php echo PerchUtil::html($Template->templatePath())?></td>  
                <td>
                    <?php if ($CurrentUser->has_priv('content.templates.delete')) { ?>
                    <a href="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/apps/content/page/templates/delete/?id=<?php echo PerchUtil::html(urlencode($Template->id())); ?>" class="delete"><?php echo PerchLang::get('Delete'); ?></a>
                    <?php  } ?>
                </td>
                
            </tr>

<?php   
    }
?>
        </tbody>
    </table>
<?php

}else{
    ?>
        <div class="info-panel">
            <h2><?php echo PerchLang::get('No page templates yet?'); ?></h2>
            <p><?php echo PerchLang::get('New page templates can be added to the %stemplates/pages%s folder.', '<code>', '</code>'); ?></p>

        </div>
    
    <?php
}
?>
<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>
