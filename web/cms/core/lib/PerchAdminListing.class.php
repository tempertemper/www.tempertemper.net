<?php

class PerchAdminListing
{
	private $cols        = [];
	private $headings    = [];
	private $filters     = [];
	
	private $Paging      = null;
	private $HTML        = null;
	private $Lang        = null;
	private $CurrentUser = null;

	private $sortable	 = false;
	private $sort_arg	 = 'sort';

	public function __construct(
		$CurrentUser, 
		PerchAPI_HTML $HTML,
		$Lang,
		PerchPaging $Paging = null)
	{
		$this->CurrentUser = $CurrentUser;
		$this->Paging      = $Paging;
		$this->HTML        = $HTML;
		$this->Lang        = $Lang;
		
		if ($Paging) {
			$this->sortable    = $Paging->sortable;
		}
		
	}

	public function add_filter($func)
	{
		$this->filters[] = $func;
	}

	public function add_col($def)
	{
		if (isset($def['type'])) {
			$def['_type'] = $def['type'];
		} else {
			$def['_type'] = 'col';	
		}

		$this->cols[] = $def;

		$sort = false;
		if (isset($def['sort'])) {
			$sort = $def['sort'];
		}
		
		if (isset($def['title'])) {
			$this->headings[] = ['title'=>$def['title'], 'class'=>null, 'sort'=>$sort, '_type'=>$def['_type']];
		} else {
			$this->headings[] = null;
		}
	}

	public function add_delete_action($def)
	{
		if (!isset($def['priv']) || $this->CurrentUser->has_priv($def['priv'])) {
			$def['_type'] = 'del';

			if (!isset($def['display'])) {
				$def['display'] = true;
			}

			if (!isset($def['inline'])) {
				$def['inline'] = true;
			}

			if (!isset($def['confirm-cascade'])) {
				$def['confirm-cascade'] = false;
			}

			if (!isset($def['message'])) {
				if ($def['confirm-cascade']) {
					$def['message'] = $this->Lang->get('Are you sure you wish to delete this item and everything below it?');
				} else {
					$def['message'] = $this->Lang->get('Are you sure?');	
				}
			}

			$this->cols[] = $def;
			$this->headings[] = ['title'=>null, 'class'=>'action', '_type'=>$def['_type']];
		}
	}

	public function add_misc_action($def)
	{
		if (!isset($def['priv']) || $this->CurrentUser->has_priv($def['priv'])) {
			$def['_type'] = 'action';

			if (!isset($def['display'])) {
				$def['display'] = true;
			}

			$this->cols[] = $def;
			$this->headings[] = ['title'=>null, 'class'=>'action'];
		}
	}

	public function render($data, $message = null)
	{	
		$headings = $this->template_heading_row();
		$values   = $this->template_data_rows($data);

		return $this->template($headings, $values);
	}

	public function template_heading_row()
	{
		$s = '<tr>';

		foreach($this->headings as $h) {
			if ($this->sortable && isset($h['sort']) && $h['sort']) {
				$icon = '';
				$sort_param = PerchUtil::get($this->Paging->get_sorting_qs_param());
				if ($sort_param == $h['sort']) {
					$icon = ' '.PerchUI::icon('core/arrow-circle-down', 12, $this->Lang->get('Sorted ascending'));
				}

				if ($sort_param == '^'.$h['sort']) {
					$icon = ' '.PerchUI::icon('core/arrow-circle-up', 12, $this->Lang->get('Sorted descending'));
				}

				$content = $this->HTML->wrap('a[href='.$this->Paging->sort_link($h['sort']).']', $this->enc($h['title']).$icon);
			} else {
				$content = $this->enc($h['title']);
			}

			
			if (isset($h['_type']) && $h['_type']=='checkbox') {
				$content = '<input type="checkbox" data-check="all">';
			}

			if (isset($h['class']) && $h['class']) {
				$s .= $this->HTML->wrap('th.'.$h['class'], $content);
			} else {
				$s .= $this->HTML->wrap('th', $content);	
			}
		}

		$s .= '</tr>';

		return $s;
	}

	public function template_data_rows($rows)
	{
		$out = [];
		if (PerchUtil::count($rows)) {
			foreach($rows as $row) {
				
				// apply filters
				$display = true;
				if (count($this->filters)) {
					foreach($this->filters as $func) {
						$result = $func($row);
						if ($result === false) {
							$display = false;
						}
					}
				}

				// if passes filters, template row
				if ($display) {
					$out[] = $this->template_data_row($row);	
				}
				
			}	
		}
		
		return implode("\n", $out);
	}

	private function template_data_row($row)
	{
		$s = '<tr>';
			foreach($this->cols as $col) {
				switch ($col['_type']) {

					case 'del':
						$s .= $this->template_delete_cell($row, $col);
						break;

					case 'action':
						$s .= $this->template_action_cell($row, $col);
						break;

					case 'status':
						$s .= $this->template_status_cell($row, $col);
						break;

					default:
						$s .= $this->template_data_cell($row, $col);
						break;
				}
				
			}
		$s .= '</tr>';

		return $s;
	}

	private function template_data_cell($row, $col)
	{
		if (isset($col['class'])) {
			$class = $col['class'];
		} else {
			$class = '';
		}

		$depth = '';
		if (isset($col['depth'])) {
			$depth = 'nested-level-'.$this->get_value($col['depth'], $row);
		}

		$s = '<td data-label="'.$this->enc($col['title']).'" class="'.$this->HTML->encode(trim($class.' '.$depth), true).'">';

		$cell_value = '';
		$class = 'primary';

		$icon_used = false;
		$icon_value = '';

		// icon?
		if (isset($col['icon']) && $col['icon']) {
			if (is_callable($col['icon'])) {
				$func = $col['icon'];
				$v = $func($row, $this->HTML, $this->Lang);
				if ($v) {
					$icon_value .= $v;	
					$icon_used = true;
				}
				
			} else {
				$icon_value .= PerchUI::icon($col['icon']).' ';	
				$icon_used = true;
			}
			
			
		}

		// gravatar?
		if (isset($col['gravatar']) && $col['gravatar']) {
			if (PERCH_PARANOID) {
				$icon_value .= PerchUI::icon('core/user').' ';
				$icon_used = true;
			} else {
				$v = PerchUI::gravatar($this->get_value($col['gravatar'], $row)).' ';	
				if ($v) {
					$icon_value .= $v;
					$icon_used = true;
				}
				
			}
			
		}

		$val = $this->get_value($col['value'], $row);

	
		// formatting 
		if (isset($col['format'])) {
			$val = $this->format_value($col, $val);
		}

		if (trim($val)=='' && isset($col['edit_link']) && $col['edit_link']) {
			$val = '#'.$row->id();
		}

		$cell_value .= $val;

		if (isset($col['edit_link']) && $col['edit_link']) {

			

			$link = $this->get_value($col['edit_link'], $row, false);

			if ($link) {
				if (substr($link, -1)=='=') {
					$link = $link . $row->id();
				} else {
					$link = $link . '/?id=' . $row->id();
				}

				$s .= '<a class="'.($icon_used ? '' : $class).'" href="'.$this->HTML->encode($link).'">';
				if ($icon_used) {
					$s .= $icon_value . $this->HTML->wrap('span.'.$class, $cell_value);
				} else {
					$s .= $cell_value;	
				}
				$s .= '</a>';	
			} else {
				$s .= $this->HTML->wrap('span.forbidden', $cell_value);
			}
			
		} else {
			if ($icon_used) {
				$s .= $icon_value . $this->HTML->wrap('span', $cell_value);
			} else {
				$s .= $cell_value;	
			}
		}



		if ($icon_used) $this->HTML->wrap('span.'.$class, $cell_value);// .= '</span>';


		if (isset($col['create_link']) && $col['create_link']) {
			$s .= $this->template_create_link($row, $col['create_link']);
		}

		$s .= '</td>';

		return $s;
	}

	private function template_status_cell($row, $col)
	{
		$s = '<td>';
			
			$cell_value = $this->get_value($col['value'], $row);

			if ($cell_value) {
				$s .= PerchUI::icon('core/circle-check', 16, $col['title'], 'icon-listing-status');
			}

		$s .= '</td>';

		return $s;
	}

	private function template_create_link($row, $col)
	{
		$s = '';

		if (!isset($col['priv']) || $this->CurrentUser->has_priv($col['priv'])) {
		
			$s = PerchUI::icon('core/plus', 8).' '.$this->enc($col['title']);

			$link = $this->get_value($col['link'], $row, false);
			$link .= '/?pid='.$row->id();

			$s = $this->HTML->wrap('a[href='.$link.'].create-subitem', $s);
		
		}
		return $s;
	}

	private function template_delete_cell($row, $col)
	{
		$s = '<td>';
			
			if ($this->get_value($col['display'], $row)) {

				$attrs = '';

				if ($col['inline']) {
					if ($col['confirm-cascade']) {
						$attrs .= ' data-delete="confirm-cascade"';
					} else {
						$attrs .= ' data-delete="confirm"';
					}

					if ($col['message']) {
						$attrs .= ' data-msg="'.$this->HTML->encode($col['message'], true).'"';
					}
					
				}

				$s .= '<a class="button button-small action-alert" href="';

				if (isset($col['custom']) && $col['custom']) {
					$s .= $this->HTML->encode($col['path']).$this->HTML->encode($row->id());
				} else {
					$s .= $this->HTML->encode($col['path']).'/?id='.$this->HTML->encode($row->id());	
				}
				
				$s .= '"'.$attrs.'>'.$this->enc('Delete').'</a>';	
			}
			
		$s .= '</td>';

		return $s;
	}

	private function template_action_cell($row, $col)
	{
		$s = '<td>';
			
			if ($this->get_value($col['display'], $row)) {

				$class = $col['class'];

				if (isset($col['new-tab']) && $col['new-tab']) {
					$class .= ' viewext';
				}

				$s .= '<a class="button button-small action-'.$this->HTML->encode($class, true).'" href="'.$this->HTML->encode($this->get_value($col['path'], $row)).'">'.$this->enc($col['title']).'</a>';	
			}
			
		$s .= '</td>';

		return $s;
	}

	public function template($headings, $values)
	{
		$s = '<div class="inner">';
		$s .= '<table>';
		$s .= '<thead>' . $headings .'</thead>';
		$s .= '<tbody>' . $values .'</tbody>';
		$s .= '</table>';

		$s .= $this->template_paging();

		$s .= '</div>';

		return $s;
	}

	public function objectify($items, $pk = null)
	{
		$out = [];
		if (PerchUtil::count($items)) {
			foreach($items as $item) {
				if (!is_array($item)) {
					$item = [$pk => $item];
				}
				$o = new PerchBase($item);
				$o->set_pk($pk);
				$out[] = $o;
			}
		}
		return $out;
	}

	private function template_paging()
	{
		$paging = $this->Paging->to_array([
				'page-links' => true,
				'page-link-template' => false,
			]);

		if ($paging['total_pages'] < 2) return '';

		$s  = '<nav class="pagination" role="navigation" aria-label="'.$this->Lang->get('Pagination').'">';

		$s .= '<ul class="pagination-prev-group">';

		$state = 'disabled';
		if (isset($paging['not_first_page']) && $paging['not_first_page']) {
			$state = '';
		}

		$s .= '<li class="pagination-first">
					<a '.$state.' href="'.$paging['first_page_url'].'" title="'.$this->Lang->get('First page').'" aria-label="'.$this->Lang->get('First page').'" class="button button-icon icon-left '.$state.'">
					<div>'.PerchUI::icon('core/o-navigate-double-left', 8).'<span>'.$this->Lang->get('First').'</span>
					</div></a>
				</li>';


        $s .= '<li class="pagination-prev">
        			<a '.$state.' href="'.$paging['prev_url'].'" rel="prev" title="'.$this->Lang->get('Previous page').'" aria-label="'.$this->Lang->get('Previous page').'" class="button button-icon icon-left '.$state.'">
           			<div>'.PerchUI::icon('core/o-navigate-left', 8).'<span>'.$this->Lang->get('Prev').'</span></div></a>
    			</li>';

    	$s .= '</ul>';
        

        $page_links = $paging['page_links'];

        if (PerchUtil::count($page_links)) {

        	$s .= '<ul class="pagination-numbers">';

        	foreach($page_links as $page) {
        		$url = $page['url'];
        		$num = $page['page_number'];
        		$class = (isset($page['selected']) ? 'pagination-number pagination-current' : 'pagination-number ');
        		$title = $this->Lang->get('Page %d', $num);
        		$aria_label = false;

        		if (isset($page['selected'])) {
        			$title = $this->Lang->get('Page %d, current page', $num);
        			$aria_label = $this->Lang->get('%d, current page', $num);
        		}
        		
        		$s .= '<li class="'.$class.'">
        				<a href="'.$url.'" title="'.$title.'" aria-label="'.$aria_label.'" class="button button-simple">'.$num.'</a>
        				</li>';
        	}

        	$s .= '</ul>';
        }


    	$s .= '<ul class="pagination-next-group">';

    	$state = 'disabled';
    	if (isset($paging['not_last_page']) && $paging['not_last_page']) {
    		$state = '';
    	}

        $s .= '<li class="pagination-next">
				    <a '.$state.' href="'.$paging['next_url'].'" rel="next" title="'.$this->Lang->get('Next page').'" aria-label="'.$this->Lang->get('Next page').'" class="button button-icon icon-right '.$state.'">
				    <div>'.PerchUI::icon('core/o-navigate-right', 8).'<span>'.$this->Lang->get('Next').'</span></div></a>
				</li>';

		$s .= '<li class="pagination-last">
				    <a '.$state.' href="'.$paging['last_page_url'].'" title="'.$this->Lang->get('Last page').'" aria-label="'.$this->Lang->get('Last page').'" class="button button-icon icon-right '.$state.'">
				    <div>'.PerchUI::icon('core/o-navigate-double-right', 8).'<span>'.$this->Lang->get('Last').'</span></div></a>
				</li>';

		$s .= '</ul>';

        
		$s .= '</nav>';

		return $s;
	}

	private function enc($val, $escape_quotes=false)
	{
		return $this->HTML->encode($this->Lang->get($val), $escape_quotes);
	}

	private function get_value($def, $row, $use_prop=true)
	{
		if (is_bool($def)) {
			return $def;
		}

		if (is_callable($def)) {
			$func = $def;
			return $func($row, $this->HTML, $this->Lang);

		} elseif ($use_prop) {
			$prop = (string)$def;
			return $this->HTML->encode($row->$prop());	
		} else {
			return $this->HTML->encode((string)$def);
		}
	}

	private function format_value($def, $value)
	{
		$s = '';
		switch($def['format']['type']) {
			case 'date':
				$value = strftime($def['format']['format'], strtotime($value));
				break;
		}

		if (isset($def['format']['non-breaking']) && $def['format']['non-breaking']) {
			$value = str_replace(' ', '&nbsp;', $value);
		}

		return $value;
	}
}