<?php
  // Future considerations for this file:
  // 1. Turn it into a front controller to handle ALL site requests
  // and handle passing those requests to the right files in the source folder
  // 2.

  // Configuration file
  include('../source/configuration/config.php');

  // Abstract page template
  include(ROOT_DIRECTORY . 'source/classes/page.php');

  $page = new recommendation_page();

  $page->print_html();

?>
