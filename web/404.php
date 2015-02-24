<?php

  // Configuration file
  include('../source/configuration/config.php');

  // Abstract page template
  include(ROOT_DIRECTORY . 'source/classes/page.php');

  $page = new error_404_page();
  $page->print_html();
?>
