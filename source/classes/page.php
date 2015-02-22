<?php

  /**
   * @author Adam Bowles <bowlesa@aston.ac.uk>
   */
  class page
  {

    // Some funcationality controllers
    private $asset_controller;
    private $database_controller;

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
      include(ROOT_DIRECTORY . "source/classes/database_controller.php");

      $this->asset_controller = new html_asset_controller();

      $this->database_controller = new database_controller();
      $this->database_controller->connect_read();
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
      return "<!doctype html>" .
             "<html>" .
             "<head>" .
               $this->get_charset_meta_tag() .
               "<title>" .
                 WEBSITE_TITLE . $this->get_title() .
               "</title>" .
               $this->asset_controller->get_bootstrap_css() .
               $this->asset_controller->get_css() .
               $this->head .
             "</head>" .
             "<body>" .
               "<header>" .
                 $this->get_navbar() .
               "</header>" .
               "<div id=\"content\" class=\"container\">"
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
      $navbar_string = "<nav class=\"navbar navbar-default navbar-fixed-top\">
                          <div class=\"container\">
                            <div class=\"navbar-header\">
                              <button type=\"button\" class=\"navbar-toggle collapsed\" data-toggle=\"collapse\" data-target=\"#navbar\" aria-expanded=\"false\" aria-controls=\"navbar\">
                                <span class=\"sr-only\">Toggle navigation</span>
                                <span class=\"icon-bar\"></span>
                                <span class=\"icon-bar\"></span>
                                <span class=\"icon-bar\"></span>
                              </button>
                              <a class=\"navbar-brand\" href=\"/\">".WEBSITE_TITLE."</a>
                            </div>
                            <div id=\"navbar\" class=\"collapse navbar-collapse\">
                              <ul class=\"nav navbar-nav\">
                                <li class=\"active\"><a href=\"/\">Home</a></li>
                                <li><a href=\"/rate.php\">Rate</a></li>
                                <li><a href=\"/recommendation.php\">Get your recommendation</a></li>
                              </ul>
                              <ul class=\"nav navbar-nav navbar-right\">
                                <li class=\"dropdown\">
                                  <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">Signed in as Adam Bowles <b class=\"caret\"></b></a>
                                  <ul class=\"dropdown-menu\" role=\"menu\">
                                    <li><a href=\"#\">Edit profile</a></li>
                                    <li class=\"divider\"></li>
                                    <li><a href=\"#\">Log out</a></li>
                                  </ul>
                                </li>
                              </ul>

                            </div><!--/.nav-collapse -->
                          </div>
                        </nav>";
      return $navbar_string;
    }

    /**
     * Set the page title ("login page" appears like "ArtAtk! · login page")
     */
    public function set_title($new_title)
    {
      $this->title = $new_title;
    }

    /**
     * //TODO
     * Set entire title (so that page does not show the ArtAtk prefix, see set_title() for info)
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
      return     "</div>" .
                 "<div class=\"container copyright-footer\">" .
                   "<div class=\"copyright\">" .
                     "<p class=\"copyright-text\">&copy; 2015 Adam Bowles</p>" .
                     $this->footer .
                   "</div>" .
                 "</div>" .
               "</body>" .
               $this->asset_controller->get_jQuery() .
               $this->asset_controller->get_bootstrap_js() .
               $this->asset_controller->get_js() .
             "</html>"
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
      return "<meta charset=\"$this->charset\">";
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
      //$this->add_body("<p>this is a front page</p>");  $page->add_body("<div class=\"starter-template\">");
  $this->add_body("  <h2>ArtAtk, art aesthetic analyser</h2>");
  $this->add_body("  <p class=\"lead\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sit amet mollis ante. Duis sollicitudin turpis ut tellus mattis, elementum auctor urna consequat. Ut nibh magna, facilisis sit amet purus quis, dignissim commodo nisi. Nullam ac convallis est. Nam vel sem vel mauris imperdiet pulvinar. Proin nibh tortor, fringilla aliquam magna non, pellentesque finibus nulla. Quisque mi mauris, cursus sed faucibus et, varius at velit. Nullam a eros sed magna viverra interdum. In hac habitasse platea dictumst. In eleifend in tortor quis bibendum.</p>");
  $this->add_body("</div>");
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
      $this->add_body("<h2>404: not found, sorry! :(</h2>");
      $this->add_body("<h3><a href=\"/\">Home page</a></h3>");
    }
  }

?>
