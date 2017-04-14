<?php
    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');

    $place_token_on_main = false;

    $lock_key = 'region:'.$Region->id();

    // test to see if image folder is writable
    $DefaultBucket = PerchResourceBuckets::get();
    $image_folder_writable = $DefaultBucket->ready_to_write();

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
    $mapcount           = 0;
    $has_map            = false;

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
                $Alert->set('alert', PerchLang::get('There was nothing to undo.'));
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

        //PerchUtil::debug($tags);

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
            $Region->create_new_revision();

            // Get items
            if (isset($item_id) && $item_id) {
                $items    = $Region->get_items_for_updating($item_id);
            }else{
                $items    = $Region->get_items_for_updating();
            }

            // Keep note of edited items
            $edited_items = array();

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

                        list($form_vars, $search_text) = PerchContent_Util::read_items_from_post($Item, $tags, $subprefix, $form_vars, $postitems, $Form, $search_text, $options, $Resources, false, $Template);

                        if (isset($form_vars['_blocks'])) {
                            $form_vars['_blocks'] = PerchUtil::array_sort($form_vars['_blocks'], '_block_index');
                        }

                        $data = array();
                        $data['itemJSON']   = PerchUtil::json_safe_encode($form_vars);
                        $data['itemSearch'] = $search_text;

                        //PerchUtil::debug($form_vars, 'success');

                        $Item->update($data);

                        $edited_items[] = $id;

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

            // Clear values from Post (for reordering of blocks etc)
            $_POST = array();// Slowly refactoring this. Baby steps.
            PerchRequest::reset_post();


            if (isset($item_id) && $item_id) {
                $details    = $Region->get_items_for_editing($item_id);
            }else{
                $details    = $Region->get_items_for_editing();
            }

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


            $Alert->set('draft', PerchLang::get('You are editing a draft.') . ' <a href="'.PerchUtil::html($preview_url).'" class="button button-small action-warning draft-preview viewext">'.PerchLang::get('Preview').'</a>');
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

    if ($Template && $CurrentUser->has_priv('templates.validate')) { // primary user only
        $Validator = new PerchTemplateValidator($Template, $Lang);
        $messages = $Validator->validate();
        if (PerchUtil::count($messages)) {
            foreach($messages as $msg) {
                $Alert->set($msg['status'], $msg['message']);
            }
        }
    }

