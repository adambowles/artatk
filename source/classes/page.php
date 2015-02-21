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

    /**
     *
     */
    public function __construct()
    {
      // Define the root director of the project.
      // Used to definitively point to project files
      // without necessarily knowing what the current working directory is
      $rd = getcwd() . "/";
      $i = 0;

      while(!(file_exists($rd . "README.md") | file_exists($rd . "LICENSE"))) {
        $rd .= "../";
        $i++;

        // Just in case README.md and LICENSE were deleted for
        // whatever reason, add an infinite loop breakout
        if ($i>255) {
          $rd = "";
          die("Files \"README.md\" and \"LICENSE\" weren't found," .
              " did you delete them?<br>Put them back, they're necessary!");
        }
      };

      define("ROOT_DIRECTORY", $rd);

      include(ROOT_DIRECTORY . "source/classes/html_asset_controller.php");

      $this->asset_controller = new html_asset_controller();
    }

    /**
     *
     */
    public function get_html()
    {
      return $this->construct_head() .
        $this->construct_body() .
        $this->construct_footer();
    }

    /**
     *
     */
    private function construct_head()
    {
      return "<!doctype html>\r\n" .
             "<html>\r\n" .
             "<head>\r\n" .
             "<title>\r\n" .
             WEBSITE_TITLE .
             "</title>\r\n" .
             $this->asset_controller->get_bootstrap_css() .
             $this->asset_controller->get_css() .
             $this->head .
             "</head>\r\n" .
             "<body>\r\n"
        ;
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
      return "</body>\r\n" .
             "<footer>\r\n" .
             $this->asset_controller->get_js() .
             $this->asset_controller->get_jQuery() .
             $this->footer .
             "</footer>\r\n" .
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

  }

  /**
   *
   */
  class rating_page extends page
  {

  }

?>
