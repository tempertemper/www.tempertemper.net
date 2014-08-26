<?php

class PerchCategories_Category extends PerchBase
{
    protected $table        = 'categories';
    protected $pk           = 'catID';
    protected $event_prefix = 'category';

    public function update($data)
    {
    	$r = parent::update($data);
    	
    	$this->update_meta();

    	return $r; 	
    }

    public function update_tree_position($parentID, $order=false)
    {
        $data = array(
            'catParentID' => $parentID
            );

        if ($order) {
            $data['catOrder'] = $order;
        }
        $this->update($data);
        $this->update_meta();
    }

    public function update_meta()
    {
    	$data = array();
    	if ($this->catParentID()!=='0' && $this->catParentID()!=='null') {
    		$Categories = new PerchCategories_Categories;
    		$ParentCat  = $Categories->find($this->catParentID());
            if (!$ParentCat) {
                PerchUtil::output_debug();
                die(print_r($this->details, true));
            }
    		$data['catPath'] = $ParentCat->catPath().$this->catSlug().'/';

    		$data['catOrder'] = $ParentCat->find_next_child_order();
    		$data['catTreePosition'] = $ParentCat->catTreePosition().'-'.str_pad($data['catOrder'], 3, '0', STR_PAD_LEFT);
    	}else{
            $Sets = new PerchCategories_Sets;
            $Set = $Sets->find($this->setID());
    		$data['catPath'] = $Set->setSlug().'/'.$this->catSlug().'/';
    		$data['catOrder'] = $this->find_next_child_order(0);
    		$data['catTreePosition'] = str_pad($this->setID(), 3, '0', STR_PAD_LEFT).'-'.str_pad($data['catOrder'], 3, '0', STR_PAD_LEFT);
    	}

        $data['catDisplayPath'] = $this->get_display_path();

    	if (count($data)) {
    		parent::update($data);

            $Perch = Perch::fetch();
            $Perch->event($this->event_prefix.'.update');
    	}
    }

    /**
     * Find the next catOrder value for subcats of the current cat
     *
     * @return void
     * @author Drew McLellan
     */
    public function find_next_child_order($parentID=false)
    {
        if ($parentID===false) {
            $parentID = $this->id();
        }
        
        $sql = 'SELECT MAX(catOrder) FROM '.$this->table.' WHERE catParentID='.$this->db->pdb($parentID);
        $max = $this->db->get_count($sql);
        
        return $max+1;
    }

    public function catDepth()
    {
    	return substr_count($this->catTreePosition(), '-');
    }

    public function get_display_path($catID=false, $s=array())
    {
        if ($catID === false) {
            $Cat = $this;
        }else{
            $Categories = new PerchCategories_Categories();
            $Cat = $Categories->find($catID);
        }

        if (is_object($Cat)) $s[] = $Cat->catTitle();


        if (!is_object($Cat) || $Cat->catParentID()==='0') {
            $s =array_reverse($s);
            return implode(' › ', $s);  
        } 

        return $this->get_display_path($Cat->catParentID(), $s);

    }
    public function to_array()
    {
        $r = parent::to_array();

        $r['catDepth'] = $this->catDepth();

        return $r;
    }


}
