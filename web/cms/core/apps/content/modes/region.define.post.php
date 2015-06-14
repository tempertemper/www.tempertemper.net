

<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
<p><?php echo PerchLang::get('Please choose a template for the content you wish to add to this region.'); ?></p>
<p><?php echo PerchLang::get('If you would like to have multiple items of content in this region, select the <em>Allow multiple items</em> option.'); ?></p>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>

    <h1><?php 
            printf(PerchLang::get('Editing %s Region'),' &#8216;' . PerchUtil::html($Region->regionKey()) . '&#8217; '); 
        ?></h1>
    
    <?php echo $Alert->output(); ?>


	<h2><?php echo PerchLang::get('Choose a template'); ?></h2>

    <form method="post" action="<?php echo PerchUtil::html($fTemplate->action()); ?>">
			
        
            <div class="field">
                <?php echo $fTemplate->label('regionTemplate', 'Template'); ?>
                <?php         
                    echo $fTemplate->grouped_select('regionTemplate', $Regions->get_templates(), $fTemplate->get('contentTemplate', false));                    
                ?>
            </div>
    
            <div class="field">
                <?php echo $fTemplate->label('regionMultiple', 'Allow multiple items'); ?>
                <?php echo $fTemplate->checkbox('regionMultiple', '1', '0'); ?>
            </div>
    

        <p class="submit">
            <?php echo $fTemplate->submit('btnsubmit', 'Submit', 'button'); ?>
        </p>
        
    </form>


<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>
