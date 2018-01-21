<?php

    if ($Region->regionTemplate() != '') {
        $view_page_url = false;

        if ($Region->regionPage() != '*') {

            if ($Region->get_option('edit_mode')=='listdetail' && $Region->get_option('searchURL')!='') {
                $search_url = $Region->get_option('searchURL');  

                $details    = $Region->get_items_for_editing();
                $Region->tmp_url_vars = $details[0];             
                $search_url = preg_replace_callback('/{([A-Za-z0-9_\-]+)}/', array($Region, 'substitute_url_vars'), $search_url);
                $Region->tmp_url_vars = false; 

                $view_page_url = rtrim($Settings->get('siteURL')->val(), '/').$search_url;
            }else{
                $view_page_url = rtrim($Settings->get('siteURL')->val(), '/').$Region->regionPage();
            }
        }

    } 

    echo $HTML->title_panel([
        'heading' => PerchLang::get('Editing Region Options'),
        ]);


    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    // Breadcrumb
    $links = [];
    if ($Region->regionPage()=='*') {
        $links[] = [
            'title' => 'Regions',
            'link'  => '/core/apps/content/page/?id=-1',
        ];
    }else{
        $links[] = [
            'title' => 'Regions',
            'link'  => '/core/apps/content/page/?id='.$Region->pageID(),
        ];
    }

    if ($Region->regionMultiple() && $Region->get_option('edit_mode')=='listdetail') {
        $links[] = [
            'title' => $Region->regionKey(),
            'translate' => false,
            'link'  => '/core/apps/content/edit/?id='.$Region->id(),
        ];

    } else {
        $links[] = [
            'title' => $Region->regionKey(),
            'translate' => false,
            'link'  => '/core/apps/content/edit/?id='.$Region->id(),
        ];
    }

    $Smartbar->add_item([
            'active' => false,
            'type' => 'breadcrumb',
            'links' => $links,
        ]);
    
    // Region Options buttons
    $Smartbar->add_item([
            'active' => true,
            'title'  => 'Region Options',
            'link'   => '/core/apps/content/options/?id='.$Region->id(),
            'priv'   => 'content.regions.options',
            'icon'   => 'core/o-toggles',
        ]);

    // View Page button
    if (isset($view_page_url) && $view_page_url) {
        $Smartbar->add_item([
                'active'        => false,
                'title'         => 'View Page',
                'link'          => $view_page_url,
                'link-absolute' => true,
                'position'      => 'end',
                'icon'          => 'core/o-world',
            ]);
    }

    // Reorder button
    if ($Region->regionMultiple()) {
        $Smartbar->add_item([
                'active'   => false,
                'title'    => 'Reorder',
                'link'     => '/core/apps/content/reorder/region/?id='.$Region->id(),
                'position' => 'end',
                'icon'     => 'core/menu',
            ]);
    } 


    echo $Smartbar->render();

?>
        
    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" class="form-simple">
        
        <h2 class="divider"><div><?php echo PerchLang::get('Editing'); ?></div></h2>
        
        <div class="field-wrap checkbox-single">
            <?php echo $Form->label('contentShared', 'Share across all pages'); ?>
            <div class="form-entry">
            <?php
                if ($Region->regionPage() == '*') {
                    $tmp = array('contentShared'=>'1');
                }else{
                    $tmp = array('contentShared'=>'0');
                }
                echo $Form->checkbox('contentShared', '1', $Form->get($tmp, 'contentShared', 0)); 
            ?>
            </div>
        </div>
        


        <div class="field-wrap checkbox-single">
            <?php echo $Form->label('regionMultiple', 'Allow multiple items'); ?>
            <div class="form-entry">
            <?php echo $Form->checkbox('regionMultiple', '1', $Form->get(array('regionMultiple'=>$Region->regionMultiple()), 'regionMultiple', 0)); ?>
            </div>
        </div>

    <?php if ($Region->regionMultiple()) { ?>
        
        <div class="field-wrap checkbox-single">
            <?php echo $Form->label('edit_mode', 'Edit all on one page'); ?>
            <div class="form-entry">
            <?php echo $Form->checkbox('edit_mode', 'singlepage', $Form->get($options, 'edit_mode', 'singlepage')); ?>
            </div>
        </div>
    
    
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
            $Template = new PerchTemplate('content/'.$Region->regionTemplate(), 'content');
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

        <?php } ?>
        
        <div class="field-wrap">
                <?php echo $Form->label('title_delimit', 'Join title fields with'); ?>
                <div class="form-entry">
                <?php
                    echo $Form->text('title_delimit', $Form->get($options, 'title_delimit'), 's input-simple');
                    echo $Form->hint(PerchLang::get('When more than one field is set as the item title, the titles will be concatenated using this value.'));
                ?>
                </div>
        </div>

        <?php if ($Region->regionMultiple()) { ?>

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


        <div class="field-wrap last">
            <?php echo $Form->label('limit', 'Number of items to display'); ?>
            <div class="form-entry">
            <?php
                echo $Form->text('limit', $Form->get($options, 'limit'), 's');
                echo $Form->hint(PerchLang::get('Leave blank to display all items'));
            ?>
            </div>
        </div>
    <?php } ?>


    
    
        <h2 class="divider"><div><?php echo PerchLang::get('Search'); ?></div></h2>

        <div class="field-wrap checkbox-single">
            <?php echo $Form->label('regionSearchable', 'Include in search results'); ?>
            <div class="form-entry">
            <?php
                $tmp = array('regionSearchable'=>$Region->regionSearchable());
                echo $Form->checkbox('regionSearchable', '1', $Form->get($tmp, 'regionSearchable', 1)); ?>
            </div>
        </div>

        <div class="field-wrap last">
            <?php echo $Form->label('searchURL', 'URL for single items'); ?>
            <div class="form-entry">
            <?php echo $Form->text('searchURL', $Form->get($options, 'searchURL', '')); ?>
            <?php echo $Form->hint(PerchLang::get('Used for search results and draft previews. See sidebar notes.')); ?>
            </div>
        </div>

        
        <h2 class="divider"><div><?php echo PerchLang::get('Permissions'); ?></div></h2>

        
        <?php
            $opts = array();
            $opts[] = array('label'=>PerchLang::get('Everyone'), 'value'=>'*', 'class'=>'single');
            
            $vals = explode(',', $Region->regionEditRoles());

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
        
        
        ?>
    
<?php   if ($CurrentUser->has_priv('content.regions.templates')) { ?>
        <h2 class="divider notification notification-warning"><?php echo PerchLang::get('Template'), ' - ', PerchLang::get('Changing the template can result in data loss if the fields in the templates are not the same.'); ?></h2>
        <div class="field-wrap">
            <?php echo $Form->label('regionTemplate', 'Template'); ?>
            <div class="form-entry">
            <?php         
                echo $Form->grouped_select('regionTemplate', $Regions->get_templates(false, true), $Form->get(array('regionTemplate'=>$Region->regionTemplate()), 'regionTemplate', 0));
                
            ?>
            </div>
        </div>


        <h2 class="divider notification notification-warning"><?php echo PerchLang::get('Region Key'), ' - ', PerchLang::get('Changing the region key requires updating any page code that uses the key, otherwise content might not display.'); ?></h2>
        <div class="field-wrap">
            <?php echo $Form->label('regionKey', 'Key'); ?>
            <div class="form-entry">
            <?php         
                echo $Form->text('regionKey', $Form->get(array('regionKey'=>$Region->regionKey()), 'regionKey', 0));
                
            ?>
            </div>
        </div>

<?php
        }


        if ($CurrentUser->has_priv('content.regions.empty')){
?>
        <h2 class="divider notification notification-warning"><?php echo PerchLang::get('Warning'), ' - ', PerchLang::get('This option deletes all content from this region.'); ?></h2>
        <div class="field-wrap">
            <?php echo $Form->label('regionReset', 'Delete all content from this region immediately'); ?>
            <div class="form-entry">
            <?php         
                echo $Form->checkbox('regionReset', 1, 0);
                
            ?>
            </div>
        </div>

<?php
        }
?>



        <div class="submit-bar">
            <div class="submit-bar-actions">
            <?php echo $Form->submit('btnsubmit', 'Save', 'button'), ' ', PerchLang::get('or'), ' <a href="',PERCH_LOGINPATH . '/core/apps/content/edit/?id='.PerchUtil::html($id).'', '">', PerchLang::get('Cancel'), '</a>'; ?>
            </div>
        </div>
    </form>
    