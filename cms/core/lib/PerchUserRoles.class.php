<?php

class PerchUserRoles extends PerchFactory
{
    protected $singular_classname = 'PerchUserRole';
    protected $table    = 'user_roles';
    protected $pk   = 'roleID';

    protected $default_sort_column  = 'roleTitle';  
}
