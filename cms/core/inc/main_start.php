<?php
	$Perch       = Perch::fetch();
	$Settings    = PerchSettings::fetch();
	$Users       = new PerchUsers;
	$CurrentUser = $Users->get_current_user();
?>
<div class="main"><?php
	$section   = $Perch->get_section();
?>
<div class="topbar <?php echo $Settings->get('headerScheme')->settingValue();?>">
    <ul class="dashnav">
		<?php
			if ($Settings->get('dashboard')->settingValue()) {
				echo '<li class="'.($section=='core/dashboard' ? 'selected' : '').'"><a href="'.PERCH_LOGINPATH.'/core/dashboard/" class="icon dash"><span>'.PerchLang::get('Dashboard').'</span></a></li>';
			}
		?>
        <li><a href="<?php
				if ($Settings->get('siteURL')->settingValue()) {
	                $path = $Settings->get('siteURL')->settingValue();
	            }else{
	                $path = '/';
	            }
				echo PerchUtil::html($path);
			
			?>" class="icon site assist"><span><?php echo PerchLang::get('My Site'); ?></span></a></li>
    </ul>
    <?php
        if ($CurrentUser->logged_in()) {
                        
            $nav   = $Perch->get_apps(true);
			$app_count = PerchUtil::count($nav);

            if (is_array($nav)) {
                echo '<ul class="mainnav">';
                
                // content app - special status
                if ($nav[0]['section']=='core/apps/content') {
                    $item = $nav[0];
                    echo ($item['section'] == $section ? '<li class="selected">' : '<li>');
                    echo '<a href="'.PerchUtil::html($item['path']).'/">'.PerchUtil::html($item['label']).'</a></li>';
                    array_shift($nav);
                }
                 
                // assets app - special status
                if ($nav[0]['section']=='core/apps/assets') {
                    $item = $nav[0];
                    echo ($item['section'] == $section ? '<li class="selected">' : '<li>');
                    echo '<a href="'.PerchUtil::html($item['path']).'/">'.PerchUtil::html($item['label']).'</a></li>';
                    array_shift($nav);
                }

                // others   
				if ($app_count>2) {
	                echo '<li id="appmenu" class="apps">';
	                    echo '<ul class="appmenu">';
	                            foreach($nav as $item) {
	                                echo ($item['section'] == $section ? '<li class="selected">' : '<li>');
	                                if (isset($item['create_page'])) {
	                                	echo '<a href="'.PerchUtil::html($item['path'].'/'.$item['create_page']).'/" class="add">+</a>';
	                                }

	                                echo '<a href="'.PerchUtil::html($item['path']).'">'.PerchUtil::html($item['label']).'</a>';
	                                
	                                echo '</li>';
	                            }
	                    echo '</ul>';
	                echo '</li>';
				}
                
                // users
                if ($CurrentUser->has_priv('perch.users.manage') && !PERCH_AUTH_PLUGIN) {
                    $item = array('path'=>PERCH_LOGINPATH.'/core/users/', 'label'=>'Users', 'section'=>'core/users');
                    echo ($item['section'] == $section ? '<li class="selected">' : '<li>');
                    echo '<a href="'.PerchUtil::html($item['path']).'">'.PerchUtil::html(PerchLang::get($item['label'])).'</a></li>';
                }
                
                echo '</ul>';
				if (PerchUtil::count($nav)>1) echo '<script type="text/javascript">document.getElementById(\'appmenu\').parentNode.style.display=\'none\';</script>';
			    
            }
        }
    ?>
</div>
<div class="body">
	<div class="inner">