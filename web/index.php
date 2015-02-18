<?php

  // Configuration file
  include('../source/configuration/config.php');

  include('../source/classes/page.php');

  $page = new page();

  echo($page->construct_page());

?>
