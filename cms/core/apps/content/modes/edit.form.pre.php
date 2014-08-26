<?php
    $place_token_on_main = false;
    
    // test to see if image folder is writable
    $image_folder_writable = is_writable(PERCH_RESFILEPATH);

    // set the current user
	$Region->set_current_user($CurrentUser->id());    

    // get options
    $options = $Region->get_options();

	// get Page
	$Pages = new PerchContent_Pages;
	$Page = $Pages->find($Region->pageID());

    if (!is_object($Page)) {
        $Page = $Pages->get_mock_shared_page();
    }

    // get details
    
    if (isset($item_id) && $item_id) {
        // Get the specified item ('detail' mode);
        $details    = $Region->get_items_for_editing($item_id);
    }else{
        $details    = $Region->get_items_for_editing();
        
        if (PerchUtil::count($details)==0) {
            $Region->add_new_item();
        }
        
        $details    = $Region->get_items_for_editing();
    }
        
    $item_count = PerchUtil::count($details);

    $template_help_html = '';
    $mapcount = 0;
    $has_map = false;

    $Perch = Perch::fetch();

    /* --------- Undo Form ----------- */
    
    if ($Region->regionTemplate() != '') {
        
        $fUndo = new PerchForm('undo');

        if ($fUndo->posted()) {
        	if ($Region->revert_most_recent()) {
                
                $Region->index();

                if (isset($item_id) && $item_id) {
                    $details    = $Region->get_items_for_editing($item_id);
                }else{
                    $details    = $Region->get_items_for_editing();
                }

                $Perch->event('page.publish', $Page);
        	    
        	    $Alert->set('success', PerchLang::get('Your most recent change has been reverted.'));
        	}else{
        	    $Alert->set('error', PerchLang::get('There was nothing to undo.'));
        	}
            
        }   
    }
    
    
        

    /* --------- Edit Form ----------- */
    
    
    if ($Region->regionTemplate() != '') {

        $Resources = new PerchResources;

        $Template = new PerchTemplate('content/'.$Region->regionTemplate(), 'content');

		if ($Template->status==404) {
			$Alert->set('error', PerchLang::get('The template for this region (%s) cannot be found.', '<code>'.$Region->regionTemplate().'</code>'));
		}

        $tags   = $Template->find_all_tags_and_repeaters('content');

                
        $template_help_html = $Template->find_help();
        
        $Form = new PerchForm('edit');
        
        $req = array();
        

        // initialise field types (add head javascript)
        $all_tags = $Template->find_all_tags('content');
        if (PerchUtil::count($all_tags)) {
            foreach($all_tags as $tag) {
                $FieldType = PerchFieldTypes::get($tag->type(), $Form, $tag, $all_tags);        
            }
        }
        


        // Check for required content
        if (is_array($tags)) {
            foreach($details as $item) {
                $id = $item['itemID'];
                set_required_fields($Form, $id, $item, $tags);
            }        
        }

        
        
        if ($Form->posted() && $Form->validate()) {
            
            // New rev
            $Region->create_new_revision();
            
            // Get items
            if (isset($item_id) && $item_id) {
                $items    = $Region->get_items_for_updating($item_id);
            }else{
                $items    = $Region->get_items_for_updating();
            }
            

            if (is_array($tags)) {

                if (PerchUtil::count($items)) {
                    
                    foreach($items as $Item) {
                        
                        $Item->clear_resources();

                        $id = $Item->itemID();
                        
                        $form_vars      = array();
                        $file_paths     = array();
                    	
                    	$search_text    = ' ';
                    	
                    	$form_vars['_id'] = $id;
                    
                        $postitems = $Form->find_items('perch_'.$id.'_');

                        $subprefix = '';
                                            
                        list($form_vars, $search_text) = read_items_from_post($Item, $tags, $subprefix, $form_vars, $postitems, $Form, $search_text, $options, $Resources);

                        $data = array();
                        $data['itemJSON']   = PerchUtil::json_safe_encode($form_vars);
                        $data['itemSearch'] = $search_text;
                        
                        $Item->update($data);
                        
                    }
                }
            }
            
            // Sort based on region options
            $Region->sort_items();
            
            // Publish (or not if draft)
            if (isset($_POST['save_as_draft'])) {        
                $Alert->set('success', PerchLang::get('Draft successfully updated'));     
            }else{
                $Region->publish();        
                $Alert->set('success', PerchLang::get('Content successfully updated'));
            }
            
       
          
            
	        // Alert any file upload errors
        	if ($_FILES) { 
        	    foreach($_FILES as $file) {
        	        if ($file['error']!=UPLOAD_ERR_NO_FILE && $file['error']!=UPLOAD_ERR_OK) {
        	            $Alert->set('error', PerchLang::get('File failed to upload'));
        	        }
        	    }
        	}

            
        	// delete unused resource files.
        	$Region->clean_up_resources();
                    
            
            // Index the region
            $Region->index();


            // Update the page modified date
            if (!$Region->has_draft()){
                $Page->update(array('pageModified'=>date('Y-m-d H:i:s')));
                $Perch->event('page.publish', $Page);
            }


            // Add a new item if Save & Add Another
            if ($Region->regionMultiple()=='1' && isset($_POST['add_another'])) {    
                $NewItem = $Region->add_new_item();   
                if ($Region->get_option('edit_mode')=='listdetail') {
                    PerchUtil::redirect(PERCH_LOGINPATH.'/core/apps/content/edit/?id='.$Region->id().'&itm='.$NewItem->itemID().'&created=true');    
                }     
                
            }
            
            
            if (isset($item_id) && $item_id) {
                $details    = $Region->get_items_for_editing($item_id);
            }else{
                $details    = $Region->get_items_for_editing();
            }
            
            // Check for required content, again
            if (is_array($tags)) {
                foreach($details as $item) {
                    $id = $item['itemID'];
                    set_required_fields($Form, $id, $item, $tags);
                }
            }

            
        }else{
            PerchUtil::debug('Form not posted or did not validate');
        }
        

    }
    
    if (!$image_folder_writable) {
        $Alert->set('error', PerchLang::get('Your resources folder is not writable. Make this folder (') . PerchUtil::html(PERCH_RESPATH) . PerchLang::get(') writable if you want to upload files and images.'));
    }
    
    // is it a draft?
    if ($Region->has_draft()) {
        $draft = true;
        
        if ($Region->regionPage() == '*') {
            $Alert->set('draft', PerchLang::get('You are editing a draft.'));
        }else{
            $path = rtrim($Settings->get('siteURL')->val(), '/');

            if ($Region->get_option('edit_mode')=='listdetail' && $Region->get_option('searchURL')!='') {
                $search_url = $Region->get_option('searchURL');  

                $Region->tmp_url_vars = $details[0];             
                $search_url = preg_replace_callback('/{([A-Za-z0-9_\-]+)}/', array($Region, 'substitute_url_vars'), $search_url);
                $Region->tmp_url_vars = false; 

                if (strpos($search_url, '?')!==false) {
                    $preview_url = $search_url . '&' . PERCH_PREVIEW_ARG.'=all';
                }else{
                    $preview_url = $search_url . '?' . PERCH_PREVIEW_ARG.'=all';
                }

            }else{
                $preview_url = $path.$Region->regionPage().'?'.PERCH_PREVIEW_ARG.'=all';
            }


            $Alert->set('draft', PerchLang::get('You are editing a draft.') . ' <a href="'.PerchUtil::html($preview_url).'" class="action draft-preview">'.PerchLang::get('Preview').'</a>');
        }
        
        
    }else{
        $draft = false;
    }
    

	if (isset($_GET['created'])) {
        $Alert->set('success', PerchLang::get('Content successfully updated and a new item added.'));
    }

    $Perch->add_javascript(PERCH_LOGINPATH.'/core/assets/js/repeaters.js?v='.$Perch->version);
    $Perch->add_css(PERCH_LOGINPATH.'/core/assets/css/repeaters.css?v='.$Perch->version);
    
    
    if (PerchUtil::count($details)) {
        $details = flatten_details($details);
    }
    

    
    /* Utilities for keeping logic readable */

    function flatten_details($details, $parent_itemID=false, $parent_key=false)
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
                        $out = flatten_details($field_val, $itemID, $field_key);
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

    function determine_title($Tag, $var, $options, $form_vars)
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

    function multi_implode($array, $glue='') 
    {
        $ret = '';

        foreach ($array as $item) {
            if (is_array($item)) {
                $ret .= multi_implode($item, $glue) . $glue;
            } else {
                $ret .= $item . $glue;
            }
        }

        return $ret;
    }

    function read_items_from_post($Item, $tags, $subprefix, $form_vars, $postitems, $Form, $search_text, $options, $Resources, $in_repeater=false) 
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
                        list($result, $search_text) = read_items_from_post($Item, $Tag->tags, $subprefix.$i, $new_form_vars, $new_postitems, $Form, $search_text, $options, $Resources, true);

                        if (strlen(multi_implode($result)) > 0) {
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
                        if (!is_array($var)) $var = stripslashes($var);
                        $form_vars[$Tag->id()] = $var;
                    
                        // title
                        $form_vars = determine_title($Tag, $var, $options, $form_vars);
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


    function set_required_fields($Form, $id, $item, $tags) 
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
                            set_required_fields($Form, $repeater_id.'_'.$repeater_i, array(), $tag->tags);
                        }
                    }else{
                        foreach($item[$tag->id()] as $subitem) {
                            set_required_fields($Form, $repeater_id.'_'.$repeater_i, $subitem, $tag->tags);    
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




    //PerchUtil::debug($details, 'error');

?>