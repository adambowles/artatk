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
     * Check whether the connection is open
     *
     * @return True of the connection is open, false if it closed
     */
    public function is_connected()
    {
      return !$this->connection == null;
    }

    /**
     * Sanitise the string provided. This helps protect against SQL injection.
     * PLEASE use this at the top of EVERY function to protect all user input..
     * If a user CAN upset a system they WILL upset the system. Maliciously or otherwise.
     *
     * @param $data The data to be sanitised
     *
     * @return  Sanitised string data, null if the mysql connection hasn't been created yet
     */
    private function sanitise($data)
    {
      if($this->is_connected()){
        return $this->connection->real_escape_string($data);
      } else {
        return null;
      }
    }

    /**
     * Execute an SQL statement
     *
     * @param $sql  The SQL to be executed
     *
     * @return  True if the SQL was successfully sent to the db, false if the conenction was open, or any error occurred
     */
    private function execute($sql)
    {
      if($this->is_connected()){
        return $success = $this->connection->query($sql) === TRUE;
      } else {
        return false;
      }
    }

    /**
     * Delete user by user id
     *
     * @param $user_id ID of the user to delete
     */
    public function delete_user_by_id($user_id)
    {
      $user_id = $this->sanitise($user_id);

      $sql = "CALL `DELETE_USER_BY_ID` ($user_id);";
      $this->execute($sql);
    }

    /**
     * Delete user by user id
     *
     * @param $username Username of the user to delete
     */
    public function delete_user_by_username($username)
    {
      $username = $this->sanitise($username);

      $sql = "CALL `DELETE_USER_BY_USERNAME` ('$username');";
      $this->execute($sql);
    }

    /**
     * Close the connection and set it to null
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
