<?php

class PerchSystemEvent
{
	public $event   = false;
	public $subject = false;
	public $user 	= false;
	public $runtime = false;
	public $args 	= array();

	public function __construct($args)
	{
		if (PerchUtil::count($args)) {
			$this->event   = array_shift($args);
			$this->subject = array_shift($args);
			$this->args    = $args;
		
			$Perch = Perch::fetch();
			
			if ($Perch->admin) {
				$Users       = new PerchUsers;
				$this->user  = $Users->get_current_user();
			}else{
				$this->runtime = true;
			}
			
		}

	}

}