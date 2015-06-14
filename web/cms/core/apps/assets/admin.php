<?php
	if ($CurrentUser->has_priv('assets.manage')) {
    	$this->register_app('assets', 'Assets', 1.1, 'Asset management', $this->version);
    }
