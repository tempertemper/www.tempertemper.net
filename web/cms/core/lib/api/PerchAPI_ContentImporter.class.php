<?php

class PerchAPI_ContentImporter
{
	private $Template = null;
	private $Form = null;
	private $Factory = null;
	private $template_tags = null;

	protected $api;


	public $app_id = false;
    public $version = 1.0;
    
    private $Lang = false;
    
    function __construct($version=1.0, $app_id, $Lang)
    {
        $this->app_id = $app_id;
        $this->version = $version;
        
        $this->Lang = $Lang;
        $this->api = new PerchAPI(1.0, $app_id);

        $this->Form = new PerchForm();
        $this->setup();

        if (!PERCH_RUNWAY) exit;
    }

    protected function setup()
    {

    }

	public function set_template(PerchAPI_Template $Template)
	{
		$this->Template = $Template;
	}

	public function set_factory($Factory)
	{
		$this->Factory = $Factory;
	}


	public function empty_all()
	{
		$Factory = $this->get_active_factory();
		$items = $Factory->all();

		if (PerchUtil::count($items)) {
			foreach($items as $Item) {
				$Item->delete();
			}
		}
	}

	public function add_item($data)
	{
		if (!count($data)) return;

		$Perch  = PerchAdmin::fetch();

		$Factory = $this->get_active_factory();

		$content_vars = [];
		$search_text  = '';

		$this->validate_input($data);

		$tags = $this->get_template_tags();
		$seen_tags = [];

		foreach($tags as $Tag) {
			if (array_key_exists($Tag->id, $data) && !in_array($Tag->id, $seen_tags)) {
				$seen_tags[] = $Tag->id;

				$FieldType = $this->get_field_type($Tag, $tags);

				// import the data
				$content_vars[$Tag->id] = $FieldType->import_data($data);

			}
		}

		if (count($content_vars)) {

			$new_item = [];

			if ($Factory) {
	            foreach($Factory->static_fields as $field) {
	                if (array_key_exists($field, $content_vars)) {
	                    if (!isset($new_item[$field])) $new_item[$field] = $content_vars[$field];
	                    unset($content_vars[$field]);
	                }
	            }

	            $new_item[$Factory->dynamic_fields_column] = PerchUtil::json_safe_encode($content_vars);
	        }

	        $Item = $Factory->create($new_item);
	        if ($Item) {
	        	$Item->update($new_item);
	        	return ['id'=>$Item->id()];
	        }
		}

		return false;
	}

	protected function get_active_factory()
	{
		if (!$this->Factory) {
			throw new \Exception('No factory set.');
		}

		return $this->Factory;
	}

	private function get_template()
	{
		if (!$this->Template) {
			throw new \Exception('No template set.');
		}

		return $this->Template;
	}

	protected function validate_input($data)
	{
		$Template        = $this->get_template();
		$tags            = $this->get_template_tags();
		$required_fields = $this->get_required_fields($tags, $Template);

		if (PerchUtil::count($required_fields)) {
			foreach($required_fields as $id) {
				if (!array_key_exists($id, $data)) {
					throw new \Exception('Missing required field: '. $id);
				}
			}
		}

	}

	private function get_required_fields($tags, $Template)
	{
		$req       = [];
		$seen_tags = [];

	    if (PerchUtil::count($tags)) {

		    foreach($tags as $tag) {

		        if (!in_array($tag->id(), $seen_tags)) {
		            if (PerchUtil::bool_val($tag->required())) {

		            	if (!PERCH_RUNWAY && $tag->runway()) {
			            	continue;
						}

		                $req[] = $tag->id();

		            }

		            $seen_tags[] = $tag->id();
		        }
		    }
		}

		return $req;
	}

	private function get_template_tags()
	{
		if (!is_null($this->template_tags)) {
			return $this->template_tags;
		}

		$Template = $this->get_template();

		$this->template_tags = $Template->find_all_tags_and_repeaters();

		return $this->template_tags;
	}

	private function get_field_type($Tag, $tags)
	{
		return PerchFieldTypes::get($Tag->type(), $this->Form, $Tag, $tags);
	}
}