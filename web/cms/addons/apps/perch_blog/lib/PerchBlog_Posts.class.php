<?php

class PerchBlog_Posts extends PerchAPI_Factory
{
    protected $table               = 'blog_posts';
    protected $pk                  = 'postID';
    protected $singular_classname  = 'PerchBlog_Post';

    protected $index_table         = 'blog_index';
    protected $namespace           = 'blog';

    protected $event_prefix        = 'blog.post';

    protected $default_sort_column = 'postDateTime';
    protected $created_date_column = 'postDateTime';

	public $static_fields          = array('postTitle', 'postSlug', 'postDateTime', 'postDescRaw', 'postDescHTML', 'postTags', 'postStatus',
                                        'authorID', 'authorGivenName', 'authorFamilyName', 'authorEmail', 'authorSlug', 'postURL', 'postAllowComments', 'sectionID', 'sectionSlug', 'postTemplate');

    public static $preview_mode = false;

    protected $bypass_tags = true;

    function __construct($api=false)
    {
        $this->cache = array();
        parent::__construct($api);

        if (self::$preview_mode) {
            PerchBlog_Cache::disable();
        }
    }

    public function all($Paging=false)
    {
        if ($Paging && $Paging->enabled()) {
            $sql = $Paging->select_sql();
        }else{
            $sql = 'SELECT';
        }

        $sql .= ' *
                FROM ' . $this->table;

        if (isset($this->default_sort_column)) {
            $sql .= ' ORDER BY ' . $this->default_sort_column . ' DESC';
        }

        if ($Paging && $Paging->enabled()) {
            $sql .=  ' '.$Paging->limit_sql();
        }

        $results = $this->db->get_rows($sql);

        if ($Paging && $Paging->enabled()) {
            $Paging->set_total($this->db->get_count($Paging->total_count_sql()));
        }

        return $this->return_instances($results);
    }


    /*
        Get a single post by its ID
    */
    public function find($postID, $is_admin=false)
    {
        $Cache = PerchBlog_Cache::fetch();

        if ($Cache->exists('p'.$postID)) {
            return $Cache->get('p'.$postID);
        }else{
            $sql = 'SELECT * FROM '.PERCH_DB_PREFIX.'blog_posts WHERE postID='.$this->db->pdb((int)$postID);

            if (!$is_admin && self::$preview_mode==false) {
                $sql .= ' AND postStatus=\'Published\' AND postDateTime<='.$this->db->pdb(date('Y-m-d H:i:00')).' ';
            }

            $row = $this->db->get_row($sql);

            // if(is_array($row)) {
            //     $sql = 'SELECT categoryID FROM '.PERCH_DB_PREFIX.'blog_posts_to_categories WHERE postID='.$this->db->pdb((int)$postID);
            //     $result = $this->db->get_rows($sql);
            //     $a = array();
            //     if(is_array($result)) {
            //         foreach($result as $cat_row) {
            //             $a[] = $cat_row['categoryID'];
            //         }
            //     }
            //     $row['cat_ids'] = $a;
            // }

            $r = $this->return_instance($row);

            $Cache->set('p'.$postID, $r);

            return $r;
        }

        return false;
    }

    /**
     * Admin function - find by importID. Used by importer tool.
     * @param  [type] $importID [description]
     * @return [type]           [description]
     */
    public function find_by_importID($importID)
    {
        $sql = 'SELECT * FROM '.PERCH_DB_PREFIX.'blog_posts WHERE postImportID='.$this->db->pdb($importID);
        $row = $this->db->get_row($sql);

        return $this->return_instance($row);
    }

    /*
        Get a single post by its Slug
    */
    public function find_by_slug($postSlug)
    {
        $Cache = PerchBlog_Cache::fetch();

        if ($Cache->exists('p'.$postSlug)) {
            return $Cache->get('p'.$postSlug);
        }else{
            if (self::$preview_mode) {
                $sql = 'SELECT * FROM '.PERCH_DB_PREFIX.'blog_posts WHERE postSlug='.$this->db->pdb($postSlug);
            }else{
                $sql = 'SELECT * FROM '.PERCH_DB_PREFIX.'blog_posts WHERE postStatus=\'Published\' AND postDateTime<='.$this->db->pdb(date('Y-m-d H:i:00')).' AND postSlug='.$this->db->pdb($postSlug);
            }

            $row = $this->db->get_row($sql);

            // if(is_array($row)) {
            //     $sql = 'SELECT categoryID FROM '.PERCH_DB_PREFIX.'blog_posts_to_categories WHERE postID = '.$this->db->pdb($row['postID']);
            //     $result = $this->db->get_rows($sql);
            //     $a = array();
            //     if(is_array($result)) {
            //         foreach($result as $cat_row) {
            //             $a[] = $cat_row['categoryID'];
            //         }
            //     }
            //     $row['cat_ids'] = $a;
            // }

            $r = $this->return_instance($row);

            $Cache->set('p'.$postSlug, $r);

            return $r;
        }

        return false;
    }


	/**
	* takes the post data and inserts it as a new row in the database.
	*/
    public function create($data, $Template=false)
    {
        if (!isset($data['postDateTime'])) $data['postDateTime'] = date('Y-m-d H:i:s');

        if (isset($data['postTitle']) && !isset($data['postSlug'])) {
            $data['postSlug'] = PerchUtil::urlify(date('Y m d', strtotime($data['postDateTime'])). ' ' . $data['postTitle']);
        }

        if (isset($data['cat_ids']) && is_array($data['cat_ids'])) {
            $cat_ids = $data['cat_ids'];
        }else{
            $cat_ids = false;
        }

        unset($data['cat_ids']);

        $postID = $this->db->insert($this->table, $data);

		if ($postID) {
			// if(is_array($cat_ids)) {
			// 	for($i=0; $i<sizeOf($cat_ids); $i++) {
			// 	    $tmp = array();
			// 	    $tmp['postID'] = $postID;
			// 	    $tmp['categoryID'] = $cat_ids[$i];
			// 	    $this->db->insert(PERCH_DB_PREFIX.'blog_posts_to_categories', $tmp);
			// 	}
			// }

			// Split tag string into array
			if(isset($data['postTags']) && $data['postTags'] != '') {
				$a = explode(',',$data['postTags']);
				if (is_array($a)) {
 					for($i=0; $i<sizeOf($a); $i++) {
						$tmp = array();
						$tmp['postID'] = $postID;

						$tag_str = trim($a[$i]);
					//does this tag exist
					$sql = 'SELECT tagID, tagTitle FROM '.PERCH_DB_PREFIX.'blog_tags WHERE tagTitle = '.$this->db->pdb($tag_str).' LIMIT 1';

					$row = $this->db->get_row($sql);


					if(is_array($row)) {
						$tmp['tagID'] = $row['tagID'];
					}else{
						$tag = array();
						$tag['tagTitle'] = $tag_str;
						$tag['tagSlug'] = PerchUtil::urlify($tag_str);
						$tmp['tagID'] = $this->db->insert(PERCH_DB_PREFIX.'blog_tags', $tag);
					}


 			    		$this->db->insert(PERCH_DB_PREFIX.'blog_posts_to_tags', $tmp);
 					}
 				}

			}

            return $this->find($postID, true);
		}
        return false;
	}



    public function get_custom($opts)
    {
        // category mirgation
        if (isset($opts['category'])) {
            if (!is_array($opts['category'])) {
                $opts['category'] = array($opts['category']);
            }

            if (PerchUtil::count($opts['category'])) {
                foreach($opts['category'] as &$cat) {
                    if (strpos($cat, '/')===false) {
                        if (substr($cat, 0, 1)=='!') {
                            $cat = '!blog/'.substr($cat, 1).'/';
                        }else{
                            $cat = 'blog/'.$cat.'/';
                        }

                    }
                }
            }
        }

        $where_callback = $this->_standard_where_callback($opts);
        $pre_template_callback = $this->_standard_pre_template_callback($opts);

        $set_template = $opts['template'];
        $opts['template'] = function($items) use ($set_template) {
            if (isset($set_template) && $set_template!=false) {
                $template = 'blog/'.str_replace('blog/', '', $set_template);
            }else{
                if (PerchUtil::count($items)==1) {
                    $template = 'blog/'.$items[0]->postTemplate();
                }else{
                    $template = 'blog/post.html';
                }
            }
            return $template;
        };


        return $this->get_filtered_listing($opts, $where_callback, $pre_template_callback);
    }


    /**
     * gets the listing by category
     * @param varchar $slug
     */
    public function get_by_category_slug($slug, $sectionID=false)
    {

        $opts = array(
            'category' => $slug,
            'return-objects' => true,
            );

        if ($sectionID) {
            $opts['section'] = $sectionID;
        }

        return $this->get_filtered_listing($opts, $this->_standard_where_callback($opts));

    }

    public function get_by_category_slug_for_admin_listing($slug, $Paging)
    {

        $opts = array(
            'category' => $slug,
            'paginate' => $Paging,
            'admin' => true,
            'return-objects' => true,
            );


        if ($Paging && $Paging->enabled()) {
            list($sort_val, $sort_dir) = $Paging->get_custom_sort_options();
            if ($sort_val) {
                $opts['sort'] = $sort_val;
                $opts['sort-order'] = $sort_dir;
            }
        }

        return $this->get_filtered_listing($opts, $this->_standard_where_callback($opts));

    }


    public function get_by_section_slug_for_admin_listing($slug, $Paging)
    {

        $sort_val = null;
        $sort_dir = null;

        list($sort_val, $sort_dir) = $Paging->get_custom_sort_options();
        if (!$sort_val) {
            $sort_val = $this->default_sort_column;
            $sort_dir = 'DESC';
        }        

        $sql = $Paging->select_sql(). ' p.*
                FROM '.$this->table.' p, '.PERCH_DB_PREFIX.'blog_sections s
                WHERE p.sectionID=s.sectionID
                    AND s.sectionSlug='.$this->db->pdb($slug).'
                ORDER BY '.$sort_val.' '.$sort_dir;


        if ($Paging && $Paging->enabled()) {
            $sql .=  ' '.$Paging->limit_sql();
        }

        $rows = $this->db->get_rows($sql);

        if ($Paging && $Paging->enabled()) {
            $Paging->set_total($this->db->get_count($Paging->total_count_sql()));
        }

        return $this->return_instances($rows);
    }

    public function get_by_status($status='PUBLISHED', $sectionID=false, $blogSlug=false, $Paging=false)
    {
        $blogID = $this->_blog_slug_to_id($blogSlug);

        switch(strtoupper($status)) {
            case 'PUBLISHED':
                $status = 'Published';
                break;

            default:
                $status = 'Draft';
                break;
        }

        if ($Paging) {
            $sql = $Paging->select_sql();
        }else{
            $sql = 'SELECT';
        }

        $sql .= ' p.*
                FROM '.$this->table.' p
                WHERE p.postStatus='.$this->db->pdb($status) .' AND blogID='.$this->db->pdb((int)$blogID);

        if ($sectionID) $sql .= ' AND p.sectionID='.$this->db->pdb($sectionID);

        $sql .= ' ORDER BY '.$this->default_sort_column.' DESC';

        if ($Paging && $Paging->enabled()) {
            $sql .=  ' '.$Paging->limit_sql();
        }

        $rows = $this->db->get_rows($sql);

        if ($Paging && $Paging->enabled()) {
            $Paging->set_total($this->db->get_count($Paging->total_count_sql()));
        }

        return $this->return_instances($rows);
    }

    public function get_years($sectionID=null, $blogID=null)
    {
        $Cache = PerchBlog_Cache::fetch();

        $cache_key = 'get_years';
        if ($sectionID) $cache_key.='_s'.$sectionID;
        if ($blogID) $cache_key.='_b'.$blogID;

        if ($Cache->exists($cache_key)) {
            return $Cache->get($cache_key);
        }

 	    $sql = 'SELECT year(postDateTime) as year, COUNT(*) AS year_qty
    	        FROM '.$this->table .'
    	        WHERE postStatus=\'Published\'
                    AND postDateTime<='.$this->db->pdb(date('Y-m-d H:i:00'));

        if ($sectionID) $sql .= ' AND sectionID='.$this->db->pdb($sectionID);
        if ($blogID) $sql .= ' AND blogID='.$this->db->pdb($blogID);

    	$sql .= ' GROUP BY year
    	        ORDER BY year DESC';

    	$rows   = $this->db->get_rows($sql);

        $Cache->set($cache_key, $rows);

    	return $rows;

    }

    public function get_months_for_year($year, $sectionID=false)
    {

        $Cache = PerchBlog_Cache::fetch();

        $cache_key = 'months_for_year_'.$year;
        if ($sectionID) $cache_key.='_'.$sectionID;


        if ($Cache->exists($cache_key)) {
            return $Cache->get($cache_key);
        }

    	$sql = 'SELECT DISTINCT
    	            year(postDateTime) AS year,
    	            month(postDateTime) AS month,
    	            CONCAT(year(postDateTime),"-",month(postDateTime),"-01") AS grouped_date,
    	            COUNT(*) AS month_qty
                FROM '.$this->table .' p
            	WHERE year(postDateTime) = '.$this->db->pdb($year).'
            	    AND p.postStatus=\'Published\'
                    AND p.postDateTime<='.$this->db->pdb(date('Y-m-d H:i:00'));

        if ($sectionID) $sql .= ' AND p.sectionID='.$this->db->pdb($sectionID);

        $sql .= ' GROUP BY year, month, grouped_date
            	ORDER BY month DESC';

        $rows   = $this->db->get_rows($sql);

        if (PerchUtil::count($rows)) {
            foreach($rows as &$row) {
                $row['postDateTime'] = $row['grouped_date'];
            }
        }

    	$Cache->set($cache_key, $rows);

    	return $rows;
    }

    public function update_category_counts()
    {
        $sql = 'SELECT COUNT(*) AS qty, c.catID
                FROM '.PERCH_DB_PREFIX.$this->index_table.' i, '.PERCH_DB_PREFIX.'categories c, '.$this->table.' p
                WHERE i.indexValue=c.catPath AND i.indexKey=\'_category\' AND i.itemKey=\'postID\' AND i.itemID=p.postID
                    AND p.postStatus=\'Published\' AND p.postDateTime<='.$this->db->pdb(date('Y-m-d H:i:00')).'
                GROUP BY i.indexValue, c.catID
                ';
        $rows = $this->db->get_rows($sql);

        $sql = 'DELETE FROM '.PERCH_DB_PREFIX.'category_counts WHERE countType='.$this->db->pdb('blog.post');
        $this->db->execute($sql);

        if (PerchUtil::count($rows)) {
            $Categories = new PerchCategories_Categories();
            foreach($rows as $row) {
                $Category = $Categories->find((int)$row['catID']);
                if ($Category) {
                    $Category->update_count('blog.post', $row['qty']);
                }
            }
        }
    }


    public function publish_posts()
    {
        $posts = $this->get_custom([
            'return-objects' => true,
            'unpublished'    => true,
            'template'       => null,
        ]);

        if (PerchUtil::count($posts)) {
            foreach($posts as $Post) {
                $Post->publish();
            }

            $this->update_category_counts();
        }

        return PerchUtil::count($posts);
    }


    private function _standard_where_callback($opts)
    {

        $preview_mode = self::$preview_mode;
        $db = $this->db;

        return function(PerchQuery $Query) use ($opts, $preview_mode, $db) {


            // blog
            if (isset($opts['blog'])) {
                $blogID = $this->_blog_slug_to_id($opts['blog']);
                $Query->where[] = ' blogID='.(int)$blogID.' ';
            }

            // section
            if (isset($opts['section'])) {
                $Sections = new PerchBlog_Sections;

                if (is_numeric($opts['section'])) {
                    $Section = $Sections->find($opts['section']);
                }else{
                    $Section = $Sections->find_by_slug($opts['section']);
                }

                if (is_object($Section)) {
                    $Query->where[] = ' sectionID='.$db->pdb($Section->id());
                }else{
                    $Query->where[] = ' sectionID IS NULL ';
                }
            }


            // author
            if (isset($opts['author'])) {
                $Authors = new PerchBlog_Authors;

                if (is_numeric($opts['author'])) {
                    $Author = $Authors->find($opts['author']);
                }else{
                    $Author = $Authors->find_by_slug($opts['author']);
                }

                if (is_object($Author)) {
                    $Query->where[] = ' authorID='.$db->pdb($Author->id());
                }else{
                    $Query->where[] = ' authorID IS NULL ';
                }
            }


            // tags
            if (isset($opts['tag'])) {
                $tags = $opts['tag'];
                if (!is_array($tags)) $tags = array($tags);

                $do_tag = array();
                $do_not_tag = array();

                foreach($tags as $tag) {
                    if (substr($tag, 0, 1)=='!') {
                        $do_not_tag[] = ltrim($tag, '!');
                    }else{
                        $do_tag[] = $tag;
                    }
                }


                if (is_array($tags)) {


                    $Query->select .= ' LEFT JOIN '.PERCH_DB_PREFIX.'blog_posts_to_tags p2t ON tbl.postID=p2t.postID LEFT JOIN '.PERCH_DB_PREFIX.'blog_tags t ON p2t.tagID=t.tagID ';


                    if (PerchUtil::count($do_tag)) {
                        $Query->where[] = ' tagSlug IN ('.$db->implode_for_sql_in($do_tag).') ';
                    }

                    if (PerchUtil::count($do_not_tag)) {
                        $Query->where[] = ' tbl.postID NOT IN (
                                SELECT p2.postID FROM '.$this->table.' p2, '.PERCH_DB_PREFIX.'blog_posts_to_tags p2t2, '.PERCH_DB_PREFIX.'blog_tags t2
                                    WHERE p2.postID=p2t2.postID  AND p2t2.tagID=t2.tagID AND t2.tagSlug IN ('.$db->implode_for_sql_in($do_not_tag).')
                                )';
                    }

                }
            }

            if ($preview_mode || (isset($opts['admin']) && $opts['admin'])) {
                // nothing
            }else{
                $Query->where[] = 'postStatus=\'Published\' AND postDateTime<='.$db->pdb(date('Y-m-d H:i:00')).' ';
            }


            // unpublished
            if (isset($opts['unpublished']) && $opts['unpublished']) {
                $Query->where[] = 'postIsPublished=0';
            }

            return $Query;

        };


    }

    private function _standard_pre_template_callback($opts)
    {

        return function($items) use ($opts) {
            if (isset($opts['include-meta'])) {
                $domain = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'];
                if (PerchUtil::count($items)) {
                    foreach($items as &$item) {
                        $item['domain'] = $domain;
                    }
                }
            }
            return $items;
        };
    }

    private function _blog_slug_to_id($slug)
    {
        if (!$slug) return 1;

        if (PERCH_RUNWAY) {
            $sql = 'SELECT blogID FROM '.PERCH_DB_PREFIX.'blogs WHERE blogSlug='.$this->db->pdb($slug).' LIMIT 1';
            $result = (int)$this->db->get_value($sql);
            if ($result > 0) return $result;
            return 1;
        }

        return 1;
    }
}
