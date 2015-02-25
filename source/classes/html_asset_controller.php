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
    private $formvalidator_version = "2.1.47";

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
        $css_string .= $this->construct_link($this->local_absolute_to_public_url($this->css_dir . $file));
      }

      return $css_string;
    }

    /**
     * Get the bootstrap themes
     */
    public function get_bootstrap_css()
    {
      $return_string = "";

      $remote = "//maxcdn.bootstrapcdn.com/bootstrap/$this->bootstrap_version/css/bootstrap.min.css";
      $local = 'bootstrap/css/bootstrap.min.css';
      $return_string .= $this->remote_or_local($remote, $local);

      $local = 'bootflat/css/bootflat.min.css';
      $return_string .= $this->remote_or_local('', $local); // Bootflat does not have a CDN option

      return $return_string;
    }

    /**
     * Get the fontawesome themes
     */
    public function get_fontawesome_css()
    {
      $remote = "//maxcdn.bootstrapcdn.com/font-awesome/$this->fontawesome_version/css/font-awesome.min.css";
      $local = 'fontawesome/fontawesome.min.css';
      return $this->remote_or_local($remote, $local);
    }

    /**
     *
     */
    public function get_js()
    {
      $js_string = "";

      foreach($this->js_files as $file) {
        $js_string .= $this->construct_link($this->local_absolute_to_public_url($this->js_dir . $file));
      }

      return $js_string;
    }

    /**
     * @param $path_to_file Path to and including the js file to include from js folder (e.g. "regiser/register.js)
     */
    public function get_specific_asset($path_to_file)
    {
      $file_type = $this->file_type($path_to_file);

      if($file_type == 'js') {
        return $this->construct_link($this->local_absolute_to_public_url(ROOT_DIRECTORY . "web/assets/$path_to_file"));
      }

      if($file_type == 'css') {
        return $this->construct_link($this->local_absolute_to_public_url(ROOT_DIRECTORY . "web/assets/$path_to_file"));
      }
    }

    /**
     * Get the bootstrap script
     */
    public function get_bootstrap_js()
    {
      $remote = "//maxcdn.bootstrapcdn.com/bootstrap/$this->bootstrap_version/js/bootstrap.min.js";
      $local = 'bootstrap/js/bootstrap.min.js';
      return $this->remote_or_local($remote, $local);
    }

    /**
     *
     */
    public function get_jQuery()
    {
      $remote = "//ajax.googleapis.com/ajax/libs/jquery/$this->jQuery_version/jquery.min.js";
      $local = 'jQuery/jQuery.min.js';
      return $this->remote_or_local($remote, $local);
    }

    /**
     *
     */
    public function get_recaptcha_js()
    {
      return $this->construct_link("//www.google.com/recaptcha/api.js");
    }

    /**
     *
     */
    public function get_recaptcha_div()
    {
      return '<div class="g-recaptcha" data-sitekey="6LcYegITAAAAANugBoDsRxp-xRHvVISPrkLBn25v"></div>';
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
    public function file_type($file)
    {
      return pathinfo($file, PATHINFO_EXTENSION);
    }

    /**
     *  Decide whether to serve the local file or link to a CDN
     */
    private function remote_or_local($remote, $local)
    {
      if($this->remote_resource_exists($remote)) {
        return $this->construct_link($remote);
      } else {
        return $this->get_specific_asset($local);
      }
    }

    /**
     * Checks whether a remotely hosted CSS/JS is available to download
     * CDNs are nice and allow users to use a cached copy of a particular file
     * but their exitence is not guaranteed to last forever
     * source:http://stackoverflow.com/a/7051633, user: dangkhoaweb, date:24/02/2015
     */
    private function remote_resource_exists($resource)
    {
      //One liner: http://stackoverflow.com/q/4503135
      $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https:' : 'http:';
      $url = $protocol . $resource;

      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_NOBODY, 1); // don't download content
      curl_setopt($curl, CURLOPT_FAILONERROR, 1);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

      if(curl_exec($curl) !== false) {
        return true;
      } else {
        return false;
      }
    }

    /**
     *
     */
    private function construct_link($item)
    {
      if($this->file_type($item) == 'css') {
        return "<link rel=\"stylesheet\" type=\"text/css\" href=\"$item\">";
      }

      if($this->file_type($item) == 'js') {
        return "<script src=\"$item\"></script>";
      }
    }
  }

?>
