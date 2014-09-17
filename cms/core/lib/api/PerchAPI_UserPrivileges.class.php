<?php

class PerchAPI_UserPrivileges extends PerchUserPrivileges
{

	/**
	 * Create a new privilege
	 * @param  string  $key     The privilege key, e.g. my_app.object.verb
	 * @param  string  $title   A human-readable description for the UI
	 * @param  integer $order=1 Display order within its section
	 * @return object           New UserPrivilege object instance
	 */
	public function create_privilege($key, $title, $order=1)
	{
		$Priv = $this->get_one_by('privKey', $key);

		if (is_object($Priv)) return $Priv;

		$data = array();
		$data['privKey'] = $key;
		$data['privTitle'] = $title;
		$data['privOrder'] = $order;

		return parent::create($data);
	}
}
