<?php
    echo $HTML->title_panel([
        'heading' => sprintf(PerchLang::get('Editing %s Page'),' &#8216;' . PerchUtil::html($Page->pageNavText()) . '&#8217; ')
        ]);


       $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);
       $Smartbar->add_item([
            
            'title'  => 'Regions',
            'link'   => '/core/apps/content/page/?id='.$Page->id(),
            'icon'   => 'core/o-grid',
        ]);

       if ($Page->pagePath()!='*') {
            $Smartbar->add_item([
                'title'  => 'Details',
                'link'   => '/core/apps/content/page/details/?id='.$Page->id(),
                'priv'   => 'content.pages.attributes',
                'icon'   => 'core/o-toggles',
            ]);

            $Smartbar->add_item([
                'title'  => 'Location',
                'link'   => '/core/apps/content/page/url/?id='.$Page->id(),
                'priv'   => 'content.pages.manage_urls',
                'icon'   => 'core/o-signs',
            ]); 

            $Smartbar->add_item([
                'title'         => 'View Page',
                'link'          => rtrim($Settings->get('siteURL')->val(), '/').$Page->pagePath(),
                'icon'          => 'core/document',
                'position'      => 'end',
                'new-tab'       => true,
                'link-absolute' => true,
            ]); 

            $Smartbar->add_item([
                'active' => true,
                'title'    => 'Settings',
                'link'     => '/core/apps/content/page/edit/?id='.$Page->id(),
                'priv'     => 'content.pages.edit',
                'icon'     => 'core/gear',
                'position' => 'end',
            ]);             
       }
       

       echo $Smartbar->render();


?>    
    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" class="form-simple">

        <?php
            if (PERCH_RUNWAY) { 

                if (PerchUtil::count($collections)) {
                    echo '<h2 class="divider"><div>'.PerchLang::get('Collections').'</div></h2>';
                
                    $opts = array();
                    $vals = explode(',', $Page->pageCollections());

                    if (PerchUtil::count($collections)) {
                        foreach($collections as $Collection) {
                            $opts[] = array('label'=>$Collection->collectionKey(), 'value'=>$Collection->id());
                        }
                    }
                    
                    echo $Form->checkbox_set('collections', 'Manage from this page', $opts, $vals, $class='', $limit=false);
                                                
                }

            }

        ?>

        
        <h2 class="divider"><div><?php echo PerchLang::get('Details'); ?></div></h2>

        <?php
            if (PERCH_RUNWAY) {
                echo '<div class="field-wrap">';
                    echo $Form->label('templateID', 'Master page');
                    echo '<div class="form-entry">';
                    
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
                        $opts[PerchLang::get('General')][] = array('label'=>PerchLang::get('Local file'), 'value'=>'0');
                    }

                    echo $Form->grouped_select('templateID', $opts, $Form->get($details, 'templateID'));
                    
                    echo '</div>';
                echo '</div>';
            }
        ?>

        <div class="field-wrap">
            <?php echo $Form->label('pageAttributeTemplate', 'Attribute template'); ?>
            <div class="form-entry">
            <?php 
                $templates = $Regions->get_templates(PERCH_TEMPLATE_PATH.'/pages/attributes');
                $opts = array();

                if (PerchUtil::count($templates)) {
                    foreach($templates as $group_name=>$group) {
                        $tmp = array();
                        $group = PerchUtil::array_sort($group, 'label');
                        foreach($group as $file) {
                            $tmp[] = array('label'=>$file['label'], 'value'=>$file['path']);
                        }
                        $opts[$group_name] = $tmp;
                    }
                }
                        
                echo $Form->grouped_select('pageAttributeTemplate', $opts, $Form->get($details, 'pageAttributeTemplate')); 
            ?>
            </div>
        </div>


        <?php
            $members = $Perch->get_app('perch_members');
        ?>


        <div class="field-wrap  checkbox-single <?php echo $members ? '' : 'last' ?>">
            <?php echo $Form->label('pageHidden', 'Hide from main navigation'); ?>
            <div class="form-entry">
            <?php echo $Form->checkbox('pageHidden', '1', $Form->get($details, 'pageHidden')); ?>
            </div>
        </div>

        <?php
            if ($members) {
        ?>
        <div class="field-wrap last">
            <?php echo $Form->label('pageAccessTags', 'Restrict access to members with tags'); ?>
            <div class="form-entry">
            <?php echo $Form->tags('pageAccessTags', $Form->get($details, 'pageAccessTags')); ?>
            </div>
        </div>
<?php
        }


    if (PerchUtil::count($navgroups)) {
        echo '<h2 class="divider"><div>'.PerchLang::get('Navigation groups').'</div></h2>';

        $opts = array();
        
        $vals = $Page->get_navgroup_ids();

        if (!$vals) $vals = array();

        foreach($navgroups as $Group) {
            $opts[] = array('label'=>$Group->groupTitle(), 'value'=>$Group->id());
        }
    
        echo $Form->checkbox_set('navgroups', 'Page belongs to', $opts, $vals, $class='', $limit=false);

    }

?>
        
<?php
    if ($CurrentUser->has_priv('content.pages.configure')) {
?>        
        <h2 class="divider"><div><?php echo PerchLang::get('Subpages'); ?></div></h2>

        <?php
            $opts = array();
            $opts[] = array('label'=>PerchLang::get('Everyone'), 'value'=>'*', 'class'=>'single');
            
            $vals = explode(',', $Page->pageSubpageRoles());

            if (PerchUtil::count($roles)) {
                foreach($roles as $Role) {
                    $tmp = array('label'=>$Role->roleTitle(), 'value'=>$Role->id());

                    if ($Role->roleMasterAdmin()) {
                        $tmp['disabled'] = true;
                        //if (!in_array('*', $vals)) 
                            $vals[] = $Role->id();
                    }

                    $opts[] = $tmp;
                }
            }
            
            echo $Form->checkbox_set('subpage_roles', 'May be created by', $opts, $vals, $class='', $limit=false);
        
        
        ?>


        <?php if (PERCH_RUNWAY) { 
            $opts = array();
            
            $vals = explode(',', $Page->pageSubpageTemplates());
            if ($ParentPage) {  
                $templates = $PageTemplates->get_filtered($ParentPage->pageSubpageTemplates());
                if ($ParentPage->pageSubpageTemplates()=='' || $ParentPage->pageSubpageTemplates()=='*') {
                    $opts[] = array('label'=>PerchLang::get('All'), 'value'=>'*', 'class'=>'single');    
                }
            }else{
                $templates = $PageTemplates->get_filtered();    
                $opts[] = array('label'=>PerchLang::get('All'), 'value'=>'*', 'class'=>'single');
            }
            
            if (PerchUtil::count($templates)) {
                foreach($templates as $Template) {
                    $tmp = array('label'=>PerchUtil::filename($Template->display_name()), 'value'=>$Template->id());
                    $opts[] = $tmp;
                }
            }
            
            echo $Form->checkbox_set('subpage_templates', 'May use these master pages', $opts, $vals, $class='', $limit=false);
        
     } // runway ?>

        
        <?php if (!PERCH_RUNWAY) { ?>
        <div class="field-wrap last">
            <?php echo $Form->label('pageSubpagePath', 'Subpage folder'); ?>
            <div class="form-entry">
            <?php 
                
            
                echo $Form->text('pageSubpagePath', $Form->get($details, 'pageSubpagePath')); 
            ?>
            </div>
        </div>
        <?php } // Runway ?>
<?php
    } // content.pages.configure



    echo $HTML->submit_bar([
        'button' =>$Form->submit('btnsubmit', 'Submit')
        ]);
?>      
    </form>

    <?php
        if ($created!==false) {
            echo '<img src="'.PerchUtil::html($Page->pagePath()).'" width="1" height="1" class="off-screen">';
        }
