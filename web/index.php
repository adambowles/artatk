<?php

  // Configuration file
  include('../source/configuration/config.php');

  // Abstract page template
  include('../source/classes/page.php');

  $page = new page();

  $page->add_body('<p>abc</p>');
  echo($page->get_html());

?>
