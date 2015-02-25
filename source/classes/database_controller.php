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
      $this->connect_read();
    }

    /**
     *
     */
    private function get_connection()
    {
      return $this->connection;
    }

    /**
     *
     */
    private function set_connection($new_connection)
    {
      return $this->connection = $new_connection;
    }

    /**
     *
     */
    private function connect_read()
    {
      if($this->is_connected()){
        $this->disconnect();
      }

      $this->set_connection(mysqli_connect(database_url, database_read_user, database_read_password, database_dbname));

      if (mysqli_connect_errno()) {
        die("Failed to connect to Database: " . mysqli_connect_error());
      }
    }

    /**
     *
     */
    private function connect_write()
    {
      if($this->is_connected()){
        $this->disconnect();
      }

      $this->set_connection(mysqli_connect(database_url, database_write_user, database_write_password, database_dbname));

      if (mysqli_connect_errno()) {
        die("Failed to connect to Database: " . mysqli_connect_error());
      }
    }

    /**
     * Check whether the connection is open
     *
     * @return True of the connection is open, false if it's closed
     */
    private function is_connected()
    {
      return $this->get_connection() != null;
    }

    /**
     * Sanitise the string provided. This helps protect against SQL injection.
     * PLEASE use this at the top of EVERY function to protect all user input..
     * If a user CAN upset a system they WILL upset the system. Maliciously or otherwise.
     *
     * @param $data The data to be sanitised
     *
     * @return Sanitised string data, null if the mysql connection hasn't been created yet (do not keep unsantised user entered data ever)
     */
    private function sanitise($data)
    {
      if($this->is_connected()) {
        return $this->get_connection()->real_escape_string($data);
      } else {
        return null;
      }
    }

    /**
     * Execute an SQL statement
     *
     * @param $sql The SQL to be executed
     *
     * @return True if the SQL was successfully sent to the db, false if the conenction wasn't open, or any db error occurred
     */
    private function execute($sql, $permission)
    {
      if($permission == "write") {
        $this->connect_write();
      }

      if($this->is_connected()){
        $success = $this->get_connection()->query($sql) === TRUE;
      } else {
        $success = false;
      }

      if($permission == "write") {
        $this->connect_read();
      }

      return $success;
    }

    /**
     * Create a user account
     *
     * @param $username
     * @param $email_address
     * @param $email_validate_token
     * @param $firstname
     * @param $surname
     * @param $password
     * @param $password_hint
     * @param $ip_address
     */
    public function create_user($username,
                                $email_address, $email_validate_token,
                                $firstname, $surname,
                                $password, $password_hint,
                                $ip_address)
    {
                  $username = $this->sanitise($username);
             $email_address = $this->sanitise($email_address);
      $email_validate_token = $this->sanitise($email_validate_token);
                 $firstname = $this->sanitise($firstname);
                   $surname = $this->sanitise($surname);
                  $password = $this->sanitise($password);
             $password_hint = $this->sanitise($password_hint);
                $ip_address = $this->sanitise($ip_address);

      $sql = "CALL `CREATE_USER` ('$username', '$email_address', '$email_validate_token', '$firstname', '$surname', '$password', '$password_hint', '$ip_address');";
      $success = $this->execute($sql, 'write');

      return $success;
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
      $success = $this->execute($sql, 'write');

      return $success;
    }

    /**
     * Delete user by username
     *
     * @param $username Username of the user to delete
     */
    public function delete_user_by_username($username)
    {
      $username = $this->sanitise($username);

      $sql = "CALL `DELETE_USER_BY_USERNAME` ('$username');";
      $success = $this->execute($sql, 'write');

      return $success;
    }

    /**
     * Check whether a username is already already in the database
     * NB THIS IS PSEUDOCODE
     *
     * @param $username Username of the user to find
     * 
     * @return True if the username is taken false if it is not
     */
    public function username_taken($username)
    {
      $username = $this->sanitise($username);

      $sql = "CALL `GET_USER_BY_USERNAME` ('$username');";
      $records = $this->execute($sql, 'read');

      return $recods->count > 0;//TODO refactor the execute() to return read records
    }

    /**
     * Close the connection and set it to null
     */
    private function disconnect()
    {
      if($this->is_connected()) {
        $this->get_connection()->close();
        $this->set_connection(null);
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
