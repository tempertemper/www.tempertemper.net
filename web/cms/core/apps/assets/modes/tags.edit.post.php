<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
<p><?php echo PerchLang::get('If a tag contains a typo, you can correct it here.'); ?></p>

<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>

    <h1><?php 
            printf(PerchLang::get('Editing Tag %s'), PerchUtil::html($Tag->tagTitle())); 
        ?></h1>

    
    <?php echo $Alert->output(); ?>

    <h2><?php echo PerchLang::get('Details'); ?></h2>
    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" class="sectioned">

        <div class="field">
            <?php echo $Form->label('tagTitle', 'Display title'); ?>
            <?php echo $Form->text('tagTitle', $Form->get($details, 'tagTitle')); ?>
        </div>
        
        <div class="field">
            <?php echo $Form->label('tagSlug', 'Tag'); ?>
            <?php echo $Form->text('tagSlug', $Form->get($details, 'tagSlug')); ?>
        </div>
        <p class="submit">
            <?php echo $Form->submit('btnsubmit', 'Submit', 'button'); ?>
        </p>
        
    </form>


<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>