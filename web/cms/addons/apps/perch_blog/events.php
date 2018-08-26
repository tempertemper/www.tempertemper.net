<?php

	if (PERCH_RUNWAY_ROUTED) {
        $shop_global_init = function(){
            $API  = new PerchAPI(1.0, 'perch_blog');
            $API->on('page.loaded', 'perch_blog_register_global_events');
        };
        $shop_global_init();
    }else{
        perch_blog_register_global_events();
    }


	function perch_blog_register_global_events()
	{
		$API = new PerchAPI(1.0, 'perch_blog');
        $Settings = $API->get('Settings');

        if ($Settings->get('perch_blog_webmention_tx')->val()) {
		  $API->on('blog.post.publish', 'PerchBlog_Events::post_published');
        }
	}