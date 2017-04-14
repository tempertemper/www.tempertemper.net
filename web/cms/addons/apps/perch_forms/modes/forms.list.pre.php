<?php
    
    $Forms = new PerchForms_Forms($API);

    $forms = $Forms->all();
    
    if (PerchUtil::count($forms)==0) {
        // Install
        $Forms->attempt_install();
    }
