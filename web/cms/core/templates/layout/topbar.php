<?php
	$Perch       = Perch::fetch();
	$Settings    = PerchSettings::fetch();
	$Users       = new PerchUsers;
	$CurrentUser = $Users->get_current_user();

	$section = $Perch->get_section();

	$topbar_class = 'user_theme_light';
	if ($Settings->get('headerScheme')->val()=='dark') {
		$topbar_class = 'user_theme_dark';
	}

	if (PERCH_RUNWAY) {
		$topbar_class .= ' runway';
	}

	if ($Settings->get('hideBranding')->val()) {
		$topbar_class .= ' nobrand';
	}

	/*
		TODO: OMG look at that down there.
			This is parsed for every CP page load, so needs to be fast. Spinning up a template subsystem doesn't really make sense.
			This might actually be the most pragmatic solution. It very rarely changes, if ever.
	*/
?>
<header class="topbar <?php echo $topbar_class; ?> custom" role="banner">
    <nav class="dashnav">
        <ul>
        	<li class="sidebar-trigger"><a href="?sidebar=show"><?php echo PerchUI::icon('core/o-birdhouse', 30); ?></a></li>
<?php
	if ($Settings->get('dashboard')->val()) {
		echo '<li class="'.($section=='core/dashboard' ? 'selected' : '').'"><a title="'.PerchLang::get('Dashboard').'" href="'.PERCH_LOGINPATH.'/core/dashboard/">'.PerchUI::icon('core/o-dashboard', 32).'</a></li>';
	}
?>
			<li><a href="<?php echo PerchUtil::html($Settings->get('siteURL')->val() ?: '/'); ?>" class="viewext" title="<?php echo PerchLang::get('My Site'); ?>"><?php echo PerchUI::icon('core/o-world', 32); ?></a></li>
        </ul>
    </nav>
    <nav class="utilnav">
        <ul>
<?php if (PERCH_RUNWAY) { ?>
        	<li><a href="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/runway/search/" id="search-link"><?php echo PerchUI::icon('core/search'); ?> <span><?php echo PerchLang::get('Search'); ?></span></a></li>
<?php } ?>
<?php if ($CurrentUser->has_priv('perch.settings')) { ?>
		    <li><a href="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/settings/" class="<?php if ($Perch->get_section()=='core/settings') echo 'selected'; ?>"><?php echo PerchUI::icon('core/gear'); ?> <span><?php echo PerchLang::get('Settings'); ?></span></a></li>
<?php } // settings ?>
<?php if (!defined('PERCH_AUTH_PLUGIN') || !PERCH_AUTH_PLUGIN) { 


				if ($CurrentUser->has_priv('perch.users.manage')) {
?>
			<li><a href="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/users/" class="<?php if (in_array($Perch->get_section(), ['core/users','core/account'])) echo 'selected ';?>"><?php echo PerchUI::icon('core/users'); ?> <span><?php echo PerchLang::get('Users'); ?></span></a></li>
<?php
                } else {
?>
			<li><a href="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/account/" class="<?php if ($Perch->get_section()=='core/account') echo 'selected ';?>"><?php echo PerchUI::icon('core/user'); ?> <span><?php echo PerchLang::get('My Account'); 
				?></span></a></li>
<?php                	
                }

}// auth plugin ?>
<?php if ($Settings->get('helpURL')->val()) {
				echo '			<li><a href="'.PerchUtil::html($Settings->get('helpURL')->val()).'">'.PerchUI::icon('core/question').' <span>'.PerchLang::get('Help').'</span></a></li>';
            	}else{
				echo '			<li><a href="'.PERCH_LOGINPATH.'/core/help/">'.PerchUI::icon('core/question').' <span>'.PerchLang::get('Help').'</span></a></li>';
            	} 
            ?>
		
			<li><a href="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/?logout=1" class="button logout"><?php echo PerchLang::get('Log out'); ?></a></li>
        </ul>
    </nav>
</header>
