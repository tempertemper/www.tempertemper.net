<?php

class PerchBlog_Authors extends PerchAPI_Factory
{
    protected $table               = 'blog_authors';
    protected $pk                  = 'authorID';
    protected $singular_classname  = 'PerchBlog_Author';
    protected $index_table         = 'blog_index';
    protected $namespace           = 'blog';
    protected $event_prefix        = 'blog.author';
    protected $default_sort_column = 'authorFamilyName, authorGivenName';
    public $static_fields          = array('authorFamilyName', 'authorGivenName', 'authorEmail', 'authorPostCount', 'authorSlug', 'authorImportRef', 'authorDynamicFields');

	/**
	 * Find an author based on their email address. If not found, create a new one.
	 * @param  Object $User Instance of a user object - usually CurrentUser.
	 * @return Object       Instance of an author object
	 */
	public function find_or_create($User)
	{
		$sql = 'SELECT * FROM '.$this->table.' WHERE authorEmail='.$this->db->pdb($User->userEmail()).' LIMIT 1';
		$row = $this->db->get_row($sql);

		if (PerchUtil::count($row)) {
			return $this->return_instance($row);
		}

		// Author wasn't found, so create a new one and return it. (It? Him or her.)

		$data = array();
        $data['authorEmail']      = $User->userEmail();
        $data['authorGivenName']  = $User->userGivenName();
        $data['authorFamilyName'] = $User->userFamilyName();
        $data['authorSlug']       = PerchUtil::urlify($data['authorGivenName'].' '.$data['authorFamilyName']);

		$Author = $this->create($data);

		return $Author;
	}


	/**
	 * Find an author based on their email address. If not found, create a new one.
	 * @param  Object $User Instance of a user object - usually CurrentUser.
	 * @return Object       Instance of an author object
	 */
	public function find_or_create_by_email($email, $data)
	{
		$sql = 'SELECT * FROM '.$this->table.' WHERE authorEmail='.$this->db->pdb($email).' LIMIT 1';
		$row = $this->db->get_row($sql);

		if (PerchUtil::count($row)) {
			return $this->return_instance($row);
		}

		// Author wasn't found, so create.

		$Author = $this->create($data);

		return $Author;
	}

    public function get_custom($opts)
    {
        $opts['template'] = 'blog/'.$opts['template'];

        $where_callback = function(PerchQuery $Query) use ($opts) {
            if (!isset($opts['include-empty']) || $opts['include-empty']==false) {
                 $Query->where[] = 'authorPostCount>0';               
            }
            return $Query;
        };

        return $this->get_filtered_listing($opts, $where_callback);
    }


    /**
     * Find an author by its authorSlug
     *
     * @param string $slug 
     * @return void
     * @author Drew McLellan
     */
    public function find_by_slug($slug)
    {
        return $this->get_one_by('authorSlug', $slug);
    }

    public function update_post_counts()
    {
        $sql = 'SELECT authorID, COUNT(*) AS qty
                FROM '.PERCH_DB_PREFIX.'blog_posts 
                WHERE postStatus=\'Published\' AND postDateTime<='.$this->db->pdb(date('Y-m-d H:i:00')).' 
                GROUP BY authorID';
        $rows = $this->db->get_rows($sql);

        if (PerchUtil::count($rows)) {

            // reset counts to zero
            $sql = 'UPDATE '.PERCH_DB_PREFIX.'blog_authors SET authorPostCount=0';
            $this->db->execute($sql);

            foreach($rows as $row) {
                $sql = 'UPDATE '.PERCH_DB_PREFIX.'blog_authors SET authorPostCount='.$this->db->pdb($row['qty']).' WHERE authorID='.$this->db->pdb((int)$row['authorID']).' LIMIT 1';
                $this->db->execute($sql);
            }
        }
    }

}
