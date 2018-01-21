<?php 
	return function($CurrentUser){

		$API    = new PerchAPI(1.0, 'core');
    	$Lang   = $API->get('Lang');
    	$HTML   = $API->get('HTML');

		$Pages = new PerchContent_Pages;
		$pages = $Pages->get_by_parent(0);

		$Regions = new PerchContent_Regions;
		$shared = $Regions->get_shared();

		$title  = $HTML->wrap('h2', $Lang->get('Pages'));
		$button = '';

		if ($CurrentUser->has_priv('content.pages.create')) {
			$button = '<a class="button button-small button-icon icon-left action-info" href="'.$HTML->encode(PERCH_LOGINPATH.'/core/apps/content/page/add/').'"><div>'.PerchUI::icon('core/plus', 8).'<span>'.$Lang->get('Add page').'</span></div></a>';
		}

		$header = $HTML->wrap('header', $title.$button);

		if (PerchUtil::count($pages)) {
			$items = [];
			if (PerchUtil::count($shared)) {				
				$s = '<a href="'.$HTML->encode(PERCH_LOGINPATH).'/core/apps/content/page/?id=-1">';
					$s .= $HTML->encode($Lang->get('Shared'));
				$s .= '</a>';
			
				$items[] = $HTML->wrap('li', $s);
			}

			foreach($pages as $Page) {
				$s = '<a href="'.$HTML->encode(PERCH_LOGINPATH.'/core/apps/content/page/?id='.$Page->id()).'">';
					$s .= $HTML->encode($Page->pageNavText());
				$s .= '</a>';

				$s .= '<span class="note">'.strftime(PERCH_DATE_SHORT .' @ '.PERCH_TIME_SHORT, strtotime($Page->pageModified())).'</span>';
				
				$items[] = $HTML->wrap('li', $s);
			}
			
			$s = $HTML->wrap('ul.dash-list', implode('', $items));

			$body = $HTML->wrap('div.body', $s);

			return $HTML->wrap('div.widget div.dash-content', $header.$body);
		}

		return '';

	};	