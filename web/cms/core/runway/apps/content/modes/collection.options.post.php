<?php

    echo $HTML->title_panel([
            'heading' => $Lang->get('Editing Collection Options'),
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
        'link'  => '/core/apps/content/manage/collections/?id='.$Collection->id(),
    ];

    $Smartbar->add_item([
            'active' => false,
            'type' => 'breadcrumb',
            'links' => $links,
        ]);
    
    // Options button
    $Smartbar->add_item([
            'active' => true,
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
            'active'   => false,
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
    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" class="form-simple">
        
        <h2 class="divider"><div><?php echo PerchLang::get('Editing'); ?></div></h2>
        
        <?php if (false && PERCH_RUNWAY) { ?>
        <div class="field-wrap checkbox-single">
            <?php echo $Form->label('collectionInAppMenu', 'Include in app menu'); ?>
            <div class="form-entry">
            <?php
                $tmp = array('collectionInAppMenu'=>$Collection->collectionInAppMenu());
                echo $Form->checkbox('collectionInAppMenu', '1', $Form->get($tmp, 'collectionInAppMenu', 1)); ?>
            </div>
        </div>
        <?php } // runway ?>

    
        <div class="field-wrap">
            <?php echo $Form->label('addToTop', 'New items are'); ?>
            <div class="form-entry">
            <?php
                $opts = array();
                $opts[] = array('label'=>PerchLang::get('Added to the top'), 'value'=>1);
                $opts[] = array('label'=>PerchLang::get('Added to the bottom'), 'value'=>0);
                echo $Form->select('addToTop', $opts, $Form->get($options, 'addToTop', 0));
            ?>
            </div>
        </div>

        <?php
            // Used by column_ids and sortField
            $Template = new PerchTemplate('content/'.$Collection->collectionTemplate(), 'content');
            $tags   = $Template->find_all_tags('(content|categories)');

        ?>

        <div class="field-wrap last">
            <?php echo $Form->label('column_ids', 'Item list columns'); ?>
            <div class="form-entry">
            <?php
                //echo $Form->text('column_ids', $Form->get($options, 'column_ids'), 'xl', false, false, 'data-display-as="tags"');
                //echo $Form->hint(PerchLang::get('Enter field IDs to list when editing in list and detail mode. Comma separated.'));

                $opts = [];

                $suggestions = array();
                $suggestions[] = '_title';

                $seen_tags = ['current_page', 'next_url', 'prev_url', 'number_of_pages'];
                if (PerchUtil::count($tags)) {
                    foreach($tags as $Tag) {
                        $tag_id = $Tag->id();
                        $label = $tag_id;
                        if ($Tag->output()) {
                            $tag_id .='['.$Tag->output().']';
                            if ($Tag->label()) { 
                                $label = $Tag->label() . ' ('.$Tag->output().')';
                            }
                        } else {
                            if ($Tag->label()) $label = $Tag->label();
                        }

                        if (!in_array($tag_id, $seen_tags) && $Tag->id()) {
                            $suggestions[] = $tag_id;
                            $seen_tags[] = $tag_id;

                            $opts[] = ['label'=>$label, 'value'=>$tag_id];
                        }
                    }
                    sort($suggestions);
                }

                echo $Form->select('column_ids', $opts, $Form->get($options, 'column_ids'), 'input-simple xl', true, 'data-display-as="tags"', true);
                //echo $Form->hint(PerchLang::get('Choose from: ').implode(', ', $suggestions));
            ?>
            </div>
        </div>

        
        <div class="field-wrap">
            <?php echo $Form->label('title_delimit', 'Join title fields with'); ?>
            <div class="form-entry">
            <?php
                echo $Form->text('title_delimit', $Form->get($options, 'title_delimit'), 's');
                echo $Form->hint(PerchLang::get('When more than one field is set as the item title, the titles will be concatenated using this value.'));
            ?>
            </div>
        </div>


        <h2 class="divider"><div><?php echo PerchLang::get('Display'); ?></div></h2>

        <div class="field-wrap">
            <?php echo $Form->label('sortField', 'Sort by'); ?>
            <div class="form-entry">
            <?php
                
                $seen_tags = array();
                $opts = array();
                $opts[] = array('label'=>PerchLang::get('Default order'), 'value'=>'');
                if (PerchUtil::count($tags)) {
                    foreach($tags as $Tag) {
                        if (!in_array($Tag->id(), $seen_tags) && $Tag->label() && $Tag->id()) {
                            $opts[] = array('label'=>$Tag->label(), 'value'=>$Tag->id());
                            $seen_tags[] = $Tag->id();
                        }
                        
                    }
                }
                echo $Form->select('sortField', $opts, $Form->get($options, 'sortField'));
            
            ?>
            </div>
        </div>
        
        <div class="field-wrap">
            <?php echo $Form->label('sortOrder', 'Sort order'); ?>
            <div class="form-entry">
            <?php
                $opts = array();
                $opts[] = array('label'=>PerchLang::get('Ascending (A-Z, oldest to newest)'), 'value'=>'ASC');
                $opts[] = array('label'=>PerchLang::get('Descending (Z-A, newest to oldest)'), 'value'=>'DESC');
                echo $Form->select('sortOrder', $opts, $Form->get($options, 'sortOrder'));
            ?>
            </div>
        </div>
    
        <h2 class="divider"><div><?php echo PerchLang::get('Search'); ?></div></h2>

        <div class="field-wrap checkbox-single">
            <?php echo $Form->label('collectionSearchable', 'Include in search results'); ?>
            <div class="form-entry">
            <?php
                $tmp = array('collectionSearchable'=>$Collection->collectionSearchable());
                echo $Form->checkbox('collectionSearchable', '1', $Form->get($tmp, 'collectionSearchable', 1)); ?>
            </div>
        </div>

        <div class="field-wrap">
            <?php echo $Form->label('searchURL', 'URL for single items'); ?>
            <div class="form-entry">
            <?php echo $Form->text('searchURL', $Form->get($options, 'searchURL', '')); ?>
            <?php echo $Form->hint(PerchLang::get('Used for search results and draft previews. If you need this, enter the root-relative URL using %sbraces%s around any dynamic fields. e.g.', '{', '}')); 
                echo '<code>'.$Form->hint(PerchLang::get('/news-article.php?s=%sslug%s', '{','}')).'</code>'; ?>
            </div>
        </div>

        
        <h2 class="divider"><div><?php echo PerchLang::get('Permissions'); ?></div></h2>

        
            <?php
                $opts = array();
                $opts[] = array('label'=>PerchLang::get('Everyone'), 'value'=>'*', 'class'=>'single');
                
                $vals = explode(',', $Collection->collectionEditRoles());

                if (PerchUtil::count($roles)) {
                    foreach($roles as $Role) {
                        $tmp = array('label'=>$Role->roleTitle(), 'value'=>$Role->id());

                        if ($Role->roleMasterAdmin()) {
                            $tmp['disabled'] = true;
                            $vals[] = $Role->id();
                        }

                        $opts[] = $tmp;
                    }
                }
                
                echo $Form->checkbox_set('edit_roles', 'May be edited by', $opts, $vals, $class='', $limit=false);
            

                $opts = array();
                $opts[] = array('label'=>PerchLang::get('Everyone'), 'value'=>'*', 'class'=>'single');
                
                $vals = explode(',', $Collection->collectionPublishRoles());

                if (PerchUtil::count($roles)) {
                    foreach($roles as $Role) {
                        $tmp = array('label'=>$Role->roleTitle(), 'value'=>$Role->id());

                        if ($Role->roleMasterAdmin()) {
                            $tmp['disabled'] = true;
                            $vals[] = $Role->id();
                        }

                        $opts[] = $tmp;
                    }
                }
                
                echo $Form->checkbox_set('publish_roles', 'Drafts may be published by', $opts, $vals, $class='', $limit=false);
            
            
            ?>
        
<?php   if ($CurrentUser->has_priv('content.regions.templates')) { ?>
        <h2 class="divider"><div><?php echo PerchLang::get('Template'); ?></div></h2>
        <?php 
            $Alert->set('warning', PerchLang::get('Changing the template can result in data loss if the fields in the templates are not the same.'));
            echo $Alert->output();
        ?>
        <div class="field-wrap">
            <?php echo $Form->label('collectionTemplate', 'Template'); ?>
            <div class="form-entry">
            <?php         
                echo $Form->grouped_select('collectionTemplate', $Regions->get_templates(false, true), $Form->get(array('collectionTemplate'=>$Collection->collectionTemplate()), 'collectionTemplate', 0));
                
            ?>
            </div>
        </div>

<?php
        }

        if ($CurrentUser->has_priv('content.regions.empty')){
?>
        <?php 
            $Alert->set('warning', PerchLang::get('This option deletes all content from this collection.'));
            echo $Alert->output();
        ?>
        <div class="field-wrap">
            <?php echo $Form->label('collectionReset', 'Delete all content from this collection immediately'); ?>
            <div class="form-entry">
            <?php         
                echo $Form->checkbox('collectionReset', 1, 0);
                
            ?>
            </div>
        </div>

<?php
        }
?>



        <div class="submit-bar">
            <div class="submit-bar-actions">
            <?php echo $Form->submit('btnsubmit', 'Save', 'button'), ' ', PerchLang::get('or'), ' <a href="',PERCH_LOGINPATH . '/core/apps/content/collections/edit/?id='.PerchUtil::html($id).'', '">', PerchLang::get('Cancel'), '</a>'; ?>
            </div>
        </div>
    </form>