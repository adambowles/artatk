<?php

  // Configuration file
  include('../source/scripts/cryptography/PasswordHash.php');

  include('../source/scripts/cryptography/PasswordHash.php');

  echo('1');
  $password = '123';
  $hash = create_hash($password);
  echo(validate_password($password, $hash));

?>
