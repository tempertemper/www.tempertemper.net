<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
<p><?php echo PerchLang::get('Give the navigation group a descriptive title.'); ?></p>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>


    <h1><?php 
        if (is_object($NavGroup)) {
            printf(PerchLang::get('Editing %s Navigation Group'), PerchUtil::html($NavGroup->groupTitle())); 
        }else{
            printf(PerchLang::get('Creating New Navigation Group')); 
        }
            
        ?></h1>

    
    <?php echo $Alert->output(); ?>

    <?php
    /* ----------------------------------------- SMART BAR ----------------------------------------- */
    ?>
    <ul class="smartbar">
        <?php if ($groupID) { ?><li><a href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/navigation/pages/?id='.$groupID); ?>"><?php echo PerchLang::get('Pages'); ?></a></li><?php } ?>
        <li class="selected"><a href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/navigation/edit/?id='.$groupID); ?>"><?php echo PerchLang::get('Group Options'); ?></a></li>
        <?php if ($groupID) { ?><li class="fin"><a class="icon reorder" href="<?php echo PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/navigation/reorder/?id='.$groupID); ?>"><?php echo PerchLang::get('Reorder Pages'); ?></a></li><?php } ?>
    </ul>
    <?php
    /* ----------------------------------------- /SMART BAR ----------------------------------------- */
    ?>




    <h2><?php echo PerchLang::get('Details'); ?></h2>
    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" class="sectioned">

        <div class="field">
            <?php echo $Form->label('groupTitle', 'Title'); ?>
            <?php echo $Form->text('groupTitle', $Form->get($details, 'groupTitle')); ?>
        </div>
        
        <p class="submit">
            <?php echo $Form->submit('btnsubmit', 'Submit', 'button'); ?>
        </p>
        
    </form>


<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>



