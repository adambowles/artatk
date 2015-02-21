<?php

  class database_controller
  {

    public function __construct()
    {
      mysqli_connect(database_url,database_read_user,database_read_password,database_dbname);
      if (mysqli_connect_errno() {
        echo("Failed to connect to MySQL: " . mysqli_connect_error());
      }
    }
  }

?>
