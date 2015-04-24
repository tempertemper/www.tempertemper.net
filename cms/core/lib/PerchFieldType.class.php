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
     * @var string
     */
    protected $Form = false;
    
    /**
     * The tag object for the field
     *
     * @var string
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
     * @var string
     */
    protected $raw_item = false;
    
    
    /**
     * All the tags from the template
     *
     * @var string
     */
    protected $sibling_tags = false;


	/**
	 * Is the processed output from this field pre-encoded markup? Use by template for safe encoding.
	 *
	 * @var string
	 */
	public $processed_output_is_markup = false;

    /**
     * The ID of the app in use
     * @var string
     */
    public $app_id = false;

   
    public function __construct($Form, $Tag, $app_id)
    {
        $this->Form   = $Form;
        $this->Tag    = $Tag;
        $this->app_id = $app_id;
        
        $this->required_id = $Tag->input_id();

        $this->add_class_dependancies();
    
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
        $s = $this->Form->text($this->Tag->input_id(), $this->Form->get($details, $id, $this->Tag->default(), $this->Tag->post_prefix()), $this->Tag->size(), $this->Tag->maxlength());
                
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
     * @param string $tags 
     * @return void
     * @author Drew McLellan
     */
    public function set_sibling_tags($tags)
    {
        $this->sibling_tags = $tags;
    }
    
    
    /**
     * Get sibling tags from the template, if set.
     *
     * @return void
     * @author Drew McLellan
     */
    public function get_sibling_tags()
    {
        return $this->sibling_tags;
    }
    
}
