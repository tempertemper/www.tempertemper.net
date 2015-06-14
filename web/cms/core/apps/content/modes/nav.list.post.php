<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
<p><?php echo PerchLang::get('These are the navigation groups available for use when displaying site navigation.'); ?></p>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>

    <?php if ($CurrentUser->has_priv('content.navgroups.create')) { ?>
        <a class="add button" href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/navigation/edit/'); ?>"><?php echo PerchLang::get('Add group'); ?></a>
    <?php } // create ?>


    <h1><?php echo PerchLang::get('Listing All Navigation Groups'); ?></h1>
    
    <?php echo $Alert->output(); ?>
    
<?php
    if (PerchUtil::count($groups)) {
?>
    <table class="d">
        <thead>
            <tr>
                <th class="first"><?php echo PerchLang::get('Title'); ?></th>
                <th><?php echo PerchLang::get('Slug'); ?></th>
                <th class="action last"></th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($groups as $Group) {
?>
            <tr>
                <td class="primary"><a href="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/apps/content/navigation/pages/?id=<?php echo PerchUtil::html(urlencode($Group->id())); ?>"><?php echo PerchUtil::html($Group->groupTitle())?></a></td>
                <td><?php echo PerchUtil::html($Group->groupSlug())?></td>  
                <td>
                    <?php if ($CurrentUser->has_priv('content.navgroups.delete')) { ?>
                    <a href="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/apps/content/navigation/delete/?id=<?php echo PerchUtil::html(urlencode($Group->id())); ?>" class="delete inline-delete"><?php echo PerchLang::get('Delete'); ?></a>
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
    <?php
}
?>
<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>
