<?php

class PerchAPI_PerchBlogComments
{
	public function query($post_slug, $opts)
	{
		$default_opts = array(
            'skip-template'        => true,
            'api'                  => true,
            'split-items'          => false,
            'filter'               => false,
            'paginate'             => false,
            'pagination-var'	   => 'cpage',
            'template'             => false,
            'sort'             	   => 'commentDateTime',
            'sort-order'       	   => 'ASC',
        );

        if (is_array($opts)) {
            $opts = array_merge($default_opts, $opts);
        }else{
            $opts = $default_opts;
        }

        $API  = new PerchAPI(1.0, 'perch_blog');

        $postID = false;

        if (is_numeric($post_slug)) {
            $postID = intval($post_slug);
        }else{
            $BlogPosts = new PerchBlog_Posts($API);
            $Post = $BlogPosts->find_by_slug($post_slug);
            if (is_object($Post)) {
                $postID = $Post->id();
            }
        }

        $Comments = new PerchBlog_Comments($API);

        return $Comments->get_custom($postID, $opts);

	}
}