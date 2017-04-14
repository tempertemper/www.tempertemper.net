<?php 

class PerchContent_Locks extends PerchFactory
{
	protected $singular_classname  = 'PerchContent_Lock';
	protected $table               = 'content_locks';
	protected $pk                  = 'lockID';
	
	protected $default_sort_column = 'lockTime';  

	const MAX_LOCK_TIME = 3600;


	public function request($key, $userID)
	{
		$this->expire_old_locks();

		$locks = $this->find_existing_locks($key);

		if (PerchUtil::count($locks)) {
			foreach($locks as $Lock) {
				if ($Lock->userID() == $userID) {
					$Lock->extend();
				}
			}
			return $Lock;
		} else {
			return $this->new_lock($key, $userID);
		}

		return false;
	}

	public function release($key, $userID)
	{
		$this->db->execute('DELETE FROM '.$this->table.' WHERE contentKey='.$this->db->pdb($key).' AND userID='.$this->db->pdb($userID));
	}

	private function new_lock($key, $userID)
	{
		return $this->create([
				'contentKey' => $key,
				'userID'	 => $userID,
				'lockTime'	 => date('Y-m-d H:i:s'),
			]);
	}

	private function find_existing_locks($key)
	{
		return $this->get_by('contentKey', $key);
	}

	private function expire_old_locks()
	{
		$oldest_lock_time = date('Y-m-d H:i:s', (time()-self::MAX_LOCK_TIME));
		$this->db->execute('DELETE FROM '.$this->table.' WHERE lockTime < ' .$this->db->pdb($oldest_lock_time));
	}
}
