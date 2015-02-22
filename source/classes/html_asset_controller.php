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

    private $jQuery_version = "2.1.3";
    private $fontawesome_version = "4.3.0";
    private $bootstrap_version = "3.3.2";

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
        $css_string .= $this->construct_css_link($this->local_absolute_to_public_url($this->css_dir . $file));
      }

      return $css_string;
    }

    /**
     * Get the bootstrap themes
     */
    public function get_bootstrap_css()
    {
      $return_string = "";
      $return_string .= $this->construct_css_link("//maxcdn.bootstrapcdn.com/bootstrap/$this->bootstrap_version/css/bootstrap.min.css");
      $return_string .= $this->construct_css_link($this->local_absolute_to_public_url(ROOT_DIRECTORY . "web/assets/bootflat/css/bootflat.min.css"));
      return $return_string;
    }

    /**
     * Get the fontawesome themes
     */
    public function get_fontawesome_css()
    {
      return $this->construct_css_link("//maxcdn.bootstrapcdn.com/font-awesome/$this->fontawesome_version/css/font-awesome.min.css");
    }

    /**
     *
     */
    public function get_js()
    {
      $js_string = "";

      foreach($this->js_files as $file) {
        $js_string .= $this->construct_js_link($this->local_absolute_to_public_url($this->js_dir . $file));
      }

      return $js_string;
    }

    /**
     * @param $path_to_file Path to and including the js file to include from js folder (e.g. "regiser/register.js)
     */
    public function get_specific_js($path_to_file)
    {
      $js_string = "";

      $js_string .= $this->construct_js_link($this->local_absolute_to_public_url(ROOT_DIRECTORY . "web/assets/js/" . $path_to_file));

      return $js_string;
    }

    /**
     * Get the bootstrap scripts
     */
    public function get_bootstrap_js()
    {
      $return_string = "";
      $return_string .= $this->construct_js_link("//maxcdn.bootstrapcdn.com/bootstrap/$this->bootstrap_version/js/bootstrap.min.js");
      return $return_string;
    }

    /**
     *
     */
    public function get_jQuery()
    {
      return $this->construct_js_link("https://ajax.googleapis.com/ajax/libs/jquery/$this->jQuery_version/jquery.min.js");
    }

    /**
     *
     */
    public function get_recaptcha_js()
    {
      return $this->construct_js_link("https://www.google.com/recaptcha/api.js");
    }

    /**
     *
     */
    public function get_recaptcha_div()
    {
      return "<div class=\"g-recaptcha\" data-sitekey=\"6LcYegITAAAAANugBoDsRxp-xRHvVISPrkLBn25v\"></div>";
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
