<?php

  // Configuration file
  include('../source/configuration/config.php');

  // Abstract page template
  include(ROOT_DIRECTORY . 'source/classes/page.php');

  $page = new error_404_page();
  $page->print_html();

  // Return proper error code
//  header("HTTP/1.0 404 Not Found");
?>
