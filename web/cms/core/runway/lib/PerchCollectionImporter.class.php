<?php

class PerchCollectionImporter
{
	private $Template      = null;
	private $Form          = null;
	private $Collection    = null;
	private $template_tags = null;

	public function set_template(PerchAPI_Template $Template)
	{
		$this->Template = $Template;
		$this->Form = new PerchForm();
	}

	public function set_collection($key)
	{
		$Collections = new PerchContent_Collections;
		$Collection = $Collections->get_one_by('collectionKey', $key);

		if ($Collection) {
			$this->Collection = $Collection;
			return true;
		}

		throw new \Exception('Collection not found: '.$key);

		return false;
	}

	public function empty_collection()
	{
		$Collection = $this->get_active_collection();
		$Collection->delete_all_items();
	}

	public function add_item($data)
	{
		if (!count($data)) return;

		$Perch  = PerchAdmin::fetch();

		$Collection = $this->get_active_collection();
		$options 	= $Collection->get_options();

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

				// find the title
				$content_vars = PerchContent_Util::determine_title($Tag, $content_vars[$Tag->id], $options, $content_vars);
				
				// build up a search string
				$search_text .= ' '.$FieldType->get_search_text($content_vars[$Tag->id]);
			}
		}

		if (count($content_vars)) {

			$Item = $Collection->add_new_item();

			if (!$Item) return false;

			// Set the ID
			$content_vars['_id'] 	= $Item->itemID();

			$new_data = [];
            $new_data['itemJSON']   = PerchUtil::json_safe_encode($content_vars);
            $new_data['itemSearch'] = trim($search_text);

            $Item->update($new_data);

            // Publish 
            $Item->publish();

            // Sort based on region options
            $Collection->sort_items($Item->itemID());

            // Index it
            $Item->index();

            // Update the page modified date
            $Collection->update(array('collectionUpdated'=>date('Y-m-d H:i:s')));
            $Perch->event('collection.publish', $Collection);

            return $content_vars['_id'];
		}

	}

	public function find_items($opts)
	{
		$opts['skip-template'] = true;

		$Collection = $this->get_active_collection();
		$Content    = PerchContent::fetch();
		$out        = $Content->get_collection($Collection->collectionKey(), $opts);
		return $out;
	}

	public function update_item($id, $data)
	{
		if (!count($data)) return;

		$Perch  = PerchAdmin::fetch();


		$Collection = $this->get_active_collection();
		$Items = new PerchContent_CollectionItems;

		$Item = $Items->find_item($Collection->id(), $id);

		if ($Item) {
			$Collection->create_new_revision($Item);
			$Item   = $Items->find_item($Collection->id(), $id);
			$items    = $Collection->get_items_for_updating($id);

			$contents = $Collection->get_items_for_editing($id);

			if (PerchUtil::count($contents)) {
				foreach($contents as $existing_content) {
					if (PerchUtil::count($items) && $Item) {
				
						$options 	= $Collection->get_options();

						foreach($items as $CollectionItem) {

							$search_text  = '';

							$CollectionItem->clear_resources();

                        	$id = $CollectionItem->itemID();

							$content_vars['_id'] = $id;
							$content_vars['_title'] = '';

							//$this->validate_input($data);

							$tags = $this->get_template_tags();
							$seen_tags = [];

							foreach($tags as $Tag) {

							
								if (array_key_exists($Tag->id, $data) && !in_array($Tag->id, $seen_tags)) {

									$seen_tags[] = $Tag->id;

									$FieldType = $this->get_field_type($Tag, $tags);

									// import the data
									$content_vars[$Tag->id] = $FieldType->import_data($data);

									// find the title

									PerchUtil::debug('1 Title = '.$content_vars['_title']);

									$content_vars = PerchContent_Util::determine_title($Tag, $content_vars[$Tag->id], $options, $content_vars);

									PerchUtil::debug('2 Title = '.$content_vars['_title']);
									
									// build up a search string
									$search_text .= ' '.$FieldType->get_search_text($content_vars[$Tag->id]);

									//$content_vars = PerchContent_Util::determine_title($Tag, $content_vars[$Tag->id], $options, $content_vars);
								} else {
									if (!in_array($Tag->id, $seen_tags)) {
										if (isset($existing_content[$Tag->id])) {
											$content_vars[$Tag->id] = $existing_content[$Tag->id];	
										}
										
									}
									if (isset($content_vars[$Tag->id])) {
										$content_vars = PerchContent_Util::determine_title($Tag, $content_vars[$Tag->id], $options, $content_vars);	
									}
									
								}
							}

							if (count($content_vars)) {

								$newdata = array();
		                        $newdata['itemJSON']   = PerchUtil::json_safe_encode($content_vars);
		                        $newdata['itemSearch'] = $search_text;

		                        $CollectionItem->update($newdata);
										
								
							}

						}

					}
				}
			}

			$Collection->sort_items($CollectionItem->itemID());
			$Collection->clean_up_resources();
			$Item->publish();
			$Item->index();
			$Collection->update(array('collectionUpdated'=>date('Y-m-d H:i:s')));
				

		}

		

	}

	public function delete_item($id)
	{
		$Perch  = PerchAdmin::fetch();
		$Collection = $this->get_active_collection();
		return $Collection->delete_item($id);

	}

	private function get_active_collection()
	{
		if (!$this->Collection) {
			throw new \Exception('No collection. Call PerchCollectionImporter::set_collection() to set.');
		}

		return $this->Collection;
	}

	private function get_template()
	{
		if (!$this->Template) {
			throw new \Exception('No template. Call PerchCollectionImporter::set_template() to set.');
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
