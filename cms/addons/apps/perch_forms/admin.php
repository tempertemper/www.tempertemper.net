<?php
	if ($CurrentUser->logged_in() && $CurrentUser->has_priv('perch_forms')) {
	    $this->register_app('perch_forms', 'Forms', 1, 'Process data from web forms', '1.8.3');
	    $this->require_version('perch_forms', '2.8');
	}

