<?php

class PerchContent_NavGroup extends PerchBase
{
    protected $table  = 'navigation';
    protected $pk     = 'groupID';


    /**
     * Get the parent page ID of the given page within the group
     * @param  [type] $pageID [description]
     * @return [type]         [description]
     */
    public function page_parent($pageID)
    {
    	$sql = 'SELECT pageParentID FROM '.PERCH_DB_PREFIX.'navigation_pages
    			WHERE groupID='.$this->db->pdb((int)$this->id()).' AND pageID='.$this->db->pdb((int)$pageID).'
    			LIMIT 1';
    	$parentID = $this->db->get_value($sql);

    	$sql = 'SELECT * FROM '.PERCH_DB_PREFIX.'navigation_pages
    			WHERE groupID='.$this->db->pdb((int)$this->id()).' AND pageID='.$this->db->pdb((int)$parentID).'
    			LIMIT 1';
    	return $this->db->get_row($sql);
    }


    public function update_tree_position($pageID, $parentID, $order=false)
    {
        PerchUtil::debug('updating tree position');
        
        $sql = 'SELECT * FROM '.PERCH_DB_PREFIX.'navigation_pages
    			WHERE groupID='.$this->db->pdb((int)$this->id()).' AND pageID='.$this->db->pdb((int)$parentID).'
    			LIMIT 1';
    	$parentPage = $this->db->get_row($sql);
        
        $data = array();
        $data['pageParentID'] = $parentID;
        
        if ($order===false) {
            if (is_array($parentPage)) {
                $sql = 'SELECT MAX(pageOrder) FROM '.PERCH_DB_PREFIX.'navigation_pages WHERE pageParentID='.$this->db->pdb((int)$parentID);
            }else{
                $sql = 'SELECT MAX(pageOrder) FROM '.PERCH_DB_PREFIX.'navigation_pages WHERE pageParentID=0';
            }
            
        	$max = $this->db->get_count($sql);
        	$max = (int)$max+1;
            
        }else{
            $data['pageOrder'] = $order;
        }
        
        
        
        if (is_array($parentPage)) {
            $data['pageDepth'] = ((int)$parentPage['pageDepth']+1);
            $data['pageTreePosition'] = $parentPage['pageTreePosition'].'-'.str_pad($data['pageOrder'], 3, '0', STR_PAD_LEFT);
        }else{
            PerchUtil::debug('Could not find parent page');
            $data['pageDepth'] = 1;
            $data['pageTreePosition'] = '000-'.str_pad($data['pageOrder'], 3, '0', STR_PAD_LEFT);
        }
        
        $sql = 'SELECT navpageID FROM '.PERCH_DB_PREFIX.'navigation_pages 
        		WHERE groupID='.$this->db->pdb((int)$this->id()).' AND pageID='.$this->db->pdb((int)$pageID).'
        		LIMIT 1';
        $pk = $this->db->get_value($sql); 

        $this->db->update(PERCH_DB_PREFIX.'navigation_pages', $data, 'navpageID', $pk);
      

    }
}
