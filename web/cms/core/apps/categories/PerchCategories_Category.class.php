<?php

class PerchCategories_Category extends PerchAPI_Base
{
    protected $table        = 'categories';
    protected $pk           = 'catID';
    protected $event_prefix = 'category';

    protected $exclude_from_api = ['catParentID', 'catTreePosition', 'setID', 'catOrder', 'catDisplayPath', 'catDepth'];

    public function update($data)
    {
    	$r = parent::update($data);

        $this->log_resources();
    	
    	$this->update_meta();

    	return $r; 	
    }

    public function update_tree_position($parentID=false, $order=false)
    {
        //PerchUtil::debug(sprintf('Update catID %s to parentID %s and order %s', $this->id(), $parentID, $order), 'notice');

        $data = array();

        if ($parentID) {
            $data['catParentID'] = $parentID;
        }else{
            $data['catParentID'] = $this->catParentID();
        }

        if ($order) {
            $data['catOrder'] = $order;
        }else{
            $data['catOrder'] = $this->find_next_child_order($data['catParentID']);    
        }

        if (count($data)) $this->update($data);
    }

    public function update_meta($fire_events=true)
    {
    	$data = array();
    	if ($this->catParentID()!=='0' && $this->catParentID()!=='null') {
            $Categories              = new PerchCategories_Categories;
            $ParentCat               = $Categories->find($this->catParentID());
            $data['catPath']         = $ParentCat->catPath().$this->catSlug().'/';
            $data['catTreePosition'] = $ParentCat->catTreePosition().'-'.str_pad($this->catOrder(), 3, '0', STR_PAD_LEFT);
    	}else{
            $Sets                    = new PerchCategories_Sets;
            $Set                     = $Sets->find($this->setID());
            $data['catPath']         = $Set->setSlug().'/'.$this->catSlug().'/';           
            $data['catTreePosition'] = str_pad($this->setID(), 3, '0', STR_PAD_LEFT).'-'.str_pad($this->catOrder(), 3, '0', STR_PAD_LEFT);
    	}

        $data['catDisplayPath'] = $this->get_display_path();

    	if (count($data)) {
    		parent::update($data);

            if ($fire_events) {
                $Perch = Perch::fetch();
                $Perch->event($this->event_prefix.'.update', $this);    
            }
            
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
        
        $sql = 'SELECT MAX(catOrder) FROM '.$this->table.' WHERE catParentID='.$this->db->pdb((int)$parentID);
        $max = $this->db->get_count($sql);
        
        return $max+1;
    }

    public function catDepth()
    {
        if (isset($this->details['catTreePosition'])) {
            return substr_count($this->details['catTreePosition'], '-');
        }
    	return 0;
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

    public function get_counts()
    {
        $sql = 'SELECT * FROM '.PERCH_DB_PREFIX.'category_counts 
                WHERE catID='.$this->id();
        $rows = $this->db->get_rows($sql);

        $out = array();

        if (PerchUtil::count($rows)) {
            foreach($rows as $row) {
                $out[$row['countType']] = (int)$row['countValue'];
            }
        }

        return $out;
    }

    public function update_count($countType, $value=0)
    {
        $this->db->execute('DELETE FROM '.PERCH_DB_PREFIX.'category_counts WHERE catID='.$this->id().' AND countType='.$this->db->pdb($countType));

        $this->db->insert(PERCH_DB_PREFIX.'category_counts', array(
            'countType'=>$countType,
            'countValue'=>(int)$value,
            'catID'=>$this->id(),
        ));
    }

}
