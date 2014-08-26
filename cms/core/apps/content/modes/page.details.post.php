<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>

    <p>
        <?php echo PerchLang::get('These are the details for this page. Each page has a title, and navigation text which can be different from the title. The navigation text is used in menus and is often shorter than the main page title. '); ?>
    </p>

<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>

	    <h1><?php 
	            printf(PerchLang::get('Editing %s Page'),' &#8216;' . PerchUtil::html($Page->pageNavText()) . '&#8217; '); 
	        ?></h1>

    <?php echo $Alert->output(); ?>

	<ul class="smartbar">
        <li><a href="<?php echo PERCH_LOGINPATH . '/core/apps/content/page/?id='.PerchUtil::html($Page->id());?>"><?php echo PerchLang::get('Regions'); ?></a></li>
        <?php
            if ($CurrentUser->has_priv('content.pages.attributes')) {
                echo '<li class="selected"><a href="'.PERCH_LOGINPATH . '/core/apps/content/page/details/?id='.PerchUtil::html($Page->id()).'">' . PerchLang::get('Page Details') . '</a></li>';
            }
        ?>
        <?php
			if ($CurrentUser->has_priv('content.pages.edit')) {
	            echo '<li class="fin"><a href="'.PERCH_LOGINPATH . '/core/apps/content/page/edit/?id='.PerchUtil::html($Page->id()).'" class="icon setting">' . PerchLang::get('Page Options') . '</a></li>';
	        }

            if ($Page->pagePath() != '*') {
                $view_page_url = rtrim($Settings->get('siteURL')->val(), '/').$Page->pagePath();

                echo '<li class="fin">';
                echo '<a href="'.PerchUtil::html($view_page_url).'" class="icon page assist">' . PerchLang::get('View Page') . '</a>';
                echo '</li>';
            }
		?>
    </ul>
    
    <h2><?php echo PerchLang::get('Details'); ?></h2>
    <?php echo $Form->form_start('editattr'); ?>

        <div class="field">
        	<?php echo $Form->label('pageTitle', 'Page title'); ?>
        	<?php echo $Form->text('pageTitle', $Form->get($details, 'pageTitle')); ?>
        </div>

        <div class="field">
            <?php echo $Form->label('pageNavText', 'Navigation text'); ?>
            <?php echo $Form->text('pageNavText', $Form->get($details, 'pageNavText')); ?>
        </div>

        <?php
            echo $Form->fields_from_template($Template, $details, array('pageTitle', 'pageNavText'));
        ?>

        <p class="submit">
            <?php echo $Form->submit('btnsubmit', 'Submit', 'button'); ?>
        </p>
        
    <?php echo $Form->form_end(); ?>

<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>