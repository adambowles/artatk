<?php

  class page
  {

    private $asset_controller;

    public function __construct()
    {
      // Define the root director of the project.
      // Used to definitively point to project files
      // without necessarily knowing what the current working directory is
      $rd = getcwd() . '/';
      $i = 0;

      while(!(file_exists($rd . 'README.md') | file_exists($rd . 'LICENSE'))) {
        $rd .= '../';
        $i++;

        // Just in case README.md and LICENSE were deleted for whatever reason, add an infinite loop breakout
        if ($i>255) {
          $rd = '';
          die('Files \'README.md\' and \'LICENSE\' weren\'t found, did you delete them?<br>Put them back, they\'re necessary!');
        }
      };

      define('ROOT_DIRECTORY', $rd);

      include(ROOT_DIRECTORY . 'source/classes/html_asset_controller.php');

      $this->asset_controller = new html_asset_controller();
    }

    public function construct_page()
    {
      return $this->construct_header() . $this->construct_body() . $this->construct_footer();
    }

    private function construct_header()
    {
      return '<!doctype html>' .
             '<html>' .
               '<head>' .
                 $this->asset_controller->get_css() .
               '</head>';
    }

    private function construct_body()
    {
      return '<body>' .
             '</body>';
    }

    private function construct_footer()
    {
      return   '<footer>' .
                 $this->asset_controller->get_js() .
               '</footer>' .
             '</html>';
    }

  }

?>
