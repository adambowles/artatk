<?php

  /**
   * @author Adam Bowles <bowlesa@aston.ac.uk>
   */
  class html_asset_controller
  {

    private $css_dir;
    private $css_files;

    private $js_dir;
    private $js_files;

    /**
     * Constructor
     */
    public function __construct()
    {
      // Collate CSS files
      $this->css_dir = ROOT_DIRECTORY . CSS_DIRECTORY;
      $this->css_files = scandir($this->css_dir);
      foreach($this->css_files as $key => $file) {
        // Remove folders
        if (is_dir($this->css_dir . "/" . $file)) {
          unset($this->css_files[$key]);
        }
      }

      // Collate JS files
      $this->js_dir = ROOT_DIRECTORY . JS_DIRECTORY;
      $this->js_files = scandir($this->js_dir);
      foreach($this->js_files as $key => $file) {
        // Remove folders
        if (is_dir($this->js_dir . "/" . $file)) {
          unset($this->js_files[$key]);
        }
      }
    }

    /**
     *
     */
    public function get_css()
    {
      $css_string = "";

      foreach($this->css_files as $file) {
        $css_string .= $this->construct_css_link($this->local_absolute_to_public_url($this->css_dir . $file)) . "\r\n";
      }

      return $css_string;
    }

    /**
     *
     */
    public function get_bootstrap_css()
    {
      return $this->construct_css_link($this->local_absolute_to_public_url(ROOT_DIRECTORY . "web/assets/bootflat/css/bootflat.css")) . "\r\n";
    }

    /**
     *
     */
    public function get_js()
    {
      $js_string = "";

      foreach($this->js_files as $file) {
        $js_string .= $this->construct_js_link($this->local_absolute_to_public_url($this->js_dir . $file)) . "\r\n";
      }

      return $js_string;
    }

    /**
     *
     */
    public function get_jQuery()
    {
      return $this->construct_js_link("https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js")) . "\r\n";
    }

    /**
     *
     */
    private function local_absolute_to_public_url($dir_to_file)
    {
      return preg_replace("/" . preg_quote(ROOT_DIRECTORY, "/") . "web/", "", $dir_to_file);
    }

    /**
     *
     */
    private function construct_css_link($stylesheet)
    {
      return "<link rel=\"stylesheet\" type=\"text/css\" href=\"$stylesheet\">";
    }

    /**
     *
     */
    private function construct_js_link($script)
    {
      return "<script src=\"$script\"></script>";
    }
  }

?>
