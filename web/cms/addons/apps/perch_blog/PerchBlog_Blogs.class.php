<?php

class PerchBlog_Blogs extends PerchAPI_Factory
{
	protected $table               = 'blogs';
    protected $pk                  = 'blogID';
    protected $singular_classname  = 'PerchBlog_Blog';

	#protected $index_table         = 'blog_index';
    protected $namespace           = 'blog';

    protected $event_prefix        = 'blog.blog';

    protected $default_sort_column = 'blogTitle';


    public $static_fields   = array('blogTitle', 'blogSlug', 'setSlug', 'postTemplate');


    public function find($id)
    {
    	if (PERCH_RUNWAY) return parent::find((int)$id);
    	return parent::find(1);
    }

    public function get_custom($opts)
    {
        $opts['template'] = 'blog/'.$opts['template'];
        return $this->get_filtered_listing($opts);
    }
}