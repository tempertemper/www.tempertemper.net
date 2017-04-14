<?php

class PerchTemplateValidator
{
	private $Template;
	private $Lang;

	private $messages = [];

	public function __construct($Template, $Lang)
	{
		$this->Template = $Template;
		$this->Lang = $Lang;

		$this->tags = null;
	}

	public function validate()
	{
		$this->check_ids();

		return $this->messages;
	}

	private function get_tags()
	{
		if (!$this->tags) $this->tags = $this->Template->find_all_tags_and_repeaters();

		return $this->tags;
	}

	private function check_ids() 
	{
		$tags = $this->get_tags();

		if (PerchUtil::count($tags)) {

			$pattern = '#^[A-Za-z_][A-Za-z0-9_]+$#';
			foreach($tags as $Tag) {
				$id = $Tag->id;
				if (!preg_match($pattern, $id)) {
					$this->messages[] = [
						'status' => 'warning',
						'message' => $this->Lang->get('Template tag ID ‘%s’ contains disallowed characters', '<code>'.$id.'</code>'),
					];
				}
			} 
		}
	}

}