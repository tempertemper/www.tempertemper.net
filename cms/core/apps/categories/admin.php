<?php
	if ($CurrentUser->has_priv('categories.manage')) {
		$this->register_app('categories', 'Categories', 1.2, 'Category management', $this->version);	
	}
    