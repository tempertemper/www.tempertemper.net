<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>

    <p>
        <?php echo PerchLang::get('These are the options for this page. Each page has a title, and navigation text which can be different from the title. The navigation text is used in menus and is often shorter than the main page title. '); ?>
    </p>
    <p>
        <?php echo PerchLang::get('If creating a page from a Master Page, the file extension (e.g. .php) will be added automatically based on the Master Page.'); ?>
    </p>
    <p>
        <?php echo PerchLang::get('If adding a link or an existing page, enter the full path.'); ?>
    </p>

    <h3><?php echo PerchLang::get('Creating pages in a folder'); ?></h3>

    <p>
        <?php echo PerchLang::get('If you check the box to indicate that the page will have more pages below it, a new folder will be created rather than just a single file.'); ?>
    </p>


<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>
    


    <h1><?php 
            echo PerchLang::get('Add a New Page'); 
        ?></h1>


    <?php echo $Alert->output(); ?>

    <h2><?php echo PerchLang::get('Page details'); ?></h2>
    
    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" class="sectioned">


        <div class="field">
        	<?php echo $Form->label('pageTitle', 'Page title'); ?>
        	<?php echo $Form->text('pageTitle', $Form->get($details, 'pageTitle'), false, false, 'text', ' data-urlify="file_name" data-copy="pageNavText" '); ?>
        </div>

        <div class="field">
        	<?php echo $Form->label('pageNavText', 'Navigation text'); ?>
        	<?php echo $Form->text('pageNavText', $Form->get($details, 'pageNavText')); ?>
        </div>

        <div class="field">
            <?php echo $Form->label('file_name', 'File name'); ?>
            <?php echo $Form->text('file_name', $Form->get($details, 'file_name')); ?>
            <?php echo $Form->hint('The file extension will be added automatically. Can be a full URL to create just a link.'); ?>
        </div>

        <div class="field">
            <?php echo $Form->label('pageParentID', 'Parent page'); ?>
            <?php 
                $opts = array();
                $opts[] = array('label'=>PerchLang::get('Top level'), 'value'=>'0', 'disabled'=>!$CurrentUser->has_priv('content.pages.create.toplevel'));
                $pages = $Pages->get_page_tree();
                if (PerchUtil::count($pages)) {
                    foreach($pages as $Item) {
                        if ($Item->role_may_create_subpages($CurrentUser)) {
                            $disabled = false;
                            
                        }else{
                            $disabled = true;
                        }
                        
                        $depth = $Item->pageDepth()-1;
                        if ($depth < 0 ) $depth = 0;
                        
                        $opts[] = array('label'=>str_repeat('-', $depth).' '.$Item->pageNavText(), 'value'=>$Item->id(), 'disabled'=>$disabled);
                    }
                }
            
                echo $Form->select('pageParentID', $opts, $Form->get($details, 'pageParentID', $parentID)); 
            ?>
        </div>

        <div class="field">
            <?php echo $Form->label('templateID', 'Master page'); ?>
            <?php
                $templates = $Templates->all();
                $opts = array();
                if (PerchUtil::count($templates)) {
                    foreach($templates as $Template) {
                        $opts[] = array('label'=>$Template->templateTitle(), 'value'=>$Template->id());
                    }
                    $opts[] = array('label'=>PerchLang::get('Page already exists, or is a link only'), 'value'=>'');
                    echo $Form->select('templateID', $opts, $Form->get($details, 'templateID')); 
                }else{
                    echo '<a href="'.PERCH_LOGINPATH.'/core/apps/content/page/templates/">'.PerchLang::get('Manage templates').'</a>';
                }             
            ?>
        </div>

        <div class="field checkboxes labelless">
            <div class="checkbox">
                <?php echo $Form->checkbox('create_folder', '1', $Form->get($details, 'create_folder')); ?>
                <?php echo $Form->label('create_folder', 'This page will have more pages below it'); ?>
            </div>
        </div>
        

        <p class="submit">
            <?php echo $Form->submit('btnsubmit', 'Submit', 'button'); ?>
        </p>
        
    </form>


<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>
