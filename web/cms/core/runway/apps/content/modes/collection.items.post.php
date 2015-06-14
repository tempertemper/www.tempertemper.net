<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
    <h3 class="em"><span><?php echo PerchLang::get('About this collection'); ?></span></h3>
    
    <p><?php 
            echo PerchLang::get("Select an item to edit its content.");
    ?></p>
    
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ($app_path.'/modes/_subnav.php'); ?>

    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>">      
        <div><?php echo $Form->submit('add_another', 'Add another item', 'add button topadd'); ?></div>
    </form>

    <h1><?php 
            printf(PerchLang::get('Editing %s Collection'),' &#8216;' . PerchUtil::html($Collection->collectionKey()) . '&#8217; '); 
        ?></h1>
    
    <?php echo $Alert->output(); ?>

	<ul class="smartbar">
        <li class="selected">
            <a href="<?php echo PERCH_LOGINPATH . '/core/apps/content/collections/?id='.PerchUtil::html($Collection->id());?>"><?php echo PerchUtil::html($Collection->collectionKey()); ?></a>
		</li>
		<?php
			if ($CurrentUser->has_priv('content.regions.options')) {
	            echo '<li><a href="'.PERCH_LOGINPATH . '/core/apps/content/collections/options/?id='.PerchUtil::html($Collection->id()).'">' . PerchLang::get('Options') . '</a></li>';
	        }

		?>
		<li class="fin"><a class="icon reorder" href="<?php echo PERCH_LOGINPATH . '/core/apps/content/reorder/collection/?id='.PerchUtil::html($Collection->id());?>"><?php echo PerchLang::get('Reorder'); ?></a></li>
        <li class="fin"><a class="icon import" href="<?php echo PERCH_LOGINPATH . '/core/apps/content/collections/import/?id='.PerchUtil::html($Collection->id());?>"><?php echo PerchLang::get('Import'); ?></a></li>
    </ul>


    
    <?php
        if (PerchUtil::count($items)) {
            
            echo '<table class="d itemlist">';
                echo '<thead>';
                    echo '<tr>';
                        foreach($cols as $col) {
                            echo '<th>'.PerchUtil::html($col['title']).'</th>';
                        }
                        echo '<th class="last action"></th>';
                    echo '</tr>';
                echo '</thead>';
            
                echo '<tbody>';
                $Template = new PerchTemplate;
                $i = 1;
                foreach($items as $item) {
                    echo '<tr>';
                        $first = true;
                        foreach($cols as $col) {

                            if ($first) { 
                                echo '<td class="primary">';
                                if ($item['_has_draft']) echo '<span class="draft icon" title="'.PerchLang::get('This item is a draft.').'"></span>';
                                echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/content/collections/edit/?id=' . PerchUtil::html($Collection->id()) . '&amp;itm='.PerchUtil::html($item['itemID']).'">';
                            }else{
                                echo '<td>';
                            }

                            if ($col['id']=='_title') {
                                if (isset($item['_title'])) {
                                    $title = $item['_title'];
                                }else{
                                    $title = PerchLang::get('Item').' '.$i;
                                }
                            }else{
                                if (isset($item[$col['id']])) {
                                    $title = $item[$col['id']];    
                                }else{
                                    if ($first) {
                                        if (isset($item['_title'])) {
                                            $title = $item['_title'];
                                        }else{
                                            $title = PerchLang::get('Item').' '.$i;
                                        }
                                    }else{
                                        $title = '-';
                                    }
                                }
                                
                            }

                            if ($col['Tag']) {

                                $FieldType = PerchFieldTypes::get($col['Tag']->type(), false, $col['Tag']);

                                $title = $FieldType->render_admin_listing($title);

                                if ($col['Tag']->format()) {
                                    $title = $Template->format_value($col['Tag'], $title);
                                }
                            }
                            
                            if ($first && trim($title)=='') $title = '#'.$item['_id'];

                            echo $title;

                            if ($first) echo '</a>';
                             
                            echo '</td>';

                            $first = false;
                        }
                        echo '<td>';
                            echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/content/delete/collection/item/?id=' . PerchUtil::html($Collection->id()) . '&amp;itm='.PerchUtil::html($item['itemID']).'" class="delete inline-delete">'.PerchLang::get('Delete').'</a>';
                        echo '</td>';
                    echo '</tr>';
                    $i++;
                }
                echo '</tbody>';
            
            
            echo '</table>';
            
    
            if ($Paging->enabled()) {
                $API = new PerchAPI(1.0, 'perch_content');
                $HTML = $API->get('HTML');
                echo $HTML->paging($Paging);
            }

        }
    



    ?>



<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>
