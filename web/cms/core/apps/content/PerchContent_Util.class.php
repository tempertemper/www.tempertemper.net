<?php

class PerchContent_Util
{
	/* Utilities for keeping logic readable */

	public static function flatten_details($details, $parent_itemID=false, $parent_key=false, $skipsub=false)
	{
	    $details_flat = array();

	    $k = 0;
	    foreach($details as $detail) {
	        if (PerchUtil::count($detail)) {
	            $itemID = $parent_itemID ? $parent_itemID : $detail['itemID'];

	            $tmp = array();

	            foreach($detail as $field_key => $field_val) {

	                $new_key = 'perch_'.$itemID.'_'.$field_key;

	                if ($skipsub) {
	                	if ($parent_itemID) $new_key = 'perch_'.$itemID.'_'.$k.'_'.$field_key;
	                }else{
	                	if ($parent_itemID) $new_key = 'perch_'.$itemID.'_'.$parent_key.'_'.$k.'_'.$field_key;
	                }


	                $tmp[$new_key] = $field_val;
	                $tmp[$field_key] = $field_val;


	                if (!$parent_key && is_array($field_val)) {
	                    $out = self::flatten_details($field_val, $itemID, $field_key, $skipsub);
	                    if ($out) {
	                        $tmp[$field_key] = $out;
	                    }
	                }
	            }

	            $details_flat[] = $tmp;
	        }
	        $k++;
	    }

	    return $details_flat;
	}

	public static function determine_title($Tag, $var, $options, $form_vars)
	{
	    if ($Tag->title()) {
	        $title_var = $var;

	        if (is_array($var)) {
	            if (isset($var['_title'])) {
	                $title_var = $var['_title'];
	            }elseif(isset($var['_default'])) {
	                $title_var = strip_tags($var['_default']);
	            }elseif(isset($var['processed'])) {
	                $title_var = strip_tags($var['processed']);
	            }
	        }

	        if (isset($form_vars['_title'])) {
	            if (isset($options['title_delimit'])) {
	                $title_delim = $options['title_delimit'];
	            }else{
	                $title_delim = ' ';
	            }
	            $form_vars['_title'] .= $title_delim.$title_var;
	        }else{
	            $form_vars['_title'] = $title_var;
	        }
	    }

	    return $form_vars;
	}

	public static function multi_implode($array, $glue='')
	{
	    $ret = '';

	    foreach ($array as $item) {
	        if (is_array($item)) {
	            $ret .= self::multi_implode($item, $glue) . $glue;
	        } else {
	            $ret .= $item . $glue;
	        }
	    }

	    return $ret;
	}

	public static function read_items_from_post($Item, $tags, $subprefix, $form_vars, $postitems, $Form, $search_text, $options, $Resources, $in_repeater=false, $Template, $in_block=false)
	{
		$debugging = false;

		if ($debugging) PerchUtil::mark('reading items from post'. ($in_block ? ' (in block)': ''));
		if ($debugging) PerchUtil::debug($_POST);

		$given_subprefix = $subprefix;
	    $seen_tags = array();

	    if ($Item && $Item->itemID()) {
	    	$perch_prefix = 'perch_'.$Item->itemID();
	    	$item_prefix  = $Item->itemID().'_';
	    }else{
	    	$perch_prefix = 'perch';
	    	$item_prefix  = '';
	    }

	    // Top level - see notes below.
	    $top_level = true;
	    if ($in_repeater) $top_level = false;

	    $deleted_blocks = array();
	    if (isset($_POST['_blocks_deleted']) && PerchUtil::count($_POST['_blocks_deleted'])) {
	    	$deleted_blocks = $_POST['_blocks_deleted'];
	    	if ($debugging) PerchUtil::debug('Deleted blocks:');
	    	if ($debugging) PerchUtil::debug($deleted_blocks);
	    }else{
	    	if ($debugging) PerchUtil::debug('No deleted blocks');
	    }

	    if ($debugging) PerchUtil::debug($tags, 'success');

	    foreach($tags as $Tag) {

	        if (!in_array($Tag->id(), $seen_tags)) {

	        	if ($debugging) PerchUtil::debug($Tag->id(). ': '.$Tag->type(), 'notice');
	        	if ($debugging) PerchUtil::debug(($in_block ? 'In block' : 'Not in block'), 'notice');
	        	if ($debugging) PerchUtil::debug(($in_repeater ? 'In repeater' : 'Not in repeater'), 'notice');
	        	if ($debugging) PerchUtil::debug('subprefix: '.$subprefix);


	            if ($Tag->type()=='PerchRepeater' || $Tag->type()=='PerchBlocks') {


	            	// Make sure prefixes work for 3 situations:
	            	// 	1) blocks and repeaters in API
	            	// 	2) repeaters at top level
	            	// 	3) repeaters in blocks
	            	if ($Tag->type()=='PerchRepeater' && !$Item->itemID() && $top_level) {
	            		$perch_prefix = 'perch_';
	            	}else{
	            		if ($Item->itemID()) {
        			    	$perch_prefix = 'perch_'.$Item->itemID();
        			    }else{
        			    	$perch_prefix = 'perch';
        			    }
	            	}

	                if ($debugging) PerchUtil::debug('Reading: '.$Tag->id());

	                $new_form_vars = array();

	                $i = 0;
	                $minor_prefix = $i.'_';

	                if ($Tag->type()=='PerchBlocks') {
	                	$subprefix .= '_blocks_';
	                }else{
	                	if ($in_repeater && $in_block) {
	                		$subprefix .= '_'.$Tag->id().'_';
	                	}else{
	                		$subprefix .= $Tag->id().'_';
	                	}

	                }

	                if ($debugging) PerchUtil::debug('Looking for  '.$item_prefix.$subprefix.$i.' in deleted blocks list');
	                //while (in_array($Item->itemID().'_'.$subprefix.$i, $deleted_blocks)) {
	                while (in_array($item_prefix.$subprefix.$i, $deleted_blocks)) {
	                	if ($debugging) PerchUtil::debug('Skipping '.$item_prefix.$subprefix.$i.' as deleted');
	                 	$i++;
	                };

	                $new_postitems = $Form->find_items($perch_prefix.'_'.$subprefix.$i.'_');

	                if ($debugging) PerchUtil::debug('Subprefix: ' . $subprefix);
	               	if ($debugging) PerchUtil::debug('*Looking for: '.$perch_prefix.'_'.$subprefix.$i.'_'. ' '. ($in_repeater ? '(in repeater)':''));
	                if ($debugging) PerchUtil::debug($new_postitems);

	                while (PerchUtil::count($new_postitems)) {

	                	if ($Tag->type()=='PerchBlocks') {
	                		$item_tags = array();
	                		if (isset($new_postitems['_block_type'])) {
	                			$item_tags = $Template->get_block_tags($new_postitems['_block_type']);
	                			$item_tags[] = new PerchXMLTag('<perch:'.$Template->namespace.' id="_block_type" value="'.$new_postitems['_block_type'].'" type="editcontrol" />');
	                			$item_tags[] = new PerchXMLTag('<perch:'.$Template->namespace.' id="_block_id" value="'.$new_postitems['_block_id'].'" type="editcontrol" />');
	                			$item_tags[] = new PerchXMLTag('<perch:'.$Template->namespace.' id="_block_index" value="'.$new_postitems['_block_index'].'" type="editcontrol" />');
	                		}
	                		$as_in_block = true;
	                	}else{
	                		$item_tags = $Tag->tags;
	                		$as_in_block = false;
	                	}


	                    list($result, $search_text) = self::read_items_from_post($Item, $item_tags, $subprefix.$i, $new_form_vars, $new_postitems, $Form, $search_text, $options, $Resources, true, $Template, $as_in_block);

	                    if ($debugging) PerchUtil::debug($result, 'notice');

	                    if (strlen(self::multi_implode($result)) > 0) {
	                        $form_vars[$Tag->id()][] = $result;
	                    }

	                    $i++;

	                    while (in_array($item_prefix.$subprefix.$i, $deleted_blocks)) {
	                    	PerchUtil::debug('Skipping '.$Item->itemID().'_'.$subprefix.$i.' as deleted (2)');
	                     	$i++;
	                    };

	                    $new_postitems = $Form->find_items($perch_prefix.'_'.$subprefix.$i.'_');

	                    if ($debugging) PerchUtil::debug('Looking for: '.$perch_prefix.$subprefix.'_'.$i.'_');
	                    if ($debugging) PerchUtil::debug($new_postitems);


	                    //if (!PerchUtil::count($new_postitems)) {
	                        //PerchUtil::debug('Not found: '.$perch_prefix.'_'.$subprefix.$i.'_', 'error');
	                    //}
	                }

	                if (PerchUtil::count($deleted_blocks)) {
	                	// were all the blocks deleted? If so, add an empty _blocks entry so that the previous value isn't persisted
	                	if (!isset($form_vars['_blocks'])) {
	                		$form_vars['_blocks'] = [];
	                	}
	                }

	                // if ($Tag->id()=='_blocks' && isset($form_vars[$Tag->id()])) {
	                // 	$form_vars[$Tag->id()] = PerchUtil::array_sort($form_vars[$Tag->id()], '_block_index');
	                // }

	                if ($in_block) {
	                	$subprefix = $given_subprefix;
	                }else{
	                	$subprefix = '';
	                }

	            }else{

	                if ($subprefix) {
	                    $field_prefix = $perch_prefix.'_'.$subprefix.'_'.$Tag->id();
	                }else{
	                    $field_prefix = $perch_prefix.'_'.$Tag->id();
	                }

	                if ($debugging) PerchUtil::debug('Form looking for field prefix: '.$field_prefix);

	                if ($in_repeater) {
	                    $Tag->set('in_repeater', true);
	                    $Tag->set('tag_context', $subprefix.'_'.$Tag->id());

	                    if (isset($_POST[$perch_prefix.'_'.$subprefix.'_prevpos'])) {
	                        $parts = explode('_', $subprefix);
	                        array_pop($parts);
	                        $parts[] = (int)$_POST[$perch_prefix.'_'.$subprefix.'_prevpos'];

	                        if (isset($_POST[$perch_prefix.'_'.$subprefix.'_present'])) {
	                            $Tag->set('tag_context', implode('_', $parts).'_'.$Tag->id());
	                        }else{
	                            $Tag->set('tag_context', false);
	                        }

	                    }else{
	                        $Tag->set('tag_context', false);
	                    }
	                }

	                $var = false;

	                $Tag->set('input_id', $field_prefix);

	                $FieldType = PerchFieldTypes::get($Tag->type(), $Form, $Tag, $tags, $Form->app_id);
	                if ($Item) $FieldType->set_unique_id($Item->id());

	                $var  = $FieldType->get_raw($postitems, $Item);
	                if ($Tag->searchable(true)) {
	                	if ($debugging) PerchUtil::debug('Getting search text for: '.$Tag->id().' ('.$Tag->type().')');
	                	$search_text    .= $FieldType->get_search_text($var).' ';
	                	if ($debugging) PerchUtil::debug($FieldType->get_search_text($var));
	                }



	                if ($var || (is_string($var) && strlen($var))) {
	                    if (!is_array($var)) {
	                    	$var = PerchUtil::safe_stripslashes($var);
	                    }
	                    $form_vars[$Tag->id()] = $var;

	                    // title
	                    $form_vars = self::determine_title($Tag, $var, $options, $form_vars);
	                }else{
	                    // Store empty values for valid fields
	                    if ($Tag->is_set('type') && $Tag->type()!='hidden' && substr($Tag->id(), 0, 1)!='_') {
	                        //PerchUtil::debug('Empty '.$Tag->id(), 'error');
	                        $form_vars[$Tag->id()] = null;
	                    }

	                }

	                // Resources
	                #if ($Item->ready_to_log_resources()) {
	                #	$resourceIDs = $Resources->get_logged_ids();
	                #	if (PerchUtil::count($resourceIDs)) {
	                #	    $Item->log_resources($resourceIDs);
	                #	}
	                #}
	            }




	            $seen_tags[] = $Tag->id();
	        }

	    }

	    // Resources
        if (!$in_repeater && !$in_block && $Item && $Item->ready_to_log_resources()) {
        	$resourceIDs = $Resources->get_logged_ids();
        	if (PerchUtil::count($resourceIDs)) {
        	    $Item->log_resources($resourceIDs);
        	}
        }

	    return array($form_vars, $search_text);
	}


	public static function set_required_fields($Form, $id, $item, $tags, $Template)
	{
	    $req = array();
	    $seen_tags = array();

	    $deleted_blocks = array();
	    if (isset($_POST['_blocks_deleted']) && PerchUtil::count($_POST['_blocks_deleted'])) {
	    	$deleted_blocks = $_POST['_blocks_deleted'];
	    }

	    if (PerchUtil::count($tags)) {

		    foreach($tags as $tag) {

		        if ($tag->type()=='PerchRepeater') {
		            $repeater_id = $id.'_'.$tag->id();
		            $repeater_i  = 0;

		            if (isset($item[$tag->id()]) && is_array($item[$tag->id()])) {

		                if (isset($_POST['perch_'.$repeater_id.'_count'])){// && (int)$_POST['perch_'.$repeater_id.'_count']>0) {
		                    for($repeater_i=0; $repeater_i<(int)$_POST['perch_'.$repeater_id.'_count']; $repeater_i++) {
		                        self::set_required_fields($Form, $repeater_id.'_'.$repeater_i, array(), $tag->tags, $Template);
		                    }
		                }else{
		                    foreach($item[$tag->id()] as $subitem) {
		                        self::set_required_fields($Form, $repeater_id.'_'.$repeater_i, $subitem, $tag->tags, $Template);
		                        $repeater_i++;
		                    }
		                }
		            }
		        }

		        if ($tag->type()=='PerchBlocks') {
		        	$block_tags = $Template->find_all_tags('block');

		        	//PerchUtil::debug($block_tags);

		        	$blocks_data = array();
		        	if (isset($item['_blocks'])) $blocks_data = $item['_blocks'];

		        	if (PerchUtil::count($blocks_data)) {
		        	    foreach($blocks_data as $count => $block_data) {

		        	    	if ($id === null) {
		        	    		$block_id = '_blocks_'.$count;
		        	    	}else{
		        	    		$block_id = $id.'__blocks_'.$count;
		        	    	}

		        	        if (!in_array($block_id, $deleted_blocks)) {
		        	        	$block_tags = $Template->get_block_tags($block_data['_block_type']);
		        	        	self::set_required_fields($Form, $block_id, $block_data, $block_tags, $Template);
		        	        }
		        	    }
		        	}


		        }


		        // initialising the field type here makes sure editor plugins are kicked off in the <head>
		        // This is now done earlier, to account for fields in repeaters.
		        // $FieldType = PerchFieldTypes::get($tag->type(), $Form, $tag, $tags);

		        if ($id===null) {
		        	$input_id = 'perch_'.$tag->id();
		        }else{
		        	$input_id = 'perch_'.$id.'_'.$tag->id();
		        }

		        if (!in_array($tag->id(), $seen_tags)) {
		            if (PerchUtil::bool_val($tag->required())) {

		            	if (!PERCH_RUNWAY && $tag->runway()) {
			            	continue;
						}

		                if ($tag->type() == 'date' && !$tag->native()) {
		                    if ($tag->time()) {
		                        $req[$input_id.'_minute'] = "Required";
		                    }else{
		                        $req[$input_id.'_year'] = "Required";
		                    }
		                }else{
		                    $req[$input_id] = "Required";
		                }

		            }

		            $seen_tags[] = $tag->id();
		        }
		    }


		    $Form->set_required($req, false);

		}
	}

	public static function display_item_fields($tags, $id, $item, $Page, $Form, $Template, $blocks_link_builder=array('PerchContent_Util', 'get_block_link'), $seen_tags=array())
	{
	    //PerchUtil::debug($tags, 'success');
	    //$seen_tags = array();

		if (!PerchUtil::count($tags)) return;

	    foreach($tags as $tag) {

	    	if ($id===null) {
	    		$item_id = 'perch_'.$tag->id();
	    		$tag->set('input_id', $item_id);
	    		$tag->set('post_prefix', 'perch_');
	    	}else{
				$item_id = 'perch_'.$id.'_'.$tag->id();
				$tag->set('input_id', $item_id);
				$tag->set('post_prefix', 'perch_'.$id.'_');
	    	}

	        if (is_object($Page)) $tag->set('page_id', $Page->id());



	        if (!in_array($tag->id(), $seen_tags) && $tag->type()!='hidden' && $tag->type()!='editcontrol' && substr($tag->id(), 0,7)!='parent.') {

	            if ($tag->type()=='slug' && !$tag->editable()) {
	                continue;
	            }

	            if (!PERCH_RUNWAY && $tag->runway()) {
	            	continue;
	            }

	            //PerchUtil::debug($tag->type(), 'success');

	            if ($tag->type()=='PerchRepeater') {
	                $repeater_id = $id.'_'.$tag->id();


	                if ($tag->divider_before()) {
	                    echo '<h2 class="divider"><div>'.PerchUtil::html($tag->divider_before()).'</div></h2>';
	                }

	                if ($tag->notes_before()) {
	                    echo '<p class="notes-before">'.PerchUtil::html($tag->notes_before()).'</p>';
	                }

	                echo '<h3 class="repeater-heading"><div>'.$tag->label().'</div></h3>';
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

	                            $edit_prefix = 'perch_'.$repeater_id.'_'.$repeater_i.'_';
	                            foreach($subitem as $key=>$val) {
	                                $subitem[$edit_prefix.$key] = $val;
	                            }

	                            echo '<div class="repeated-item">';
	                                echo '<div class="index"><span class="number">'.($repeater_i+1).'</span><span class="icon"></span><div class="rm"></div> '.PerchUI::icon('core/menu', 12).'</div>';
	                                echo '<div class="repeated-fields">';
	                                PerchContent_Util::display_item_fields($tag->tags, $repeater_id.'_'.$repeater_i, $subitem, $Page, $Form, $Template);
	                                echo '<input type="hidden" name="perch_'.($repeater_id.'_'.$repeater_i).'_present" class="present" value="1" />';
	                                echo '<input type="hidden" name="perch_'.($repeater_id.'_'.$repeater_i).'_prevpos" value="'.$repeater_i.'" />';
	                                echo '</div>';
	                                //echo '<div class="rm"></div>';
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
	                            echo '<div class="index"><span class="number">'.($repeater_i+1).'</span><span class="icon"></span><div class="rm"></div> '.PerchUI::icon('core/menu', 12).'</div>';
	                                echo '<div class="repeated-fields">';
	                                PerchContent_Util::display_item_fields($tag->tags, $repeater_id.'_'.$repeater_i, array(), $Page, $Form, $Template);
	                                echo '<input type="hidden" name="perch_'.($repeater_id.'_'.$repeater_i).'_present" class="present" value="1" />';
	                                echo '</div>';
	                               // echo '<div class="rm"></div>';
	                        echo '</div>';
	                        echo '</div>'; // .repeated
	                        // footer
	                        echo '<div class="repeater-footer">';
	                        	echo '<span class="repeater-add-prompt">'.PerchUI::icon('core/add', 20).'</span>';
	                            echo '<input type="hidden" name="perch_'.$repeater_id.'_count" value="0" class="count" />';
	                        echo '</div>';
	                    }


	                echo '</div>';

	                if ($tag->divider_after()) {
	                    echo '<h2 class="divider"><div>'.PerchUtil::html($tag->divider_after()).'</div></h2>';
	                }
	            }elseif ($tag->type()=='PerchBlocks') {

	            	if ($tag->divider_before()) {
	                    echo '<h2 class="divider"><div>'.PerchUtil::html($tag->divider_before()).'</div></h2>';
	                }

	                if ($tag->notes_before()) {
	                    echo '<p class="notes-before">'.PerchUtil::html($tag->notes_before()).'</p>';
	                }

	                echo PerchContent_Util::display_blocks($tags, $id, $item, $Page, $Form, $Template, $blocks_link_builder);

	                if ($tag->divider_after()) {
	                    echo '<h2 class="divider"><div>'.PerchUtil::html($tag->divider_after()).'</div></h2>';
	                }

	            }else{

	            	$FieldType = PerchFieldTypes::get($tag->type(), $Form, $tag, false, $Form->app_id);

	                if ($tag->divider_before()) {
	                    echo '<h2 class="divider"><div>'.PerchUtil::html($tag->divider_before()).'</div></h2>';
	                }

	                if ($tag->notes_before()) {
	                    echo '<p class="notes-before">'.PerchUtil::html($tag->notes_before()).'</p>';
	                }

	                $inputs = $FieldType->render_inputs($item);
	                $help   = $Template->find_help($tag->id);
	                $wrap_class = $FieldType->get_wrapper_class();

	                echo '<div class="field-wrap '.$Form->error($item_id, false).($help ? ' with-detailed-help':'').'">';
	                
	                    $label_text  = PerchUtil::html($tag->label());

	                    if ($wrap_class) {
	                		echo '<div class="'.PerchUtil::html($wrap_class, true).'">';
	                	}

	                    $Form->disable_html_encoding();
	                    echo $Form->label($item_id, $label_text, '', false, false);
	                    $Form->enable_html_encoding();

	                    
	                    if ($help) {
	                    	echo '<div class="input-detailed-help">'.PerchUI::icon('core/info-alt', 14).' '.$help.'</div>';
	                    }

	                    if ($FieldType->hints_before) {
	                    	if ($tag->help()) {
		                        echo $Form->translated_hint($tag->help());
		                    }	
	                    }

	                    echo '<div class="form-entry">';
	                    echo $inputs;
	                    echo '</div>';

	                    if ($wrap_class) {
	                		echo '</div>';
	                	}

	                    if (!$FieldType->hints_before) {
	                    	if ($tag->help()) {
		                        echo $Form->translated_hint($tag->help());
		                    }	
	                    }

	                echo '</div>';

	                if ($tag->divider_after()) {
	                    echo '<h2 class="divider"><div>'.PerchUtil::html($tag->divider_after()).'</div></h2>';
	                }

	            }


	            $seen_tags[] = $tag->id();
	        }else{
	            if (!in_array($tag->id(), $seen_tags) && $tag->edit_control()) {
	                // Hidden fields for editing purposes.
	                $FieldType = PerchFieldTypes::get('editcontrol', $Form, $tag, false, $Form->app_id);
	                echo $FieldType->render_inputs($item);
	                $seen_tags[] = $tag->id();
	            }
	        }

	    }
	}


	public static function display_blocks($tags, $id, $item, $Page, $Form, $Template, $blocks_link_builder)
	{
	    $block_tags = $Template->find_all_tags('block');
	    $blocks_index = array();
	    if (PerchUtil::count($block_tags)) {
	        foreach($block_tags as $Tag) {
	            if ($Tag->is_set('type')) {
	                $blocks_index[$Tag->type] = $Tag;
	            }
	        }
	    }
	    $blocks_data = array();
	    if (isset($item['_blocks'])) $blocks_data = $item['_blocks'];

	    $stamp = time();

	    echo '<div class="blocks">';
	    if (PerchUtil::count($blocks_data)) {

	    	$flat_data = array();
	    	if ($id===null) {
	    		$flat_data = PerchContent_Util::flatten_details($blocks_data, '_blocks', false, true);
	    	}

	        foreach($blocks_data as $count => $block_data) {
	        	if (isset($flat_data[$count])) {
	        		$block_data = array_merge($block_data, $flat_data[$count]);
	        	}
	        	self::display_block($id, $blocks_index, $block_data, $count, $Page, $Template, $Form, $stamp);
	        }
	    }

	    echo '<div class="master block-add-bar '.(PerchUtil::count($blocks_data)?'hidden':'').'" tabindex="0">';
	    	echo '<span class="block-add-prompt">'.PerchUI::icon('core/add', 20, PerchLang::get('Add')).'</span>';
			$qs        = array();
			$qs['id']  = PerchUtil::get('id');
			$qs['itm'] = $id;

	        foreach($blocks_index as $BlockTag) {

	        	$qs['add-block'] = $BlockTag->type();

	        	$url = call_user_func($blocks_link_builder, $qs);

	            echo '<a href="'.PerchUtil::html($url, true).'" class="block-add" tabindex="0">';
	            if ($BlockTag->icon()) {
	            	echo PerchUI::icon('blocks/'.$BlockTag->icon(), 10).' ';
	            }
	            echo PerchUtil::html($BlockTag->label());
	            echo '</a> ';
	        }
	    echo '</div>';

	    echo '</div>'; // .blocks
	}

	public static function display_block($id, $blocks_index, $block_data, $count, $Page, $Template, $Form, &$stamp, $empty=false)
	{
		if ($id===null) {
			$block_id = '_blocks_'.$count;
		}else{
			$block_id = $id.'__blocks_'.$count;
		}

		$label = false;
		if (isset($blocks_index[$block_data['_block_type']])) {
		    $Tag = $blocks_index[$block_data['_block_type']];
		    $label = $Tag->label();
		}

		if (!$label) {
		    $label = $block_data['_block_type'];
		}

		if (!isset($block_data['_block_id'])) {
		    $block_data['_block_id'] = base_convert($stamp, 10, 36);
		    $stamp++;
		}

		$block_tags = $Template->get_block_tags($block_data['_block_type']);
		$block_tags[] = new PerchXMLTag('<perch:'.$Template->namespace.' id="_block_type" value="'.$block_data['_block_type'].'" type="editcontrol" edit-control="true" />');
		$block_tags[] = new PerchXMLTag('<perch:'.$Template->namespace.' id="_block_id" value="'.$block_data['_block_id'].'" type="editcontrol" edit-control="true" />');
		$block_tags[] = new PerchXMLTag('<perch:'.$Template->namespace.' id="_block_index" class="index" value="'.$count.'" type="editcontrol" edit-control="true" />');

		echo '<div class="block-wrap" tabindex="0" data-block="'.$block_data['_block_id'].'" data-prefix="'.$block_id.'"'.($empty?' data-empty="true"':'').'>';
		    echo '<div class="block-item">';
		        echo '<h2 class="divider"><div>'.PerchUtil::html($label).'</div><span class="rm"></span></h2>';
		        PerchContent_Util::display_item_fields($block_tags, $block_id, $block_data, $Page, $Form, $Template);
		    echo '</div>';
		echo '</div>';
	}

	public static function get_empty_block($id, $block_type, $block_index, $Page, $Template, $Form)
	{
		$stamp = time();
		$block_data = array();
		$block_data['_block_type'] = $block_type;

		$block_tags = $Template->find_all_tags('block');
		$blocks_index = array();
		if (PerchUtil::count($block_tags)) {
		    foreach($block_tags as $Tag) {
		        if ($Tag->is_set('type') && $Tag->type()==$block_type) {
		            $blocks_index[$Tag->type] = $Tag;
		        }
		    }
		}


		$block_id = $id.'__blocks_'.$block_index;
		$tags_for_block = $Template->get_block_tags($block_data['_block_type']);
		self::set_required_fields($Form, $block_id, $block_data, $tags_for_block, $Template);


		self::display_block($id, $blocks_index, $block_data, $block_index, $Page, $Template, $Form, $stamp, $empty=true);
	}

	public static function get_block_link($qs)
	{
		return 'block/add/?'.http_build_query($qs);
	}

}