<?php

class PerchBlog_Blog extends PerchAPI_Base
{
    protected $table        = 'blogs';
    protected $pk           = 'blogID';

    protected $index_table  = 'blog_index';
    protected $event_prefix = 'blog.blog';

    public function blogPostCount()
    {
    	$sql = 'SELECT COUNT(*) FROM '.PERCH_DB_PREFIX.'blog_posts WHERE blogID='.$this->db->pdb((int)$this->id());
    	return $this->db->get_value($sql);
    }
}