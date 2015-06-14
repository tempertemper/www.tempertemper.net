<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
    <h3 class="em"><span><?php echo PerchLang::get('About this collection'); ?></span></h3>
    <p>
        <?php            
            echo PerchLang::get("Required fields are marked with an asterisk.");
        ?>
    </p>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ($app_path.'/modes/_subnav.php'); ?>
    <h1><?php 
            printf(PerchLang::get('Editing %s Collection'),' &#8216;' . PerchUtil::html($Collection->collectionKey()) . '&#8217; ');     
        ?></h1>
   <?php echo $Alert->output(); ?>
		<ul class="smartbar">
            <li class="selected">
				<span class="set">
                <a class="sub" href="<?php echo PERCH_LOGINPATH . '/core/apps/content/collections/edit/?id='.PerchUtil::html($Collection->id());?>"><?php echo PerchUtil::html($Collection->collectionKey()); ?></a>
                <span class="sep icon"></span> 
                <a href="<?php echo PERCH_LOGINPATH . '/core/apps/content/collections/edit/?id='.PerchUtil::html($Collection->id()).'&amp;itm='.$details[0]['itemID'];?>"><?php 
                        
                        $item = $details[0];
                        $id = $item['itemID'];                   
                
                        if (isset($item['perch_'.$id.'__title'])) {
                            echo PerchUtil::html(PerchUtil::excerpt($item['perch_'.$id.'__title'], 10));
                        }else{
                            if (isset($item['itemOrder'])) {
                                echo PerchLang::get('Item'). ' '.PerchUtil::html($item['itemOrder']-999);
                            }else{
                                echo PerchLang::get('New Item');
                            }
                        }
                ?></a>


				</span>
			</li>
			<?php
				if ($CurrentUser->has_priv('content.regions.options')) {
		            echo '<li><a href="'.PERCH_LOGINPATH . '/core/apps/content/collections/edit/options/?id='.PerchUtil::html($Collection->id()).'&amp;itm='.$item_id.'">' . PerchLang::get('Item Options') . '</a></li>';
		        }
			?>
			<?php
                
                    echo '<li class="fin">';
                    echo '<a href="'.PERCH_LOGINPATH . '/core/apps/content/reorder/collection/?id='.PerchUtil::html($Collection->id()).'" class="icon reorder">' . PerchLang::get('Reorder') . '</a>';
                    echo '</li>';
                

                if (isset($view_page_url) && $view_page_url) {
                    echo '<li class="fin">';
                    echo '<a href="'.PerchUtil::html($view_page_url).'" class="icon page assist">' . PerchLang::get('View Page') . '</a>';
                    echo '</li>';
                }

			
				if ($Item->is_undoable()) {
					echo '<li class="fin">';
			        echo '<form method="post" action="'.PerchUtil::html($fUndo->action()).'">';
			        echo '<div>'.$fUndo->submit('btnUndo', 'Undo', 'unbutton icon undo', true, true).'</div>';
			        echo '</form>';
					echo '</li>';
			    }


			
			?>
        </ul>
<form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" <?php echo $Form->enctype(); ?> id="content-edit" class="magnetic-save-bar">
    <div id="main-panel"<?php if ($place_token_on_main) echo 'data-token="'.PerchUtil::html($place_token_on_main->get_token()).'"'; ?>>
<?php
    /*  ------------------------------------ EDIT CONTENT ----------------------------------  */

 
    if ($template_help_html) {
        echo '<h2><span>' . PerchLang::get('Help') .'</span></h2>';
        echo '<div id="template-help">' . $template_help_html . '</div>';
    }
    
?>
        <div class="items">
<?php

        if (is_array($tags)) {
            
            // loop through each item (usually one, sometimes more)
            $i = 0;
            foreach($details as $item) {

                $id = $item['itemID'];
                
                echo '<div class="edititem">';

                echo '<div class="h2" id="item'.($id).'">';
                    if (isset($item['perch_'.$id.'__title'])) {
                        echo '<h2>'. PerchUtil::html($item['perch_'.$id.'__title']) .'</h2>';
                    }else{
                        if (isset($item['itemOrder'])) {
                            echo '<h2>'. PerchLang::get('Item'). ' '.PerchUtil::html($item['itemOrder']-999).'</h2>';
                        }else{
                            //PerchUtil::debug($item);
                            echo '<h2>'. PerchLang::get('New Item'). '</h2>';
                        }
                        
                    }
                    
                    echo '<a href="'.PERCH_LOGINPATH.'/core/apps/content/delete/collection/item/?id='.PerchUtil::html($Collection->id()).'&amp;itm='.$id.'" class="delete action inline-delete">'.PerchLang::get('Delete').'</a>';
                echo '</div>';

                
                //display_item_fields($tags, $id, $item, false, $Form);
                PerchContent_Util::display_item_fields($tags, $id, $item, false, $Form, $Template);
                        
                
                echo '</div>';
                
                $i++; // item count
            }
        }
?>        
        </div>
        <p class="submit<?php if (defined('PERCH_NONSTICK_BUTTONS') && PERCH_NONSTICK_BUTTONS) echo ' nonstick'; ?><?php if ($Form->error) echo ' error'; ?>">
            <?php 
                echo $Form->submit('btnsubmit', 'Save Changes', 'button'); 
                
            
                echo '<input type="submit" name="add_another" value="'.PerchUtil::html(PerchLang::get('Save & Add another')).'" id="add_another" class="button" />';
            
                
                echo '<label class="save-as-draft" for="save_as_draft"><input type="checkbox" name="save_as_draft" value="1" id="save_as_draft" '.($draft?'checked="checked"':'').'  /> '.PerchUtil::html(PerchLang::get('Save as Draft')).'</label>';
                

				
                echo ' ' . PerchLang::get('or') . ' <a href="'.PERCH_LOGINPATH.'/core/apps/content/collections/?id='.$Collection->id().'">' . PerchLang::get('Cancel'). '</a>'; 
            	
                // prev/next links
                echo '<span class="prevnext">';

                    if ($PrevItem) {
                        echo ' <a href="'.PERCH_LOGINPATH.'/core/apps/content/collections/edit/?id='.$Collection->id().'&amp;itm='.$PrevItem->itemID().'" class="paging-prev icon" title="'.PerchLang::get('Previous').'"><span class="hidden">'.PerchLang::get('Previous').'</span></a>';
                    }

                    if ($NextItem) {
                        echo ' <a href="'.PERCH_LOGINPATH.'/core/apps/content/collections/edit/?id='.$Collection->id().'&amp;itm='.$NextItem->itemID().'" class="paging-next icon" title="'.PerchLang::get('Next').'"><span class="hidden">'.PerchLang::get('Next').'</span></a>';
                    }

                echo '</span>';
            ?>
        </p>
        <?php

            echo '<input type="submit" name="add_another" value="'.PerchUtil::html(PerchLang::get('Save & Add another')).'" class="add button topadd" />';
        ?>
    </div>
</form>
<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>