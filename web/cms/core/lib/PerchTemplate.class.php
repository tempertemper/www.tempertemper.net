<?php

class PerchTemplate
{
	public $namespace;
	public $file;
	public $file_path;
	public $status                = 0;
	public $apply_post_processing = false;
	public $current_file          = false;

	protected $template;
	protected $cache             = [];
	protected $autoencode        = true;

	private $layout_renderer     = 'perch_layout';
	private $_previous_item      = [];
	private $cached_objects      = [];
	private $blocks              = [];
	private $help 				 = [];

	protected $disabled_features = [];

	function __construct($file=false, $namespace='content', $relative_path=true)
	{
		$Perch = Perch::fetch(); // required to define constants

		if ($file && substr($file, -5)!=='.html') $file .= '.html';

		$this->current_file = $file;
		$this->namespace    = $namespace;

		if ($file && $relative_path) {
			$file = PerchUtil::file_path(PERCH_TEMPLATE_PATH.'/'.$file);
		}

		if ($file!=false && file_exists($file)) {
			$this->file     = $file;
			$this->template = $file;
			PerchUtil::debug('Using template: '.str_replace(PERCH_PATH, '', $file), 'template');
			$this->status   = 200;

			$this->file_path = pathinfo($file, PATHINFO_DIRNAME);
		}else{
		    if ($file!=false) PerchUtil::debug('Template file not found: ' . $file, 'template-error');
			$this->status = 404;
		}

		// Mock up fallback functions if server doesn't have mbstring
		PerchUtil::mb_fallback();
	}

	public function set_template($template)
	{
		$this->template = $template;
	}

	public function disable_feature($feature)
	{
		if (!in_array($feature, $this->disabled_features)) {
			$this->disabled_features[] = $feature;
		}
	}

	public function render_group($content_vars, $return_string=false, $limit=false)
	{
		$r     = array();
		$count = PerchUtil::count($content_vars);

		PerchUtil::debug_badge($count);

		if ($count){

			if ($limit===false) {
				$limit = 9999999;
			}else{
				$limit = (int)$limit;
			}

		    $ids = $this->find_all_tag_ids($this->namespace);
		    $this->_previous_item = array();

		    for($i=0; $i<$count && $i<$limit; $i++) {

                if (isset($content_vars[$i])) {

                    $item = $content_vars[$i];

    			    if (is_object($item)) {
                        $item = $item->to_array($ids);
                    }

                    // safeguard
                    if (!is_array($item)) $item = array('item_'.$item=>$item);

    			    if ($i==0) 			$item['perch_item_first'] = true;
    			    if ($i==($count-1)) $item['perch_item_last']  = true;

					$item['perch_item_zero_index']     = (string)$i;
					$item['perch_item_index']          = $i+1;
					$item['perch_item_rev_index']      = (string)($count - $i);
					$item['perch_item_rev_zero_index'] = (string)($count - ($i+1));
					$item['perch_item_odd']            = ($i % 2 == 0 ? '' : 'odd');
					$item['perch_item_count']          = $count;

					if (isset($item['paging'])) {
						$item = $this->_assign_paging_values($item, $i);
					}

    				$r[] = $this->render($item, $i+1);

    				$this->_previous_item = $item;

    				$this->blocks = array();
    			}
			}

			$this->_previous_item = null;
		}

		if ($return_string) {
		    return implode('', $r);
		}

		return $r;
	}

	public function render($content_vars, $index_in_group=false)
	{
	    $system_vars = PerchSystem::get_vars();

        if (is_object($content_vars)) {
        	$ids 		  = $this->find_all_tag_ids($this->namespace);
            $content_vars = $content_vars->to_array($ids);
        }

        if (is_array($system_vars) && is_array($content_vars)) {
            $content_vars = array_merge($system_vars, $content_vars);
        }

        if (!is_array($content_vars)) {
        	$content_vars = array();
        }

        if ($index_in_group===false && !count($content_vars)) {
			PerchUtil::debug_badge('0');
		}

		$template	= str_replace(PERCH_PATH, '', $this->template);

		$contents	= $this->load();

		// API HANDLERS
		$contents 	= $this->render_template_handlers($content_vars, $contents);

		// BLOCKS
		$contents   = $this->parse_blocks($contents, $content_vars);

		// SINGLE BLOCK
		$contents 	= $this->render_block($contents, $content_vars);

		// REPEATERS
		$contents 	= $this->parse_repeaters($contents, $content_vars);

		// RELATED
		$contents   = $this->parse_related($contents, $content_vars);

		// CATEGORIES
		$contents 	= $this->parse_categories($contents, $content_vars);

		// FORMS
		if ($template) $contents = str_replace('<perch:form ', '<perch:form template="'.$template.'" ', $contents);

		// BEFORE
		$contents 	= $this->parse_paired_tags('before', true,  $contents, $content_vars, $index_in_group, 'parse_conditional');

		// AFTER
		$contents 	= $this->parse_paired_tags('after',  true,  $contents, $content_vars, $index_in_group, 'parse_conditional');

		// IF
		$contents 	= $this->parse_paired_tags('if', 	 false, $contents, $content_vars, $index_in_group, 'parse_conditional');

		// EVERY
		$contents 	= $this->parse_paired_tags('every',  false, $contents, $content_vars, $index_in_group, 'parse_every');

		// CONTENT
		$contents 	= $this->replace_content_tags($this->namespace, $content_vars, $contents);

		// SHOW ALL
		$contents 	= $this->process_show_all($content_vars, $contents);

		// HELP
		$contents   = $this->remove_help($contents);

		// NO RESULTS
		$contents   = $this->remove_noresults($contents);

		// RUNWAY
		$contents   = $this->remove_runway_tags($contents);

		// UNMATCHED TAGS
		$contents 	= $this->remove_unmatched_tags($contents);

    	return $contents;
	}

	public function render_template_handlers($content_vars, $contents)
	{
		$handlers = PerchSystem::get_registered_template_handlers();

    	if (PerchUtil::count($handlers)) {
    		foreach($handlers as $handlerClass) {
				$Handler  = new $handlerClass;
				$contents = $Handler->render($content_vars, $contents, $this);
    		}
    	}

    	return $contents;
	}

	public function parse_blocks($contents, $content_vars)
	{
		if (strpos($contents, 'perch:blocks')>0) {
			if (count($this->blocks)==0) {
				$contents = $this->parse_paired_tags('block', false, $contents, array(), false, '_cache_block_template_variation');
				$contents = $this->parse_paired_tags('blocks', true, ' '.$contents, $content_vars, false, 'render_blocks');
			}
		}
		return $contents;
	}

	public function render_blocks($type, $opening_tag, $condition_contents, $exact_match, $template_contents, $content_vars, $index_in_group=false)
	{
		if (count($this->blocks)) {
			foreach($this->blocks as $key=>$val) {
				$this->blocks[$key] = str_replace('<BLOCKS />', $val, $condition_contents);
			}
		}

		if (isset($content_vars['_blocks'])) {
			$blocks = $content_vars['_blocks'];

			// scope parent
			$Tag = new PerchXMLTag($opening_tag);
			if (PerchUtil::bool_val($Tag->scope_parent())) {
				$vars_for_repeater = array();
				if (PerchUtil::count($content_vars)) {
					foreach($content_vars as $key => $val) {
						if ($key!=$Tag->id() && $key!='itemJSON' && $key!='_blocks') {
							$vars_for_repeater['parent.'.$key] = $val;
						}
					}
				}
				foreach($blocks as &$item) {
					$item = array_merge($item, $vars_for_repeater);
				}
			}
			// end scope parent


			$out = $this->render_group($blocks, true);
			return str_replace($exact_match, $out, $template_contents);
		}
		return str_replace($exact_match, '', $template_contents);
	}

	public function render_block($content, $content_vars)
	{
		if (isset($content_vars['_block_type'])) {
			if (isset($this->blocks[$content_vars['_block_type']])) {
				return $this->blocks[$content_vars['_block_type']];
			}
		}

		return $content;
	}

	public function replace_content_tags($namespace, $content_vars, $contents)
	{
		if (is_array($content_vars)) {

			// Find all matching tags
			$s 		= '#<perch:'.$namespace.'[^>]*/>#';
			$count	= preg_match_all($s, $contents, $matches, PREG_SET_ORDER);
	
			if ($count) {
				foreach($matches as $match) {
					$match = $match[0];
					$tag   = new PerchXMLTag($match);

					if ($tag->suppress) {
						$contents = str_replace($match, '', $contents);
					}else{

						if (isset($content_vars[$tag->id])) {
							$value = $content_vars[$tag->id];
						}else{
							$replacement = '';
							if ($tag->else()) $replacement = $tag->else();

							$contents = str_replace($match, $replacement, $contents);
							continue;
						}

						$field_is_markup = false;

				        if ($tag->type) {
							$FieldType       = PerchFieldTypes::get($tag->type, false, $tag);
							$modified_value  = $FieldType->get_processed($value);
							$field_is_markup = $FieldType->processed_output_is_markup;
				        }else{
				            $modified_value  = $value;
				        }

				        // check that what we've got isn't an array. If it is, try your best to get a good string.
				        if (is_array($modified_value)) {
				            if (isset($modified_value['_default'])) {
				                $modified_value = (string) $modified_value['_default'];
				            }else{
				            	if (isset($modified_value['processed'])) {
				            		$modified_value = (string) $modified_value['processed'];
				            	}else{
				            		$modified_value = (string) array_shift($modified_value);
				            	}
				            }
				        }

				        // Filters: before processing
				        if (PERCH_TEMPLATE_FILTERS && $tag->filter) {
				        	list($modified_value, $field_is_markup) = $this->_apply_filters(0, $tag, $modified_value, $content_vars, $field_is_markup);
				        }

				        // check for 'rewrite' attribute
				        if ($tag->rewrite) {
				        	$modified_value = $this->_rewrite($tag, $modified_value);
				        }


					    // check for 'format' attribute
					    if ($tag->format) {
					    	$modified_value = $this->_format($tag, $modified_value);
					    }

					    // check for 'replace' strings
					    if ($tag->replace) {
					        $pairs = explode(',', $tag->replace);
				            if (PerchUtil::count($pairs)) {
				                foreach($pairs as $pair) {
				                    $pairparts = explode('|', $pair);
				                    if (isset($pairparts[0]) && isset($pairparts[1])) {
				                        $modified_value = str_replace(trim($pairparts[0]), trim($pairparts[1]), $modified_value);
				                    }
				                }
				            }
					    }

					    // Urlify
					    if ($tag->urlify) {
					        $modified_value = PerchUtil::urlify($modified_value);
					    }

				        // Trim by chars
                        if ($tag->chars) {
                            if (strlen($modified_value) > (int)$tag->chars) {
                                $modified_value = PerchUtil::excerpt_char($modified_value, (int)$tag->chars, false, true, $tag->append);
                            }
                        }

                        // Trim by words
                        if ($tag->words) {
                            $modified_value = PerchUtil::excerpt($modified_value, (int)$tag->words, false, true, $tag->append);
                        }

                        // Hash
                        if ($tag->hash=='md5') {
                        	$modified_value = md5($modified_value);
                        }

				        // Strip tags
				        if ($tag->striptags) {
				        	$modified_value = strip_tags($modified_value);
				        }

				        // Append
				        if (!$tag->words && !$tag->chars && $tag->append) {
				        	$modified_value .= $tag->append;
				        }

				        // Filters: after processing
				        if (PERCH_TEMPLATE_FILTERS && $tag->filter) {
				        	list($modified_value, $field_is_markup) = $this->_apply_filters(1, $tag, $modified_value, $content_vars, $field_is_markup);
				        }

				        // URL Encode
					    if ($tag->urlencode) {
				            $modified_value = rawurlencode($modified_value);
				        }

				        // Escape quotes
				        if ($tag->escape) {
				            $modified_value = PerchUtil::html($modified_value, true, false);
				            $field_is_markup = true;
				        }

					    // check encoding
					    if ($this->autoencode && !$field_is_markup) {
					    	if (!$tag->textile && !$tag->markdown) {
					    		if ((!$tag->is_set('encode') || $tag->encode==true) && (!$tag->is_set('html') || $tag->html==false)) {
				                	$modified_value = PerchUtil::html($modified_value);
					        	}
					    	}
					    }

					    // JSON encoding
					    if ($tag->jsonencode) {
					    	$modified_value = json_encode($modified_value);
					    }

						$contents = str_replace($match, $modified_value, $contents);
					}


				}
			}
		}
		return $contents;
	}

	/**
	 * Find tag by ID. Optionally also ID with a given output="" attribute
	 * @return PerchXMLTag
	 */
	public function find_tag($tag, $output=false, $raw=false)
	{
		$template	= $this->template;
		$path		= $this->file;
		$contents	= $this->load();

		$s = '/<perch:[^>]*id="'.$tag.'"[^>]*>/';

		if ($output) {
			$count	= preg_match_all($s, $contents, $matches, PREG_SET_ORDER);

			if ($count) {
				foreach($matches as $match) {
					$Tag = new PerchXMLTag($match[0]);
					if ($Tag->output() && $Tag->output()==$output) {
						return $Tag;
					}
				}
			}
		}else{
			$count	= preg_match($s, $contents, $match);

			if ($count == 1){
				if ($raw) return $match[0];
				return new PerchXMLTag($match[0]);
			}
		}

		return false;
	}

	// Finds the ID of the first field with title="true" - used for control panel column sorting
	public function find_title_field_id()
	{
		$tags = $this->find_all_tags();
		if (PerchUtil::count($tags)) {
			foreach($tags as $Tag) {
				if ($Tag->title()) return $Tag->id();
			}
		}

		return false;
	}

	public function find_all_tags($type='content', $contents=false)
	{
	    $template	= $this->template;
		$path		= $this->file;

		if ($contents===false) {
			$contents	= $this->load();
		}

		$s = '/<perch:'.$type.'[^>]*>/';
		$count	= preg_match_all($s, $contents, $matches);

		if ($count > 0) {
		    $out = array();
		    $i = 100;
		    if (is_array($matches[0])){
		        foreach($matches[0] as $match) {
		            $tmp = array();
		            $tmp['tag'] = new PerchXMLTag($match);

		            if ($tmp['tag']->order()) {
		                $tmp['order'] = (int) $tmp['tag']->order();
		            }else{
		                $tmp['order'] = $i;
		                $i++;
		            }
                    $out[] = $tmp;
		        }
		    }

		    // sort tags using 'order' attribute
		    $out = PerchUtil::array_sort($out, 'order');

		    $final = array();
		    foreach($out as $tag) {
		        $final[] = $tag['tag'];
		    }

		    return $final;
		}

		return null;
	}

	public function find_all_tags_and_repeaters($type='content', $contents=false)
	{
		if ($contents===false) $contents = $this->load();

		$untouched_content = $contents;

		$out = array();

		// Excluded tags are discarded
		$tag_pairs_to_exclude         = array();

		$tag_pairs_with_empty_openers = array('blocks');

		//	List of tags to process. Blocks needs to come before others, as blocks can contain e.g. repeaters.
		$tag_pairs_to_process         = array('blocks', 'repeater');

		if (PERCH_RUNWAY) {
			$tag_pairs_to_process[]   = 'related';
		}

		// Add excluded tags so they can be discarded.
		$tag_pairs_to_process = array_merge($tag_pairs_to_process, $tag_pairs_to_exclude);

		foreach($tag_pairs_to_process as $tag_type) {
			// parse out tag pairs

			$empty_opener  = in_array($tag_type, $tag_pairs_with_empty_openers);
			$close_tag     = '</perch:'.$tag_type.'>';
			$close_tag_len = mb_strlen($close_tag);
			$open_tag      = '<perch:'.$tag_type.($empty_opener ? '' : ' ');

			// escape hatch
			$i = 0;
			$max_loops = 1000;

			// loop through while we have closing tags
	    	while($close_pos = mb_strpos($contents, $close_tag)) {

	    		// we always have to go from the start, as the string length changes,
	    		// but stop at the closing tag
	    		$chunk = mb_substr($contents, 0, $close_pos);

	    		// search from the back of the chunk for the opening tag
	    		$open_pos = mb_strrpos($chunk, $open_tag);

	    		// get the pair html chunk
	    		$len = ($close_pos+$close_tag_len)-$open_pos;
	    		$pair_html = mb_substr($contents, $open_pos, $len);

	    		// find the opening tag - it's right at the start
	    		$opening_tag_end_pos = mb_strpos($pair_html, '>')+1;
	    		$opening_tag = mb_substr($pair_html, 0, $opening_tag_end_pos);

	    		// condition contents
	    		$condition_contents = mb_substr($pair_html, $opening_tag_end_pos, 0-$close_tag_len);

	    		// Do the business
	    		$OpeningTag = new PerchXMLTag($opening_tag);

	    		$tmp = array();

	    		if ($tag_type=='repeater') {
	    			$Repeater = new PerchRepeater($OpeningTag->attributes);
	    			$Repeater->set('id', $OpeningTag->id());
	    			$Repeater->tags = $this->find_all_tags_and_repeaters($type, $condition_contents);

	    			$tmp['tag'] = $Repeater;
	    		}elseif ($tag_type=='blocks'){
	    			$OpeningTag->set('id', '_blocks');
	    			$OpeningTag->set('type', 'PerchBlocks');
	    			$tmp['tag'] = $OpeningTag;
	    		}else{
	    			$tmp['tag'] = $OpeningTag;
	    		}

	    		// Set the order
	    		if ($OpeningTag->order()) {
	                $tmp['order'] = (int) $OpeningTag->order();
	            }else{
	                $tmp['order'] = strpos($untouched_content, $opening_tag);
	            }

	            // If the tag isn't one to strip/exclude, add it to the list.
	            if (!in_array($tag_type, $tag_pairs_to_exclude)) {
	            	$out[] = $tmp;
	            }

				// Remove the pair so we can parse the next one
				$contents = str_replace($pair_html, '', $contents);

	    		// escape hatch counter
	    		$i++;
	    		if ($i > $max_loops) return $contents;
	    	}
		}

		$s = '/<perch:('.$type.'|categories)[^>]*>/';
		$count	= preg_match_all($s, $contents, $matches);

		if ($count > 0) {
		    $i = 100;
		    if (is_array($matches[0])){
		        foreach($matches[0] as $match) {
		            $tmp = array();
		            $tmp['tag'] = new PerchXMLTag($match);

		            if (!in_array('categories', $this->disabled_features)) {
	    	            if ($tmp['tag']->tag_name()=='perch:categories') {
	    					$tmp['tag']->set('type', 'category');
	    				}
		            }

		            if ($tmp['tag']->tag_name()=='perch:related') {
						$tmp['tag']->set('type', 'related');
					}

		            if ($tmp['tag']->type()!='repeater') {
		            	if ($tmp['tag']->order()) {
			                $tmp['order'] = (int) $tmp['tag']->order();
			            }else{
			                $tmp['order'] = strpos($untouched_content, $match);
			                #PerchUtil::debug('Setting order to: '.$tmp['order']);
			                $i++;
			            }
	                    $out[] = $tmp;
		            }
		        }
		    }				
		}

		if (PerchUtil::count($out)) {

			// sort tags using 'order' attribute
		    $out = PerchUtil::array_sort($out, 'order');  

			$final = array();

		    foreach($out as $tag) {
		        $final[] = $tag['tag'];
		    }

		    return $final;

		}

		return false;
	}


	public function find_all_tag_ids($type='content')
	{
	    $contents	= $this->load();
		$out = array();

		$s = '/<perch:'.$type.'[^>]*id="(.*?)"[^>]*>/';
		$count	= preg_match_all($s, $contents, $matches, PREG_SET_ORDER);
		if ($count && PerchUtil::count($matches)) {
			foreach($matches as $match) {
				$out[] = $match[1];
			}
		}

		return $out;
	}

	public function get_field_type_map($type='content', $contents=false)
	{
		$tags = $this->find_all_tags_and_repeaters($type, $contents);

		$out = [];

		if (PerchUtil::count($tags)) {
			foreach($tags as $tag) {
				if (!array_key_exists($tag->id, $out)) {
					if ($tag->type) {
						if ($tag->type == 'PerchBlocks') {

							$Ft = PerchFieldTypes::get($tag->type, false, $tag);

							if (count($this->blocks)==0) {
					    		$template = $this->load();
					    		$this->parse_blocks($template, array());
					    	}

					    	if (count($this->blocks)>0) {
					    		$block_field_map = [];
					    		foreach($this->blocks as $block_type=>$block_markup) {
					    			$block_field_map[$block_type] = $this->get_field_type_map($type, $block_markup);
					    		}

					    		$Ft->field_type_map = $block_field_map;
					    	}

							$out[$tag->id] = $Ft;
							
						} else {
							$out[$tag->id] = PerchFieldTypes::get($tag->type, false, $tag);	
						}
					}
				}
			}
		}

		return $out;
	}

	public function find_help($id = '_global')
	{
		if (isset($this->help[$id])) {
			return $this->help[$id];
		} else{
			// don't keep doing the work
			if (isset($this->help['_done'])) {
				return null;
			}
		}

	    $template	= $this->template;
		$path		= $this->file;

		$contents	= $this->load();

		$out        = '';

		if (strpos($contents, 'perch:help')>0) {
            $s = '/(<perch:help[^>]*>)(.*?)<\/perch:help>/s';
    		$count	= preg_match_all($s, $contents, $matches, PREG_SET_ORDER);

    		if ($count > 0) {
    			foreach($matches as $match) {
    				$Tag = new PerchXMLTag($match[1]);
    				if ($Tag->is_set('for')) {
    					$this->help[$Tag->for()] = $match[2];
    				} else {
    					$out .= $match[2];
    				}
    			}
    		}
    	}

    	$this->help['_global'] = $out;
    	$this->help['_done']   = true;

    	if (isset($this->help[$id])) {
    		return $this->help[$id];
    	}
    	
    	return '';
	}

	public function process_show_all($vars, $contents)
	{
		if (strpos($contents, 'perch:showall')) {
			$vars['perch_namespace'] = 'perch:'.$this->namespace;
			$s = '/<perch:showall[^>]*>/s';
			$table = PerchUtil::table_dump($vars, 'showall').'<link rel="stylesheet" href="'.PERCH_LOGINPATH.'/core/assets/css/debug.css" />';

			if (preg_match_all($s, $contents, $matches, PREG_SET_ORDER)) {
				if (count($matches)) {
					foreach($matches as $match) {
						$contents = str_replace($match[0], $table, $contents);
					}	
				}
			}
		}

		return $contents;
	}

	public function remove_unmatched_tags($contents)
	{
		$s 			= '/<perch:(?!(form|input|label|error|success|';

		$handlers = PerchSystem::get_registered_template_handlers();

    	if (PerchUtil::count($handlers)) {
    		foreach($handlers as $handlerClass) {
    			$Handler = new $handlerClass;
    			if ($Handler->tag_mask!='') $s .= $Handler->tag_mask.'|';
    		}
    	}

		$s 			.= 'setting|url|layout))[^>]*>/';

		$contents	= preg_replace($s, '', $contents);

		return $contents;
	}

    public function remove_help($contents)
    {
    	if (strpos($contents, 'perch:help')) {
        	$s = '/<perch:help[^>]*>.*?<\/perch:help>/s';
        	return preg_replace($s, '', $contents);
        }else{
        	return $contents;
        }
    }

    public function remove_noresults($contents)
    {
    	if (strpos($contents, 'perch:noresults')) {
        	$s = '/<perch:noresults[^>]*>.*?<\/perch:noresults>/s';
        	return preg_replace($s, '', $contents);
        }else{
        	return $contents;
        }
    }

    public function remove_runway_tags($contents)
    {
    	if (strpos($contents, 'perch:runway')) {
        	$s = '/<perch:runway[^>]*>(.*?)<\/perch:runway>/s';
        	if (PERCH_RUNWAY) {
        		return preg_replace($s, '$1', $contents);
        	}else{
        		return preg_replace($s, '', $contents);	
        	}
        	
        }else{
        	return $contents;
        }
    }

    public function use_noresults()
    {
    	$contents = $this->load();
    	$out = '';
    	if (strpos($contents, 'perch:noresults')) {
	        $s = '/<perch:noresults[^>]*>(.*?)<\/perch:noresults>/s';
	        $count	= preg_match_all($s, $contents, $matches, PREG_SET_ORDER);

			if ($count > 0) {
				foreach($matches as $match) {
				    $out .= $match[1];
				}
			}
		}
		// replace template with string
		$this->load($out);
    }

	public function load($template_string=false, $parse_includes=true)
	{
		$contents	= '';

		if ($template_string!==false) {
		    $contents = $this->_strip_comments($template_string);
		    $this->cache[$this->template]	= $contents;
		    $this->blocks = array();
		}else{
		    // check if template is cached
    		if (isset($this->cache[$this->template])){
    			// use cached copy
    			$contents	= $this->cache[$this->template];
    		}else{
    			// read and cache
    			PerchUtil::invalidate_opcache($this->file);
    			if (file_exists($this->file)){
    				# PerchUtil::debug('Opening template file: '.$this->file, 'template');
    				$contents = file_get_contents($this->file);
    				$contents = $this->_strip_comments($contents);
    				$this->cache[$this->template]	= $contents;
    				$this->blocks = array();
    			}
    		}
		}

		if ($parse_includes) {
			if (strpos($contents, 'perch:template')) {
			    $s = '/<perch:template[^>]*path="([^"]*)"[^>]*>/';
	            $count	= preg_match_all($s, $contents, $matches, PREG_SET_ORDER);
	    	    $out = '';
	    		if ($count > 0) {
	    			foreach($matches as $match) {
	    				if (PerchUtil::file_extension($match[1])===false) {
	    					$match[1].= '.html';
	    				}

	    			    $file = PERCH_TEMPLATE_PATH.DIRECTORY_SEPARATOR.$match[1];

	    			    if (!file_exists($file)) {
	    			    	$file = $this->file_path.DIRECTORY_SEPARATOR.$match[1];
	    			    }

	    			    if (file_exists($file)) {
	    			        $subtemplate = file_get_contents($file);
	    			        $subtemplate = $this->_strip_comments($subtemplate);

	    			        // rescope?
	    			        if($this->namespace!='content' && strpos($match[0], 'rescope=')) {
	    			        	PerchUtil::debug('Rescoping to perch:'.$this->namespace);
	    			        	$subtemplate = str_replace('<perch:content ', '<perch:'.$this->namespace.' ', $subtemplate);
	    			        }

	        			    $contents = str_replace($match[0], $subtemplate, $contents);
	        			    PerchUtil::debug('Using sub-template: '.str_replace(PERCH_PATH, '', $file), 'template');
	    			    }else{
	    			    	PerchUtil::debug('Requested sub-template not found: '.$file, 'template-error');
	    			    }
	    			}
	    			$this->cache[$this->template]	= $contents;
	    			$this->blocks = array();
	    		}
	    	}
		}

		return $contents;
	}

	public function append($template_string, $parse_includes=false)
	{
		if (!isset($this->cache[$this->template])){
			$this->load();
		}
		if (isset($this->cache[$this->template])) {
			$contents = $this->cache[$this->template].$template_string;
		} else {
			$contents = $template_string;	
		}
		return $this->load($contents, $parse_includes);
	}

	protected function parse_conditional($type, $opening_tag, $condition_contents, $exact_match, $template_contents, $content_vars, $index_in_group=false)
	{
	    // IF
	    if ($type == 'if') {
	        $tag = new PerchXMLTag($opening_tag);

	        $positive = $condition_contents;
            $negative = '';

	        // else condition
	        if (strpos($condition_contents, 'perch:else')>0) {
    	        $parts   = preg_split('/<perch:else\s*\/>/', $condition_contents);
                if (is_array($parts) && count($parts)>1) {
                    $positive = $parts[0];
                    $negative = $parts[1];
                }
            }

	        // exists and not-exists
	        if ($tag->exists() || $tag->not_exists()) {

	        	$exists_string = $tag->exists();

 				// Not-exists - just swaps the pos and neg over.
	        	if ($tag->not_exists()) {
	        		$exists_string = $tag->not_exists();
	        		// swap pos and neg
	        		$tmp      = $positive;
	        		$positive = $negative;
	        		$negative = $tmp;
	        		$tmp 	  = null;
	        	}


	        	// do we have spaces? Then it could be a logic string
	        	if (strpos(trim($exists_string), ' ')!==false) {
	        		$operators = array('AND', 'OR', 'XOR');

	        		preg_match_all('#!?\b[A-Za-z0-9_]+\b#', $exists_string, $ids, PREG_SET_ORDER);

	        		if (PerchUtil::count($ids)) {

	        			$logic_string = $exists_string;

		        		foreach($ids as $id) {
		        			$id = $id[0];
		        			if (!in_array($id, $operators)) {

		        				// Flip the operator?
		        				if (substr($id, 0, 1)=='!') {
		        					$id = substr($id, 1);
		        					$flip_operator = true;
		        				}else{
		        					$flip_operator = false;
		        				}

					            if (array_key_exists($id, $content_vars) && $this->_resolve_to_value($content_vars[$id]) != '') {
					            	$op = 'true';
					            	if ($flip_operator) $op = 'false';
				    	        }else{
				    	        	$op = 'false';
					            	if ($flip_operator) $op = 'true';
				    	        }
				    	        $logic_string = preg_replace('#'.($flip_operator ? '!' : '(?<!\!)').'\b'.preg_quote($id, '#').'\b#', $op, $logic_string);       
		        			}
		        		}

		        		$LogicString = new PerchLogicString($logic_string);
		        		if ($LogicString->parse()) {
		        			$template_contents  = str_replace($exact_match, $positive, $template_contents);
		        		}else{
		        			$template_contents  = str_replace($exact_match, $negative, $template_contents);
		        		}

		        		return $template_contents;
		        	}

	        	}else{

		            if (array_key_exists($exists_string, $content_vars) && $this->_resolve_to_value($content_vars[$exists_string]) != '') {
	    	            $template_contents  = str_replace($exact_match, $positive, $template_contents);
	    	        }else{
	    	            $template_contents  = str_replace($exact_match, $negative, $template_contents);
	    	        }

	    	        return $template_contents;
	        	}

	        }

	        // different
	        if ($tag->different()) {
	        	$prev_value = '';
	        	$new_value = '';

	        	if (array_key_exists($tag->different(), $this->_previous_item)) {
	        		$prev_value = $this->_resolve_to_value($this->_previous_item[$tag->different()]);
	        		if ($tag->format()) $prev_value = $this->_format($tag, $prev_value);
	        	}

	        	if (array_key_exists($tag->different(), $content_vars)) {
	        		$new_value = $this->_resolve_to_value($content_vars[$tag->different()]);
	        		if ($tag->format()) $new_value = $this->_format($tag, $new_value);
	        	}

	        	if ($tag->case()=='insensitive') {
	        		$prev_value = strtolower($prev_value);
	        		$new_value  = strtolower($new_value);
	        	}

	        	if ($prev_value != $new_value) {
    	            $template_contents  = str_replace($exact_match, $positive, $template_contents);
    	        }else{
    	            $template_contents  = str_replace($exact_match, $negative, $template_contents);
    	        }

    	        return $template_contents;
	        }

	        // id
	        if ($tag->id()) {
	            $matched = false;
	            $sideA = false;
	        	$sideB = false;

	        	if (is_array($content_vars) && array_key_exists($tag->id(), $content_vars) && $this->_resolve_to_value($content_vars[$tag->id()]) != '') {
    	            $sideA  = $this->_resolve_to_value($content_vars[$tag->id()]);

	        		if ($tag->format()) $sideA = $this->_format($tag, $sideA);
    	        }

	            $comparison = 'eq';
	            if ($tag->match()) $comparison = $tag->match();
	            if ($tag->value()) $sideB = $tag->value();

	            // parse sideB?
	            if ($sideB && substr($sideB, 0, 1)=='{' && substr($sideB, -1, 1)=='}') {
	            	$sideB = str_replace(array('{', '}'), '', $sideB);
	            	if (isset($content_vars[$sideB])) {
	            		$sideB = $this->_resolve_to_value($content_vars[$sideB]);
	            	}else{
	            		$sideB = false;
	            	}

	            	if ($tag->format() && $tag->format_both()) $sideB = $this->_format($tag, $sideB);
	            }

	            switch($comparison) {
	                case 'eq':
                    case 'is':
                    case 'exact':
                        if ($sideA == $sideB) $matched = true;
                        break;
                    case 'neq':
                    case 'ne':
                    case 'not':
                        if ($sideA != $sideB) $matched = true;
                        break;
                    case 'gt':
                        if ($sideA > $sideB) $matched = true;
                        break;
                    case 'gte':
                        if ($sideA >= $sideB) $matched = true;
                        break;
                    case 'lt':
                        if ($sideA < $sideB) $matched = true;
                        break;
                    case 'lte':
                        if ($sideA <= $sideB) $matched = true;
                        break;
                    case 'contains':
                        if (preg_match('/\b'.$sideB.'\b/i', $sideA)) $matched = true;
                        break;
                    case 'regex':
                    case 'regexp':
                        if (preg_match($sideB, $sideA)) $matched = true;
                        break;
                    case 'between':
                    case 'betwixt':
                        $vals  = explode(',', $sideB);
                        if (PerchUtil::count($vals)==2) {
                            if ($sideA>trim($vals[0]) && $sideA<trim($vals[1])) $matched = true;
                        }
                        break;
                    case 'eqbetween':
                    case 'eqbetwixt':
                        $vals  = explode(',', $sideB);
                        if (PerchUtil::count($vals)==2) {
                            if ($sideA>=trim($vals[0]) && $sideA<=trim($vals[1])) $matched = true;
                        }
                        break;
                    case 'in':
                    case 'within':
                        $vals  = explode(',', $sideB);
                        if (PerchUtil::count($vals)) {
                            foreach($vals as $value) {
                                if ($sideA==trim($value)) {
                                    $matched = true;
                                    break;
                                }
                            }
                        }
                        break;
	            }

	            if ($matched) {
	                $template_contents  = str_replace($exact_match, $positive, $template_contents);
	            }else{
	                $template_contents  = str_replace($exact_match, $negative, $template_contents);
	            }

	            return $template_contents;
	        }

	    }

	    // BEFORE
        if ($type == 'before') {
            if (array_key_exists('perch_item_first', $content_vars)) {
                $template_contents = str_replace($exact_match, $condition_contents, $template_contents);
            }else{
                $template_contents = str_replace($exact_match, '', $template_contents);
            }

            return $template_contents;
        }

        // AFTER
        if ($type == 'after') {
            if (array_key_exists('perch_item_last', $content_vars)) {
                $template_contents = str_replace($exact_match, $condition_contents, $template_contents);
            }else{
                $template_contents = str_replace($exact_match, '', $template_contents);
            }

            return $template_contents;
        }

	    return $template_contents;
	}

	protected function parse_every($type='every', $opening_tag, $condition_contents, $exact_match, $template_contents, $content_vars, $index_in_group)
	{
	    $tag = new PerchXMLTag($opening_tag);

	    $positive = $condition_contents;
        $negative = '';

        // else condition
        if (strpos($condition_contents, 'perch:else')>0) {
	        $parts   = preg_split('/<perch:else\s*\/>/', $condition_contents);
            if (is_array($parts) && count($parts)>1) {
                $positive = $parts[0];
                $negative = $parts[1];
            }
        }

	    if ($tag->count()) {
	        $count = (int) $tag->count();
            $offset = 0;

            if ($count !== 0 && ($index_in_group % $count == 0)) {
	            $template_contents = str_replace($exact_match, $positive, $template_contents);
	        }else{
	            $template_contents = str_replace($exact_match, $negative, $template_contents);
	        }

	    }elseif ($tag->nth_child()) {

	        $nth_child = $tag->nth_child();
	        $nths = array(0);

	        if (is_numeric($nth_child)) {
	            $nths[] = (int)$nth_child;
	        }else{

	            $multiplier = 0;
	            $offset = 0;

	            switch($nth_child) {

	                case 'odd':
	                    $multiplier = 2;
	                    $offset = 1;
	                    break;

	                case 'even':
	                    $multiplier = 2;
	                    $offset = 0;
	                    break;

	                default:
	                    $s = '/([\+-]{0,1}[0-9]*)n([\+-]{0,1}[0-9]+){0,1}/';
                        if (preg_match($s, $tag->nth_child(), $matches)) {
                            if (isset($matches[1]) && $matches[1]!='' && $matches[1]!='-') {
                                $multiplier = (int) $matches[1];
                            }else{
                                if ($matches[1]=='-') {
                                    $multiplier = -1;
                                }else{
                                    $multiplier = 1;
                                }
                            }

                            if (isset($matches[2])) {
                                $offset = (int) $matches[2];
                            }else{
                                $offset = 0;
                            }
                        }
	                    break;
	            }

                $n=0;
                if ($multiplier>0) {
                    while($n<1000 && max($nths)<=$index_in_group) {
                        $nths[] = ($multiplier*$n) + $offset;
                        $n++;
                    }
                }else{
                    while($n<1000) {
                        $nth = ($multiplier*$n) + $offset;
                        if ($nth>0) {
                            $nths[] = $nth;
                        }else{
                            break;
                        }
                        $n++;
                    }
                }
	        }

	        if (PerchUtil::count($nths)) {
                if (in_array($index_in_group, $nths)) {
                    $template_contents = str_replace($exact_match, $positive, $template_contents);
                }else{
                    $template_contents = str_replace($exact_match, $negative, $template_contents);
                }
	        }else{
	           $template_contents = str_replace($exact_match, $negative, $template_contents);
	        }


	    }else{
	        // No count or nth-child, so scrub it.
	        $template_contents = str_replace($exact_match, $negative, $template_contents);
	    }



	    return $template_contents;
	}

	protected function parse_repeaters($contents, $content_vars)
	{
		return $this->parse_paired_tags('repeater', false, $contents, $content_vars, false, 'render_repeater');
	}

	protected function render_repeater($type, $opening_tag, $condition_contents, $exact_match, $template_contents, $content_vars, $index_in_group)
	{
		$Tag = new PerchXMLTag($opening_tag);
		$out = '';

		if (is_array($content_vars) && isset($content_vars[$Tag->id()]) && PerchUtil::count($content_vars[$Tag->id()])) {
			$limit = $Tag->max() ? $Tag->max() : false;

			$RepeaterTemplate = new PerchTemplate(false, $this->namespace);
			$RepeaterTemplate->load($condition_contents);

			if (PerchUtil::bool_val($Tag->scope_parent())) {
				$vars_for_repeater = array();
				if (PerchUtil::count($content_vars)) {
					foreach($content_vars as $key => $val) {
						if ($key!=$Tag->id() && $key!='itemJSON') {
							$vars_for_repeater['parent.'.$key] = $val;
						}
					}
				}
				$vars_for_repeater = array_merge($vars_for_repeater, $content_vars[$Tag->id()]);

				foreach($content_vars[$Tag->id()] as &$item) {
					$item = array_merge($item, $vars_for_repeater);
				}
			}

			$out = $RepeaterTemplate->render_group($content_vars[$Tag->id()], true, $limit);
		}else{
			if (strpos($condition_contents, 'perch:noresults')) {
		        $s = '/<perch:noresults[^>]*>(.*?)<\/perch:noresults>/s';
		        $count	= preg_match_all($s, $condition_contents, $matches, PREG_SET_ORDER);

				if ($count > 0) {
					foreach($matches as $match) {
					    $out .= $match[1];
					}
				}
			}
		}
		return str_replace($exact_match, $out, $template_contents);
	}

	protected function parse_categories($contents, $content_vars)
	{
		if (in_array('categories', $this->disabled_features)) {
			return $contents;
		}

		return $this->parse_paired_tags('categories', false, $contents, $content_vars, false, 'render_categories');
	}

	protected function render_categories($type, $opening_tag, $condition_contents, $exact_match, $template_contents, $content_vars, $index_in_group)
	{
		$Tag = new PerchXMLTag($opening_tag);
		$out = '';

		if ($Tag->suppress()) {
			return str_replace($exact_match, '', $template_contents);
		}

		if (is_array($content_vars) && isset($content_vars[$Tag->id()]) && PerchUtil::count($content_vars[$Tag->id()])) {

			if (!class_exists('PerchCategories_Categories', false)) {
			    include_once(PERCH_CORE.'/apps/categories/PerchCategories_Categories.class.php');
			    include_once(PERCH_CORE.'/apps/categories/PerchCategories_Category.class.php');
			}

			$Categories = $this->_get_cached_object('PerchCategories_Categories');
			$value = $Categories->get_categories_from_ids_runtime($content_vars[$Tag->id()], $Tag->sort());

			$CatTemplate = new PerchTemplate(false, 'category');
			$CatTemplate->load($condition_contents);

			if (PerchUtil::bool_val($Tag->scope_parent())) {
				$vars_for_cat = array();
				if (PerchUtil::count($content_vars)) {
					foreach($content_vars as $key => $val) {
						if ($key!=$Tag->id() && $key!='itemJSON') {
							$vars_for_cat['parent.'.$key] = $val;
						}
					}
				}
				$vars_for_cat = array_merge($vars_for_cat, $content_vars[$Tag->id()]);

				foreach($value as &$item) {
					$item = array_merge($item, $vars_for_cat);
				}
			}

			$out = $CatTemplate->render_group($value, true);
		}else{
			if (strpos($condition_contents, 'perch:noresults')) {
		        $s = '/<perch:noresults[^>]*>(.*?)<\/perch:noresults>/s';
		        $count	= preg_match_all($s, $condition_contents, $matches, PREG_SET_ORDER);

				if ($count > 0) {
					foreach($matches as $match) {
					    $out .= $match[1];
					}
				}
			}
		}
		return str_replace($exact_match, $out, $template_contents);
	}

	protected function parse_related($contents, $content_vars)
	{
		if (PERCH_RUNWAY) {
			return $this->parse_paired_tags('related', false, $contents, $content_vars, false, 'render_related');
		}

		return $contents;
	}

	protected function render_related($type, $opening_tag, $condition_contents, $exact_match, $template_contents, $content_vars, $index_in_group)
	{
		$Tag = new PerchXMLTag($opening_tag);
		$out = '';

		if ($Tag->suppress()) {
			return str_replace($exact_match, '', $template_contents);
		}

		if (is_array($content_vars) && isset($content_vars[$Tag->id()]) && PerchUtil::count($content_vars[$Tag->id()])) {

			if (!class_exists('PerchContent_Collections', false)) {
			    include_once(PERCH_CORE.'/runway/apps/content/PerchContent_Collections.class.php');
			    include_once(PERCH_CORE.'/runway/apps/content/PerchContent_Collection.class.php');
			    include_once(PERCH_CORE.'/runway/apps/content/PerchContent_CollectionItems.class.php');
			    include_once(PERCH_CORE.'/runway/apps/content/PerchContent_CollectionItem.class.php');
			}

			$Collections = $this->_get_cached_object('PerchContent_Collections');
			$value 		 = $Collections->get_data_from_ids_runtime($Tag->collection(), $content_vars[$Tag->id()], $Tag->sort(), $Tag->count());

			$RelatedTemplate = new PerchTemplate(false, $this->namespace);
			$RelatedTemplate->load($condition_contents);

			if (PerchUtil::bool_val($Tag->scope_parent())) {
				$vars_for_cat = array();
				if (PerchUtil::count($content_vars)) {
					foreach($content_vars as $key => $val) {
						if ($key!=$Tag->id() && $key!='itemJSON') {
							$vars_for_cat['parent.'.$key] = $val;
						}
					}
				}
				$vars_for_cat = array_merge($vars_for_cat, $content_vars[$Tag->id()]);

				foreach($value as &$item) {
					$item = array_merge($item, $vars_for_cat);
				}
			}

			$out = $RelatedTemplate->render_group($value, true);
		}else{
			if (strpos($condition_contents, 'perch:noresults')) {
		        $s = '/<perch:noresults[^>]*>(.*?)<\/perch:noresults>/s';
		        $count	= preg_match_all($s, $condition_contents, $matches, PREG_SET_ORDER);

				if ($count > 0) {
					foreach($matches as $match) {
					    $out .= $match[1];
					}
				}
			}
		}
		return str_replace($exact_match, $out, $template_contents);
	}

	public function enable_encoding()
	{
	    $this->autoencode = true;
	}

	public function apply_runtime_post_processing($html, $vars=array())
    {
    	$handlers = PerchSystem::get_registered_template_handlers();

    	if (PerchUtil::count($handlers)) {
    		foreach($handlers as $handlerClass) {
				$Handler = new $handlerClass;
				$html    = $Handler->render_runtime($html, $this);
    		}
    	}

        $html = $this->render_settings($html);
        $html = $this->render_forms($html, $vars);
        $html = $this->render_layouts($html, $vars);

        return $html;
    }

    public function render_forms($html, $vars=array())
    {
        if (strpos($html, 'perch:form ')!==false) {
            $Form = new PerchTemplatedForm($html);
            $html = $Form->render($vars);
        }

        return $html;
    }

    public function render_settings($html)
    {
        if (strpos($html, 'perch:setting')!==false) {
            $Settings = PerchSettings::fetch();
            $settings = $Settings->get_as_array();

            $this->load($html);
            $this->namespace = 'setting';
            $html = $this->render($settings);

            $s = '/<perch:setting[^>]*\/>/s';
            $html = preg_replace($s, '', $html);
        }

        return $html;
    }

    public function render_layouts($html, $vars=array())
    {
    	if (strpos($html, 'perch:layout')!==false) {
			$s = '/<perch:layout[^>]*>/';
			$count	= preg_match_all($s, $html, $matches);

			$renderer = $this->layout_renderer;

			if ($count > 0) {
			    if (is_array($matches[0])){
			        foreach($matches[0] as $match) {
			            $Tag = new PerchXMLTag($match);

			            $attrs = $Tag->get_attributes();
			            if (is_array($vars)) {
			            	$vars = array_merge($vars, $attrs);
			            }else{
			            	$vars = $attrs;
			            }

			            $out = $renderer($Tag->path(), $vars, true);

			            $html = str_replace($match, $out, $html);
			        }
			    }
    	    }
    	}

    	return $html;
    }

    public function format_value($tag, $value)
    {
    	return $this->_format($tag, $value);
    }

    public function set_layout_renderer($renderer)
    {
    	$this->layout_renderer = $renderer;
    }

    public function get_block_tags($type)
    {
    	if (count($this->blocks)==0) {
    		$template = $this->load();
    		$this->parse_blocks($template, array());
    	}

    	if (isset($this->blocks[$type])) {
    		return $this->find_all_tags_and_repeaters($this->namespace, $this->blocks[$type]);
    	}
    }

    private function _resolve_to_value($val)
    {
    	if (!is_array($val)) {
    		return trim($val);
    	}

    	if (is_array($val)) {

    		if (count($val)==0) {
    			return '';
    		}

    		if (isset($val['_default'])) {
    			return trim($val['_default']);
    		}

    		if (isset($val['processed'])) {
    			return trim($val['processed']);
    		}

      	}

      	return $val;
    }

    protected function _format($tag, $modified_value)
    {
    	switch (substr($tag->format(), 0, 2)) {

            case '$:':
                // Money format = begins $:
                if (substr($tag->format(), 0, 2)==='$:') {
                	if (function_exists('money_format')) {
                		$modified_value = money_format(substr($tag->format(), 2), floatval($modified_value));
                	}
                }
                break;

            case '#:':
                // Number format = begins #:
                if (substr($tag->format(), 0, 2)==='#:') {
                    $decimals = 0;
                    $point = '.';
                    $thou = ',';

                    $number_parts = explode('|', substr($tag->format(), 2));

                    if (is_array($number_parts)) {
                        if (isset($number_parts[0])) $decimals = (int) $number_parts[0];
                        if (isset($number_parts[1])) $point = $number_parts[1];
                        if (isset($number_parts[2])) $thou = $number_parts[2];

                        $modified_value = number_format(floatval($modified_value), $decimals, $point, $thou);
                    }
                }
                break;

            case 'P:':
            	// string padding
            	$parts = explode('|', substr($tag->format(), 2));
            	$length = 1;
            	$string = ' ';
            	$type 	= STR_PAD_RIGHT;

            	if (is_array($parts)) {
                    if (isset($parts[0])) $length = (int) $parts[0];
                    if (isset($parts[1])) $string = $parts[1];
                    if (isset($parts[2])) {
                    	switch($parts[2]) {
                    		case 'left':
                    			$type = STR_PAD_LEFT;
                    			break;
                    		case 'both':
                    			$type = STR_PAD_BOTH;
                    			break;
                    		default:
                    			$type = STR_PAD_RIGHT;
                    			break;
                    	}
                    }


                    $modified_value = str_pad($modified_value, $length, $string, $type);
                }
            	break;

            case 'MB':
                // Format bytes into KB for small values, MB for larger values.
                $modified_value 	= floatval($modified_value);

                if ($modified_value < 1048576) {
                	$modified_value = round($modified_value/1024, 0).'KB';
                }else{
                	$modified_value = round($modified_value/1024/1024, 0).'MB';
                }
                break;

            case 'UC':
            	$modified_value = strtoupper($modified_value);
            	break;

            case 'LC':
            	$modified_value = strtolower($modified_value);
            	break;

            case 'C:':
            	$count = (int) str_replace('C:', '', $tag->format());
            	$modified_value = substr($modified_value, 0, $count);
            	break;

            default:
                if (strpos($tag->format(), '%')===false) {
                	// dates
		            $modified_value = date($tag->format(), strtotime($modified_value));
		        }else{
		        	// dates
		            $modified_value = strftime($tag->format(), strtotime($modified_value));
		        }
                break;
        }


	    return $modified_value;
    }

    protected function _rewrite($tag, $value)
    {
    	$pattern = $tag->rewrite();
    	$query 	 = parse_url($value, PHP_URL_QUERY);

    	$params = array();

    	if ($query) {
    		$query = htmlspecialchars_decode($query);
    		$pairs = explode('&', $query);

    		if (PerchUtil::count($pairs)) {
    			foreach($pairs as $pair) {
    				$parts = explode('=', $pair);
    				if (PerchUtil::count($parts)) {
    					$params[$parts[0]]  = $parts[1];
    				}
    			}
    		}
    	}

    	preg_match_all('#{([^:]+):([^}]+)}#', $pattern, $matches, PREG_SET_ORDER);

    	if (PerchUtil::count($matches)) {

    		foreach($matches as $match) {
    			if (isset($params[$match[1]])) {
    				$replacement = sprintf($match[2], $params[$match[1]]);
    				$pattern = str_replace($match[0], $replacement, $pattern);
    			}else{
    				$pattern = str_replace($match[0], '', $pattern);
    			}
    		}

    		return $pattern;

    	}

    	return $value;
    }

    private function parse_paired_tags($type, $empty_opener=false, $contents, $content_vars, $index_in_group=false, $callback='parse_conditional')
    {
		$close_tag     = '</perch:'.$type.'>';
		$close_tag_len = mb_strlen($close_tag);
		$open_tag      = '<perch:'.$type.($empty_opener ? '' : ' ');

		// escape hatch
		$i = 0;
		$max_loops = 1000;

		// loop through while we have closing tags
    	while($close_pos = mb_strpos($contents, $close_tag)) {

    		// we always have to go from the start, as the string length changes,
    		// but stop at the closing tag
    		$chunk = mb_substr($contents, 0, $close_pos);

    		// search from the back of the chunk for the opening tag
    		$open_pos = mb_strrpos($chunk, $open_tag);

    		// get the pair html chunk
    		$len = ($close_pos+$close_tag_len)-$open_pos;
    		$pair_html = mb_substr($contents, $open_pos, $len);

    		// find the opening tag - it's right at the start
    		$opening_tag_end_pos = mb_strpos($pair_html, '>')+1;
    		$opening_tag = mb_substr($pair_html, 0, $opening_tag_end_pos);

    		// condition contents
    		$condition_contents = mb_substr($pair_html, $opening_tag_end_pos, 0-$close_tag_len);

    		// Do the business
    		$contents = $this->$callback($type, $opening_tag, $condition_contents, $pair_html, $contents, $content_vars, $index_in_group);

    		// escape hatch counter
    		$i++;
    		if ($i > $max_loops) {
    			PerchUtil::debug('Template max limit hit for perch:'.$type.' tags, or malformed template.', 'error');
    			return $contents;	
    		} 
    	}

    	return $contents;
    }

    private function _get_cached_object($name)
    {
    	if (isset($this->cached_objects[$name]) && is_object($this->cached_objects[$name])) {
    		return $this->cached_objects[$name];
    	}

    	$o = new $name;

    	if (is_object($o)) {
    		$this->cached_objects[$name] = $o;
    		return $o;
    	}

    	return false;
    }

    private function _strip_comments($str)
    {
    	if (strpos($str, '<!--*')!==false) {
    		$pattern = '#\s*\Q<!--*\E([\w\W]*?)\Q*-->\E\s*#';
    		return preg_replace($pattern, '', $str);
    	}
    	return $str;
    }

    private function _cache_block_template_variation($type, $opening_tag, $condition_contents, $exact_match, $template_contents, $content_vars, $index_in_group=false)
    {
    	$OpeningTag = new PerchXMLTag($opening_tag);
    	$block_index = count($this->blocks);
    	$this->blocks[$OpeningTag->type()] = $condition_contents;

    	$replacement = '';
    	if ($block_index==0) $replacement = '<BLOCKS />';
    	return str_replace($exact_match, $replacement, $template_contents);
    }

    private function _apply_filters($stage, $tag, $value, $vars, $field_is_markup)
    {
    	$filters = explode(' ', $tag->filter);
    	if (PerchUtil::count($filters)) {
    		$class_map = PerchSystem::get_registered_template_filters();

    		foreach($filters as $filter) {
    			$filter = trim($filter);

    			if (array_key_exists($filter, $class_map)) {
    				$Filter = new $class_map[$filter]($tag, $vars);
    				$pre_value = $value;

    				switch($stage) {
    					case 0:
    						$value = $Filter->filterBeforeProcessing($value, $field_is_markup);
    						break;

    					case 1: 
    						$value = $Filter->filterAfterProcessing($value, $field_is_markup);
    						break;
    				}

    				
    				// Has it changed?
    				if ($pre_value !== $value && $Filter->returns_markup) {
    					$field_is_markup = true;
    				}
    			} else {
    				PerchUtil::debug("Missing template filter: ".$filter, 'error');
    			}
    		}
    	}

    	return [$value, $field_is_markup];
    }

    private function _assign_paging_values($item, $index)
    {
		$page     = (int)$item['current_page'];
		$per_page = (int)$item['per_page'];

		$item['perch_index_in_set']      = (($page-1) * $per_page) + $index+1;
		$item['perch_zero_index_in_set'] = $item['perch_index_in_set']-1;
		
		if ($item['perch_index_in_set'] == 1) {
			$item['perch_first_in_set'] = true;
		}

		if ($item['perch_index_in_set'] == (int)$item['total']) {
			$item['perch_last_in_set'] = true;	
		}
    	return $item;
    }

}