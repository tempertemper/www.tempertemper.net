<?php

  PerchSystem::register_feather('tempertemper');

  class PerchFeather_tempertemper extends PerchFeather
  {
    public function get_css($opts, $index, $count)
    {
      $out = array();

      if (!$this->component_registered('style')) {
        $out[] = $this->_single_tag('link', array(
            'rel'=>'stylesheet',
            'href'=>$this->path.'/css/style.css?4.3.9'
          ));
        $this->register_component('style');
      }

      if (!$this->component_registered('html5shiv')) {
        $out[] = $this->_script_tag(array(
            'src'=>$this->path.'/js/html5shiv.min.js'
          ));
        $this->register_component('html5shiv');
      }

      if (!$this->component_registered('responsive-nav')) {
        $out[] = $this->_script_tag(array(
            'src'=>$this->path.'/js/responsive-nav.min.js'
          ));
        $this->register_component('responsive-nav');
      }

      return implode("\n\t", $out)."\n";
    }

    public function get_javascript($opts, $index, $count)
    {
      $out = array();

      if (!$this->component_registered('jquery')) {
        $out[] = $this->_script_tag(array(
          'src'=>$this->path.'/js/production.min.js'
        ));
        $this->register_component('jquery');
      }

      return implode("\n\t", $out)."\n";
    }
  }

?>