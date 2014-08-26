<?php

class PerchAlert
{
	
	private $alerts	= array();

	function __construct()
	{

	}
	
	
	public function set($type='success', $message='Thank you')
	{
		// type = success or failure
		$this->alerts[] = array('type'=>$type, 'message'=>$message);
		
		PerchUtil::debug('Setting alert: ' . $message . ' ('.$type.')');
	}
	
	public function output($return=false)
	{
		$alerts	= $this->alerts;
		$s		= '';
		
		for ($i=0; $i<PerchUtil::count($alerts); $i++) {
			$s	.= '<p class="alert '.$alerts[$i]['type'].'">' . ($alerts[$i]['message']) . '</p>';
		}
		
		$this->alerts	= array();

		if ($return) return $s;
		echo $s;
	}
	
}


?>