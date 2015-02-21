<?php

  // Define the root director of the project.
  // Used to definitively point to project files
  // without necessarily knowing what the current working directory is
  $rd = getcwd() . "/";
  $i = 0;

  function at_root_directory()
  {
    return file_exists($rd . "README.md") | file_exists($rd . "LICENSE");
  }

  while(!at_root_directory()) {
    $rd .= "../";
//    $at_root_directory = file_exists($rd . "README.md") | file_exists($rd . "LICENSE");
    $i++;

    // Just in case README.md and LICENSE were deleted for
    // whatever reason, add an infinite loop breakout
    if ($i>255) {
      $rd = "";
      die("Files \"README.md\" and \"LICENSE\" weren't found," .
          " did you delete them?<br>Put them back, they're necessary!");
    }
  };

  define("ROOT_DIRECTORY", $rd);


  define('database_url', 'localhost');

  define('database_write_user', '');
  define('database_write_password', '');

  define('database_read_user', '');
  define('database_read_password', '');


  define('WEBSITE_TITLE', 'ArtAtk!');


  define('CSS_DIRECTORY', 'web/assets/css/');
  define('JS_DIRECTORY', 'web/assets/js/');

?>
