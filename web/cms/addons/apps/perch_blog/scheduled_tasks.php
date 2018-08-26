<?php

	PerchScheduledTasks::register_task('perch_blog', 'delete_spam_comments', 1440, 'scheduled_blog_delete_spam_comments');
	PerchScheduledTasks::register_task('perch_blog', 'publish_posts', 1, 'scheduled_blog_publish_posts');
	PerchScheduledTasks::register_task('perch_blog', 'process_webmentions', 1, 'scheduled_blog_process_webmentions');

	function scheduled_blog_delete_spam_comments($last_run)
	{
		$API  = new PerchAPI(1.0, 'perch_blog');
		$Settings = $API->get('Settings');

		$days = $Settings->get('perch_blog_max_spam_days')->val();

		if (!$days) return array(
				'result'=>'OK',
				'message'=> 'Spam message deletion not configured.'
			);

		$count = perch_blog_delete_old_spam($days);

		if ($count == 1) {
			$comments = 'comment';
		}else{
			$comments = 'comments';
		}

		return array(
				'result'=>'OK',
				'message'=>$count.' old spam '.$comments.' deleted.'
			);
	}

	function scheduled_blog_publish_posts($last_run)
	{
		$API  = new PerchAPI(1.0, 'perch_blog');
		$Posts = new PerchBlog_Posts($API);

		$count = $Posts->publish_posts();

		if ($count == 1) {
			$msg = "1 post published.";
		} else {
			$msg = $count. ' posts published.';
		}

		return array(
				'result'=>'OK',
				'message'=> $msg,
			);

	}

	function scheduled_blog_process_webmentions($last_run)
	{
		$API  = new PerchAPI(1.0, 'perch_blog');
		$Settings = $API->get('Settings');

		if ($Settings->get('perch_blog_webmention_rx')->val()) {
			$Webmentions = new PerchBlog_WebmentionProvider($API);
			$count = $Webmentions->process_mention_queue();
		} else {
			$count = 0;
		}

		if ($count == 1) {
			$msg = "1 webmention processed.";
		} else {
			$msg = $count. ' webmentions processed.';
		}

		return array(
				'result'=>'OK',
				'message'=> $msg,
			);
	}
