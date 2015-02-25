<?php

  // Define the root director of the project.
  // Used to definitively point to project files
  // without necessarily knowing what the current working directory is
  $rd = getcwd() . "/";
  $i = 0;

  // Returns true if all of the files expected to be in the root directory are in fact present.
  // This indicates we are at the root directory. Not 100% resilient as if can be fooled by moving these files somewhere, but it works well enough
  function is_root_directory($dir)
  {
    return file_exists($dir . "README.md") & file_exists($dir . "LICENSE");
  }

  while(!is_root_directory($rd)) {
    $rd .= "../";
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


  define('database_url', '');

  define('database_dbname', '');

  define('database_write_user', '');
  define('database_write_password', '');

  define('database_read_user', '');
  define('database_read_password', '');


  // Whether new user accounts need to verify their email address
  define('emails_require_verification', false);


  define('WEBSITE_TITLE', 'ArtAtk');


  define('CSS_DIRECTORY', 'web/assets/css/');
  define('JS_DIRECTORY', 'web/assets/js/');

  define('recaptcha_site_key', '');
  define('recaptcha_secret', '');

?>
