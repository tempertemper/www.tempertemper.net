<?php
    echo $HTML->title_panel([
        'heading' => $Lang->get('Add a new page'),
        ]);
?>

    <h2 class="divider"><div><?php echo PerchLang::get('Page details'); ?></div></h2>
    
    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" class="form-simple">


        <div class="field-wrap">
        	<?php echo $Form->label('pageTitle', 'Page title'); ?>
            <div class="form-entry">
        	<?php echo $Form->text('pageTitle', $Form->get($details, 'pageTitle'), null, false, false, ' data-urlify="file_name" data-copy="pageNavText" '); ?>
            </div>
        </div>

        <div class="field-wrap">
        	<?php echo $Form->label('pageNavText', 'Navigation text'); ?>
            <div class="form-entry">
        	<?php echo $Form->text('pageNavText', $Form->get($details, 'pageNavText')); ?>
            </div>
        </div>

        <div class="field-wrap">
        <?php if (PERCH_RUNWAY) { ?>
        
            <?php echo $Form->label('file_name', 'URL segment'); ?>
            <div class="form-entry">
            <?php echo $Form->text('file_name', $Form->get($details, 'file_name')); ?>
            </div>
        
        <?php  } else { ?>
        
            <?php echo $Form->label('file_name', 'File name'); ?>
            <div class="form-entry">
            <?php echo $Form->text('file_name', $Form->get($details, 'file_name')); ?>
            <?php echo $Form->hint('The file extension will be added automatically. Can be a full URL to create just a link.'); ?>
            </div>
        <?php } // runway ?>
        </div>
        
        <div class="field-wrap">
            <?php echo $Form->label('pageParentID', 'Parent page'); ?>
            <div class="form-entry">
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
        </div>

        <div class="field-wrap">
            <?php echo $Form->label('templateID', 'Master page'); ?>
            <div class="form-entry">
            <?php
                if (PERCH_RUNWAY) {
                                        
                    $opts = array();
                    if ($ParentPage) {
                        $templates = $PageTemplates->get_templates($ParentPage->pageSubpageTemplates());
                    }else{
                        $templates = $PageTemplates->get_templates();
                    }
                    

                    if (PerchUtil::count($templates)) {
                        $opts = array();

                        foreach($templates as $group_name=>$group) {
                            $tmp = array();
                            $group = PerchUtil::array_sort($group, 'label');
                            foreach($group as $file) {
                                $tmp[] = array('label'=>$file['label'], 'value'=>$file['id']);
                            }
                            $opts[$group_name] = $tmp;
                        }
                        //$opts[PerchLang::get('General')][] = array('label'=>PerchLang::get('Local file'), 'value'=>'');

                        echo $Form->grouped_select('templateID', $opts, $Form->get($details, 'templateID'));
                    }else{
                        echo '<a href="'.PERCH_LOGINPATH.'/core/apps/content/page/templates/">'.PerchLang::get('Manage templates').'</a>';
                    }
               
                    

                }else{

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

                }
                  





            ?>
            </div>
        </div>
        <?php if (!PERCH_RUNWAY) { ?>
        <div class="field-wrap">
            <?php echo $Form->checkbox('create_folder', '1', $Form->get($details, 'create_folder')); ?>
            <div class="form-entry">
            <?php echo $Form->label('create_folder', 'This page will have more pages below it'); ?>
            </div>
        </div>
        <?php } ?>
        
        <?php
            echo $HTML->submit_bar([
                'button' => $Form->submit('btnsubmit', 'Submit', 'button'),
                'cancel_link' => '/core/apps/content'

                ]);

        ?>
        
    </form>
