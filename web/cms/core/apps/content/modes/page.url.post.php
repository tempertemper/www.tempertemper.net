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
                'active' => true,
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

        <h2 class="divider"><div><?php echo PerchLang::get('Location'); ?></div></h2>

        <div class="field-wrap checkboxes labelless">
            <?php echo $Form->label('pagePath', 'Path'); ?>
            <div class="form-entry">
            <?php echo $Form->text('pagePath', $Form->get($details, 'pagePath')); ?>
            </div>

            <?php if (!PERCH_RUNWAY) { ?>
            <div class="checkbox supplemental">
                <?php echo $Form->checkbox('move', '1', 0); ?>
                <?php echo $Form->label('move', 'Move the page to this location'); ?>
            </div>
            <?php } // not Runway ?>
        </div>

        <div class="field-wrap">
            <?php echo $Form->label('pageParentID', 'Parent page'); ?>
            <div class="form-entry">
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
        </div>
        <?php
            if (PERCH_RUNWAY) { 

                echo '<h2 class="divider"><div>'.PerchLang::get('Routes').'</div></h2>';
         

                if (PerchUtil::count($routes)) {

                    foreach($routes as $Route) {

                        echo '<div class="field-wrap routes">';
                            $id = 'routePattern_'.$Route->id();
                            echo $Form->label($id, 'URL pattern');
                            echo '<div class="form-entry">';
                            echo $Form->text($id, $Form->get($details, $id, $Route->routePattern()));
                            echo '</div>';
                        echo '</div>';
                    }
                }

                echo '<div class="field-wrap routes-spare">';
                    echo $Form->label('new_pattern', 'URL pattern');
                    echo '<div class="form-entry">';
                    echo $Form->text('new_pattern', $Form->get($details, 'new_pattern'));
                    echo '</div>';
                echo '</div>';


            }


    echo $HTML->submit_bar([
        'button' =>$Form->submit('btnsubmit', 'Submit')
        ]);
?>      
    </form>

    <?php
        if ($created!==false) {
            echo '<img src="'.PerchUtil::html($Page->pagePath()).'" width="1" height="1" class="off-screen">';
        }
