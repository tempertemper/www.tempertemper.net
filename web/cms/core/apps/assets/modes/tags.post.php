<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
<p><?php echo PerchLang::get('Tags help you search for assets in order to reuse them later. Tag any assets with keywords to help find them again later.'); ?></p>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>

    <h1><?php echo PerchLang::get('Listing All Asset Tags'); ?></h1>
    
    <?php echo $Alert->output(); ?>
    
<?php
    if (PerchUtil::count($tags)) {
?>
    <table class="d">
        <thead>
            <tr>
                <th class="first"><?php echo PerchLang::get('Tag name'); ?></th>
                <th><?php echo PerchLang::get('Count'); ?></th>
                <th class="action last"></th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($tags as $Tag) {
?>
            <tr>
                <td class="primary"><a href="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/apps/assets/tags/edit/?id=<?php echo PerchUtil::html(urlencode($Tag->id())); ?>"><?php echo PerchUtil::html($Tag->tagTitle())?></a></td>
                <td><?php echo PerchUtil::html($Tag->tagCount())?></td>  
                <td>
                    <a href="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/apps/assets/tags/delete/?id=<?php echo PerchUtil::html(urlencode($Tag->id())); ?>" class="delete inline-delete"><?php echo PerchLang::get('Delete'); ?></a>
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
