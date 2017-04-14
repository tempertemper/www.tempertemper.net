<?php

class PerchContent_Lock extends PerchBase
{
    protected $table  = 'content_locks';
    protected $pk     = 'lockID';

	public function extend()
	{
		$this->update([
			'lockTime' => date('Y-m-d H:i:s'),
			]);
	}

	public function get_user()
	{
		$Users = new PerchUsers;
		$User = $Users->find($this->userID());

		if ($User) {
			return $User;
		}

		return null;
	}
}