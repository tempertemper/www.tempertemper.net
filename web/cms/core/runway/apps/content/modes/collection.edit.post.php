<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>

<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ($app_path.'/modes/_subnav.php'); ?>

    <h1><?php echo PerchLang::get('Creating a New Collection'); ?></h1>
    
    <?php echo $Alert->output(); ?>

    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" class="magnetic-save-bar">
        
        <h2><?php echo PerchLang::get('Collection'); ?></h2>
        
        
        <div class="field">
                <?php echo $Form->label('collectionKey', 'Collection Key'); ?>
                <?php
                    echo $Form->text('collectionKey', $Form->get($details, 'collectionKey'), 'm');
                    echo $Form->hint(PerchLang::get('Examples: Articles, Events, Locations, Departments'));
                ?>
        </div>

        <div class="field">
            <?php echo $Form->label('collectionTemplate', 'Template'); ?>
            <?php         
                echo $Form->grouped_select('collectionTemplate', $Regions->get_templates(false, true), $Form->get(array('collectionTemplate'=>''), 'collectionTemplate', 0));               
            ?>
        </div>

        <p class="submit">
            <?php echo $Form->submit('btnsubmit', 'Save', 'button'), ' ', PerchLang::get('or'), ' <a href="',PERCH_LOGINPATH . '/core/apps/content/manage/collections/">', PerchLang::get('Cancel'), '</a>'; ?>
        </p>
    </form>
<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>
