<?php

    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');

    $Item     = false;
    $NextItem = false;
    $PrevItem = false;

    $place_token_on_main = false;

    // test to see if image folder is writable
    $DefaultBucket = PerchResourceBuckets::get();
    $image_folder_writable = $DefaultBucket->ready_to_write();

    // set the current user
	$Collection->set_current_user($CurrentUser->id());

    // get options
    $options = $Collection->get_options();

    // get details
    if (isset($item_id) && $item_id) {
        // Get the specified item ('detail' mode);
        $details    = $Collection->get_items_for_editing($item_id);
        $Item       = $Items->find_item($Collection->id(), $item_id);

        if ($Item) {
            $NextItem   = $Items->find_next_item($Item);
            $PrevItem   = $Items->find_previous_item($Item);
        }
    }

    $lock_key = 'collection:'.$Collection->id().':'.$item_id;

    $item_count         = 1;

    $template_help_html = '';
    $mapcount           = 0;
    $has_map            = false;

    $Perch = Perch::fetch();

    /* --------- Undo Form ----------- */

    if ($Collection->collectionTemplate() != '') {

        $fUndo = new PerchForm('undo');

        if ($fUndo->posted()) {
        	if ($Item->revert_most_recent()) {

                $Item->index();

                $details    = $Collection->get_items_for_editing($item_id);

                $Perch->event('collection.publish', $Collection);

        	    $Alert->set('success', PerchLang::get('Your most recent change has been reverted.'));
        	}else{
        	    $Alert->set('error', PerchLang::get('There was nothing to undo.'));
        	}

        }
    }




    /* --------- Edit Form ----------- */


    if ($Collection->collectionTemplate() != '') {

        $Resources = new PerchResources;

        $Template = new PerchTemplate('content/'.$Collection->collectionTemplate(), 'content');

		if ($Template->status==404) {
			$Alert->set('error', PerchLang::get('The template for this collection (%s) cannot be found.', '<code>'.$Collection->collectionTemplate().'</code>'));
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
                PerchContent_Util::set_required_fields($Form, $id, $item, $tags, $Template);
            }
        }


        if ($Form->posted() && $Form->validate()) {

            // New rev
            $Collection->create_new_revision($Item);
            $Item   = $Items->find_item($Collection->id(), $item_id);

            // Get items
            $items    = $Collection->get_items_for_updating($item_id);

            if (is_array($tags)) {

                if (PerchUtil::count($items)) {

                    foreach($items as $CollectionItem) {

                        $CollectionItem->clear_resources();

                        $id = $CollectionItem->itemID();

                        $form_vars      = array();
                        $file_paths     = array();

                    	$search_text    = ' ';

                    	$form_vars['_id'] = $id;

                        $postitems = $Form->find_items('perch_'.$id.'_');

                        $subprefix = '';

                        list($form_vars, $search_text) = PerchContent_Util::read_items_from_post($CollectionItem, $tags, $subprefix, $form_vars, $postitems, $Form, $search_text, $options, $Resources, false, $Template);

                        if (isset($form_vars['_blocks'])) {
                            $form_vars['_blocks'] = PerchUtil::array_sort($form_vars['_blocks'], '_block_index');
                        }

                        $data = array();
                        $data['itemJSON']   = PerchUtil::json_safe_encode($form_vars);
                        $data['itemSearch'] = $search_text;

                        $CollectionItem->update($data);
                    }
                }
            }


            // Publish (or not if draft)
            if (isset($_POST['save_as_draft'])) {
                $Alert->set('success', PerchLang::get('Draft successfully updated'));
            }else{
                if ($Collection->role_may_publish($CurrentUser)) {
                    $Item->publish();
                }
                $Alert->set('success', PerchLang::get('Content successfully updated'));
            }

            // Sort based on region options
            $Collection->sort_items($CollectionItem->itemID());


	        // Alert any file upload errors
        	if ($_FILES) {
        	    foreach($_FILES as $file) {
        	        if ($file['error']!=UPLOAD_ERR_NO_FILE && $file['error']!=UPLOAD_ERR_OK) {
        	            $Alert->set('error', PerchLang::get('File failed to upload'));
        	        }
        	    }
        	}


        	// delete unused resource files.
        	$Collection->clean_up_resources();


            // Index the region
            $Item->index();


            // Update the page modified date
            if (!$Item->has_draft()){
                $Collection->update(array('collectionUpdated'=>date('Y-m-d H:i:s')));
                $Perch->event('collection.publish', $Collection);
            }


            // Add a new item if Save & Add Another
            if (isset($_POST['add_another'])) {
                $NewItem = $Collection->add_new_item();
                PerchUtil::redirect(PERCH_LOGINPATH.'/core/apps/content/collections/edit/?id='.$Collection->id().'&itm='.$NewItem->itemID().'&created=true');
            }

            // Clear values from Post (for reordering of blocks etc)
            $_POST = array(); // Slowly refactoring this. Baby steps.
            PerchRequest::reset_post();

            $details    = $Collection->get_items_for_editing($item_id);

            // Check for required content, again
            if (is_array($tags)) {
                foreach($details as $item) {
                    $id = $item['itemID'];
                    PerchContent_Util::set_required_fields($Form, $id, $item, $tags, $Template);
                }
            }


        }else{
            PerchUtil::debug('Form not posted or did not validate');
        }


    }

    if (!$image_folder_writable) {
        $Alert->set('error', PerchLang::get('Your resources folder is not writable. Make this folder (') . PerchUtil::html($DefaultBucket->get_file_path()) . PerchLang::get(') writable if you want to upload files and images.'));
    }

    // is it a draft?
    if ($Item->has_draft()) {
        $draft = true;

        $path = rtrim($Settings->get('siteURL')->val(), '/');

        if ($Collection->get_option('searchURL')!='') {
            $search_url = $Collection->get_option('searchURL');

            $Collection->tmp_url_vars = $details[0];
            $search_url = preg_replace_callback('/{([A-Za-z0-9_\-]+)}/', array($Collection, 'substitute_url_vars'), $search_url);
            $Collection->tmp_url_vars = false;

            if (strpos($search_url, '?')!==false) {
                $preview_url = $search_url . '&' . PERCH_PREVIEW_ARG.'=all';
            }else{
                $preview_url = $search_url . '?' . PERCH_PREVIEW_ARG.'=all';
            }

            $Alert->set('draft', PerchLang::get('You are editing a draft.') . ' <a href="'.PerchUtil::html($preview_url).'" class="button button-small action-warning viewext">'.PerchLang::get('Preview').'</a>');

        }else{
            $Alert->set('draft', PerchLang::get('You are editing a draft.'));
        }




    }else{
        $draft = false;
    }


	if (isset($_GET['created'])) {
        $Alert->set('success', PerchLang::get('Content successfully updated and a new item added.'));
    }


    if (PerchUtil::count($details)) {
        $details = PerchContent_Util::flatten_details($details);
    }
