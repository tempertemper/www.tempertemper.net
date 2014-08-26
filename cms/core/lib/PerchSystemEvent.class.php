<?php

class PerchSystemEvent
{
	public $subject = false;
	public $user 	= false;
	public $args 	= array();

	public function __construct($args)
	{
		if (PerchUtil::count($args)) {
			$this->subject = array_shift($args);
			$this->args    = $args;
					
			$Users       = new PerchUsers;
			$this->user  = $Users->get_current_user();
		}

	}

}