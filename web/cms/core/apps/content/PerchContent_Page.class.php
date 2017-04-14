<?php

class PerchContent_Page extends PerchBase
{
    protected $table  = 'pages';
    protected $pk     = 'pageID';


    /**
     * Calculate the depth of the current page
     *
     * @return void
     * @author Drew McLellan
     */
    public function find_depth()
    {
        $path = str_replace('/'.PERCH_DEFAULT_DOC, '', $this->pagePath());
        
        if ($path=='') return 1;
        
        return substr_count($path, '/');
    }
    
    /**
     * Update the page's position in the tree
     *
     * @param string $parentID 
     * @param string $order 
     * @return void
     * @author Drew McLellan
     */
    public function update_tree_position($parentID, $order=false, $cascade=false)
    {
        PerchUtil::debug('updating tree position');
        
        $Pages = new PerchContent_Pages;
        $ParentPage = $Pages->find($parentID);
        
        $data = array();
        $data['pageParentID'] = $parentID;
        
        if ($order===false) {
            if (is_object($ParentPage)) {
                $data['pageOrder'] = $ParentPage->find_next_child_order();
            }else{ 
                $data['pageOrder'] = $this->find_next_child_order(0);
            }
            
        }else{
            $data['pageOrder'] = $order;
        }
              
        
        if (is_object($ParentPage)) {
            $data['pageDepth'] = ($ParentPage->pageDepth()+1);
            $data['pageTreePosition'] = $ParentPage->pageTreePosition().'-'.str_pad($data['pageOrder'], 3, '0', STR_PAD_LEFT);
        }else{
            PerchUtil::debug('Could not find parent page');
            $data['pageDepth'] = 1;
            $data['pageTreePosition'] = '000-'.str_pad($data['pageOrder'], 3, '0', STR_PAD_LEFT);
            $data['pageParentID'] = 0;
        }
        
        
        $this->update($data);
        
        if ($cascade) {
            $child_pages = $Pages->get_by('pageParentID', $this->id());
            if (PerchUtil::count($child_pages)) {
                foreach($child_pages as $ChildPage) {
                    $ChildPage->update_tree_position($this->id());
                }
            }
        }
        

    }
    
    /**
     * Find the next pageOrder value for subpages of the current page.
     *
     * @return void
     * @author Drew McLellan
     */
    public function find_next_child_order($parentID=false)
    {
        if ($parentID===false) {
            $parentID = $this->id();
        }
        
        $sql = 'SELECT MAX(pageOrder) FROM '.$this->table.' WHERE pageParentID='.$this->db->pdb((int)$parentID);
        $max = $this->db->get_count($sql);
        
        return $max+1;
    }
    
    
    /**
     * Does the given roleID have permission to create a subpage?
     *
     * @param string $roleID 
     * @return void
     * @author Drew McLellan
     */
    public function role_may_create_subpages($User)
    {
        if ($User->roleMasterAdmin()) return true;

        // top level?
        // if ((int)$this->pageParentID() == 0) {
        //     if (get_class($User)=='PerchAuthenticatedUser') {
        //         return $User->has_priv('content.pages.create.toplevel');
        //     }
        // }


        $roleID = $User->roleID();

        $str_roles = $this->pageSubpageRoles();
    
        if ($str_roles=='*') return true;
        
        $roles = explode(',', $str_roles);

        return in_array($roleID, $roles);
    }


    public function role_may_delete($User)
    {
        if ($this->id() < 0) return false; // shared page
        
        //if (($User->has_priv('content.pages.delete') || ($User->has_priv('content.pages.delete.own') && $this->pageCreatorID()==$User->id()) ) && !$this->subpages()) {
        if (($User->has_priv('content.pages.delete') || ($User->has_priv('content.pages.delete.own') && $this->pageCreatorID()==$User->id()) )) {
            return true;
        }

        return false;
    }

    /**
     * Delete the page, along with its file
     * @return nothing
     */
    public function delete($cascade = true)
    {
        $Pages = new PerchContent_Pages;

        // Delete sub pages
        if ($cascade) {
            $child_pages = $Pages->get_by('pageParentID', $this->id());
            if (PerchUtil::count($child_pages)) {
                foreach($child_pages as $ChildPage) {
                    $ChildPage->delete($cascade);
                }
            }
        }

        // Delete regions
        $Regions = new PerchContent_Regions;
        $regions = $Regions->get_for_page($this->id(), false);
        if (PerchUtil::count($regions)) {
            foreach($regions as $Region) {
                $Region->delete();
            }
        }

        $site_path = $Pages->find_site_path();

        $file = PerchUtil::file_path($site_path.'/'.$this->pagePath());
        if (!PERCH_RUNWAY && !$this->pageNavOnly() && file_exists($file)) {
            if (defined('PERCH_DONT_DELETE_FILES') && PERCH_DONT_DELETE_FILES==true) {
                // don't delete files!
            }else{
                unlink($file);   
            } 
        }
        return parent::delete();
    }

    /**
     * Get an array of groupIDs of the navgroups this page belongs to.
     * @return [type] [description]
     */
    public function get_navgroup_ids()
    {
        $sql = 'SELECT DISTINCT groupID FROM '.PERCH_DB_PREFIX.'navigation_pages
                WHERE pageID='.$this->db->pdb((int)$this->id());
        return $this->db->get_rows_flat($sql);
    }

    /**
     * Update the page to be in the navgroups given.
     * @param  [type] $groupIDs [description]
     * @return [type]           [description]
     */
    public function update_navgroups($groupIDs)
    {
        if (PerchUtil::count($groupIDs)) {

            // remove any not in this set
            $sql = 'DELETE FROM '.PERCH_DB_PREFIX.'navigation_pages
                    WHERE pageID='.$this->db->pdb((int)$this->id()).' AND groupID NOT IN ('.$this->db->implode_for_sql_in($groupIDs, true).')';
            $this->db->execute($sql);

            $existing = $this->get_navgroup_ids();
            if (!$existing) $existing = array();

            foreach($groupIDs as $groupID) {
                if (!in_array($groupID, $existing)) {
                    $data = array(
                        'pageID'=>$this->id(),
                        'groupID'=>(int)$groupID,
                        'pageTreePosition'=>'000-000',
                        'pageDepth'=>$this->pageDepth(),
                    );
                    $this->db->insert(PERCH_DB_PREFIX.'navigation_pages', $data);
                }

            }
        }
    }

    /**
     * Delete this page from all navgroups
     * @return [type] [description]
     */
    public function remove_from_navgroups()
    {
        $this->db->delete(PERCH_DB_PREFIX.'navigation_pages', 'pageID', $this->id());
    }


    /**
     * Get an array of the page's access tags
     * @return [type] [description]
     */
    public function access_tags()
    {
        if ($this->details['pageAccessTags']) {
            return explode(',', $this->details['pageAccessTags']);
        }else{
            return array();
        }
    }


    public function to_array($template_ids=false)
    {
        $out = parent::to_array();

        if (isset($out['pageAttributes']) && $out['pageAttributes'] != '') {
            $dynamic_fields = PerchUtil::json_safe_decode($out['pageAttributes'], true);
            if (PerchUtil::count($dynamic_fields)) {
                foreach($dynamic_fields as $key=>$value) {
                    $out['perch_'.$key] = $value;
                }
            }
            $out = array_merge($dynamic_fields, $out);
        }

        $out = array_merge($out, PerchSystem::get_attr_vars());

        return $out;
    }

    public function template_attributes($opts)
    {
        $Template = new PerchTemplate('pages/attributes/'.$opts['template'], 'pages');
        return $Template->render($this);
    }

    public function template_attribute($id, $opts)
    {
        $attr_vars = PerchSystem::get_attr_vars();
        if (isset($attr_vars[$id])) return $attr_vars[$id];

        if ($id=='pageTitle' || $id=='pageNavText') {
            return $this->details[$id]; 
        }

        $Template = new PerchTemplate('pages/attributes/'.$opts['template'], 'pages');
        $tag = $Template->find_tag($id, false, true);
        if ($tag) {
            // prevent tag suppression here.
            $tag = str_replace(' suppress=', ' xsuppress=', $tag);
            $Template->load($tag);
            return $Template->render($this);
        }

        if (isset($this->details[$id])){
            return $this->details[$id]; 
        }
        
        return false;
    }

    public function move_file($new_location)
    {
        $new_location = PerchUtil::file_path($new_location);
        $new_location = str_replace(PERCH_LOGINPATH, '/', $new_location);
        $new_location = str_replace('\\', '/', $new_location);
        $new_location = str_replace('..', '', $new_location);
        $new_location = str_replace('//', '/', $new_location);

        $old_path = PERCH_SITEPATH.$this->pagePath();
        $new_path = PerchUtil::file_path(PERCH_SITEPATH.'/'.ltrim($new_location, '/'));

        if ($old_path!=$new_path) {
            if (file_exists($old_path)) {
                if (!file_exists($new_path)) {
                    $new_dir = PerchUtil::strip_file_name($new_path);
                    if (!file_exists($new_dir)) {
                        mkdir($new_dir, 0755, true);
                    }
                    if (is_writable($new_dir)) {
                        if(rename($old_path, $new_path)) {

                            // Is it a reference to a master page? If so, update the include
                            $contents = file_get_contents($new_path);
                            $pattern  = '#'.preg_quote("<?php include(str_replace('/', DIRECTORY_SEPARATOR, 'XXX')); ?>").'#';
                            $pattern  = str_replace('XXX', '([a-zA-Z/\.-]+)', $pattern);
                            if (preg_match($pattern, $contents, $match)) {
                                
                                $current_path = $match[1];
                                $template_dir = PERCH_TEMPLATE_PATH.'/pages';
                                $template_path = str_replace(PERCH_SITEPATH.DIRECTORY_SEPARATOR, '', PERCH_TEMPLATE_PATH).'/pages/';

                                // normalise
                                $current_path = str_replace(DIRECTORY_SEPARATOR, '/', $current_path);
                                $template_dir = str_replace(DIRECTORY_SEPARATOR, '/', $template_dir);
                                $template_path = str_replace(DIRECTORY_SEPARATOR, '/', $template_path);

                                $parts = explode($template_path, $current_path);
                                if (PerchUtil::count($parts)) {
                                    $master_page_template = $parts[1];

                                    $Pages = new PerchContent_Pages();
                                    $a = PerchUtil::file_path($template_dir.'/'.$master_page_template);
                                    $b = PerchUtil::file_path(dirname($new_path));
                                    $new_include_path = $Pages->get_relative_path($a, $b);

                                    $new_include = '<'.'?php include(str_replace(\'/\', DIRECTORY_SEPARATOR, \''.$new_include_path.'\')); ?'.'>';
                                    /*
                                    $new_include .= '<' . '?php /* '.PHP_EOL;
                                    $new_include .= 'Current path: '.$current_path.PHP_EOL;
                                    $new_include .= 'Template dir: '.$template_dir.PHP_EOL;
                                    $new_include .= 'Template path: '.$template_path.PHP_EOL;
                                    $new_include .= 'Master page template: '.$master_page_template.PHP_EOL;
                                    $new_include .= 'A: '.$a.PHP_EOL;
                                    $new_include .= 'B: '.$b.PHP_EOL;
                                    $new_include .= 'New include path: '.$new_include_path.PHP_EOL;
                                    $new_include .= 'Parts: '.print_r($parts, true).PHP_EOL;
                                    $new_include .= PHP_EOL.' *'.'/ ?' . '>';
                                    */

                                    file_put_contents($new_path, str_replace($match[0], $new_include, $contents));

                                }

                            }else{
                                // Else just update the Perch runtime.

                                $pattern  = '#'.preg_quote("include(__Y____X__".trim(PERCH_LOGINPATH, '/')."__DS__runtime.php__Y__);").'#';
                                $pattern  = str_replace('__X__', '([a-zA-Z/\.-]*)', $pattern);
                                $pattern  = str_replace('__Y__', '[\'\"]', $pattern);
                                $pattern  = str_replace('__DS__', '[\\\/]', $pattern);

                                if (preg_match($pattern, $contents, $match)) {
                                    PerchUtil::debug($match);

                                    $Pages = new PerchContent_Pages();
                                    $a = PerchUtil::file_path(PERCH_PATH.'/runtime.php');
                                    $b = PerchUtil::file_path(dirname($new_path));
                                    $new_include_path = $Pages->get_relative_path($a, $b);

                                    PerchUtil::debug('New include path: '. $new_include_path);

                                    $new_include = "include('$new_include_path');"; 

                                    file_put_contents($new_path, str_replace($match[0], $new_include, $contents));
                                }

                            }

                            


                            return array(true, false);
                        }else{
                            return array(false, 'The page could not be moved.');
                        } 
                    }else{
                        return array(false, 'The destination folder could not be written to, so the page cannot be moved.');
                    }
                }else{
                    return array(false, 'A page file already exists at the new location.');
                }
                
            }else{
                return array(false, 'No page file exists at that location to move.');
            }
        }else{
            // It's ok, as the file is already where they want it to be.
            return array(true, false);
        }
    }

    public function log_resources($resourceIDs=false)
    {
        PerchUtil::debug('Logging resources for '.$this->api->app_id);
        
        if ($resourceIDs===false) {
            $Resources = new PerchResources();
            $resourceIDs = $Resources->get_logged_ids();    
        } 

        if (PerchUtil::count($resourceIDs) && $this->api) {

            $app_id = $this->api->app_id;
            
            $sql = 'DELETE FROM '.PERCH_DB_PREFIX.'resource_log WHERE appID='.$this->db->pdb($app_id).' AND itemFK='.$this->db->pdb($this->pk).' AND itemRowID='.$this->db->pdb((int)$this->id());
            $this->db->execute($sql);
            
            $sql    = 'INSERT IGNORE INTO '.PERCH_DB_PREFIX.'resource_log(`appID`, `itemFK`, `itemRowID`, `resourceID`) VALUES';      
            $vals   = array();
            
            foreach($resourceIDs as $id) {
                $vals[] = '('.$this->db->pdb($app_id).','.$this->db->pdb($this->pk).','.(int)$this->id().','.(int)$id.')';
            }

            $sql .= implode(',', $vals);

            $this->db->execute($sql);
        }else{
            PerchUtil::debug('No ids to log.');
        }
    }   
}
