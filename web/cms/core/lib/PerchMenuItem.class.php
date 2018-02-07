<?php

class PerchMenuItem extends PerchBase
{
    protected $table  = 'menu_items';
    protected $pk     = 'itemID';

    public function get_link()
    {
        switch($this->details['itemType']) {

            case 'app':
                return $this->resolve_app_path($this->details['itemValue']);
                break;

            case 'menu':
                return '';
                break;

            case 'link':
                return $this->details['itemValue'];
                break;

        }
    }

    public function is_permitted($CurrentUser, $apps)
    {

        if ($this->details['itemType'] == 'app' && !is_numeric($this->details['itemValue'])) {
            if (!in_array($this->details['itemValue'], $apps)) {
                return false;
            }
        } 


        if (!$this->privID()) {
            return true;
        }

        if ($CurrentUser->has_priv($this->privKey())) {
            return true;
        }

        return false;
    }


    public function update_tree_position($parentID=false, $order=false)
    {
        $data = array();

        if ($parentID) {
            $data['parentID'] = $parentID;
        }else{
            $data['parentID'] = $this->parentID();
        }

        if ($order) {
            $data['itemOrder'] = $order;
        }else{
            $data['itemOrder'] = $this->find_next_child_order($data['parentID']);    
        }

        if (count($data)) $this->update($data);
    }



    private function resolve_app_path($appSlug)
    {
        $core_apps = ['content', 'assets', 'categories'];

        if (PERCH_RUNWAY && is_numeric($appSlug)) {
            return PERCH_LOGINPATH.'/core/apps/content/collections/?id='.$appSlug;
        }

        if (in_array($appSlug, $core_apps)) {
            return PERCH_LOGINPATH.'/core/apps/'.str_replace('perch_', '', $appSlug).'/';
        }

        return PERCH_LOGINPATH.'/addons/apps/'.$appSlug.'/';
    }
}
