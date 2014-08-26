<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
<p><?php echo PerchLang::get('These are the pages that belong to this navigation group. Each page can appear in multiple groups.'); ?></p>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>


    <h1><?php printf(PerchLang::get('Editing %s Navigation Group'), PerchUtil::html($NavGroup->groupTitle())); ?></h1>
    
    <?php echo $Alert->output(); ?>

    <?php
    /* ----------------------------------------- SMART BAR ----------------------------------------- */
    ?>
    <ul class="smartbar">
        <li class="selected"><a href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/navigation/pages/?id='.$groupID); ?>"><?php echo PerchLang::get('Pages'); ?></a></li>
        <li><a href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/navigation/edit/?id='.$groupID); ?>"><?php echo PerchLang::get('Group Options'); ?></a></li>
        <li class="fin"><a class="icon reorder" href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/navigation/reorder/?id='.$groupID); ?>"><?php echo PerchLang::get('Reorder Pages'); ?></a></li>
    </ul>
    <?php
    /* ----------------------------------------- /SMART BAR ----------------------------------------- */
    ?>

    <?php
        echo render_tree($Pages, $groupID, 0, 'sortable disabled');
        
        function render_tree($Pages, $groupID, $parentID=0, $class=false)
        {
            $pages = $Pages->get_by_parent($parentID, $groupID);
            
            $s = '';
            $s = '<ol class="'.$class.'">';
            
            if (PerchUtil::count($pages)) {
                
                foreach($pages as $Page) {
                    $s .= '<li><div class="page icon">'.PerchUtil::html($Page->pageTitle()).'</div>';
                    $s .= render_tree($Pages, $groupID, $Page->id(), $class);
                    $s .= '</li>';
                }
                
            }
            $s .= '</ol>';
            
            return $s;
        }
    ?>


<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>