<?php
	include('../runtime/runtime.php');
	$s = filter_input(INPUT_GET, 's', FILTER_SANITIZE_STRING);
	if ($s) {
		echo PerchUtil::urlify($s);
	}