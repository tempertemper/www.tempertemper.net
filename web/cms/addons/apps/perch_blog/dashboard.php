<?php
	return function(){

		$API   = new PerchAPI(1, 'perch_blog');
    	$Lang  = $API->get('Lang');
    	$HTML  = $API->get('HTML');
    	
    	$Posts = new PerchBlog_Posts($API);
    	$posts = $Posts->get_recent(5);
		
		$Comments = new PerchBlog_Comments($API);
	    $comment_count = $Comments->get_count();

	    $comments = array();
		$comments['Pending']  = $Comments->get_count('PENDING');
		$comments['Live']     = $Comments->get_count('LIVE');
		$comments['Rejected'] = $Comments->get_count('REJECTED');
		$comments['Spam']     = $Comments->get_count('SPAM');


		$title  = $HTML->wrap('h2', $Lang->get('Blog'));
		$button = '<a class="button button-small button-icon icon-left action-info" href="'.$HTML->encode(PERCH_LOGINPATH.'/addons/apps/perch_blog/edit/').'"><div>'.PerchUI::icon('core/plus', 8).'<span>'.$Lang->get('Add post').'</span></div></a>';
		$header = $HTML->wrap('header', $title.$button);

		$body = '';

		if (PerchUtil::count($posts)) {

			$items = [];

			foreach($posts as $Post) {
				$s = '';
				
				$s .= '<a href="'.PerchUtil::html(PERCH_LOGINPATH.'/addons/apps/perch_blog/edit/?id='.$Post->id()).'">';
					if ($Post->postStatus()=='Draft') {
						$s .= PerchUI::icon('core/o-pencil', 12, $Lang->get('Draft')) .' ';
					}
					$s .= $HTML->encode($Post->postTitle());
				$s .= '</a>';

				$s .= '<span class="note">'.strftime(PERCH_DATE_SHORT, strtotime($Post->postDateTime())).'</span>';

				$items[] = $HTML->wrap('li', $s);
			}
			
			$body .= $HTML->wrap('ul.dash-list', implode('', $items));
		}


		if ($comment_count > 0) {

			$body .= $HTML->heading3('Comments');

			$items = [];
			$first = true;
			foreach($comments as $label=>$count) {
				$s = '';
				$s .= '<a href="'.$HTML->encode(PERCH_LOGINPATH.'/addons/apps/perch_blog/comments/?status='.strtolower($label)).'">';
					$s .= $HTML->encode($Lang->get($label));
				$s .= '</a>';
				$s .= '<span class="'.($first&&$count ? 'badge success' : 'note').'">'.$count.'</span>';
				
				$items[] = $HTML->wrap('li', $s);
				$first = false;
			}

			$body .= $HTML->wrap('ul.dash-list', implode('', $items));

		}

		$body = $HTML->wrap('div.body', $body);
		return $HTML->wrap('div.widget div.dash-content', $header.$body);

	};