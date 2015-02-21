<?php
  // Future considerations for this file:
  // 1. Turn it into a front controller to handle ALL site requests
  // and handle passing those requests to the right files in the source folder
  // 2.

  // Configuration file
  include('../source/configuration/config.php');

  // Abstract page template
  include(ROOT_DIRECTORY . 'source/classes/page.php');

  $page = new front_page();
//  $page->set_title('front');

  $page->add_body("<div class=\"starter-template\">");
  $page->add_body("<h1>Bootstrap starter template</h1>");
  $page->add_body("<p class=\"lead\">something here</p>");
  $page->add_body("</div>");

  $page->print_html();

?>
