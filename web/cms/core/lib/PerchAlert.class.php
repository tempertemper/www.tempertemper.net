<?php

class PerchAlert
{
	public static $alerts	= array();

	public function set($type='success', $message='Thank you')
	{
		switch($type) {
			case 'error':
				$type = 'alert';
				break;
		}

		// type = success, notice, error
		PerchAlert::$alerts[] = array('type'=>$type, 'message'=>$message);
		
		PerchUtil::debug('Setting alert: ' . $message . ' ('.$type.')');
	}
	
	public function output($return=false)
	{
		$alerts	= PerchAlert::$alerts;
		$s		= '';
		
		for ($i=0; $i<PerchUtil::count($alerts); $i++) {

			$icon = '';

			switch($alerts[$i]['type']) {
				case 'alert':
					$icon = PerchUI::icon('core/face-pain');
					break;

				case 'warning':
					$icon = PerchUI::icon('core/alert');
					break;

				case 'draft':
					$alerts[$i]['type'] = 'warning';
					$icon = PerchUI::icon('core/pencil');
					break;

				case 'info':
					$icon = PerchUI::icon('core/info-alt');
					break;

				case 'success':
					$icon = PerchUI::icon('core/circle-check');
					break;
			}


			$s	.= '<div role="alert" class="notification notification-'.$alerts[$i]['type'].'">' .$icon. ($alerts[$i]['message']) . '</div>';
		}
		
		PerchAlert::$alerts	= array();

		if ($return) return $s;
		echo $s;
	}
	
}