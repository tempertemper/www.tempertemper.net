<?php
    $API    = new PerchAPI(1.0, 'content');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');
    $Paging = $API->get('Paging');


    $Pages = new PerchContent_Pages;
    $Page  = false;

    // Find the page
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = (int) $_GET['id'];
        $Page = $Pages->find($id);
    }
    
    // Check we have a page
    if (!$Page || !is_object($Page)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/');
    }
    
    // Check permissions
    if (!$CurrentUser->has_priv('content.pages.attributes')) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/');
    }

    // Page attributes
    $Page->api($API);
    $Template = $API->get('Template');
    $status = $Template->set('pages/attributes/'.$Page->pageAttributeTemplate(), 'pages');


    if ($status == 404) {
        $Alert->set('notice', PerchLang::get('The page attribute template (%s) could not be found.', '<code>templates/pages/attributes/'.$Page->pageAttributeTemplate().'</code>'));
    }

    $details = $Page->to_array();

    $Form = $API->get('Form');

    $Form->handle_empty_block_generation($Template);
    
    
    $req = array();
    $req['pageTitle']   = "Required";
    $req['pageNavText'] = "Required";
    
    $Form->set_required($req);

    $Form->set_required_fields_from_template($Template, $details, array('pageTitle', 'pageNavText'));
    
    if ($Form->posted() && $Form->validate()) {
    	$postvars = array('pageTitle', 'pageNavText');
    	$data = $Form->receive($postvars);
    	   
        $existing = PerchUtil::json_safe_decode($Page->pageAttributes(), true);

    	$dynamic_fields = $Form->receive_from_template_fields($Template, $existing, $Pages, $Page);

        $data['pageAttributes'] = PerchUtil::json_safe_encode($dynamic_fields);


        $Page->update($data);

        // log resources
        
    	$Page->log_resources();

    	$Alert->set('success', PerchLang::get('Successfully updated'));

        $details = $Page->to_array();
    }

    $created = false;
    
    if (isset($_GET['created'])) {
        $Alert->set('success', PerchLang::get('Your page has been successfully created. Return to %spage listing%s', '<a href="'.PERCH_LOGINPATH .'/core/apps/content/" class="notification-link">', '</a>'));
        $created = true;
    }
