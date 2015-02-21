<?php

  /**
   *
   */
  class page
  {

    private $asset_controller;

    // Strings of page content, append whatever you like in order to add it to the page
    // (hint: user the add_header()/add_body()/add_footer() funcs)
    private $head;
    private $body;
    private $footer;

    // Some page settings
    private $charset = "UTF-8";
    private $title;

    /**
     *
     */
    public function __construct()
    {
      include(ROOT_DIRECTORY . "source/classes/html_asset_controller.php");

      $this->asset_controller = new html_asset_controller();
    }

    /**
     * Returns the page as contiguous HTML string
     */
    public function get_html()
    {
      return $this->construct_head() .
        $this->construct_body() .
        $this->construct_footer();
    }

    /**
     * Helper method to print the HTML string directly
     * Keeps object code tidier
     */
    public function print_html()
    {
      echo($this->get_html());
    }

    /**
     *
     */
    private function construct_head()
    {
      return "<!doctype html>\r\n" .
             "<html>\r\n" .
             "<head>\r\n" .
             $this->get_charset_meta_tag() .
             "<title>\r\n" .
             WEBSITE_TITLE . $this->get_title() .
             "</title>\r\n" .
             $this->asset_controller->get_bootstrap_css() .
             $this->asset_controller->get_css() .
             $this->head .
             "</head>\r\n" .
             "<body>\r\n" .
             "<header>\r\n" .
             $this->get_navbar() .
             "</header>\r\n" .
             "<div id=\"body\" class=\"container\">\r\n"
        ;
    }

    /**
     *
     */
    public function get_title()
    {
      if ($this->title != "") {
        return " &middot " . $this->title;
      } else {
        return "";
      }
    }

    /**
     * //TODO make this a bit more smart
     * e.g. add_page_navlink() for example will add to here, something like that
     */
    public function get_navbar()
    {
      $navbar_string = "    <nav class=\"navbar navbar-default navbar-fixed-top\">
      <div class=\"container\">
        <div class=\"navbar-header\">
          <button type=\"button\" class=\"navbar-toggle collapsed\" data-toggle=\"collapse\" data-target=\"#navbar\" aria-expanded=\"false\" aria-controls=\"navbar\">
            <span class=\"sr-only\">Toggle navigation</span>
            <span class=\"icon-bar\"></span>
            <span class=\"icon-bar\"></span>
            <span class=\"icon-bar\"></span>
          </button>
          <a class=\"navbar-brand\" href=\"#\">Project name</a>
        </div>
        <div id=\"navbar\" class=\"collapse navbar-collapse\">
          <ul class=\"nav navbar-nav\">
            <li class=\"active\"><a href=\"#\">Home</a></li>
            <li><a href=\"#about\">About</a></li>
            <li><a href=\"#contact\">Contact</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>";
      return $navbar_string;
    }

    /**
     * // Set the page title
     */
    public function set_title($new_title)
    {
      $this->title = $new_title;
    }

    /**
     * //TODO
     * Set entire title (so that page does not show the ArtAtk prefix)
     */
    public function set_whole_title($new_title)
    {
      $this->title = $new_title;
    }

    /**
     *
     */
    public function add_head($content)
    {
      $this->head = $this->head . $content;
    }

    /**
     *
     */
    private function construct_body()
    {
      return $this->body;
    }

    /**
     *
     */
    public function add_body($content)
    {
      $this->body = $this->body . $content;
    }

    /**
     *
     */
    private function construct_footer()
    {
      return "</div>\r\n" .
             "<footer>\r\n" .
             $this->asset_controller->get_js() .
             $this->asset_controller->get_jQuery() .
             $this->footer .
             "</footer>\r\n" .
             "</body>\r\n" .
             "</html>\r\n"
        ;
    }

    /**
     *
     */
    public function add_footer($content)
    {
      $this->footer = $this->footer . $content;
    }

    /**
     *
     */
    private function set_charset($new_charset)
    {
      $this->charset = $new_charset;
    }

    /**
     *
     */
    private function get_charset_meta_tag()
    {
      return "<meta charset=\"$this->charset\">\r\n";
    }

  }

  // The following subclasses are bascially just normal pages with some handy default behaviours added

  /**
   *
   */
  class front_page extends page
  {
    public function __construct(){
      // Perform a superclass construction
      parent::__construct();

      // Demo content
      //$this->add_body("<p>this is a front page</p>\r\n");
    }
  }

  /**
   * //TODO keep this one at the end of the script
   */
  class error_404_page extends page
  {
    public function __construct(){
      // Perform a superclass construction
      parent::__construct();

      // Some defaults
      $this->set_title("Page not found");
      $this->add_body("<p>404: not found, sorry! :(</p>\r\n");
      $this->add_body("<a href=\"/\">Home page</a>\r\n");
    }
  }

?>
