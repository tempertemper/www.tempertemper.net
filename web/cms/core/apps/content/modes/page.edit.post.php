<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>

    <p>
        <?php echo PerchLang::get('These are the options for this page.'); ?>
    </p>

    <p>
        <?php echo PerchLang::get('If the link for the page is incorrect, it can be fixed using the Path option. You shouldn\'t change that unless you are sure what you\'re doing. An incorrect value could break links in your navigation. Changing this value doesn\'t move the page itself.'); ?>
        <?php echo PerchLang::get('Do do that, you need to check the box to move the page to the newly entered location.'); ?>
    </p>

    <p>
        <?php echo PerchLang::get('If you want to hide the page from navigation, just check the checkbox. This will also hide any pages below this one when displaying a navigation tree.'); ?>
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
                echo '<li><a href="'.PERCH_LOGINPATH . '/core/apps/content/page/details/?id='.PerchUtil::html($Page->id()).'">' . PerchLang::get('Page Details') . '</a></li>';
            }
        ?>
		<?php
			if ($CurrentUser->has_priv('content.pages.edit')) {
	            echo '<li class="fin selected"><a href="'.PERCH_LOGINPATH . '/core/apps/content/page/edit/?id='.PerchUtil::html($Page->id()).'" class="icon setting">' . PerchLang::get('Page Options') . '</a></li>';
	        }

            if ($Page->pagePath() != '*') {
                $view_page_url = rtrim($Settings->get('siteURL')->val(), '/').$Page->pagePath();

                echo '<li class="fin">';
                echo '<a href="'.PerchUtil::html($view_page_url).'" class="icon page assist">' . PerchLang::get('View Page') . '</a>';
                echo '</li>';
            }
		?>
    </ul>
    
    <?php
        if ($details['pageSubpagePath']==''){
            $details['pageSubpagePath'] = PerchUtil::strip_file_name($Page->pagePath());
        }
        
        $Pages->find_site_path();
        if (!PERCH_RUNWAY && !file_exists(PerchUtil::file_path(PERCH_SITEPATH.$details['pageSubpagePath']))) {
            $Alert->set('error', PerchLang::get('Subpage folder does not exist'));
            $Alert->output();
            PerchUtil::debug(PerchUtil::file_path(PERCH_SITEPATH.$details['pageSubpagePath']));
        }
    ?>

    
    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" class="magnetic-save-bar">

        <h2><?php echo PerchLang::get('Location'); ?></h2>

        <div class="field checkboxes labelless">
            <?php echo $Form->label('pagePath', 'Path'); ?>
            <?php echo $Form->text('pagePath', $Form->get($details, 'pagePath')); ?>

            <?php if (!PERCH_RUNWAY) { ?>
            <div class="checkbox supplemental">
                <?php echo $Form->checkbox('move', '1', 0); ?>
                <?php echo $Form->label('move', 'Move the page to this location'); ?>
            </div>
            <?php } // not Runway ?>
        </div>

        <div class="field">
            <?php echo $Form->label('pageParentID', 'Parent page'); ?>
            <?php 
                $opts = array();
                $opts[] = array('label'=>PerchLang::get('Top level'), 'value'=>0);
                $pages = $Pages->get_page_tree();

                if (PerchUtil::count($pages)) {
                    foreach($pages as $Item) {
                        if ($Item->role_may_create_subpages($CurrentUser)) {
                            $disabled = false;

                            if ($Item->id()==$Page->id()) $disabled=true;
                            
                        }else{
                            $disabled = true;
                        }
                        
                        $depth = $Item->pageDepth()-1;
                        if ($depth < 0 ) $depth = 0;
                        
                        $opts[] = array('label'=>str_repeat('-', $depth).' '.$Item->pageNavText(), 'value'=>$Item->id(), 'disabled'=>$disabled);
                    }
                }
                
            
                echo $Form->select('pageParentID', $opts, $Form->get($details, 'pageParentID')); 
            ?>
        </div>
        <?php
            if (PERCH_RUNWAY) { 

                echo '<h2>'.PerchLang::get('Routes').'</h2>';
         

                if (PerchUtil::count($routes)) {

                    foreach($routes as $Route) {

                        echo '<div class="field routes">';
                            $id = 'routePattern_'.$Route->id();
                            echo $Form->label($id, 'URL pattern');
                            echo $Form->text($id, $Form->get($details, $id, $Route->routePattern()));
                        echo '</div>';
                    }
                }

                echo '<div class="field routes-spare">';
                    echo $Form->label('new_pattern', 'URL pattern');
                    echo $Form->text('new_pattern', $Form->get($details, 'new_pattern'));
                echo '</div>';

       

                if (PerchUtil::count($collections)) {
                    echo '<h2>'.PerchLang::get('Collections').'</h2>';

                    echo '<div class="field">';
                        
                            $opts = array();
                            $vals = explode(',', $Page->pageCollections());

                            if (PerchUtil::count($collections)) {
                                foreach($collections as $Collection) {
                                    $opts[] = array('label'=>$Collection->collectionKey(), 'value'=>$Collection->id());
                                }
                            }
                            
                            echo $Form->checkbox_set('collections', 'Manage from this page', $opts, $vals, $class='', $limit=false);
                        
                        
                        
                    echo '</div>';
                }

            }

        ?>

        
        <h2><?php echo PerchLang::get('Details'); ?></h2>

        <?php
            if (PERCH_RUNWAY) {
                echo '<div class="field">';
                    echo $Form->label('templateID', 'Master page');
                    
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
            }
        ?>

        <div class="field">
            <?php echo $Form->label('pageAttributeTemplate', 'Attribute template'); ?>
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


        <?php
            $members = $Perch->get_app('perch_members');
        ?>


        <div class="field <?php echo $members ? '' : 'last' ?>">
            <?php echo $Form->label('pageHidden', 'Hide from main navigation'); ?>
            <?php echo $Form->checkbox('pageHidden', '1', $Form->get($details, 'pageHidden')); ?>
        </div>

        <?php
            if ($members) {
        ?>
        <div class="field last">
            <?php echo $Form->label('pageAccessTags', 'Restrict access to members with tags'); ?>
            <?php echo $Form->tags('pageAccessTags', $Form->get($details, 'pageAccessTags')); ?>
        </div>
<?php
        }


    if (PerchUtil::count($navgroups)) {
        echo '<h2>'.PerchLang::get('Navigation groups').'</h2>';

        echo '<div class="field last">';

            $opts = array();
            
            $vals = $Page->get_navgroup_ids();

            if (!$vals) $vals = array();

            foreach($navgroups as $Group) {
                $opts[] = array('label'=>$Group->groupTitle(), 'value'=>$Group->id());
            }
        
            
            echo $Form->checkbox_set('navgroups', 'Page belongs to', $opts, $vals, $class='', $limit=false);
        
        echo '</div>';

    }

?>
        
<?php
    if ($CurrentUser->has_priv('content.pages.configure')) {
?>        
        <h2><?php echo PerchLang::get('Subpages'); ?></h2>

        <div class="field">
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
        </div>

        <?php if (PERCH_RUNWAY) { ?>

        <div class="field">
            <?php
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
            
            
            ?>
        </div>

        <?php } // runway ?>

        
        <?php if (!PERCH_RUNWAY) { ?>
        <div class="field last">
            <?php echo $Form->label('pageSubpagePath', 'Subpage folder'); ?>
            <?php 
                
            
                echo $Form->text('pageSubpagePath', $Form->get($details, 'pageSubpagePath')); 
            ?>
        </div>
        <?php } // Runway ?>
<?php
    } // content.pages.configure
?>

        <p class="submit">
            <?php echo $Form->submit('btnsubmit', 'Submit', 'button'); ?>
        </p>
        
    </form>

    <?php
        if ($created!==false) {
            echo '<img src="'.PerchUtil::html($Page->pagePath()).'" width="1" height="1" />';
        }
    ?>
<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>
