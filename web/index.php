<?php

  // Configuration file
  include('../source/configuration/config.php');

  include('../source/classes/page.php');

  $page = new page();

  echo($page->construct_page());
  $password = '123';
  $hash = create_hash($password);
  echo(validate_password($password, $hash));

?>
