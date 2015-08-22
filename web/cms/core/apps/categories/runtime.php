<?php
	#include(PERCH_CORE.'/apps/categories/PerchCategories_Categories.class.php');
	#include(PERCH_CORE.'/apps/categories/PerchCategories_Category.class.php');
	#include(PERCH_CORE.'/apps/categories/PerchCategories_Sets.class.php');
	#include(PERCH_CORE.'/apps/categories/PerchCategories_Set.class.php');

	spl_autoload_register(function($class_name){
        if (strpos($class_name, 'PerchCategories')===0) {
            include(PERCH_CORE.'/apps/categories/'.$class_name.'.class.php');
            return true;
        }
        return false;
    });

	function perch_categories($opts=array(), $return=false)
	{
		$opts = PerchUtil::extend(array(
			'set'           => false,
			'skip-template' => false,
			'template'      => 'category.html',
		), $opts);

		$Categories = new PerchCategories_Categories();

		$r = $Categories->get_custom($opts);

		if ($opts['skip-template']) $return = true;

		if ($return) return $r;

		echo $r;
	}

	function perch_category($path, $opts=array(), $return=false)
	{
		$path = rtrim(ltrim($path, '/'), '/').'/';

		$opts = PerchUtil::extend(array(
			'set'           => false,
			'skip-template' => false,
			'template'      => 'category.html',
			'filter'		=> 'catPath',
			'match'			=> 'eq',
			'value'			=> $path,
		), $opts);

		$Categories = new PerchCategories_Categories();

		$r = $Categories->get_custom($opts);

		if ($opts['skip-template']) $return = true;

		if ($return) return $r;

		echo $r;
	}
