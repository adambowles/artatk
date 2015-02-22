<?php

  /**
   * Tips for writing functions in this class.
   * Security and attention to what you're writing here is very important
   *
   * Start each method off by sanitising it's parameters. Help avoid SQL injections
   *
   * Avoid using exposed SQL anywhere in this class. Write stored procedures instead.
   *
   * @author Adam Bowles <bowlesa@aston.ac.uk>
   */
  class database_controller
  {

    private $connection;

    /**
     *
     */
    public function __construct()
    {

    }

    /**
     *
     */
    public function connect_read()
    {
      if($this->is_connected()){
        mysqli_close($this->connection);
      }

      $this->connection = mysqli_connect(database_url, database_write_user, database_write_password, database_dbname);

      if (mysqli_connect_errno()) {
        die("Failed to connect to Database: " . mysqli_connect_error());
      }
    }

    /**
     *
     */
    public function connect_write()
    {
      if($this->is_connected()){
        mysqli_close($this->connection);
      }

      $this->connection = mysqli_connect(database_url, database_write_user, database_write_password, database_dbname);

      if (mysqli_connect_errno()) {
        die("Failed to connect to Database: " . mysqli_connect_error());
      }
    }

    /**
     *
     */
    public function is_connected()
    {
      return !$this->connection == null;
    }

    /**
     * Destroy the connection and set it to null
     */
    public function disconnect()
    {
      if($this->is_connected()) {
        $this->connection->close();
        $this->connection = null;
      }
    }

    /**
     *
     */
    function __destruct() {
      $this->disconnect();
    }

  }

?>
