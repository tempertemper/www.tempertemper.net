<?php
	include(realpath(__DIR__ . '/../../../..').'/inc/pre_config.php');
    include(realpath(__DIR__ . '/../../../../..').'/config/config.php');
    include(PERCH_CORE . '/inc/loader.php');
    $Perch  = PerchAdmin::fetch();
    include(PERCH_CORE . '/inc/auth_light.php');

    if (!PerchRequest::post('key')) exit;
    if (!PERCH_RUNWAY) exit;

    $Locks = new PerchContent_Locks();

    if (PerchRequest::post('release', false)) {

    	// Release the lock
    	$Locks->release(PerchRequest::post('key'), $CurrentUser->id());
    	exit;

    } else {

    	// Request a lock 

    	$Lock = $Locks->request(PerchRequest::post('key'), $CurrentUser->id());

	    $result = [ 'status' => 'error' ];

	    if ($Lock) {
	    	if ($Lock->userID() == $CurrentUser->id()) {
	    		$result = [
	    			'status' => 'granted'
	    		];
	    	} else {

	    		$User = $Lock->get_user();

	    		$result = [
					'status'     => 'denied',
					'time'       => $Lock->lockTime(),
					'first_name' => $User->userGivenName(),
					'last_name'  => $User->userFamilyName(),
	    		];

	    		if (!PERCH_PARANOID) {
	    			$result['gravatar']	= md5($User->userEmail());
	    		}
	    	}
	    }

	    echo PerchUtil::json_safe_encode($result);
    }