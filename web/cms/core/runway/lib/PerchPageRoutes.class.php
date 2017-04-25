<?php

class PerchPageRoutes extends PerchFactory
{
	protected $singular_classname = 'PerchPageRoute';
	protected $table              = 'page_routes';
	protected $pk                 = 'routeID';
	protected $namespace 		  = 'route';
	protected $event_prefix       = 'route';
	
	protected $default_sort_column  = 'routeOrder';  

	public $static_fields   = array('routeID', 'pageID', 'routePattern', 'routeRegExp', 'routeOrder');


	public function create($data)
	{
		if (isset($data['routePattern'])) {
			$Router = new PerchRouter();
			$data['routeRegExp'] = $Router->pattern_to_regexp($data['routePattern']);
		}

		if (!isset($data['routeOrder']) && isset($data['pageID'])) {
			$data['routeOrder'] = $this->get_next_route_order($data['pageID']);
		}

		return parent::create($data);
	}
	
	public function get_routes_for_page($pageID)
	{
		return $this->get_by('pageID', $pageID);
	}

	public function get_routes_for_template($templateID)
	{
		return $this->get_by('templateID', $templateID);
	}

	public function get_next_route_order($pageID)
	{
		$sql = 'SELECT MAX(routeOrder) FROM '.$this->table.' WHERE pageID='.$this->db->pdb((int)$pageID);
		$result = $this->db->get_value($sql);

		if (!$result) return 1;

		return $result;
	}

	public function get_routes_for_admin_edit($Paging)
	{
		$sql = $Paging->select_sql().' pr.*, p.pagePath, pt.templatePath, pt.templateTitle
				FROM '.$this->table.' pr 
					LEFT JOIN  '.PERCH_DB_PREFIX.'pages p ON pr.pageID=p.pageID
					LEFT JOIN  '.PERCH_DB_PREFIX.'page_templates pt ON pr.templateID=pt.templateID
				ORDER BY pr.routeOrder ASC '.$Paging->limit_sql();

		$results = $this->db->get_rows($sql);
		$Paging->set_total($this->db->get_count($Paging->total_count_sql()));
		return $this->return_instances($results);
	}

}