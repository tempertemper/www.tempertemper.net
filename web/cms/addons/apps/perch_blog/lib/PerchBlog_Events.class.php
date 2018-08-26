<?php

class PerchBlog_Events
{
	public static function post_published($Event)
	{
		if (PERCH_PRODUCTION_MODE > PERCH_STAGING) {
			$Post = $Event->subject;

			$API  = new PerchAPI(1.0, 'perch_blog');
			$Webmentions = new PerchBlog_WebmentionProvider($API);
			$Webmentions->send_for_post($Post);
		}
	}
}