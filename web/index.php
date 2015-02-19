<?php

  // Configuration file
  include('../source/configuration/config.php');

  // Abstract page template
  include('../source/classes/page.php');

  $page = new page();

  echo($page->construct_page());

?>
