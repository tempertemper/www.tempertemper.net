<?php 

    echo $HTML->title_panel([
        'heading' => $Lang->get('Importing into Collection'),
        ]);


    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    // Breadcrumb
    $links = [];

    $links[] = [
        'title' => 'Collections',
        'link'  => '/core/apps/content/manage/collections/',
    ];

    $links[] = [
        'title' => $Collection->collectionKey(),
        'translate' => false,
        'link'  => '/core/apps/content/collections/?id='.$Collection->id(),
    ];

    $Smartbar->add_item([
            'active' => false,
            'type' => 'breadcrumb',
            'links' => $links,
        ]);
    
    // Options button
    $Smartbar->add_item([
            'active' => false,
            'title'  => 'Options',
            'link'   => '/core/apps/content/collections/options/?id='.$Collection->id(),
            'priv'   => 'content.collections.options',
            'icon'   => 'core/o-toggles',
        ]);


    // Revision history
    /*
    $Smartbar->add_item([
        'active' => false,
        'title'  => 'Revision History',
        'link'   => '/core/apps/content/collections/revisions/?id='.$Collection->id(),
        'priv'   => 'content.regions.options',
        'icon'   => 'core/o-backup',
        'position' => 'end',
    ]);
    */


    // Import button
    $Smartbar->add_item([
            'active'   => true,
            'title'    => 'Import',
            'link'     => '/core/apps/content/collections/import/?id='.$Collection->id(),
            'position' => 'end',
            'icon'     => 'core/inbox-download',
        ]);


    // Reorder button    
    $Smartbar->add_item([
            'active'   => false,
            'title'    => 'Reorder',
            'link'     => '/core/apps/content/reorder/collection/?id='.$Collection->id(),
            'position' => 'end',
            'icon'     => 'core/menu',
        ]);




    echo $Smartbar->render();

 ?>     
    <form method="post" action="<?php echo PerchUtil::html($Form->action(), true); ?>" class="form-simple">
        
        <h2 class="divider"><div><?php echo PerchLang::get('Import'); ?></div></h2>
        

        <div class="field-wrap">
            <?php echo $Form->label('pageID', 'Page'); ?>
            <?php 
                $opts = array();
        
                $pages = $Pages->get_page_tree();
                if (PerchUtil::count($pages)) {
                    foreach($pages as $Item) {
                        $opts[] = array('label'=>str_repeat('-', ($Item->pageDepth()-1)).' '.$Item->pageNavText(), 'value'=>$Item->id());
                    }
                }
                    
                echo $Form->select('pageID', $opts, $Form->get($details, 'pageID')); 
            ?>
        </div>

        <?php if ($pageID) { ?>
        <div class="field-wrap">
            <?php echo $Form->label('regionID', 'Region'); ?>
            <?php 
                $opts = array();
        
                $regions = $Regions->get_for_page($pageID, false);
                if (PerchUtil::count($regions)) {
                    foreach($regions as $RegionX) {
                        $opts[] = array('label'=>$RegionX->regionKey(), 'value'=>$RegionX->id());
                    }
                }
                    
                echo $Form->select('regionID', $opts, $Form->get($details, 'regionID')); 
            ?>
        </div>
        <?php } // if pageID ?>

        <?php

        if ($pageID && $regionID) {

            echo $HTML->heading2('About the source');

            echo '<div class="instructions">';
            echo $HTML->para('The source region %s uses template %s with the these fields:', $Region->regionKey(), '<code>'.$Region->regionTemplate().'</code>');

            $Template = new PerchTemplate('content/'.$Region->regionTemplate(), 'content');
            echo '<p><code>'.implode(', ', $Template->find_all_tag_ids()).'</code></p>';
            echo '</div>';

            echo $HTML->heading2('About the target');

            echo '<div class="instructions">';
            echo $HTML->para('The target collection %s uses template %s with the these fields:', $Collection->collectionKey(), '<code>'.$Collection->collectionTemplate().'</code>');

            $Template = new PerchTemplate('content/'.$Collection->collectionTemplate(), 'content');
            echo '<p><code>'.implode(', ', $Template->find_all_tag_ids()).'</code></p>';

            echo '</div>';

            echo $HTML->warning_message('If you are happy that the source and the target have compatible data structures, click Import to proceed.');
          

            echo $Form->hidden('go', 'go');

        }


        ?>


        <div class="submit-bar">
            <div class="submit-bar-actions">
            <?php 
                $label = 'Next';

                if ($pageID && $regionID) {
                    $label = 'Import';
                }
                

                echo $Form->submit('btnsubmit', $label, 'button'), ' ', PerchLang::get('or'), ' <a href="',PERCH_LOGINPATH . '/core/apps/content/collections/edit/?id='.PerchUtil::html($id).'', '">', PerchLang::get('Cancel'), '</a>'; 

            ?>
            </div>
        </div>
    </form>