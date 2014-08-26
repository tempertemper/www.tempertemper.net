<?php
    if ($CurrentUser->userMasterAdmin()) {
      	$Alert->set('notice' , 'For help configuring Perch and writing templates, visit the <a href="http://docs.grabaperch.com/">online documentation</a>.');
    }

?>