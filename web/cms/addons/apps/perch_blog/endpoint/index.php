<?php
	include(__DIR__.'/../../../../runtime.php');

	$API  = new PerchAPI(1.0, 'perch_blog');
	$protocol = $_SERVER['SERVER_PROTOCOL'];

	$source = perch_post('source');
	$target = perch_post('target');

	$postID = perch_get('pid');

	if (($source && $target) && ($source != $target) && $postID) {
		header($protocol . ' 202 Accepted');

		$Webmentions = new PerchBlog_WebmentionProvider($API);
		$Webmentions->receive_notification($source, $target, 'post', $postID);

		exit;
	}


	header($protocol . ' 400 Bad Request');
	exit;