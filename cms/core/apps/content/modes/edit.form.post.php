<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
    <h3 class="em"><span><?php echo PerchLang::get('About this region'); ?></span></h3>
    <p>
        <?php 
            if ($Region->regionMultiple()=='1') {
                echo PerchLang::get("This region may contain one or more items.");
            }else{
                echo PerchLang::get("This region only has a single item.");
            }
            
            echo ' '. PerchLang::get("Required fields are marked with an asterisk.");
        ?>
    </p>
<?php

    if ($Region->regionTemplate() != '') {

        echo '<h3>' . PerchLang::get('Page assignment') . '</h3>';

        $view_page_url = false;

        if ($Region->regionPage() == '*') {
            echo '<p>' . PerchLang::get('This region is shared across all pages.') . '</p>';
        }else{

            if ($Region->get_option('edit_mode')=='listdetail' && $Region->get_option('searchURL')!='') {
                $search_url = $Region->get_option('searchURL');  

                $Region->tmp_url_vars = $details[0];             
                $search_url = preg_replace_callback('/{([A-Za-z0-9_\-]+)}/', array($Region, 'substitute_url_vars'), $search_url);
                $Region->tmp_url_vars = false; 

                $view_page_url = rtrim($Settings->get('siteURL')->val(), '/').$search_url;
            }else{
                $view_page_url = rtrim($Settings->get('siteURL')->val(), '/').$Region->regionPage();
            }



            
            echo '<p>' . PerchLang::get('This region is only available within') . ':</p><p><code><a href="' . PerchUtil::html($view_page_url) . '">' . PerchUtil::html($Region->regionPage()) . '</a></code></p>';
        }

    }  

?>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>
    <h1><?php 
            if ($Region->regionPage()=='*') {
                printf(PerchLang::get('Editing Shared Regions')); 
            }else{
                printf(PerchLang::get('Editing %s Page'),' &#8216;' . PerchUtil::html($Page->pageNavText()) . '&#8217; ');     
            }

            
        ?></h1>
   <?php echo $Alert->output(); ?>
		<ul class="smartbar">
            <li class="selected">
				<span class="set">
				<a class="sub" href="<?php 
                    if ($Region->regionPage()=='*') {
                        echo PERCH_LOGINPATH . '/core/apps/content/page/?id=-1';
                    }else{
                        echo PERCH_LOGINPATH . '/core/apps/content/page/?id='.PerchUtil::html($Region->pageID());
                    }
                ?>"><?php echo PerchLang::get('Regions'); ?></a> 
				<span class="sep icon"></span> 
				

                <?php
                    if ($Region->regionMultiple() && $Region->get_option('edit_mode')=='listdetail') {
                ?>
                <a class="sub" href="<?php echo PERCH_LOGINPATH . '/core/apps/content/edit/?id='.PerchUtil::html($Region->id());?>"><?php echo PerchUtil::html($Region->regionKey()); ?></a>
                <span class="sep icon"></span> 
                <a href="<?php echo PERCH_LOGINPATH . '/core/apps/content/edit/?id='.PerchUtil::html($Region->id()).'&amp;itm='.$details[0]['itemID'];?>"><?php 
                        
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
                <?php
                    }else{
                ?>
                    <a href="<?php echo PERCH_LOGINPATH . '/core/apps/content/edit/?id='.PerchUtil::html($Region->id());?>"><?php echo PerchUtil::html($Region->regionKey()); ?></a>
                <?php
                    }
                ?>

				</span>
			</li>
			<?php
				if ($CurrentUser->has_priv('content.regions.options')) {
		            echo '<li><a href="'.PERCH_LOGINPATH . '/core/apps/content/options/?id='.PerchUtil::html($Region->id()).'">' . PerchLang::get('Region Options') . '</a></li>';
		        }
			?>
			<?php
                if ($Region->regionMultiple()) {
                    echo '<li class="fin">';
                    echo '<a href="'.PERCH_LOGINPATH . '/core/apps/content/reorder/region/?id='.PerchUtil::html($Region->id()).'" class="icon reorder">' . PerchLang::get('Reorder') . '</a>';
                    echo '</li>';
                }

                if (isset($view_page_url) && $view_page_url) {
                    echo '<li class="fin">';
                    echo '<a href="'.PerchUtil::html($view_page_url).'" class="icon page assist">' . PerchLang::get('View Page') . '</a>';
                    echo '</li>';
                }

			
				if ($Region->is_undoable()) {
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
                if ($Region->regionMultiple()) {
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
                        
                        echo '<a href="'.PERCH_LOGINPATH.'/core/apps/content/delete/item/?id='.PerchUtil::html($Region->id()).'&amp;itm='.$id.'" class="delete action inline-delete">'.PerchLang::get('Delete').'</a>';
                    echo '</div>';
                }else{
                    echo '<h2 class="em">'. PerchUtil::html($Region->regionKey()).'</h2>';
                }
                
                display_item_fields($tags, $id, $item, $Page, $Form);
                                
                
                echo '</div>';
                
                $i++; // item count
            }
        }



        function display_item_fields($tags, $id, $item, $Page, $Form)
        {          
            $seen_tags = array();
            
            foreach($tags as $tag) {
                
                $item_id = 'perch_'.$id.'_'.$tag->id();
                $tag->set('input_id', $item_id);
                $tag->set('post_prefix', 'perch_'.$id.'_');
                if (is_object($Page)) $tag->set('page_id', $Page->id());

                if (!in_array($tag->id(), $seen_tags) && $tag->type()!='hidden' && substr($tag->id(), 0,7)!='parent.') {

                    if ($tag->type()=='slug' && !$tag->editable()) {
                        continue;
                    }


                    if ($tag->type()=='PerchRepeater') {
                        $repeater_id = $id.'_'.$tag->id();

                        echo '<h3 class="label repeater-heading">'.$tag->label().'</h3>';
                        echo '<div class="repeater" data-prefix="perch_'.PerchUtil::html($repeater_id).'"';
                        if ($tag->max()) echo ' data-max="'.PerchUtil::html($tag->max()).'"';
                        echo '>';
                            echo '<div class="repeated">';
                        
                            
                            $repeater_i = 0;

                            if (isset($item[$tag->id()]) && is_array($item[$tag->id()])) {
                                
                                $subitems = $item[$tag->id()];

                                if (isset($_POST['perch_'.$repeater_id.'_count']) && (int)$_POST['perch_'.$repeater_id.'_count']>0) {
                                    $submitted_count = (int)$_POST['perch_'.$repeater_id.'_count'];
                                    if (PerchUtil::count($subitems) < $submitted_count) {
                                        for ($i=PerchUtil::count($subitems); $i<$submitted_count; $i++) {
                                            $subitems[] = array();
                                        }
                                    }
                                }

                                foreach($subitems as $subitem) {
                                    echo '<div class="repeated-item">';
                                        echo '<div class="index"><span>'.($repeater_i+1).'</span><span class="icon"></span></div>';
                                        echo '<div class="repeated-fields">';
                                        display_item_fields($tag->tags, $repeater_id.'_'.$repeater_i, $subitem, $Page, $Form);    
                                        echo '<input type="hidden" name="perch_'.($repeater_id.'_'.$repeater_i).'_present" class="present" value="1" />';
                                        echo '<input type="hidden" name="perch_'.($repeater_id.'_'.$repeater_i).'_prevpos" value="'.$repeater_i.'" />';
                                        echo '</div>';
                                        echo '<div class="rm"></div>';
                                    echo '</div>';
                                    $repeater_i++;
                                }
                                                               
                            }

                            $spare = true;

                            if ($tag->max() && ($repeater_i-1)>=(int)$tag->max()) {
                                $spare = false;
                            }


                            if ($spare) {
                                // And one spare
                                echo '<div class="repeated-item spare">';
                                    echo '<div class="index icon"><span>'.($repeater_i+1).'</span><span class="icon"></span></div>';
                                        echo '<div class="repeated-fields">';
                                        display_item_fields($tag->tags, $repeater_id.'_'.$repeater_i, array(), $Page, $Form);  
                                        echo '<input type="hidden" name="perch_'.($repeater_id.'_'.$repeater_i).'_present" class="present" value="1" />';  
                                        echo '</div>';
                                        echo '<div class="rm"></div>';
                                echo '</div>';
                                echo '</div>'; // .repeated
                                // footer
                                echo '<div class="repeater-footer">';
                                    echo '<input type="hidden" name="perch_'.$repeater_id.'_count" value="0" class="count" />';
                                echo '</div>';
                            }
                            
                            
                        echo '</div>';
                    }else{

                        if ($tag->divider_before()) {
                            echo '<h2 class="divider">'.PerchUtil::html($tag->divider_before()).'</h2>';
                        }

                        echo '<div class="field '.$Form->error($item_id, false).'">';
                        echo '<div class="fieldtbl">';
                        
                            $label_text  = PerchUtil::html($tag->label());
                            if ($tag->type() == 'textarea') {
                                if (PerchUtil::bool_val($tag->textile()) == true) {
                                    $label_text .= ' <span><a href="'.PERCH_LOGINPATH.'/core/help/textile" class="assist">Textile</a></span>';
                                }
                                if (PerchUtil::bool_val($tag->markdown()) == true) {
                                    $label_text .= ' <span><a href="'.PERCH_LOGINPATH.'/core/help/markdown" class="assist">Markdown</a></span>';
                                }
                            }
                            $Form->disable_html_encoding();
                            echo '<div class="fieldlbl">'.$Form->label($item_id, $label_text, '', false, false).'</div>';
                            $Form->enable_html_encoding();
                            
                            
                            $FieldType = PerchFieldTypes::get($tag->type(), $Form, $tag);
                            
                            
                            echo '<div class="field-wrap">';
                            echo $FieldType->render_inputs($item);
                                
                            if ($tag->help()) {
                                echo $Form->hint($tag->help());
                            }
                            echo '</div>';
                
                        echo '</div>';
                        echo '</div>';      

                        if ($tag->divider_after()) {
                            echo '<h2 class="divider">'.PerchUtil::html($tag->divider_after()).'</h2>';
                        }                      
                    }

            
                    $seen_tags[] = $tag->id();
                }
            }
        }
?>        
        </div>
        <p class="submit<?php if (defined('PERCH_NONSTICK_BUTTONS') && PERCH_NONSTICK_BUTTONS) echo ' nonstick'; ?><?php if ($Form->error) echo ' error'; ?>">
            <?php 
                echo $Form->submit('btnsubmit', 'Save Changes', 'button'); 
                
                if ($Region->regionMultiple()=='1') {
                    echo '<input type="submit" name="add_another" value="'.PerchUtil::html(PerchLang::get('Save & Add another')).'" id="add_another" class="button" />';
                }
                
                echo '<label class="save-as-draft" for="save_as_draft"><input type="checkbox" name="save_as_draft" value="1" id="save_as_draft" '.($draft?'checked="checked"':'').'  /> '.PerchUtil::html(PerchLang::get('Save as Draft')).'</label>';
                

				if ($Region->regionMultiple()=='1' && $Region->get_option('edit_mode')=='listdetail') {
                	echo ' ' . PerchLang::get('or') . ' <a href="'.PERCH_LOGINPATH.'/core/apps/content/edit/?id='.$Region->id().'">' . PerchLang::get('Cancel'). '</a>'; 
            	}else{
				    if ($Region->regionPage() == '*') {
                        $pageID = '-1';
                    }else{
                        $pageID = $Page->id();
                    }

                	echo ' ' . PerchLang::get('or') . ' <a href="'.PERCH_LOGINPATH.'/core/apps/content/page/?id='.$pageID.'">' . PerchLang::get('Cancel'). '</a>'; 
				}
                
            ?>
        </p>
        <?php
            if ($Region->regionMultiple()=='1') {
                echo '<input type="submit" name="add_another" value="'.PerchUtil::html(PerchLang::get('Save & Add another')).'" class="add button topadd" />';
            }
        ?>
    </div>
</form>
<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>