<?php

class PerchContent_Pages extends PerchFactory
{
    protected $singular_classname = 'PerchContent_Page';
    protected $table    = 'pages';
    protected $pk   = 'pageID';

    protected $default_sort_column  = 'pageParentID, pageOrder';

    private $error_messages = array();

    private $nav_page_cache = array();
    static $path_cache     = array();

    public $static_fields  = array('pageTitle', 'pageNavText');

    /**
     * Find a page based on its path, or create a new one. Used by the Upgrade app.
     * @param  string $path A root-relative site path
     * @return object       Instance of Page class.
     */
    public function find_or_create($path)
    {
        $Page = $this->find_by_path($path);

        if (is_object($Page)) return $Page;

        $data = array();
        $data['pagePath']       = $path;
        $data['pageTitle']      = PerchUtil::filename($path, false, false);
        $data['pageNavText']    = $data['pageTitle'];
        $data['pageNew']        = 1;
        $data['pageDepth']      = 0;
        $data['pageModified']   = date('Y-m-d H:i:s');
        $data['pageAttributes'] = '';

        $Page = $this->create($data);

        $Perch = Perch::fetch();
        $Perch->event('page.create', $Page);

        $this->order_new_pages();

        return $this->find($Page->id());
    }

    /**
     * Find the site path
     *
     * @return void
     * @author Drew McLellan
     */
    public function find_site_path()
    {
        // Find the site path
        if (!defined('PERCH_SITEPATH')) {
            $login_path_parts = explode('/', PERCH_LOGINPATH);
            $path_parts = explode(DIRECTORY_SEPARATOR, PERCH_PATH);
            foreach($login_path_parts as $part) if ($part!='') array_pop($path_parts);
            $path = implode(DIRECTORY_SEPARATOR, $path_parts);
            define('PERCH_SITEPATH', $path);
        }
        return PERCH_SITEPATH;
    }

    /**
     * Get the tree of pages
     *
     * @return void
     * @author Drew McLellan
     */
    public function get_page_tree()
    {
        $sql = 'SELECT p.*, (SELECT COUNT(*) FROM '.$this->table.' WHERE pageParentID=p.pageID) AS subpages
                FROM '.$this->table.' p
                ORDER BY pageTreePosition ASC';
        $rows   = $this->db->get_rows($sql);

        return $this->return_instances($rows);
    }

    /**
     * Get the page tree, but filter out any items that don't have a parent ID matching those provided
     *
     * @param array $parentIDs
     * @return void
     * @author Drew McLellan
     */
    public function get_page_tree_collapsed($parentIDs=array())
    {
        if (!PerchUtil::count($parentIDs)) {
            $parentIDs = array(0);
        }

        $sql = 'SELECT p.*, (SELECT COUNT(*) FROM '.$this->table.' WHERE pageParentID=p.pageID) AS subpages
                FROM '.$this->table.' p
                WHERE p.pageParentID IN ('.$this->db->implode_for_sql_in($parentIDs).')
                ORDER BY p.pageTreePosition ASC';
        $rows   = $this->db->get_rows($sql);

        return $this->return_instances($rows);
    }


	public function get_page_tree_filtered($type='new', $value=false)
	{
		switch($type) {

			case 'new':
				$sql = 'SELECT p.*, 1 AS pageDepth
		                FROM '.$this->table.' p
		                WHERE (SELECT COUNT(*) FROM '.PERCH_DB_PREFIX.'content_regions WHERE pageID=p.pageID AND regionNew=1) > 0
		                ORDER BY p.pageTreePosition ASC';

				break;

			case 'template':
				$sql = 'SELECT p.*, 1 AS pageDepth
		                FROM '.$this->table.' p
		                WHERE (SELECT COUNT(*) FROM '.PERCH_DB_PREFIX.'content_regions WHERE pageID=p.pageID AND regionTemplate='.$this->db->pdb($value).') > 0
		                ORDER BY p.pageTreePosition ASC';

				break;


		}


        $rows   = $this->db->get_rows($sql);

        return $this->return_instances($rows);
	}

    /**
     * Find the IDs of any child pages from the given page ID
     *
     * @param string $pageID
     * @return void
     * @author Drew McLellan
     */
    public function find_child_page_ids($pageID)
    {
        $sql = 'SELECT pageTreePosition FROM '.$this->table.' WHERE pageID='.$this->db->pdb((int)$pageID).' LIMIT 1';
        $pageTreePosition = $this->db->get_value($sql);

        if ($pageTreePosition) {
            $sql = 'SELECT pageID FROM '.$this->table.' WHERE pageTreePosition LIKE \''.$pageTreePosition.'-%\'';
            $rows = $this->db->get_rows($sql);

            if (PerchUtil::count($rows)) {
                $out = array();
                foreach($rows as $row) {
                    $out[] = $row['pageID'];
                }
                return $out;
            }
        }
        return array();
    }

    /**
     * Find the IDs of all ancestor pages
     *
     * @param string $pageID
     * @return void
     * @author Drew McLellan
     */
    public function find_parent_page_ids($pageID)
    {
        $sql = 'SELECT pageTreePosition FROM '.$this->table.' WHERE pageID='.$this->db->pdb((int)$pageID).' LIMIT 1';
        $pageTreePosition = $this->db->get_value($sql);

        if ($pageTreePosition) {
            $parts = explode('-', $pageTreePosition);
            $values = array();
            while(count($parts)) {
                $values[] = implode('-', $parts);
                array_pop($parts);
            }
            $sql = 'SELECT pageID FROM '.$this->table.' WHERE pageTreePosition IN ('.$this->db->implode_for_sql_in($values).')';
            $rows = $this->db->get_rows($sql);

            if (PerchUtil::count($rows)) {
                $out = array();
                foreach($rows as $row) {
                    $out[] = $row['pageID'];
                }
                return $out;
            }
        }

        return false;
    }

    /**
     * Find the IDs of all ancestor pages
     *
     * @param string $pageID
     * @return void
     * @author Drew McLellan
     */
    public function find_parent_page_ids_by_path($pagePath, $groupID=false)
    {
        if ($groupID!==false) {
            $table = PERCH_DB_PREFIX.'navigation_pages';
            $where = 'groupID='.$this->db->pdb((int)$groupID).' AND ';
            $sql = 'SELECT np.pageTreePosition FROM '.$this->table.' p, '.PERCH_DB_PREFIX.'navigation_pages np WHERE np.pageID=p.pageID AND np.groupID='.$this->db->pdb((int)$groupID).' AND p.pagePath='.$this->db->pdb($pagePath).' LIMIT 1';
        }else{
            $table = $this->table;
            $where = '';

            $sql = 'SELECT pageTreePosition FROM '.$this->table.' WHERE pagePath='.$this->db->pdb($pagePath).' LIMIT 1';
        }

        $pageTreePosition = $this->db->get_value($sql);

        if ($pageTreePosition) {
            $parts = explode('-', $pageTreePosition);
            $values = array();
            while(count($parts)) {
                $values[] = implode('-', $parts);
                array_pop($parts);
            }
            $sql = 'SELECT pageID FROM '.$table.' WHERE '.$where.' pageTreePosition IN ('.$this->db->implode_for_sql_in($values).') ORDER BY pageTreePosition DESC';
            $rows = $this->db->get_rows($sql);

            if (PerchUtil::count($rows)) {
                $out = array();
                foreach($rows as $row) {
                    $out[] = $row['pageID'];
                }
                return $out;
            }
        }

        return false;
    }


    /**
     * Get pages by the pageID of their parent.
     *
     * @param string $parentID
     * @return void
     * @author Drew McLellan
     */
    public function get_by_parent($parentID=0, $navgroupID=false)
    {
        if ($navgroupID===false) {
            $sql = 'SELECT * FROM '.$this->table.'
                    WHERE pageParentID='.$this->db->pdb((int)$parentID).'
                    ORDER BY pageTreePosition ASC';

        }else{
            $sql = 'SELECT np.*, p.pageTitle FROM '.PERCH_DB_PREFIX.'navigation_pages np, '.$this->table.' p
                    WHERE np.pageID=p.pageID AND np.pageParentID='.$this->db->pdb((int)$parentID).'
                        AND np.groupID='.$this->db->pdb((int)$navgroupID).'
                    ORDER BY np.pageTreePosition ASC';
        }

        $rows   = $this->db->get_rows($sql);

        return $this->return_instances($rows);
    }


    /**
     * Find a page based on its path
     *
     * @param string $path
     * @return void
     * @author Drew McLellan
     */
    public function find_by_path($path)
    {
        if (!$path) return null;

        if (isset(self::$path_cache[$path])) return self::$path_cache[$path];

        $sql = 'SELECT * FROM '.$this->table.' WHERE pagePath='.$this->db->pdb($path).' LIMIT 1';
        $row   = $this->db->get_row($sql);

        self::$path_cache[$path] = $this->return_instance($row);
        return self::$path_cache[$path];
    }


    /**
     * Get a page object for the fake page that represents shared items.
     *
     * @return void
     * @author Drew McLellan
     */
    public function get_mock_shared_page()
    {
        $page = array();

        $page['pageID']                = '-1';
        $page['pageParentID']          = '0';
        $page['pagePath']              = '*';
        $page['pageTitle']             = PerchLang::get('Shared');
        $page['pageNavText']           = PerchLang::get('Shared');
        $page['pageNew']               = '0';
        $page['pageOrder']             = '0';
        $page['pageDepth']             = '1';
        $page['pageSortPath']          = '/';
        $page['pageTreePosition']      = '000';
        $page['pageSubpageRoles']      = '';
        $page['pageSubpagePath']       = '';
        $page['pageHidden']            = '0';
        $page['pageNavOnly']           = '0';
        $page['subpages']              = false;
        $page['pageAccessTags']        = '';
        $page['pageCreatorID']         = '0';
        $page['pageModified']          = date('Y-m-d H:i:s');
        $page['pageAttributes']        = '';
        $page['pageAttributeTemplate'] = 'default.html';
        $page['pageTemplate']          = '';
        $page['templateID']            = '0';
        $page['pageSubpageTemplates']  = '';
        $page['pageCollections']       = '';

        return $this->return_instance($page);

    }



    /**
     * Find newly registered pages, and figure out their position in the tree
     *
     * @return void
     * @author Drew McLellan
     */
    public function order_new_pages($_count=1)
    {

        $sql = 'SELECT *, REPLACE(pagePath, '.$this->db->pdb('/'.PERCH_DEFAULT_DOC).', \'\') as sortPath FROM '.$this->table.'
                WHERE pageNew=1 ORDER BY LENGTH(sortPath)-LENGTH(REPLACE(sortPath, \'/\', \'\')) ASC';
        $rows   = $this->db->get_rows($sql);

        if (PerchUtil::count($rows)) {

            if ($_count>10) return;


            $pages = $this->return_instances($rows);

            foreach($pages as $Page) {
                $data = array();

                if (!$Page->pageDepth()) {
                    $depth = $Page->find_depth();
                    $data['pageDepth'] = $depth;
                }else{
                    $depth = (int)$Page->pageDepth();
                }


                $data['pageSortPath'] = PerchUtil::strip_file_extension($Page->sortPath());


                if (!$Page->pageParentID()) {
                    if ($depth==1) {
                        $data['pageParentID'] = 0;
                    }else{
                        // find parent

                        $parts = explode('/', $Page->sortPath());
                        array_pop($parts);
                        $sections = array();
                        while(PerchUtil::count($parts)) {
                            $t = implode('/', $parts);
                            if ($t) $sections[] = $t;
                            array_pop($parts);
                        }

                        PerchUtil::debug($Page->sortPath());
                        $sql = 'SELECT pageID, pageDepth, pageTreePosition FROM '.$this->table.' WHERE pageDepth<'.$depth.' AND pageNew=0 AND pageSortPath IN ('.$this->db->implode_for_sql_in($sections).')
                                ORDER BY LENGTH(pageSortPath)-LENGTH(REPLACE(pageSortPath, \'/\', \'\')) DESC LIMIT 1';
                        $parent = $this->db->get_row($sql);

                        if ($parent) {
                            $data['pageParentID'] = $parent['pageID'];
                            $data['pageDepth'] = $parent['pageDepth']+1;
                            $depth = $data['pageDepth'];
                        }
                    }
                }else{
                    $data['pageParentID'] = $Page->pageParentID();
                    $sql = 'SELECT pageID, pageDepth, pageTreePosition FROM '.$this->table.' WHERE pageID='.$this->db->pdb((int)$Page->pageParentID()).' LIMIT 1';
                    $parent = $this->db->get_row($sql);
                }

                if (!isset($data['pageParentID'])) {
                    $data['pageParentID'] = $Page->pageParentID();
                    $sql = 'SELECT pageID, pageTreePosition FROM '.$this->table.' WHERE pageID='.$this->db->pdb((int)$Page->pageParentID());
                    $parent = $this->db->get_row($sql);

                    // no parent, so reset depth to show at top of tree.
                    $depth = 1;
                    $data['pageDepth'] = $depth;
                }

                if (isset($data['pageParentID'])) {
                    // order
                    $sql = 'SELECT COUNT(*) FROM '.$this->table.' WHERE pageNew=0 AND pageParentID='.$this->db->pdb((int)$data['pageParentID']);
                    $data['pageOrder'] = $this->db->get_count($sql)+1;


                    // Tree position
                    if ($data['pageParentID']==0) {
                        $data['pageTreePosition'] = '000-'.str_pad($data['pageOrder'], 3, '0', STR_PAD_LEFT);
                        $base = '000-';
                    }else{
                        $data['pageTreePosition'] = $parent['pageTreePosition'].'-'.str_pad($data['pageOrder'], 3, '0', STR_PAD_LEFT);
                        $base = $parent['pageTreePosition'].'-';
                    }

                    $count = (int) $data['pageOrder'];

                    while($this->db->get_count('SELECT COUNT(*) FROM '.$this->table.' WHERE pageTreePosition='.$this->db->pdb($data['pageTreePosition']))>0) {
                        $data['pageTreePosition'] = $base.str_pad($count, 3, '0', STR_PAD_LEFT);
                        $count++;
                    }

                    $data['pageNew'] = 0;
                    $Page->update($data);
                }
            }


            // recurse
            $this->order_new_pages($_count++);
        }

        return false;
    }


    /**
     * Create a new page, including adding a new file to the filesystem
     *
     * @param string $data
     * @return void
     * @author Drew McLellan
     */
    public function create_with_file($data)
    {
        $create_folder = false;
        if (isset($data['create_folder'])) {
            $create_folder = $data['create_folder'];
            unset($data['create_folder']);
        }


        $this->find_site_path();

        // Grab the template this page uses
        $Templates = new PerchContent_PageTemplates;
        $Template  = $Templates->find($data['templateID']);

        if (is_object($Template)) {

            // we don't store this, so unset
            //unset($data['templateID']);

            // grab the template's file extension, as pages use the same ext as the template.
            $file_extension = PerchUtil::file_extension($Template->templatePath());

            // use the file name given (if stated) or create from the title. Sans extension.
            if (isset($data['file_name'])) {
                $parts = explode('.', $data['file_name']);
                $file_name  = PerchUtil::urlify($parts[0]);
                unset($data['file_name']);
            }else{
                $file_name      = PerchUtil::urlify($data['pageTitle']);
            }

            // Find the parent page
            $ParentPage = $this->find($data['pageParentID']);

            if (is_object($ParentPage)) {
                if ($ParentPage->pageSubpagePath()) {
                    $pageSection = $ParentPage->pageSubpagePath();
                }else{
                    $pageSection = PerchUtil::strip_file_name($ParentPage->pagePath());
                }

                $parentPageID = $ParentPage->id();

                $data['pageDepth']    = $ParentPage->pageDepth() + 1;

                // Copy subpage info
                $data['pageSubpageRoles']     = $ParentPage->pageSubpageRoles();
                $data['pageSubpageTemplates'] = $ParentPage->pageSubpageTemplates();

            }else{
                $pageSection = '/';
                $parentPageID = 0;
                $data['pageParentID'] = '0';
                $data['pageDepth']    = '1';

                $data['pageSubpageRoles']     = '';
                $data['pageSubpageTemplates'] = '';
            }




            $dir = PerchUtil::file_path(PERCH_SITEPATH.$pageSection);

            // Are we creating a new folder?
            if ($create_folder) {
                $new_folder = $this->get_unique_folder_name($dir, $file_name);
                PerchUtil::debug('Trying to create: '.$new_folder);

                if (!is_dir($new_folder)) mkdir($new_folder, 0755, true);

                if (is_dir($new_folder)) {
                    $new_dir_name = str_replace($dir, '', $new_folder);
                    $dir          = $new_folder;
                    $new_file     = PerchUtil::file_path($dir. '/'.PERCH_DEFAULT_DOC);
                }
            }


            // Can we write to this dir?
            if (is_writable($dir)) {

                // Are we creating a new folder?
                if (!$create_folder) {
                    // Get a new file name
                    $new_file = $this->get_unique_file_name($dir, $file_name, $file_extension);
                }



                $template_dir = PerchUtil::file_path(PERCH_TEMPLATE_PATH.'/pages');

                if (file_exists($template_dir)) {
                    $template_file = PerchUtil::file_path($template_dir.'/'.$Template->templatePath());

                    // Is this referenced or copied?
                    if ($Template->templateReference()) {
                        // Referenced, so write a PHP include
                        $include_path = str_replace(DIRECTORY_SEPARATOR, '/', $this->get_relative_path($template_file, $dir));

                        // If changing, update pattern in PerchPages_Page::move_file(), and account for old-patterned pages.
                        $contents = '<'.'?php include(str_replace(\'/\', DIRECTORY_SEPARATOR, \''.$include_path.'\')); ?'.'>';
                    }else{
                        // Copied, so grab the template's contents
                        $contents = file_get_contents($template_file);
                    }

                    if ($contents) {

                        // Write the file
                        if (!file_exists($new_file) && file_put_contents($new_file, $contents)) {

                            // Get the new file path
                            if ($create_folder) {
                                $new_url = $pageSection.'/'.$new_dir_name.str_replace($dir, '', $new_file);
                                $data['pageSubpagePath'] = $pageSection.'/'.$new_dir_name;
                            }else{
                                $new_url = $pageSection.str_replace($dir, '', $new_file);
                                $data['pageSubpagePath'] = $pageSection;
                            }

                            $data['pageSubpagePath'] = str_replace('//', '/', $data['pageSubpagePath']);

                            $r = str_replace(DIRECTORY_SEPARATOR, '/', $new_url);
                            $r = str_replace('//', '/', $r);
                            $data['pagePath'] = $r;


                            // Insert into the DB
                            $Page =  $this->create($data);


                            #if (!is_object($Page)) {
                            #    PerchUtil::output_debug();
                            #}

                            // Set its position in the tree
                            $Page->update_tree_position($parentPageID);

                            // Add to nav groups
                            if ($Template->templateNavGroups()!='') {
                                $Page->update_navgroups(explode(',', $Template->templateNavGroups()));
                            }

                            // Copy page options?
                            if ($Template->optionsPageID() != '0') {

                                $CopyPage = $this->find($Template->optionsPageID());

                                if (is_object($CopyPage)) {

                                    $sql = 'INSERT INTO '.PERCH_DB_PREFIX.'content_regions (
                                            pageID,
                                            regionKey,
                                            regionPage,
                                            regionHTML,
                                            regionNew,
                                            regionOrder,
                                            regionTemplate,
                                            regionMultiple,
                                            regionOptions,
                                            regionSearchable,
                                            regionEditRoles
                                        )
                                        SELECT
                                            '.$this->db->pdb($Page->id()).' AS pageID,
                                            regionKey,
                                            '.$this->db->pdb($r).' AS regionPage,
                                            "<!-- Undefined content -->" AS regionHTML,
                                            regionNew,
                                            regionOrder,
                                            regionTemplate,
                                            regionMultiple,
                                            regionOptions,
                                            regionSearchable,
                                            regionEditRoles
                                        FROM '.PERCH_DB_PREFIX.'content_regions
                                        WHERE regionPage!='.$this->db->pdb('*').' AND pageID='.$this->db->pdb((int)$CopyPage->id());

                                    $this->db->execute($sql);

                                }
                            }

                            return $Page;

                        }else{
                            PerchUtil::debug('Could not put file contents.');
                            $this->error_messages[] = 'Could not write contents to file: '.$new_file;
                        }
                    }
                }else{
                    PerchUtil::debug('Template folder not found: '.$template_dir);
                    $this->error_messages[] = 'Template folder not found: '.$template_dir;
                }

            }else{
                PerchUtil::debug('Folder is not writable: '.$dir);
                $this->error_messages[] = 'Folder is not writable: '.$dir;
            }



        }else{
            PerchUtil::debug('Template not found.');
            PerchUtil::debug($data);
            $this->error_messages[] = 'Template could not be found.';
        }

        return false;

    }

    /**
     * Create a new page, either from an existing page, or just as a nav link
     *
     * @param string $data
     * @return void
     * @author Drew McLellan
     */
    public function create_without_file($data)
    {
        $create_folder = false;
        if (isset($data['create_folder'])) {
            $create_folder = $data['create_folder'];
            unset($data['create_folder']);
        }

        $link_only = false;

        // is this a URL or just local file?
        if (isset($data['file_name'])) {
            $url = parse_url($data['file_name']);
            if ($url && is_array($url) && isset($url['scheme']) && $url['scheme']!='') {
                $link_only = true;
                $url = $data['file_name'];
                unset($data['file_name']);
            }
        }

        // Find the parent page
        $ParentPage = $this->find($data['pageParentID']);


        if ($link_only) {

            $data['pagePath'] = $url;
            $data['pageNavOnly'] = '1';

            // Insert into the DB
            $Page =  $this->create($data);

            // Set its position in the tree
            if (is_object($Page)) {
                if (is_object($ParentPage)) $Page->update_tree_position($ParentPage->id());
                return $Page;
            }

        }else{
            // use the file name given (if stated) or create from the title. Sans extension.
            if (isset($data['file_name'])) {
                $file_name  = $data['file_name'];
                unset($data['file_name']);
            }else{
                $file_name      = PerchUtil::urlify($data['pageTitle']);
            }

            $this->find_site_path();


            // Find the parent page
            $ParentPage = $this->find($data['pageParentID']);

            if (is_object($ParentPage)) {

                if (PERCH_RUNWAY) {
                    $pageSection = $ParentPage->pageSortPath();
                }else{
                    if ($ParentPage->pageSubpagePath()) {
                        $pageSection = $ParentPage->pageSubpagePath();
                    }else{
                        $pageSection = PerchUtil::strip_file_name($ParentPage->pagePath());
                    }
                }

                // Copy subpage info
                $data['pageSubpageRoles']     = $ParentPage->pageSubpageRoles();
                $data['pageSubpageTemplates'] = $ParentPage->pageSubpageTemplates();


                $parentPageID = $ParentPage->id();

                $data['pageDepth']    = $ParentPage->pageDepth() + 1;

            }else{
                $pageSection = '/';
                $parentPageID = 0;
                $data['pageParentID'] = '0';
                $data['pageDepth']    = '1';
            }

            if (!isset($data['templateID']) || $data['templateID']=='') {
                $data['templateID'] = 0;
            }

            $dir  = PERCH_SITEPATH.str_replace('/', DIRECTORY_SEPARATOR, $pageSection);

            // Get the new file path
            $new_url = $pageSection.'/'.str_replace($dir, '', $file_name);
            $r = str_replace(DIRECTORY_SEPARATOR, '/', $new_url);
            while(strpos($r, '//')!==false) $r = str_replace('//', '/', $r);
            $data['pagePath'] = $r;

            // Insert into the DB
            $Page =  $this->create($data);

            // Set its position in the tree
            if (is_object($Page)) {
                $Page->update_tree_position($parentPageID);

                if (PERCH_RUNWAY) {

                    // Grab the template this page uses
                    $Templates = new PerchContent_PageTemplates;
                    $Template  = $Templates->find($Page->templateID());

                    if (is_object($Template)) {

                        // Add to nav groups
                        if ($Template->templateNavGroups()!='') {
                            $Page->update_navgroups(explode(',', $Template->templateNavGroups()));
                        }

                        // Copy page options?
                        if ($Template->optionsPageID() != '0') {

                            $CopyPage = $this->find($Template->optionsPageID());

                            if (is_object($CopyPage)) {

                                $sql = 'INSERT INTO '.PERCH_DB_PREFIX.'content_regions (
                                        pageID,
                                        regionKey,
                                        regionPage,
                                        regionHTML,
                                        regionNew,
                                        regionOrder,
                                        regionTemplate,
                                        regionMultiple,
                                        regionOptions,
                                        regionSearchable,
                                        regionEditRoles
                                    )
                                    SELECT
                                        '.$this->db->pdb($Page->id()).' AS pageID,
                                        regionKey,
                                        '.$this->db->pdb($r).' AS regionPage,
                                        "<!-- Undefined content -->" AS regionHTML,
                                        regionNew,
                                        regionOrder,
                                        regionTemplate,
                                        regionMultiple,
                                        regionOptions,
                                        regionSearchable,
                                        regionEditRoles
                                    FROM '.PERCH_DB_PREFIX.'content_regions
                                    WHERE regionPage!='.$this->db->pdb('*').' AND pageID='.$this->db->pdb((int)$CopyPage->id());

                                $this->db->execute($sql);
                            }
                        }
                    }
                }
                return $Page;
            }
        }
        return false;
    }

    private function get_unique_file_name($dir, $file_name, $file_extension, $count=0)
    {
        if ($count==0) {
            $file = $dir.DIRECTORY_SEPARATOR.$file_name.'.'.$file_extension;
        }else{
            $file = $dir.DIRECTORY_SEPARATOR.$file_name.'-'.$count.'.'.$file_extension;
        }

        $file = str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $file);

        if (file_exists($file)) {
            $count++;
            return $this->get_unique_file_name($dir, $file_name, $file_extension, $count);
        }else{
            return $file;
        }
    }

    private function get_unique_folder_name($dir, $folder_name, $count=0)
    {
        if ($count==0) {
            $folder = $dir.DIRECTORY_SEPARATOR.$folder_name;
        }else{
            $folder = $dir.DIRECTORY_SEPARATOR.$folder_name.'-'.$count;
        }

        $folder = str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $folder);

        if (file_exists($folder)) {

            // is it a folder without an index file? That would be ok.
            if (!file_exists(PerchUtil::file_path($folder.'/'.PERCH_DEFAULT_DOC))) {
                return $folder;
            }

            $count++;
            return $this->get_unique_folder_name($dir, $folder_name, $count);
        }else{
            return $folder;
        }
    }


    // Thanks, jpic in php.net realpath comments!
    public function get_relative_path($path, $compareTo)
    {
        // clean arguments by removing trailing and prefixing slashes
        if (substr($path, -1 ) == DIRECTORY_SEPARATOR) {
            $path = substr($path, 0, -1);
        }
        if (substr($path, 0, 1) == DIRECTORY_SEPARATOR) {
            $path = substr($path, 1);
        }

        if (substr($compareTo, -1) == DIRECTORY_SEPARATOR) {
            $compareTo = substr($compareTo, 0, -1);
        }
        if (substr($compareTo, 0, 1) == DIRECTORY_SEPARATOR) {
            $compareTo = substr($compareTo, 1);
        }

        // simple case: $compareTo is in $path
        if (strpos($path, $compareTo) === 0) {
            $offset = strlen($compareTo) + 1;
            return substr($path, $offset);
        }

        $relative  = array();
        $pathParts = explode(DIRECTORY_SEPARATOR, $path);
        $compareToParts = explode(DIRECTORY_SEPARATOR, $compareTo);

        foreach($compareToParts as $index => $part) {
            if (isset($pathParts[$index]) && $pathParts[$index] == $part) {
                continue;
            }

            $relative[] = '..';
        }

        foreach($pathParts as $index => $part) {
            if (isset($compareToParts[$index]) && $compareToParts[$index] == $part) {
                continue;
            }

            $relative[] = $part;
        }

        return implode(DIRECTORY_SEPARATOR, $relative);
    }


    public function get_errors()
    {
        return $this->error_messages;
    }


    public function get_breadcrumbs($opts)
    {
        $from_path          = $opts['from-path'];
        $hide_extensions    = $opts['hide-extensions'];
        $hide_default_doc   = $opts['hide-default-doc'];
        $template           = $opts['template'];
        $skip_template      = $opts['skip-template'];
        $add_trailing_slash = $opts['add-trailing-slash'];
        $navgroup           = $opts['navgroup'];
        $include_hidden     = $opts['include-hidden'];
        $expand_attributes  = $opts['use-attributes'];

        $template = 'navigation/'.$template;


        $from_path = rtrim($from_path, '/');

        if ($navgroup) {
            $groupID = $this->db->get_value('SELECT groupID FROM '.PERCH_DB_PREFIX.'navigation WHERE groupSlug='.$this->db->pdb($navgroup).' LIMIT 1');

            $sql = 'SELECT np.pageTreePosition FROM '.PERCH_DB_PREFIX.'navigation_pages np, '.$this->table.' p
                    WHERE p.pageID=np.pageID AND np.groupID='.$this->db->pdb((int)$groupID).' AND (p.pagePath='.$this->db->pdb($from_path).' OR p.pageSortPath='.$this->db->pdb($from_path).') LIMIT 1';
        }else{
            $sql = 'SELECT pageTreePosition FROM '.$this->table.' WHERE pagePath='.$this->db->pdb($from_path).' OR pageSortPath='.$this->db->pdb($from_path).' LIMIT 1';
        }


        $pageTreePosition = $this->db->get_value($sql);

        if ($pageTreePosition) {
            $parts = explode('-', $pageTreePosition);
            $values = array();
            while(count($parts)) {
                $values[] = implode('-', $parts);
                array_pop($parts);
            }

            if ($navgroup) {
                $sql = 'SELECT p.*, np.* FROM '.PERCH_DB_PREFIX.'navigation_pages np, '.$this->table.' p
                        WHERE p.pageID=np.pageID AND np.groupID='.$this->db->pdb((int)$groupID).' AND p.pageNew=0 AND np.pageTreePosition IN ('.$this->db->implode_for_sql_in($values).') ORDER BY np.pageTreePosition';
            }else{
                $sql = 'SELECT * FROM '.$this->table.' WHERE ';

                if (!$include_hidden) {
                    $sql .= 'pageHidden=0 AND ';
                }

                $sql .= 'pageNew=0 AND pageTreePosition IN ('.$this->db->implode_for_sql_in($values).') ORDER BY pageTreePosition';
            }


            $rows = $this->db->get_rows($sql);


            if (PerchUtil::count($rows)) {
                foreach($rows as &$page) {

                    // hide default doc
                    if ($hide_default_doc) {
                        $page['pagePath'] = preg_replace('/'.preg_quote(PERCH_DEFAULT_DOC).'$/', '', $page['pagePath']);
                    }

                    // hide extensions
                    if ($hide_extensions) {
                        $page['pagePath'] = preg_replace('/'.preg_quote(PERCH_DEFAULT_EXT).'$/', '', $page['pagePath']);
                    }

                    // trailing slash
                    if ($add_trailing_slash) {
                        $page['pagePath'] = rtrim($page['pagePath'], '/').'/';
                    }

                    // expand attributes
                    if ($expand_attributes && isset($page['pageAttributes']) && $page['pageAttributes']!='') {
                        $dynamic_fields = PerchUtil::json_safe_decode($page['pageAttributes'], true);
                        if (PerchUtil::count($dynamic_fields)) {
                            foreach($dynamic_fields as $key=>$value) {
                                $page[$key] = $value;
                            }
                        }
                        $page = array_merge($dynamic_fields, $page);
                    }

                }
            }



            if ($skip_template) {
                return $rows;
            }

            $Template = new PerchTemplate($template, 'pages');
            return $Template->render_group($rows, true);
        }

        return '';
    }

    public function get_full_url($opts)
    {
        $hide_extensions    = $opts['hide-extensions'];
        $hide_default_doc   = $opts['hide-default-doc'];
        $add_trailing_slash = $opts['add-trailing-slash'];
        $include_domain     = $opts['include-domain'];

        $Perch = Perch::fetch();
        $current_page = $Perch->get_page_as_set();

        // hide default doc
        if ($hide_default_doc) {
            $current_page = preg_replace('/'.preg_quote(PERCH_DEFAULT_DOC).'$/', '', $current_page);
        }

        // hide extensions
        if ($hide_extensions) {
            $current_page = preg_replace('/'.preg_quote(PERCH_DEFAULT_EXT).'$/', '', $current_page);
        }

        // trailing slash
        if ($add_trailing_slash) {
            $current_page = rtrim($current_page, '/').'/';
        }

        if ($include_domain) {
            $current_page = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']!='off') ? 's' : '') . '://' . $_SERVER['HTTP_HOST'].$current_page;
        }


        return $current_page;
    }


    public function get_sibling_navigation($type='prev', $opts=array(), $from_path)
    {
        $hide_extensions       = $opts['hide-extensions'];
        $hide_default_doc      = $opts['hide-default-doc'];
        $template              = $opts['template'];
        $skip_template         = $opts['skip-template'];
        $add_trailing_slash    = $opts['add-trailing-slash'];
        $navgroup              = $opts['navgroup'];
        $include_hidden        = $opts['include-hidden'];
        $expand_attributes     = $opts['use-attributes'];

        $template = 'navigation/'.$template;

        if ($from_path && $from_path != '/') {

            $from_path = rtrim($from_path, '/');

            if ($navgroup) {
                $groupID = $this->db->get_value('SELECT groupID FROM '.PERCH_DB_PREFIX.'navigation WHERE groupSlug='.$this->db->pdb($navgroup).' LIMIT 1');

                $sql = 'SELECT np.pageID, np.pageParentID, np.pageDepth, np.pageTreePosition FROM '.PERCH_DB_PREFIX.'navigation_pages np, '.$this->table.' p
                        WHERE p.pageID=np.pageID AND np.groupID='.$this->db->pdb((int)$groupID).' AND (p.pagePath='.$this->db->pdb($from_path).' OR p.pageSortPath='.$this->db->pdb($from_path).') LIMIT 1';
            }else{
                $sql = 'SELECT pageID, pageParentID, pageDepth, pageTreePosition FROM '.$this->table.' WHERE pagePath='.$this->db->pdb($from_path).' OR pageSortPath='.$this->db->pdb($from_path).' LIMIT 1';
            }


            $root = $this->db->get_row($sql);

            if (PerchUtil::count($root)) {

                if ($navgroup) {
                    $sql = 'SELECT p.*, np.* FROM '.PERCH_DB_PREFIX.'navigation_pages np, '.$this->table.' p
                        WHERE p.pageID=np.pageID AND np.groupID='.$this->db->pdb((int)$groupID).' AND np.pageParentID='.$this->db->pdb((int)$root['pageParentID']).' AND np.pageDepth='.$this->db->pdb($root['pageDepth']).'
                            AND np.pageTreePosition '.($type=='prev' ? '<' : '>').' '.$this->db->pdb($root['pageTreePosition']).'
                        ORDER BY np.pageTreePosition '.($type=='prev' ? 'DESC' : 'ASC').'
                        LIMIT 1';
                }else{
                    $sql = 'SELECT * FROM '.$this->table.' WHERE ';

                    if (!$include_hidden) {
                        $sql .= 'pageHidden=0 AND ';
                    }

                    $sql .='pageParentID='.$this->db->pdb((int)$root['pageParentID']).' AND pageDepth='.$this->db->pdb($root['pageDepth']).'
                            AND pageTreePosition '.($type=='prev' ? '<' : '>').' '.$this->db->pdb($root['pageTreePosition']).'
                        ORDER BY pageTreePosition '.($type=='prev' ? 'DESC' : 'ASC').'
                        LIMIT 1';
                }


                $page = $this->db->get_row($sql);

                if (PerchUtil::count($page)) {

                    // hide default doc
                    if ($hide_default_doc) {
                        $page['pagePath'] = preg_replace('/'.preg_quote(PERCH_DEFAULT_DOC).'$/', '', $page['pagePath']);
                    }

                    // hide extensions
                    if ($hide_extensions) {
                        $page['pagePath'] = preg_replace('/'.preg_quote(PERCH_DEFAULT_EXT).'$/', '', $page['pagePath']);
                    }

                    // trailing slash
                    if ($add_trailing_slash) {
                        $page['pagePath'] = rtrim($page['pagePath'], '/').'/';
                    }

                    // expand attributes
                    if ($expand_attributes && isset($page['pageAttributes']) && $page['pageAttributes']!='') {
                        $dynamic_fields = PerchUtil::json_safe_decode($page['pageAttributes'], true);
                        if (PerchUtil::count($dynamic_fields)) {
                            foreach($dynamic_fields as $key=>$value) {
                                $page[$key] = $value;
                            }
                        }
                        $page = array_merge($dynamic_fields, $page);
                    }


                    if ($skip_template) return $page;

                    $Template = new PerchTemplate($template, 'pages');
                    return $Template->render_group(array($page), true);

                }
            }



        }

        return false;

    }

    public function get_parent_navigation($opts=array(), $from_path)
    {
        $hide_extensions       = $opts['hide-extensions'];
        $hide_default_doc      = $opts['hide-default-doc'];
        $template              = $opts['template'];
        $skip_template         = $opts['skip-template'];
        $add_trailing_slash    = $opts['add-trailing-slash'];
        $navgroup              = $opts['navgroup'];
        $expand_attributes     = $opts['use-attributes'];

        $template = 'navigation/'.$template;

        if ($from_path && $from_path != '/') {

            $from_path = rtrim($from_path, '/');

            if ($navgroup) {
                $groupID = $this->db->get_value('SELECT groupID FROM '.PERCH_DB_PREFIX.'navigation WHERE groupSlug='.$this->db->pdb($navgroup).' LIMIT 1');

                $sql = 'SELECT np.pageID, np.pageParentID, np.pageDepth, np.pageTreePosition FROM '.PERCH_DB_PREFIX.'navigation_pages np, '.$this->table.' p
                        WHERE p.pageID=np.pageID AND np.groupID='.$this->db->pdb((int)$groupID).' AND (p.pagePath='.$this->db->pdb($from_path).' OR p.pageSortPath='.$this->db->pdb($from_path).') LIMIT 1';
            }else{
                $sql = 'SELECT pageID, pageParentID, pageDepth, pageTreePosition FROM '.$this->table.' WHERE pagePath='.$this->db->pdb($from_path).' OR pageSortPath='.$this->db->pdb($from_path).' LIMIT 1';
            }

            $root = $this->db->get_row($sql);

            if (PerchUtil::count($root)) {

                if ($navgroup) {
                    $sql = 'SELECT p.*, np.* FROM '.PERCH_DB_PREFIX.'navigation_pages np, '.$this->table.' p
                        WHERE p.pageID=np.pageID AND np.groupID='.$this->db->pdb((int)$groupID).' AND np.pageParentID='.$this->db->pdb((int)$root['pageParentID']).' LIMIT 1';
                }else{
                    $sql = 'SELECT * FROM '.$this->table.' WHERE pageID='.$this->db->pdb((int)$root['pageParentID']).' LIMIT 1';
                }



                $page = $this->db->get_row($sql);

                if (PerchUtil::count($page)) {

                    // hide default doc
                    if ($hide_default_doc) {
                        $page['pagePath'] = preg_replace('/'.preg_quote(PERCH_DEFAULT_DOC).'$/', '', $page['pagePath']);
                    }

                    // hide extensions
                    if ($hide_extensions) {
                        $page['pagePath'] = preg_replace('/'.preg_quote(PERCH_DEFAULT_EXT).'$/', '', $page['pagePath']);
                    }

                    // trailing slash
                    if ($add_trailing_slash) {
                        $page['pagePath'] = rtrim($page['pagePath'], '/').'/';
                    }

                    // expand attributes
                    if ($expand_attributes && isset($page['pageAttributes']) && $page['pageAttributes']!='') {
                        $dynamic_fields = PerchUtil::json_safe_decode($page['pageAttributes'], true);
                        if (PerchUtil::count($dynamic_fields)) {
                            foreach($dynamic_fields as $key=>$value) {
                                $page[$key] = $value;
                            }
                        }
                        $page = array_merge($dynamic_fields, $page);
                    }

                    if ($skip_template) return $page;

                    $Template = new PerchTemplate($template, 'pages');
                    return $Template->render_group(array($page), true);
                }
            }
        }
        return false;
    }

    public function get_navigation($opts, $current_page)
    {
        $from_path             = $opts['from-path'];
        $levels                = $opts['levels'];
        $hide_extensions       = $opts['hide-extensions'];
        $hide_default_doc      = $opts['hide-default-doc'];
        $flat                  = $opts['flat'];
        $templates             = $opts['template'];
        $include_parent        = $opts['include-parent'];
        $skip_template         = $opts['skip-template'];
        $siblings              = $opts['siblings'];
        $only_expand_selected  = $opts['only-expand-selected'];
        $add_trailing_slash    = $opts['add-trailing-slash'];
        $navgroup              = $opts['navgroup'];
        $access_tags           = $opts['access-tags'];
        $include_hidden        = $opts['include-hidden'];
        $from_level            = $opts['from-level'];
        $expand_attributes     = $opts['use-attributes'];


        if ($access_tags == false) $access_tags = array();

        if (!is_array($templates)) {
            $templates = array($templates);
        }

        foreach($templates as &$template) {
            $template = 'navigation/'.$template;
        }

        // navgroup
        if ($navgroup) {
            $groupID = $this->db->get_value('SELECT groupID FROM '.PERCH_DB_PREFIX.'navigation WHERE groupSlug='.$this->db->pdb($navgroup).' LIMIT 1');
        }else{
            $groupID = false;
        }


        // from path
        if ($from_path && $from_path != '/') {

            $from_path = rtrim($from_path, '/');

            if ($navgroup) {
                $sql = 'SELECT np.pageID, np.pageParentID, np.pageDepth, np.pageTreePosition
                        FROM '.PERCH_DB_PREFIX.'navigation_pages np, '.$this->table.' p
                        WHERE p.pageID=np.pageID AND np.groupID='.$this->db->pdb((int)$groupID).' AND (p.pagePath='.$this->db->pdb($from_path).' OR p.pageSortPath='.$this->db->pdb($from_path).') LIMIT 1';
            }else{
                $sql = 'SELECT pageID, pageParentID, pageDepth, pageTreePosition FROM '.$this->table.' WHERE pagePath='.$this->db->pdb($from_path).' OR pageSortPath='.$this->db->pdb($from_path).' LIMIT 1';
            }


            $root = $this->db->get_row($sql);

            if ($siblings) {
                // show siblings, so we actually want to select the parent page
                if ($navgroup) {
                    $sql = 'SELECT pageID, pageParentID, pageDepth, pageTreePosition FROM '.PERCH_DB_PREFIX.'navigation_pages WHERE groupID='.$this->db->pdb((int)$groupID).' AND pageID='.$this->db->pdb((int)$root['pageParentID']).' LIMIT 1';
                }else{
                    $sql = 'SELECT pageID, pageParentID, pageDepth, pageTreePosition FROM '.$this->table.' WHERE pageID='.$this->db->pdb((int)$root['pageParentID']).' LIMIT 1';
                }

                $root = $this->db->get_row($sql);
            }

            if ($from_level!==false) {
                $parts = explode('-', $root['pageTreePosition']);
                if (PerchUtil::count($parts)) {
                    $new_root_tree_position = implode('-', array_slice($parts, 0, (int)$from_level+1));

                    if ($new_root_tree_position) {
                        if ($navgroup) {
                            $sql = 'SELECT pageID, pageParentID, pageDepth, pageTreePosition FROM '.PERCH_DB_PREFIX.'navigation_pages WHERE groupID='.$this->db->pdb((int)$groupID).' AND pageTreePosition='.$this->db->pdb($new_root_tree_position).' LIMIT 1';
                        }else{
                            $sql = 'SELECT pageID, pageParentID, pageDepth, pageTreePosition FROM '.$this->table.' WHERE pageTreePosition='.$this->db->pdb($new_root_tree_position).' LIMIT 1';
                        }

                        $root = $this->db->get_row($sql);
                    }
                }
            }

            $min_level = (int)$root['pageDepth'];
            $max_level = $min_level + $levels;

        }else{
            $root = false;

            $min_level = 0;
            $max_level = $min_level + $levels;
        }



        // cache page list
        if ($navgroup) {
            $sql = 'SELECT np.pageID, np.pageParentID, p.pagePath, p.pageTitle, p.pageNavText, p.pageNew, p.pageOrder, np.pageDepth, p.pageSortPath, np.pageTreePosition, p.pageAccessTags, p.pageAttributes
                    FROM '.PERCH_DB_PREFIX.'navigation_pages np, '.$this->table.' p WHERE p.pageID=np.pageID AND np.groupID='.$this->db->pdb((int)$groupID).' AND p.pageNew=0 ';

            // if from path is set
            if ($root) {
                $sql .= ' AND np.pageTreePosition LIKE '.$this->db->pdb($root['pageTreePosition'].'%').' ';
            }

            // levels
            if ($levels) {
                $sql .= ' AND np.pageDepth >='.$min_level.' AND np.pageDepth<='.$max_level.' ';
            }

             $sql .= ' ORDER BY np.pageTreePosition ASC';

        }else{
            $sql = 'SELECT * FROM '.$this->table.' WHERE pageNew=0 ';

            if (!$include_hidden) {
                $sql .= ' AND pageHidden=0 ';
            }

            // if from path is set
            if ($root) {
                $sql .= ' AND pageTreePosition LIKE '.$this->db->pdb($root['pageTreePosition'].'%').' ';
            }

            // levels
            if ($levels) {
                $sql .= ' AND pageDepth >='.$min_level.' AND pageDepth<='.$max_level.' ';
            }

             $sql .= ' ORDER BY pageTreePosition ASC';
        }



        $this->nav_page_cache = $this->db->get_rows($sql);


        if (PerchUtil::count($this->nav_page_cache)) {

            $selected_ids = array();

            $ext_length = strlen(PERCH_DEFAULT_EXT);

            // select the tree
            $selected_ids = $this->find_parent_page_ids_by_path($current_page, $groupID);

            // loop-de-loop-de-pages
            $chosen_ones = array();
            foreach($this->nav_page_cache as &$page) {

                // find current page
                if ($page['pagePath']==$current_page) {

                    if (is_array($selected_ids)) {
                        array_unshift($selected_ids, $page['pageID']);
                    }else{
                        $selected_ids = array($page['pageID']);
                    }

                }

                // hide default doc
                if ($hide_default_doc) {
                    $page['pagePath'] = preg_replace('/'.preg_quote(PERCH_DEFAULT_DOC).'$/', '', $page['pagePath']);
                }

                // hide extensions
                if ($hide_extensions) {
                    $page['pagePath'] = preg_replace('/'.preg_quote(PERCH_DEFAULT_EXT).'$/', '', $page['pagePath']);
                }

                // trailing slash
                if ($add_trailing_slash) {
                    $page['pagePath'] = rtrim($page['pagePath'], '/').'/';
                }

                if (trim($page['pageAccessTags'])=='') {
                    $chosen_ones[] = $page;
                }else{
                    $intersection = array_intersect($access_tags, explode(',', $page['pageAccessTags']));
                    if (PerchUtil::count($intersection)) {
                        $chosen_ones[] = $page;
                    }
                }


            }
            $this->nav_page_cache = $chosen_ones;
            $chosen_ones = null;


            if ($flat) {

                // Template them all flat.

                $rows = $this->nav_page_cache;
                foreach($rows as &$row) {
                    if (is_array($selected_ids) && in_array($row['pageID'], $selected_ids)) {
                        if ($selected_ids[0]==$row['pageID']) {
                            $row['current_page'] = true;
                        }else{
                            $row['ancestor_page'] = true;
                        }

                    }

                    if ($expand_attributes && isset($row['pageAttributes']) && $row['pageAttributes']!='') {
                        $dynamic_fields = PerchUtil::json_safe_decode($row['pageAttributes'], true);
                        if (PerchUtil::count($dynamic_fields)) {
                            foreach($dynamic_fields as $key=>$value) {
                                $row[$key] = $value;
                            }
                        }
                        $row = array_merge($dynamic_fields, $row);
                    }
                }

                if ($skip_template) {
                    return $rows;
                }

                $Template = new PerchTemplate($templates[0], 'pages');
                return $Template->render_group($rows, true);

            }else{

                // Template nested

                if ($root) {
                    if ($include_parent) {
                        $parentID = $root['pageParentID'];
                    }else{
                        $parentID = $root['pageID'];
                    }

                }else{
                    $parentID = 0;
                }

                if ($skip_template) $templates = false;

                return $this->_template_nav($templates, $selected_ids, $parentID, $level=0, $skip_template, $only_expand_selected, $expand_attributes);
            }


        }



        return '';
    }

    public function modify_subpage_permissions($grant_or_revoke='grant', $roleID)
    {
        // get all role IDs
        $sql = 'SELECT roleID FROM '.PERCH_DB_PREFIX.'user_roles WHERE roleID != '.(int)$roleID.' ORDER BY roleID ASC';
        $all_roles_but_this = $this->db->get_rows_flat($sql);

        $pages = $this->all();

        $Perch = Perch::fetch();

        if (PerchUtil::count($pages)) {

            foreach($pages as $Page) {

                if ($grant_or_revoke == 'grant') {

                    if ($Page->pageSubpageRoles()=='*') {
                        continue;
                    }else{
                        $roles = explode(',', $Page->pageSubpageRoles());
                        if (!in_array($roleID, $roles)) {
                            $roles[] = $roleID;

                            sort($roles);

                            $Page->update(array(
                                'pageSubpageRoles' => implode(',', $roles)
                            ));

                            $Perch->event('page.update_permissions', $Page);
                            continue;
                        }
                    }
                }

                if ($grant_or_revoke == 'revoke') {

                    if ($Page->pageSubpageRoles()=='*') {
                        $Page->update(array(
                            'pageSubpageRoles' => implode(',', $all_roles_but_this)
                        ));
                        continue;
                    }else{
                        $roles = explode(',', $Page->pageSubpageRoles());
                        if (in_array($roleID, $roles)) {
                            $position = array_search($roleID, $roles);
                            unset($roles[$position]);

                            sort($roles);

                            $Page->update(array(
                                'pageSubpageRoles' => implode(',', $roles)
                            ));

                            $Perch->event('page.update_permissions', $Page);
                            continue;
                        }
                    }
                }
            }
        }
    }

    /**
     * If there are no pages, set up the basic home page and error pages etc
     * @return [type] [description]
     */
    public function create_defaults($CurrentUser)
    {
        $PageTemplates = new PerchContent_PageTemplates();
        $PageTemplates->find_and_add_new_templates();


        // Create home page
        $DefaultTemplate = $PageTemplates->get_one_by('templatePath', 'home.php');
        if ($DefaultTemplate) {
            $data = array(
                    'pageTitle'      => PerchLang::get('Home page'),
                    'pageNavText'    => PerchLang::get('Home page'),
                    'file_name'      => '',
                    'pageParentID'   => '0',
                    'templateID'     => $DefaultTemplate->id(),
                    'pageNew'        => 1,
                    'pageCreatorID'  => $CurrentUser->id(),
                    'pageModified'   => date('Y-m-d H:i:s'),
                    'pageAttributes' => '',
                    'pageTemplate'   => $DefaultTemplate->templatePath(),
                    );

            $this->create_without_file($data);
        }


        // Create error pages
        $ErrorTemplate = $PageTemplates->get_one_by('templatePath', 'errors/404.php');
        if ($ErrorTemplate) {
            $data = array(
                    'pageTitle'      => PerchLang::get('Errors'),
                    'pageNavText'    => PerchLang::get('Errors'),
                    'file_name'      => '/errors',
                    'pageParentID'   => '0',
                    'templateID'     => $ErrorTemplate->id(),
                    'pageNew'        => 1,
                    'pageCreatorID'  => $CurrentUser->id(),
                    'pageModified'   => date('Y-m-d H:i:s'),
                    'pageAttributes' => '',
                    'pageTemplate'   => $ErrorTemplate->templatePath(),
                    'pageHidden'     => '1',
                    );

            $ErrorsPage = $this->create_without_file($data);
            $TopErrorsPage = $ErrorsPage;

            $data = array(
                    'pageTitle'      => '404',
                    'pageNavText'    => '404',
                    'file_name'      => '/errors/404',
                    'pageParentID'   => $ErrorsPage->id(),
                    'templateID'     => $ErrorTemplate->id(),
                    'pageNew'        => 1,
                    'pageCreatorID'  => $CurrentUser->id(),
                    'pageModified'   => date('Y-m-d H:i:s'),
                    'pageAttributes' => '',
                    'pageTemplate'   => $ErrorTemplate->templatePath(),
                    'pageHidden'     => '1',
                    );

            $ErrorsPage = $this->create_without_file($data);
        }

        $ErrorTemplate = $PageTemplates->get_one_by('templatePath', 'errors/login-required.php');
        if ($ErrorTemplate) {
            $data = array(
                    'pageTitle'      => 'Login required',
                    'pageNavText'    => 'Login required',
                    'file_name'      => '/errors/login-required',
                    'pageParentID'   => $TopErrorsPage->id(),
                    'templateID'     => $ErrorTemplate->id(),
                    'pageNew'        => 1,
                    'pageCreatorID'  => $CurrentUser->id(),
                    'pageModified'   => date('Y-m-d H:i:s'),
                    'pageAttributes' => '',
                    'pageTemplate'   => $ErrorTemplate->templatePath(),
                    'pageHidden'     => '1',
                    );

            $ErrorsPage = $this->create_without_file($data);
        }

        $ErrorTemplate = $PageTemplates->get_one_by('templatePath', 'errors/site-offline.php');
        if ($ErrorTemplate) {
            $data = array(
                    'pageTitle'      => 'Site offline',
                    'pageNavText'    => 'Site offline',
                    'file_name'      => '/errors/site-offline',
                    'pageParentID'   => $TopErrorsPage->id(),
                    'templateID'     => $ErrorTemplate->id(),
                    'pageNew'        => 1,
                    'pageCreatorID'  => $CurrentUser->id(),
                    'pageModified'   => date('Y-m-d H:i:s'),
                    'pageAttributes' => '',
                    'pageTemplate'   => $ErrorTemplate->templatePath(),
                    'pageHidden'     => '1',
                    );

            $ErrorsPage = $this->create_without_file($data);
        }


    }



    private function _template_nav($templates, $selected_ids, $parentID=0, $level=0, $Template=false, $only_expand_selected=false, $expand_attributes=false)
    {
        $rows = array();
        foreach($this->nav_page_cache as $page) {
            if ($page['pageParentID']==$parentID) {
                $rows[] = $page;
            }
        }

        if (PerchUtil::count($rows)) {

            if ($templates) {
                if (isset($templates[$level])) {
                    $template = $templates[$level];
                }else{
                    $template = $templates[count($templates)-1];
                }

                if ($Template==false || $Template->current_file!=$template) {
                    $Template = new PerchTemplate($template, 'pages');
                }

            }

            foreach($rows as &$row) {

                if ($only_expand_selected) {
                    if (is_array($selected_ids) && in_array($row['pageID'], $selected_ids)) {

                        if ($selected_ids[0]==$row['pageID']) {
                            $row['current_page'] = true;
                        }else{
                            $row['ancestor_page'] = true;
                        }

                        $row['subitems'] = $this->_template_nav($templates, $selected_ids, $row['pageID'], $level+1, $Template, $only_expand_selected, $expand_attributes);
                    }
                }else{
                    $row['subitems'] = $this->_template_nav($templates, $selected_ids, $row['pageID'], $level+1, $Template, $only_expand_selected, $expand_attributes);
                    if (is_array($selected_ids) && in_array($row['pageID'], $selected_ids)) {

                        if ($selected_ids[0]==$row['pageID']) {
                            $row['current_page'] = true;
                        }else{
                            $row['ancestor_page'] = true;
                        }

                    }
                }


                if ($expand_attributes && isset($row['pageAttributes']) && $row['pageAttributes']!='') {
                    $dynamic_fields = PerchUtil::json_safe_decode($row['pageAttributes'], true);
                    if (PerchUtil::count($dynamic_fields)) {
                        foreach($dynamic_fields as $key=>$value) {
                            $row[$key] = $value;
                        }
                    }
                    $row = array_merge($dynamic_fields, $row);
                }

            }

            if ($templates) {
                return $Template->render_group($rows, true);
            }

            return $rows;

        }

        return '';
    }
}