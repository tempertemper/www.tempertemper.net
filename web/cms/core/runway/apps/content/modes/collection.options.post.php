<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
    
    <p>
        <?php echo PerchLang::get('It\'s sometimes useful to use a different URL in search results, or for previewing drafts for a list/detail region.'); ?>
    </p>
    <p>
        <?php printf(PerchLang::get('If you need this, enter the root-relative URL using %sbraces%s around any dynamic fields. e.g.'), '{', '}'); ?>
    </p>
    <p>
        <code><?php  printf(PerchLang::get('/news-article.php?s=%sslug%s'), '{','}'); ?></code>
    </p>

<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include (PERCH_PATH.'/core/apps/content/modes/_subnav.php'); ?>


	    <h1><?php echo PerchLang::get('Editing Collection Options'); ?></h1>
	   <?php echo $Alert->output(); ?>

		<ul class="smartbar">
            <li>
				<a href="<?php echo PERCH_LOGINPATH . '/core/apps/content/collections/?id='.PerchUtil::html($id);?>"><?php echo PerchUtil::html($Collection->collectionKey()); ?></a>
			</li>
			<?php
				if ($CurrentUser->has_priv('content.regions.options')) {
		            echo '<li class="selected"><a href="'.PERCH_LOGINPATH . '/core/apps/content/collections/options/?id='.PerchUtil::html($id).'">' . PerchLang::get('Options') . '</a></li>';
		        }

                echo '<li class="fin">';
                echo '<a href="'.PERCH_LOGINPATH . '/core/apps/content/reorder/collection/?id='.PerchUtil::html($Collection->id()).'" class="icon reorder">'.PerchLang::get('Reorder').'</a>';
                echo '</li>';

			?>
        </ul>
		

    
        
    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" class="magnetic-save-bar">
        
        <h2><?php echo PerchLang::get('Editing'); ?></h2>
        
        <?php if (PERCH_RUNWAY) { ?>
        <div class="field">
            <?php echo $Form->label('collectionInAppMenu', 'Include in app menu'); ?>
            <?php
                $tmp = array('collectionInAppMenu'=>$Collection->collectionInAppMenu());
                echo $Form->checkbox('collectionInAppMenu', '1', $Form->get($tmp, 'collectionInAppMenu', 1)); ?>
        </div>
        <?php } // runway ?>

    
        <div class="field">
            <?php echo $Form->label('addToTop', 'New items are'); ?>
            <?php
                $opts = array();
                $opts[] = array('label'=>PerchLang::get('Added to the top'), 'value'=>1);
                $opts[] = array('label'=>PerchLang::get('Added to the bottom'), 'value'=>0);
                echo $Form->select('addToTop', $opts, $Form->get($options, 'addToTop', 0));
            ?>
        </div>

        <?php
            // Used by column_ids and sortField
            $Template = new PerchTemplate('content/'.$Collection->collectionTemplate(), 'content');
            $tags   = $Template->find_all_tags('(content|categories)');

        ?>

        <div class="field last">
            <?php echo $Form->label('column_ids', 'Item list column IDs'); ?>
            <?php
                echo $Form->text('column_ids', $Form->get($options, 'column_ids'), 'xl');
                echo $Form->hint(PerchLang::get('Enter field IDs to list when editing in list and detail mode. Comma separated.'));

                $suggestions = array();
                $suggestions[] = '_title';

                $seen_tags = array();
                if (PerchUtil::count($tags)) {
                    foreach($tags as $Tag) {
                        $tag_id = $Tag->id();
                        if ($Tag->output()) {
                            $tag_id .='['.$Tag->output().']';
                        }
                        if (!in_array($tag_id, $seen_tags) && $Tag->id()) {
                            $suggestions[] = $tag_id;
                            $seen_tags[] = $tag_id;
                        }
                    }
                    sort($suggestions);
                }


                echo $Form->hint(PerchLang::get('Choose from: ').implode(', ', $suggestions));
            ?>
        </div>

        
        <div class="field">
                <?php echo $Form->label('title_delimit', 'Join title fields with'); ?>
                <?php
                    echo $Form->text('title_delimit', $Form->get($options, 'title_delimit'), 's');
                    echo $Form->hint(PerchLang::get('When more than one field is set as the item title, the titles will be concatenated using this value.'));
                ?>
        </div>


        <h2><?php echo PerchLang::get('Display'); ?></h2>

        <div class="field">
            <?php echo $Form->label('sortField', 'Sort by'); ?>
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
        
        <div class="field last">
                <?php echo $Form->label('sortOrder', 'Sort order'); ?>
                <?php
                    $opts = array();
                    $opts[] = array('label'=>PerchLang::get('Ascending (A-Z, oldest to newest)'), 'value'=>'ASC');
                    $opts[] = array('label'=>PerchLang::get('Descending (Z-A, newest to oldest)'), 'value'=>'DESC');
                    echo $Form->select('sortOrder', $opts, $Form->get($options, 'sortOrder'));
                ?>
        </div>


        <!-- <div class="field last">
                <?php //echo $Form->label('limit', 'Number of items to display'); ?>
                <?php
                    //echo $Form->text('limit', $Form->get($options, 'limit'), 's');
                    //echo $Form->hint(PerchLang::get('Leave blank to display all items'));
                ?>
        </div> -->
   
    
        <h2><?php echo PerchLang::get('Search'); ?></h2>

        <div class="field">
            <?php echo $Form->label('collectionSearchable', 'Include in search results'); ?>
            <?php
                $tmp = array('collectionSearchable'=>$Collection->collectionSearchable());
                echo $Form->checkbox('collectionSearchable', '1', $Form->get($tmp, 'collectionSearchable', 1)); ?>
        </div>

        <div class="field last">
            <?php echo $Form->label('searchURL', 'URL for single items'); ?>
            <?php echo $Form->text('searchURL', $Form->get($options, 'searchURL', '')); ?>
            <?php echo $Form->hint(PerchLang::get('Used for search results and draft previews. See sidebar notes.')); ?>

        </div>

        
        <h2><?php echo PerchLang::get('Permissions'); ?></h2>

        <div class="field last">
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
            
            
            ?>
        </div>
<?php   if ($CurrentUser->has_priv('content.regions.templates')) { ?>
        <h2><?php echo PerchLang::get('Template'); ?></h2>
        <?php 
            $Alert->set('notice', PerchLang::get('Changing the template can result in data loss if the fields in the templates are not the same.'));
            echo $Alert->output();
        ?>
        <div class="field">
            <?php echo $Form->label('collectionTemplate', 'Template'); ?>
            <?php         
                echo $Form->grouped_select('collectionTemplate', $Regions->get_templates(false, true), $Form->get(array('collectionTemplate'=>$Collection->collectionTemplate()), 'collectionTemplate', 0));
                
            ?>
        </div>

<?php
        }
?>



        <p class="submit">
            <?php echo $Form->submit('btnsubmit', 'Save', 'button'), ' ', PerchLang::get('or'), ' <a href="',PERCH_LOGINPATH . '/core/apps/content/collections/edit/?id='.PerchUtil::html($id).'', '">', PerchLang::get('Cancel'), '</a>'; ?>
        </p>
    </form>
    
<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>