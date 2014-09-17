<?php

class PerchBlog_Author extends PerchAPI_Base
{
    protected $table        = 'blog_authors';
    protected $pk           = 'authorID';
    protected $index_table  = 'blog_index';
    protected $namespace    = 'blog';
    protected $event_prefix = 'blog.author';

}