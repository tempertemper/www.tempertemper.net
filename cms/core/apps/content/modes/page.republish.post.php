
<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
<p><?php echo PerchLang::get('Republishing rebuilds the cached copy of each page. It is useful if you have changed a template and need to refresh existing content to pick up the change.'); ?></p>
<p><?php echo PerchLang::get('Republishing a lot of content can be slow and consume server resources.'); ?></p>

<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>


<h1><?php echo PerchLang::get('Republishing Pages'); ?></h1>
    
    <?php echo $Alert->output(); ?>
    

    <?php
    /* ----------------------------------------- SMART BAR ----------------------------------------- */

        $filter = false;
    ?>

    <ul class="smartbar">
        <li><span class="set"><?php echo PerchLang::get('Filter'); ?></span></li>
        <li class="<?php echo ($filter=='all'?'selected':''); ?>"><a href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/'); ?>"><?php echo PerchLang::get('All'); ?></a></li>
        <li class="new <?php echo ($filter=='new'?'selected':''); ?>"><a href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/?filter=new'); ?>"><?php echo PerchLang::get('New'); ?></a></li>
        <?php

            if ($filter == 'new') {
                $Alert->set('filter', PerchLang::get('You are viewing pages with new regions.'). ' <a href="'.PERCH_LOGINPATH.'/core/apps/content/" class="action">'.PerchLang::get('Clear Filter').'</a>');
            }

            $templates = $Regions->get_templates_in_use();
            if (PerchUtil::count($templates)) {
                
                $items = array();
                foreach ($templates as $template) {
                    if ($template['regionTemplate']!='') {
                        $items[] = array(
                            'arg'=>'template',
                            'val'=>$template['regionTemplate'],
                            'label'=>$Regions->template_display_name($template['regionTemplate']),
                            'path'=>PERCH_LOGINPATH.'/core/apps/content/'
                        );
                    }
                }
                
                echo PerchUtil::smartbar_filter('rtf', 'By Region Type', 'Filtered by ‘%s’', $items, 'region', $Alert, "You are viewing pages filtered by region type ‘%s’", PERCH_LOGINPATH.'/core/apps/content/');
        
        } ?>

        <?php 
            if ($CurrentUser->has_priv('content.pages.reorder')) { 
        ?>
        <li class="fin"><a class="icon reorder" href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/reorder/'); ?>"><?php echo PerchLang::get('Reorder Pages'); ?></a></li>
        <?php
            }// reorder
        ?>

        <?php 
            if ($CurrentUser->has_priv('content.pages.republish')) { 
        ?>
        <li class="fin selected"><a class="icon page" href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/republish/'); ?>"><?php echo PerchLang::get('Republish'); ?></a></li>
        <?php
            }// republish
        ?>
    </ul>
    

     <?php echo $Alert->output(); ?>


    <?php
    /* ----------------------------------------- /SMART BAR ----------------------------------------- */
    ?>


<?php

    if ($republish) {
        echo '<ul class="importables">';
        $Regions->republish_all(true);
        echo '</ul>';
    }else{
?>
    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>">
    
        <p class="alert notice"><?php 
            printf(PerchLang::get('Are you sure you wish to republish all pages?')); ?>
        </p>    

        <p class="submit">
            <?php echo $Form->submit('btnsubmit', 'Republish', 'button'), ' ', PerchLang::get('or'), ' <a href="',PERCH_LOGINPATH . '/core/apps/content', '">', PerchLang::get('Cancel'), '</a>'; ?>
        </p>

    
    </form>

<?php } // republish ?>
<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>