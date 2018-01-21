<?php

class PerchSmartbar
{
	private $items        	 = [];
	private $rendered_panels = [];
	private $panels_open 	 = false;
	
	private $HTML        = null;
	private $Lang        = null;
	private $CurrentUser = null;

	private $end_position_set = false;
	private $end_position_rendered = false;

	public function __construct(
		$CurrentUser, 
		PerchAPI_HTML $HTML,
		$Lang)
	{
		$this->CurrentUser = $CurrentUser;
		$this->HTML = $HTML;
		$this->Lang = $Lang;
	}

	public function add_item(array $def)
	{
		$defaults = [
			'type'          => 'tab',
			'active'        => false,
			'position'      => 'start',
			'link'          => null,
			'title'         => 'Untitled item',
			'icon'          => null,
			'icon-position' => 'start',
			'icon-size' 	=> 14,
			'priv'          => null,
			'new-tab'       => false,
			'translate'     => true,
			'link-absolute' => false,
			];

		$def = array_merge($defaults, $def);

		if (!is_null($def['priv'])) {
			if (!$this->CurrentUser->has_priv($def['priv'])) {
				return;
			}
		}

		if ($def['position'] == 'end') {

			if ($this->end_position_set) {
				$def['position'] = null;
			} else {
				$this->end_position_set = true;	
			}
	
		}


		$this->items[] = $def;
	}

	public function render()
	{
		if (PerchUtil::count($this->items)) {
			$contents = '';

			foreach($this->items as $item) {

				switch($item['type']) {

					case 'submit':
						$contents .= $this->render_submit($item);
						break;

					case 'breadcrumb':
						$contents .= $this->render_breadcrumb($item);
						break;

					case 'filter':
						$contents .= $this->render_filter($item);
						break;

					case 'toggle':
						$contents .= $this->render_toggle($item);
						break;

					case 'search':
						$contents .= $this->render_search($item);
						break;

					default:
						$contents .= $this->render_tab($item);
						break;
				}
			}

			$contents = $this->HTML->wrap('ul', $contents);
			
			$class = '.smartbar';

			if (count($this->rendered_panels)) {
				$contents .= $this->HTML->wrap('div.tab-content', implode($this->rendered_panels));
				if ($this->panels_open) {
					$class .= '.with-panels';	
				}
			}

			return $this->HTML->wrap('div'.$class, $contents);
		}

		return '';
	}

	private function render_tab($item)
	{
		$content    = '';
		$data_attrs = '';

		if ($item['translate']) {
			$title = $this->Lang->get($item['title']);
		} else {
			$title = $item['title'];
		}

		if (isset($item['data'])) {
			$tmp = [];
			foreach($item['data'] as $key=>$val) {
				$tmp[] = '[data-'.$key.'='.$val.']';
			}
			$data_attrs = implode($tmp, ' ');
		}

		if ($item['link']) {
			$item['link'] = urldecode($item['link']);
			if ($item['link-absolute']) {
				$tag = 'a[href='.$this->HTML->encode($item['link'], true).'][title='.$this->HTML->encode($title, true).']'.$data_attrs.'.viewext';
			} else {
				$tag = 'a[href='.$this->HTML->encode(PERCH_LOGINPATH.$item['link'], true).'][title='.$this->HTML->encode($title, true).']'.$data_attrs.'';
			}
			
		}else{
			$tag = 'div';
		}

		if ($item['active']) {
			$tag .= '.tab-active';
		}

		if ($item['new-tab']) {
			$tag .= '.viewext';
		}

		if ($item['icon'] && $item['icon-position'] == 'start') {
			$content .= PerchUI::icon($item['icon'], $item['icon-size'], $title).' ';
		}

		if ($item['translate']) {
			$content .= $this->HTML->wrap('span', $this->Lang->get($item['title']));
		} else {
			$content .= $this->HTML->wrap('span', $item['title']);
		}

		if ($item['icon'] && $item['icon-position'] == 'end') {
			$content .= ' '.PerchUI::icon($item['icon'], $item['icon-size'], $title, 'end');
		}
		

		$list = 'li';
		if ($item['position']=='end') {
			$list .= '.smartbar-end.smartbar-util';
			$this->end_position_rendered = true;
		} elseif ($this->end_position_rendered) {
			$list .= '.smartbar-util';
		}

		return $this->HTML->wrap($list.' '.$tag, $content);
	}

	private function render_search($item)
	{
		$content    = '';
		$data_attrs = '';

		if ($item['translate']) {
			$title = $this->Lang->get($item['title']);
		} else {
			$title = $item['title'];
		}

		if (isset($item['data'])) {
			$tmp = [];
			foreach($item['data'] as $key=>$val) {
				$tmp[] = '[data-'.$key.'='.$val.']';
			}
			$data_attrs = implode($tmp, ' ');
		}

		$tag = 'form[method=get]'.$data_attrs.'.smartbar-search';

		if ($item['active']) {
			$tag .= '.tab-active';
		}
		
		if ($item['icon']) {
			$content .= PerchUI::icon($item['icon'], $item['icon-size'], $title).' ';
		}

		$value = PerchRequest::get($item['arg'], '');

		$content .= $this->HTML->open('input[name='.$item['arg'].'][type=search][placeholder='.$title.'][value='.$this->HTML->encode($value).'].search');

		$content = $this->HTML->wrap('label', $content);

		$content .= $this->HTML->wrap('button[type=submit].button.button-small.action-search', $title);

		$list = 'li';
		if ($item['position']=='end') {
			$list .= '.smartbar-end.smartbar-util';
			$this->end_position_rendered = true;
		} elseif ($this->end_position_rendered) {
			$list .= '.smartbar-util';
		}

		return $this->HTML->wrap($list.' '.$tag, $content);
	}

	private function render_toggle($item)
	{
		$links = '';
		$first_option = true;

		foreach($item['options'] as $option) {

			$url = clone(PerchRequest::url());

			$args = [$item['arg'] => $option['value']];
			if (isset($item['persist'])) {
				foreach($item['persist'] as $arg) {
					if (PerchRequest::get($arg)) {
						$args[$arg] = PerchRequest::get($arg);	
					}
				}
			}

			$link = urldecode($url->set_query($args)->path_with_qs_within_cp());
			
			$tag = 'a[href='.$this->HTML->encode(PERCH_LOGINPATH.$link, true).'].button.button-small';

			if ((!PerchRequest::get($item['arg']) && $first_option )|| PerchRequest::get($item['arg']) == $option['value']) {
				$tag .= '.toggle-active';
			}

			$content = '';

			if ($option['icon']) {
				$content .= PerchUI::icon($option['icon'], 12).' ';
			}

			if ($item['translate']) {
				$content .= $this->Lang->get($option['title']);
			} else {
				$content .= $option['title'];
			}

			$links .= $this->HTML->wrap($tag, $content);
			$first_option = false;
		}

		$list = 'li';
		if ($item['position']=='end') {
			$list .= '.smartbar-end';
		}

		return $this->HTML->wrap($list . ' div.button-group.button-toggle', $links);
	}

	private function render_filter($item)
	{
		$url = clone(PerchRequest::url());

		// automatically add the ID as a 'show filter' qs param
		$args = ['show-filter' => $item['id']];
		if (isset($item['persist'])) {
			foreach($item['persist'] as $arg) {
				if (PerchRequest::get($arg)) {
					$args[$arg] = PerchRequest::get($arg);	
				}
			}
		}
		$item['link'] = $url->set_query($args)->path_with_qs_within_cp();
		
		// if the param we set is selected, make this item active
		if (PerchRequest::get('show-filter') == $item['id']) {
			$item['active'] = true;
			$this->panels_open = true;
		}

		// tab
		$tab = $this->render_tab($item);
		
		// panel
		$this->rendered_panels[] = $this->render_filter_panel($item);

		return $tab;
	}

	private function render_filter_panel($item)
	{
		
		$el = 'section[role=tabpanel].tab-panel';

		if ($item['active']) {
			$el .= '.active';
			$this->panels_open = true;
		} else {
			$el .= '[aria-hidden=true].hidden';
		}

		$content = '';


		foreach($item['options'] as $option) {
			$url = clone(PerchRequest::url());
			$url->replace_in_query([$item['arg'] => $option['value']]);
			$url->remove_from_query('show-filter');
			$class = '';
			if (PerchRequest::get($item['arg']) == $option['value']) {
				$class = '.tab-active';
			}
			$content .= $this->HTML->wrap('li a[href='.urldecode($url).']'.$class, $option['title']);
		}


		if (PerchUtil::count($item['actions'])) {
			$actions = '';

			foreach($item['actions'] as $action) {
				$url = clone(PerchRequest::url());

				if (isset($action['remove']) && PerchUtil::count($action['remove'])) {
					$url->remove_from_query($action['remove']);
				}

				$icon = '';
				if ($action['icon']) {
					$icon = PerchUI::icon($action['icon'], 12).' ';
				}
				
				$actions .= $this->HTML->wrap('a[href='.urldecode($url).'].button.button-small', $icon.$this->Lang->get($action['title']));
			}

			$content .= $this->HTML->wrap('div.smartbar-actions', $actions);
		}

		return $this->HTML->wrap($el.' ul.smartbar-filters', $content);
	}

	private function render_breadcrumb($item)
	{
		$content = '';

		$tag = 'div.breadcrumb';

		if ($item['active']) {
			$tag .= '.tab-active';
		}

		for($i=0; $i<PerchUtil::count($item['links']);$i++) {
		
			$link = $item['links'][$i];

			$atag = 'a';
			if (isset($link['link'])) {
				$atag = 'a[href='.$this->HTML->encode(PERCH_LOGINPATH.$link['link'], true).']';	
			};

			if (!isset($link['translate'])) {
				$link['translate'] = true;
			}

			if ($link['translate']) {
				$atitle = $this->Lang->get($link['title']);
			} else {
				$atitle = $link['title'];
			}

			$content .= $this->HTML->wrap($atag, $atitle);

			if ($i+1<PerchUtil::count($item['links'])) {
				$content .= PerchUI::icon('core/o-navigate-right', 8).' ';
			}
		}

		$list = 'li';
		if ($item['position']=='end') {
			$list .= '.smartbar-end';
		}

		return $this->HTML->wrap($list.' '.$tag, $content);
	}

	private function render_submit($item)
	{
		$content = '';
		$class   = 'button ';

		if ($item['icon']) {
			$class .= ' icon-left ';
			$content .= PerchUI::icon($item['icon'], 14).' ';
		}


		$list = 'li';
		if ($item['position']=='end') {
			$list .= '.smartbar-end';
			$this->end_position_rendered = true;
		}

		if ($this->end_position_rendered) {
			$list .= '.smartbar-util';
		}

		$tag = $item['form']->open();

		$tag .= $item['form']->submit($item['fieldID'], $this->Lang->get($item['title']), $class, false, true, $content);

		$tag .= $item['form']->close();

		return $this->HTML->wrap($list, $tag);
	}


}