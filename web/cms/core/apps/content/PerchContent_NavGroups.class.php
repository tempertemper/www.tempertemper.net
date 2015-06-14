<?php

class PerchContent_NavGroups extends PerchFactory
{
    protected $singular_classname = 'PerchContent_NavGroup';
    protected $table    = 'navigation';
    protected $pk   	= 'groupID';

    protected $default_sort_column  = 'groupTitle';  
    
    
}
