<?php

class PerchCategories_Set extends PerchBase
{
    protected $table  = 'category_sets';
    protected $pk     = 'setID';
    protected $event_prefix = 'category.set';


    public function delete()
    {
        $Categories = new PerchCategories_Categories();
        $categories = $Categories->get_by('setID', $this->id());

        if (PerchUtil::count($categories)) {
            foreach($categories as $Category) {
                $Category->delete();
            }
        }

        return parent::delete();
    }


    public function update_all_in_set()
    {
    	$Categories = new PerchCategories_Categories;
    	$categories = $Categories->get_by('setID', $this->id());

    	if (PerchUtil::count($categories)) {
    		foreach($categories as $Cat) {
    			$Cat->update_meta(false);
    		}
    	}
    }
}
