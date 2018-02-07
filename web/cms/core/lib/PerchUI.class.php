<?php

class PerchUI
{

	private static $subnav = null;

	public static function icon($path, $size=16, $title=null, $custom_class=null)
	{
		$parts = explode('/', $path);
		$set  = $parts[0];
		$icon = $parts[1];

		$class = 'icon icon-'.$icon. ($custom_class ? ' '.PerchUtil::html($custom_class, true) : '');

		$title_atr = '';

		if ($title) {
			$title_atr = ' title="'.PerchUtil::html($title, true).'" aria-label="'.PerchUtil::html($title, true).'"';
		}

		return '<svg role="img" width="'.$size.'" height="'.$size.'" class="'.$class.'"'.$title_atr.'> <use xlink:href="'.PERCH_LOGINPATH.'/core/assets/svg/'.$set.'.svg#'.$icon.'" /> </svg>';
	}

	public static function gravatar($email, $size=24)
	{
		$s2   = (int)$size*2;
		$hash = md5($email);

		return "<img src=\"https://www.gravatar.com/avatar/$hash?s=$s2\" width=\"$size\" height=\"$size\" alt=\"\" class=\"avatar\">";
	}

	public static function set_subnav($data)
	{
		self::$subnav = $data;
	}

	public static function get_subnav()
	{
		return self::$subnav;
	}

	public static function has_subnav()
	{
		return (is_array(self::$subnav) && count(self::$subnav));
	}

	public static function render_subnav($CurrentUser, $title = '', $main_nav_title = '')
	{
		$API = new PerchAPI(1.0, 'core');
		$HTML = $API->get('HTML');

		$s = '';

		$subnav = PerchUI::get_subnav();
		$toplink = null;
		if (PerchUtil::count($subnav)) {
			if (is_array($subnav[0]['page'])) {
				$toplink = $subnav[0]['page'][0];
				$title = $HTML->wrap('a[href='.PerchUtil::subnav_link($toplink).']', $title);
			}
		}

		$Settings = $API->get('Settings');

		if ($Settings->get('sidebar_back_link')->val()) {
			$s .= $HTML->wrap('div.sidebar-back a[href='.PERCH_LOGINPATH.'].back', PerchUI::icon('core/o-navigate-left', 12). ' ' . $main_nav_title);	
		} else {
			$title = $HTML->wrap('a[href='.PERCH_LOGINPATH.'].back', PerchUI::icon('core/o-navigate-left', 16)). ' '.$title;	
		}

		

		//$title = $HTML->wrap('a[href='.PERCH_LOGINPATH.'].back', PerchUI::icon('core/o-birdhouse', 20)). ' '.$title;

		

		$s .= PerchUI::render_menu_heading($HTML, $title, 1, $toplink);

		$s .= PerchUtil::subnav($CurrentUser, $subnav);

		return $HTML->wrap('div.subnav-container', $s);
	}

	public static function render_menu($CurrentUser, PerchMenu $Menu, $level = 1)
	{
		$API = new PerchAPI(1.0, 'core');
		$HTML = $API->get('HTML');

		if (!$Menu->is_permitted($CurrentUser)) {
			return '';
		}

		$s = '';

		$s .= PerchUI::render_menu_heading($HTML, $Menu->title(), $level);

		$item_html = '';

		$items = $Menu->get_items();
		$Perch = PerchAdmin::fetch();
		$apps  = $Perch->get_app_ids();

		if (PerchUtil::count($items)) {
			foreach($items as $Item) {
				if ($Item->is_permitted($CurrentUser, $apps)) {
					$item_html .= PerchUI::render_menu_item($HTML, $Item);	
				}
			}
		}

		$s .= $HTML->wrap('ul.appmenu', $item_html);

		return $s;
	}

	public static function render_menu_heading($HTML, $heading, $level=1)
	{

		if ($level == 1) {
			return $HTML->wrap('header h2.sidebar-back', $heading);
		}
		
		return $HTML->wrap('h'.($level+1), $heading);
	}

	public static function render_menu_item($HTML, PerchMenuItem $Item)
	{
		$s = '';

		$url = $Item->get_link();

		$class = '';
		if (strpos($url, 'http')===0) {
			$class = '.viewext';
		}
		
		$s .= $HTML->wrap('a[href='.$url.']'.$class, $Item->itemTitle());


		return $HTML->wrap('li', $s);
	}

	public static function render_dialog($HTML, $id, $title)
	{
		$str_title   = $HTML->wrap('h1#'.$id.'-title', $title);
		$str_doc     = $HTML->wrap('div[role=document].dialog-body', $str_title);
		$str_button  = $HTML->wrap('button[type=button][data-a11y-dialog-hide][aria-label="'.PerchLang::get('Close dialog window').'"].dialog-close', PerchUI::icon('core/cancel', 24, PerchLang::get('Close dialog window')));
		$str_dialog  = $HTML->wrap('div[role=dialog][aria-labelledby='.$id.'-title].dialog-content', $str_doc.$str_button);
		$str_overlay = $HTML->wrap('div[tabindex=-1][data-a11y-dialog-hide].dialog-overlay');
		$str_wrap    = $HTML->wrap('div#'.$id.'[aria-hidden=true].dialog', $str_overlay.$str_dialog);
		return $str_wrap;
	}

	public static function format_date($date) 
	{
		$t = strtotime($date);
		return strftime(PERCH_DATE_LONG.' '.PERCH_TIME_SHORT, $t);
	}

	public static function render_progress_list($HTML, $results)
	{
		if (!PerchUtil::count($results)) return '';

        $items = [];
        foreach($results as $result) {
            switch($result['status']) {
                case 'failure':
                    $icon  = PerchUI::icon('core/face-pain');
                    $class = '.progress-alert';
                    break;

                case 'warning':
                    $icon  = PerchUI::icon('core/alert');
                    $class = '.progress-warning';
                    break;

                default:
                    $icon  = PerchUI::icon('core/circle-check');
                    $class = '.progress-success';
                    break;
            }
            $items[] = $HTML->wrap('li.progress-item'.$class, $icon . ' ' .$result['message']);
        }
        return $HTML->wrap('ul.progress-list', implode('', $items));
	}
}