<?php

	return function() {

		$API   = new PerchAPI(1.0, 'perch_forms');
	    $Lang  = $API->get('Lang');
	    $HTML  = $API->get('HTML');
	    $Forms = new PerchForms_Forms($API);
	    $forms = $Forms->all();

	    $header  = $HTML->wrap('header h2', $Lang->get('Forms'));

	    $body = '';

	    if (PerchUtil::count($forms)) {
			

			$items = '';

			foreach($forms as $Form) {

				$s = '';
				
					$s .= '<a href="'.$HTML->encode(PERCH_LOGINPATH.'/addons/apps/perch_forms/responses/?id='.$Form->id()).'">';
						$s .= $HTML->encode($Form->formTitle());
					$s .= '</a>';
					$s .= '<a class="button button-small action" href="'.$HTML->encode(PERCH_LOGINPATH.'/addons/apps/perch_forms/responses/export/?id='.$Form->id()).'">';
						$s .= $Lang->get('CSV');
					$s .= '</a>';
					$responses = (int)$Form->number_of_responses();

					if ($responses == 1) {
						$s .= '<span class="note">'.$Lang->get('%s response', $responses).'</span>';	
					} else {
						$s .= '<span class="note">'.$Lang->get('%s responses', $responses).'</span>';
					}
					
	

				$items[] = $HTML->wrap('li', $s);
			}
			
			$body .= $HTML->wrap('ul.dash-list', implode('', $items));
		}

	    return $HTML->wrap('div.widget div.dash-content', $header.$body);

	};
    