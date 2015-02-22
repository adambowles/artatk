<?php

  /**
   * Tips for writing functions in this class.
   * Security and attention to what you're writing here is very important
   *
   * Start each method off by sanitising it's parameters. Help avoid SQL injections
   *
   * Avoid using exposed SQL anywhere in this class. Write stored procedures instead.
   */
  class database_controller
  {

    /**
     *
     */
    public function __construct()
    {
      mysqli_connect(database_url,database_read_user,database_read_password,database_dbname);
      if (mysqli_connect_errno()) {
        echo("Failed to connect to MySQL: " . mysqli_connect_error());
      }
    }

  }

?>
