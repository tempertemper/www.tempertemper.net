<?php

class PerchCategoryImporter
{
	private $Template      = null;
	private $Form          = null;
	private $Set           = null;
	private $template_tags = null;
	private $api 		   = null;

	public function set_template(PerchAPI_Template $Template)
	{
		$this->api      = new PerchAPI(1.0, 'categories');
		$this->Template = $Template;
		$this->Form     = $this->api->get('Form');

	}

	public function set_set($key)
	{
		$CategorySets = new PerchCategories_Sets;
		$Set = $CategorySets->get_one_by('setSlug', $key);

		if ($Set) {
			$this->Set = $Set;
			return true;
		}

		throw new \Exception('Category Set not found: '.$key);

		return false;
	}

	public function empty_set()
	{
		$Categories = new PerchCategories_Categories();

		$Set = $this->get_active_set();
		$categories = $Categories->get_for_set($Set->setSlug());
		if (PerchUtil::count($categories)) {
			foreach($categories as $Category) {
				$Category->delete();
			}
		}
	}

	public function add_item($data)
	{
		if (!count($data)) return;

		$Perch  = PerchAdmin::fetch();
		$Categories = new PerchCategories_Categories();
		$Category  = false;

		$Set = $this->get_active_set();

		$this->validate_input($data);

		$content_vars = [
			'setID' => $Set->id(),
			'catTitle' => $data['catTitle'],
		];


		$tags = $this->get_template_tags();
		$seen_tags = array_keys($content_vars);

		foreach($tags as $Tag) {
			if (array_key_exists($Tag->id, $data) && !in_array($Tag->id, $seen_tags)) {
				$seen_tags[] = $Tag->id;

				$FieldType = $this->get_field_type($Tag, $tags);

				// import the data
				$content_vars[$Tag->id] = $FieldType->import_data($data);

			}
		}

		if (count($content_vars)) {

			$dynamic = [];

			foreach($content_vars as $key => &$val) {
				if (!in_array($key, $Categories->static_fields)) {
					$dynamic[$key] = $val;
					unset($content_vars[$key]);
				} 
			}

			$content_vars['catDynamicFields'] = PerchUtil::json_safe_encode($dynamic);

			//PerchUtil::debug($content_vars);

			$Category = $Categories->find_or_create_from_data($content_vars);

			if (!$Category) return false;

			$Set->update_all_in_set();

            return $Category->catPath();
		}

	}

	private function get_active_set()
	{
		if (!$this->Set) {
			throw new \Exception('No category set. Call PerchCategoryImporter::set_set() to set.');
		}

		return $this->Set;
	}

	private function get_template()
	{
		if (!$this->Template) {
			throw new \Exception('No template. Call PerchCategoryImporter::set_template() to set.');
		}

		return $this->Template;
	}

	private function validate_input($data)
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