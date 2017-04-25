<?php

class PerchAPI_PerchBlogPosts
{
	public function query($opts)
	{
		$default_opts = array(
            'skip-template' => true,
            'split-items'   => false,
            'filter'        => false,
            'paginate'      => false,
            'template'      => false,
            'api'           => true,
        );

        if (is_array($opts)) {
            $opts = array_merge($default_opts, $opts);
        }else{
            $opts = $default_opts;
        }

        $API  = new PerchAPI(1.0, 'perch_blog');
        $BlogPosts = new PerchBlog_Posts($API);

        $r = $BlogPosts->get_custom($opts);

        return $r;
	}
}