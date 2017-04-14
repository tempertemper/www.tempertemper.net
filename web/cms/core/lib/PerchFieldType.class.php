<?php
/**
 * Default field type
 *
 * @package default
 * @author Drew McLellan
 */
class PerchFieldType
{
    /**
     * The form object the field is used with
     *
     * @var object
     */
    protected $Form = false;

    /**
     * The tag object for the field
     *
     * @var object
     */
    protected $Tag = false;


    /**
     * The field ID used with setting the required validation
     *
     * @var string
     */
    protected $required_id = false;


    /**
     * A unique ID for using when e.g. outputting unique elements to the HTML
     *
     * @var string
     */
    protected $unique_id = false;


    /**
     * The un-processed item
     *
     * @var array
     */
    protected $raw_item = false;


    /**
     * All the tags from the template
     *
     * @var array
     */
    protected $sibling_tags = false;


	/**
	 * Is the processed output from this field pre-encoded markup? Use by template for safe encoding.
	 *
	 * @var bool
	 */
	public $processed_output_is_markup = false;

    /**
     * The ID of the app in use
     * @var string
     */
    public $app_id = false;


    /**
     * Class to apply to the field's wrapper div (.field-wrap)
     */
    public $wrap_class = null;


    /**
     * Does the hint text go before the field? It's normally after.
     */
    public $hints_before = false;


    /**
     * For basic types that just use the base class, enable override of type="" on the rendered input.
     */
    public $input_type = 'text';

    public function __construct(PerchForm $Form=null, PerchXMLTag $Tag=null, $app_id)
    {
        $this->Form   = $Form;
        $this->Tag    = $Tag;
        $this->app_id = $app_id;

        $this->required_id = $Tag->input_id();

        $this->add_class_dependancies();

    }

    public function get_wrapper_class()
    {
        $s = '';

        if ($this->wrap_class) {
            $s .= $this->wrap_class.' ';
        }

        return $s;
    }

    public function add_page_resources()
    {

    }

    public function add_class_dependancies()
    {

    }

    /**
     * Set the unique ID used by this field for rendering
     *
     * @param string $id
     * @return void
     * @author Drew McLellan
     */
    public function set_unique_id($id)
    {
        $this->unique_id = $id;
    }

    /**
     * Get the field ID for required valiation
     *
     * @return void
     * @author Drew McLellan
     */
    public function get_required_id()
    {
        return $this->required_id;
    }

    /**
     * Generate HTML string of form input controls
     *
     * @param string $details
     * @return void
     * @author Drew McLellan
     */
    public function render_inputs($details=array())
    {
        $s = '';
        $id = $this->Tag->id();
        $attrs = array();

        $size = 'm';

        if ($this->Tag->size()) {
            $size = $this->Tag->size();
        }

        $copy_atts = ['placeholder', 'autocomplete', 'autofill'];

        foreach($copy_atts as $att) {
            if ($this->Tag->is_set($att)) {
                $attrs[] = $att.'="' .PerchUtil::html($this->Tag->$att(), true). '"';
            }
        }

        $attrs = array_merge($attrs, $this->get_annotation_attributes());

        $attrs = implode(' ', $attrs);

        $s = $this->Form->text($this->Tag->input_id(), 
                            $this->Form->get($details, $id, $this->Tag->default(), $this->Tag->post_prefix()), 
                            $size.' input-simple', 
                            $this->Tag->maxlength(), 
                            $this->input_type, 
                            $attrs.$this->Tag->get_data_attribute_string()
                        );

        $s .= $this->render_field_annotations();

        return $s;
    }

    /**
     * Get raw value
     *
     * @return void
     * @author Drew McLellan
     */
    public function get_raw($post=false, $Item=false)
    {
        if ($post===false) {
            $post = $_POST;
        }

        $id = $this->Tag->id();
        if (isset($post[$id])) {
            $this->raw_item = trim(PerchUtil::safe_stripslashes($post[$id]));
            return $this->raw_item;
        }

        return null;
    }

    /**
     * Get processed value
     *
     * @return void
     * @author Drew McLellan
     */
    public function get_processed($raw=false)
    {
        if ($raw===false) $raw = $this->get_raw();

        $value = $raw;

        return $value;
    }

    /**
     * Get the text used for search indexing
     *
     * @param string $raw
     * @return void
     * @author Drew McLellan
     */
    public function get_search_text($raw=false)
    {
        if ($raw===false) $raw = $this->get_raw();

        return $raw;
    }

    /**
     * Get values for filtering index
     * @param  boolean $raw [description]
     * @return [type]       [description]
     */
    public function get_index($raw=false)
    {
        if ($raw===false) $raw = $this->get_raw();

        $id = $this->Tag->id();

        $out = array();

        if (is_array($raw)) {

            if (isset($raw['_default'])) {
                $out[] = array('key'=>$id, 'value'=>trim($raw['_default']));
            }else if (isset($raw['processed'])) {
                $out[] = array('key'=>$id, 'value'=>trim($raw['processed']));
            }

            foreach($raw as $key=>$val) {
                if (!is_array($val)) {
                    $out[] = array('key'=>$id.'_'.$key, 'value'=>trim($val));
                }
            }

        }else{
            $out[] = array('key'=>$id, 'value'=>trim($raw));
        }


        return $out;
    }

    public function get_api_value($raw=false)
    {
        if ($raw===false) $raw = $this->get_raw();

        if (isset($raw['processed'])) {
            return $raw['processed'];
        }else if (isset($raw['_default'])) {
            return $raw['_default'];
        }

        return $raw;
    }

    /**
     * Get a version of the content for listing in the admin editing interface.
     * @param  boolean $raw [description]
     * @return [type]       [description]
     */
    public function render_admin_listing($raw=false)
    {
        return PerchUtil::html($this->get_processed($raw));
    }

    /**
     * Set sibling tags
     *
     * @param array $tags
     * @return void
     * @author Drew McLellan
     */
    public function set_sibling_tags($tags=array())
    {
        $this->sibling_tags = $tags;
    }


    /**
     * When data is coming in programmatically rather than from a form.
     */
    public function import_data($data)
    {
        return $this->get_raw($data);
    }


    /**
     * Get sibling tags from the template, if set.
     *
     * @return array
     * @author Drew McLellan
     */
    public function get_sibling_tags()
    {
        return $this->sibling_tags;
    }

    protected function get_annotation_attributes($as_data_attrs=false)
    {
        $attrs = [];

        if ($this->Tag->count()) {
            if ($as_data_attrs) {
                if ($this->Tag->count()=='chars') $attrs['count'] = 'chars';
                if ($this->Tag->count()=='words') $attrs['count'] = 'words';
                $attrs['count-container'] = $this->Tag->input_id().'__count';
            } else {
                if ($this->Tag->count()=='chars') $attrs[] = 'data-count="chars"';
                if ($this->Tag->count()=='words') $attrs[] = 'data-count="words"';
                $attrs[] = 'data-count-container="' . $this->Tag->input_id().'__count"';
            }
            
        }

        return $attrs;
    }

    protected function render_field_annotations()
    {
        $s = '';

        // Word/char count
        if ($this->Tag->count()) {
            $s .= '<div class="char-limit-count" aria-live="polite">';
            $s .= PerchUI::icon('core/o-typewriter', 10). ' <span id="'.$this->Tag->input_id().'__count">-</span>';
            $s .= '</div>';
        }

        // Formatting lang
        if ($this->Tag->markdown() || $this->Tag->textile() || $this->Tag->flang()) {
            $s .= '<div class="formatting-language">'.PerchUI::icon('core/o-pencil', 10).' ';

            if ($this->Tag->markdown() || $this->Tag->flang() == 'markdown') {
                $s .= '<a href="'.PERCH_LOGINPATH.'/core/help/markdown">Markdown</a>';
            }

            if ($this->Tag->textile() || $this->Tag->flang() == 'textile') {
                $s .= '<a href="'.PERCH_LOGINPATH.'/core/help/textile">Textile</a>';
            }
            
            $s .= '</div>';
        }

        // Max selections
        if ($this->Tag->max()) {
            $s .= '<div class="char-limit-count" aria-live="polite">';
            $s .= PerchUI::icon('core/o-connect', 10). ' <span>'.PerchLang::get('Max: %s', (int)$this->Tag->max()).'</span>';
            $s .= '</div>';
        }
        

        if (strlen($s) > 0) {
            $class = 'field-annotations '.$this->Tag->type().' ';
            if ($this->Tag->size()) {
                $class .= $this->Tag->size();
            }
            $id = $this->Tag->input_id();

            $s = '<div class="'.PerchUtil::html($class, true).'" id="'.PerchUtil::html($id, true).'">'.$s.'</div>';
        }

        if ($s !='' || ($this->Tag->help && !$this->hints_before)) {
            $this->wrap_class .= 'annotated ';
        }

        return $s;
    }

    protected function parse_shortcodes($html)
    {
        $ShortcodeParser = new PerchShortcode_Parser();
        return $ShortcodeParser->parse($html, $this->Tag);
    }

}
