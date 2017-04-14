<?php
	include(PERCH_CORE.'/runway/apps/content/PerchContent_Collections.class.php');
	include(PERCH_CORE.'/runway/apps/content/PerchContent_Collection.class.php');
	include(PERCH_CORE.'/runway/apps/content/PerchContent_CollectionItems.class.php');
	include(PERCH_CORE.'/runway/apps/content/PerchContent_CollectionItem.class.php');
	include(PERCH_CORE.'/runway/apps/content/PerchContent_Runway.class.php');
	include(PERCH_CORE.'/runway/apps/content/PerchContent_RunwaySearch.class.php');

	PerchSystem::register_search_handler('PerchContent_RunwaySearch');

    if (PERCH_RUNWAY_ROUTED) {
        $Perch = Perch::fetch();
        $Perch->on('page.loaded', function(){
            perch_runway_content_check_preview();
        });
    }else{
        perch_runway_content_check_preview();
    }

	function perch_collection($key=false, $opts=false, $return=false)
    {
        if ($key === false) return ' ';

        if (isset($opts['skip-template']) && $opts['skip-template']==true) {
            $return  = true;
            $postpro = false;

            if (isset($opts['return-html']) && $opts['return-html']) {
                $postpro = true;
            }
        }else{
            $postpro = true;
        }

        if (isset($opts['data'])) PerchSystem::set_vars($opts['data']);

        $Content = PerchContent::fetch();
        $out = $Content->get_collection($key, $opts);

        // Post processing - if there are still <perch:x /> tags
        if ($postpro) {
            if (is_array($out)) {

                // return-html
                if (isset($out['html'])) {
                    if (strpos($out['html'], '<perch:')!==false) {
                        $Template = new PerchTemplate();
                        $out['html'] = $Template->apply_runtime_post_processing($out['html']);
                    }
                }

                // split-items
                if (PerchUtil::count($out) && !isset($out['html'])) {
                    $Template = new PerchTemplate();
                    foreach($out as &$html_item) {
                        if (strpos($html_item, '<perch:')!==false) {
                            $html_item        = $Template->apply_runtime_post_processing($html_item);
                        }
                    }
                }
            }else{
                if (strpos($out, '<perch:')!==false) {
                    $Template = new PerchTemplate();
                    $out     = $Template->apply_runtime_post_processing($out);
                }
            }
        }

        if ($return) return $out;
        echo $out;
        PerchUtil::flush_output();
    }

    function perch_runway_content_check_preview()
    {
        if (!defined('PERCH_PREVIEW_ARG')) define('PERCH_PREVIEW_ARG', 'preview');

        if (perch_get(PERCH_PREVIEW_ARG)) {

            $contentID   = 'all';
            $rev         = false;

            $var = perch_get(PERCH_PREVIEW_ARG);

            if ($var != 'all' && $var != 'preview') {
                $rev  = $var;
                if (strpos($rev, 'r')) {
                    $parts = explode('r', $rev);
                    $contentID = (int) $parts[0];
                    $rev       = (int) $parts[1];
                }
            }

            $Users       = new PerchUsers;
            $CurrentUser = $Users->get_current_user();

            if (is_object($CurrentUser) && $CurrentUser->logged_in()) {
                $Content = PerchContent::fetch();
                $Content->set_preview($contentID, $rev);
            }
        }
    }