<?php	
    echo $HTML->open('aside.sidebar div.sidebar-nav');

    $Menu = new PerchMenu();
    $menu = $Menu->get_menu();
    if (PerchUtil::count($menu)) {
        $MainMenu = $menu[0];
    } else {
        $MainMenu = false;
    }

    if ($MainMenu) {
        echo $HTML->wrap('div.appmenu-container', PerchUI::render_menu($CurrentUser, $MainMenu));
    }
    
    if (PerchUI::has_subnav()) {
        $app_id = $API->nav_app_id;
        if ($app_id === 'core') {
            $app_id = 'content';
            
            switch($Perch->get_section()) {

                case 'core/settings':
                    $app_id = 'settings';
                    break;

                case 'core/users':
                    $app_id = 'users';
                    break;

                case 'core/help':
                    $app_id = 'help';
                    break;

                default:
                    $app_id = 'content';
                    break;
            }  
        } 
        $subnav_title = $MainMenu->find_app_title($app_id);
        echo $HTML->wrap('div.submenu-container', PerchUI::render_subnav($CurrentUser, $subnav_title, $MainMenu->title()));
    }

    echo $HTML->close('div');

    $menus = $Menu->get_menu(0, 1, 10);
    
    if (PerchUtil::count($menus)) {
        $menu_html = '';

        foreach($menus as $UtilMenu) {
            $menu_html .= PerchUI::render_menu($CurrentUser, $UtilMenu, 2);
            if (!PERCH_RUNWAY) break;
        }

        echo $HTML->wrap('div.utilmenu-container', $menu_html);   
    }
	
    $hideBranding = $Settings->get('hideBranding')->val();
	if (!$hideBranding) {	        
		if (($CurrentUser->has_priv('perch.updatenotices')) && (version_compare($Perch->version, $Settings->get('latest_version')->settingValue(), '<'))) {
	        echo '<a href="https://grabaperch.com/update">' . sprintf(PerchLang::get('You are running version %s - a newer version is available.'), $Perch->version) . '</a>';
	    }
	}

    echo $HTML->close('aside');
