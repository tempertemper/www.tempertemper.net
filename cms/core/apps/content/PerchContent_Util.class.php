<?php

class PerchContent_Util
{

	/* Utilities for keeping logic readable */

	public static function flatten_details($details, $parent_itemID=false, $parent_key=false)
	{
	    $details_flat = array();

	    $k = 0;
	    foreach($details as $detail) {
	        if (PerchUtil::count($detail)) {
	            $itemID = $parent_itemID ? $parent_itemID : $detail['itemID'];
	            
	            $tmp = array();

	            foreach($detail as $field_key => $field_val) {
	             
	                $new_key = 'perch_'.$itemID.'_'.$field_key;


	                if ($parent_itemID) $new_key = 'perch_'.$itemID.'_'.$parent_key.'_'.$k.'_'.$field_key;

	                $tmp[$new_key] = $field_val;
	                $tmp[$field_key] = $field_val;
	             

	                if (!$parent_key && is_array($field_val)) {
	                    $out = self::flatten_details($field_val, $itemID, $field_key);
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

	public static function read_items_from_post($Item, $tags, $subprefix, $form_vars, $postitems, $Form, $search_text, $options, $Resources, $in_repeater=false) 
	{
	    $seen_tags = array();

	    foreach($tags as $Tag) {                   

	        if (!in_array($Tag->id(), $seen_tags)) {


	            if ($Tag->type()=='PerchRepeater') {

	                //PerchUtil::debug('Reading: '.$Tag->id());

	                
	                $new_form_vars = array();

	                $i = 0;
	                $minor_prefix = $i.'_';

	                $subprefix .= $Tag->id().'_';
	                $new_postitems = $Form->find_items('perch_'.$Item->itemID().'_'.$subprefix.$i.'_');


	                //PerchUtil::debug('Looking for: '.'perch_'.$Item->itemID().'_'.$subprefix.$i.'_');
	                //PerchUtil::debug($new_postitems);

	                while (PerchUtil::count($new_postitems)) {
	                    list($result, $search_text) = self::read_items_from_post($Item, $Tag->tags, $subprefix.$i, $new_form_vars, $new_postitems, $Form, $search_text, $options, $Resources, true);

	                    if (strlen(self::multi_implode($result)) > 0) {
	                        $form_vars[$Tag->id()][] = $result;
	                    }
	                    
	                    $i++;
	                    $new_postitems = $Form->find_items('perch_'.$Item->itemID().'_'.$subprefix.$i.'_');
	                    
	                    if (!PerchUtil::count($new_postitems)) {
	                        //PerchUtil::debug('Not found: '.'perch_'.$Item->itemID().'_'.$subprefix.$i.'_', 'error');
	                    }
	                }
	                    
	                $subprefix = '';             

	            }else{

	                if ($subprefix) {
	                    $field_prefix = 'perch_'.$Item->itemID().'_'.$subprefix.'_'.$Tag->id();
	                }else{
	                    $field_prefix = 'perch_'.$Item->itemID().'_'.$Tag->id();
	                }
	                
	                //PerchUtil::debug('Form looking for field prefix: '.$field_prefix);

	                if ($in_repeater) {
	                    $Tag->set('in_repeater', true);
	                    $Tag->set('tag_context', $subprefix.'_'.$Tag->id());

	                    if (isset($_POST['perch_'.$Item->itemID().'_'.$subprefix.'_prevpos'])) {
	                        $parts = explode('_', $subprefix);
	                        array_pop($parts);
	                        $parts[] = (int)$_POST['perch_'.$Item->itemID().'_'.$subprefix.'_prevpos'];

	                        if (isset($_POST['perch_'.$Item->itemID().'_'.$subprefix.'_present'])) {
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
	        
	                $FieldType = PerchFieldTypes::get($Tag->type(), $Form, $Tag, $tags);
	                $FieldType->set_unique_id($Item->id());
	                
	                $var             = $FieldType->get_raw($postitems, $Item);
	                $search_text    .= $FieldType->get_search_text($var).' ';

	                            
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
	                        PerchUtil::debug($Tag->id(), 'error');    
	                        $form_vars[$Tag->id()] = null;
	                    }
	                    
	                }

	                // Resources
	                $resourceIDs = $Resources->get_logged_ids();
	                if (PerchUtil::count($resourceIDs)) {
	                    $Item->log_resources($resourceIDs);
	                }
	            }




	            $seen_tags[] = $Tag->id();
	        }
	        
	    }

	    return array($form_vars, $search_text);
	}


	public static function set_required_fields($Form, $id, $item, $tags) 
	{
	    $req = array();
	    $seen_tags = array();

	    //PerchUtil::debug($id);

	    foreach($tags as $tag) {

	        if ($tag->type()=='PerchRepeater') {  
	            $repeater_id = $id.'_'.$tag->id();
	            $repeater_i  = 0;

	            if (isset($item[$tag->id()]) && is_array($item[$tag->id()])) {
	                
	                if (isset($_POST['perch_'.$repeater_id.'_count'])){// && (int)$_POST['perch_'.$repeater_id.'_count']>0) {
	                    for($repeater_i=0; $repeater_i<(int)$_POST['perch_'.$repeater_id.'_count']; $repeater_i++) {
	                        self::set_required_fields($Form, $repeater_id.'_'.$repeater_i, array(), $tag->tags);
	                    }
	                }else{
	                    foreach($item[$tag->id()] as $subitem) {
	                        self::set_required_fields($Form, $repeater_id.'_'.$repeater_i, $subitem, $tag->tags);    
	                        $repeater_i++;
	                    }
	                }
	            }
	        }


	        // initialising the field type here makes sure editor plugins are kicked of in the <head>
	        // This is now done earlier, to account for fields in repeaters.
	        // $FieldType = PerchFieldTypes::get($tag->type(), $Form, $tag, $tags);

	        $input_id = 'perch_'.$id.'_'.$tag->id();
	        if (!in_array($tag->id(), $seen_tags)) {
	            if (PerchUtil::bool_val($tag->required())) {
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