<?php

  /**
   * Tips for writing functions in this class.
   * Security and attention to what you're writing here is very important
   *
   * Start each method off by sanitising it's parameters. Help avoid SQL injections
   *
   * Only connect to the database write privilege user account when you /need/ to write data
   * A method that only reads data and does not use the write user cannot be SQL inject attacked to drop data
   * Be sure to switch back to the database read privilege user account once writing is done
   *
   * @author Adam Bowles <bowlesa@aston.ac.uk>
   */
  class database_controller
  {

    private $connection = null;

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
      $this->connect_generic(database_url, database_dbname, database_read_user, database_read_password);
    }

    /**
     *
     */
    private function connect_write()
    {
      $this->connect_generic(database_url, database_dbname, database_write_user, database_write_password);
    }

    /**
     *
     */
    private function connect_generic($url, $db, $user, $pass)
    {
      if($this->is_connected()){
        $this->disconnect();
      }

      try {
        $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);
        $this->set_connection(new PDO("mysql:host=$url;dbname=$db", $user, $pass, $options));
//        $this->get_connection()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
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
        return $this->get_connection()->quote(trim($data));
      } else {
        return null;
      }
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
     *
     * @return ID of record inserted
     */
    public function create_user($username,
                                $email_address, $email_validate_token,
                                $firstname, $surname,
                                $password, $password_hint,
                                $ip_address,
                                $in_education, $year_of_study, $degree_level,
                                $institution, $field_of_study,
                                $interested_in_art, $art_appreciation_frequency)
    {
                        $username = $this->sanitise($username);
                   $email_address = $this->sanitise($email_address);
            $email_validate_token = $this->sanitise($email_validate_token);
                       $firstname = $this->sanitise($firstname);
                         $surname = $this->sanitise($surname);
                        $password = $this->sanitise(password_hash($password, PASSWORD_DEFAULT));
                   $password_hint = $this->sanitise($password_hint);
                      $ip_address = $this->sanitise($ip_address);
                    $in_education = $this->sanitise($in_education);
                   $year_of_study = $this->sanitise($year_of_study);
                    $degree_level = $this->sanitise($degree_level);
                     $institution = $this->sanitise($institution);
                  $field_of_study = $this->sanitise($field_of_study);
               $interested_in_art = $this->sanitise($interested_in_art);
      $art_appreciation_frequency = $this->sanitise($art_appreciation_frequency);

      $sql = "INSERT INTO `artatk_user` (
                `username`,
                `email`, `email_validate_token`,
                `firstname`, `surname`,
                `hashed_password`, `password_hint`,
                `registered_ip_address`,
                `in_education`, `year_of_study`, `degree_level`,
                `institution`, `field_of_study`,
                `interested_in_art`, `art_appreciation_frequency`
              ) VALUES (
                $username,
                $email_address, $email_validate_token,
                $firstname, $surname,
                $password, $password_hint,
                $ip_address,
                $in_education, $year_of_study, $degree_level,
                $institution, $field_of_study,
                $interested_in_art, $art_appreciation_frequency
              )";

      $this->connect_write();

      $statement = $this->get_connection()->prepare($sql);
      $statement->execute();

      $last_id = $this->get_connection()->lastInsertId();

      $this->connect_read();

      return $last_id;
    }

    /**
     * Create a user account
     *
     * @param $token
     *
     * @return ID of record inserted
     */
    public function verify_email_address($token)
    {
      $token = $this->sanitise($token);

      $sql = "UPDATE `artatk_user` SET `email_validated`=1 WHERE `email_validate_token` = $token";

      $this->connect_write();

      try {
        $statement = $this->get_connection()->prepare($sql);
        $statement->execute();
      } catch(PDOException $e) {
        $this->connect_read();
        return false;
      }

      $this->connect_read();

      return true;
    }

    /**
     * @param $user_id ID to fetch by
     *
     * @return Record associative array
     */
    public function get_user_by_id($user_id)
    {
      $user_id = $this->sanitise($user_id);

      $sql = "SELECT * FROM `artatk_user` WHERE `user_id` = $user_id";

      $statement = $this->get_connection()->prepare($sql);
      $statement->execute();

      $record = $statement->fetch(); // Fetch single row

      return $record;
    }

    /**
     * //TODO
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
     * //TODO
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
     * Check whether a field is already taken in the database
     * Used, for example, to check if a username has not already been taken
     *  or whether someone has already registered with a given email address
     *
     * @param $data data to check
     * @param $as type to check
     *
     * @return True if the data is available
     */
    public function check_availability($data, $as)
    {
      $data = strtolower($this->sanitise($data));
      $as = trim($this->sanitise($as), "'");

      $sql = "SELECT `user_id` FROM `artatk_user` WHERE lower(`$as`) = $data";

      $statement = $this->get_connection()->prepare($sql);
      $statement->execute();

      $found = $statement->rowCount(); // Fetch single row

      return $found < 1;
    }

    /**
     * //TODO
     */
    public function log_in($username, $password)
    {
      $username = $this->sanitise($username);

      $sql = "SELECT * FROM `artatk_user` WHERE `username` = $username";

      $statement = $this->get_connection()->prepare($sql);
      $statement->execute();

      $row = $statement->fetch(); // Fetch single row

      if(password_verify($password, $row['hashed_password'])) {

        $this->update_last_login($row['user_id']);

        return $row;
      } else {
        return false;
      }
    }

    /**
     * //TODO
     */
    private function update_last_login($user_id)
    {
      $sql = "UPDATE `artatk_user` SET `last_login` = CURRENT_TIMESTAMP WHERE `artatk_user`.`user_id` = $user_id;";

      $this->connect_write();

      $statement = $this->get_connection()->prepare($sql);
      $statement->execute();

      $this->connect_read();
    }

    /**
     * //TODO
     */
    public function vote($user_id, $art_id, $vote, $deliberation_time)
    {
      $user_id = $this->sanitise($user_id);
      $art_id = $this->sanitise($art_id);
      $vote = $this->sanitise($vote);
      $deliberation_time = $this->sanitise($deliberation_time);

      $sql = "INSERT INTO `artatk_vote` (`user_id`, `art_id`, `vote`, `deliberation_time`) VALUES ($user_id, $art_id, $vote, $deliberation_time)";

      $this->connect_write();

      $statement = $this->get_connection()->prepare($sql);
      $statement->execute();

      $this->connect_read();
    }

    /**
     * Returns whether a user has already voted on an image or not
     */
    public function has_voted_on_image($user_id, $art_id)
    {
      $user_id = $this->sanitise($user_id);
      $art_id = $this->sanitise($art_id);

      $sql = "SELECT count(`vote_id`) as count FROM `artatk_vote` WHERE `art_id` = $art_id and `user_id` = $user_id";

      $statement = $this->get_connection()->prepare($sql);
      $statement->execute();

      $voted = $statement->fetch()['count']; // Fetch single row

      return $voted == 1;
    }

    /**
     * //TODO
     */
    public function get_number_of_votes($user_id)
    {
      $user_id = $this->sanitise($user_id);

      $sql = "SELECT count(`user_id`) AS count FROM `artatk_vote` WHERE `user_id` = $user_id;";
      $statement = $this->get_connection()->prepare($sql);

      $statement->execute();

      $count = $statement->fetch()['count']; // Fetch single row

      return $count;
    }

    /**
     * Gets a random art item from the training set that the specified user has not voted on
     *
     * @return Associative array of art id and the local path
     */
    public function get_next_image($user_id)
    {
      $user_id = $this->sanitise($user_id);

//      $sql = "SELECT `artatk_art`.`art_id` , `artatk_art`.`local_path`
//              FROM `artatk_art`
//              LEFT JOIN `artatk_vote` ON `artatk_art`.`art_id` = `artatk_vote`.`art_id`
//              WHERE ((`artatk_vote`.`user_id` IS NULL
//              OR `artatk_vote`.`user_id` <> $user_id)
//              AND `artatk_art`.`training_set` = 1)
//              ORDER BY RAND()
//              LIMIT 1;";
      $sql = "SELECT `art_id`, `local_path`
              FROM `artatk_art`
              WHERE `artatk_art`.`training_set` = 1
              and `artatk_art`.`art_id` NOT IN
              (
                SELECT `art_id`
                FROM `artatk_vote`
                WHERE `artatk_vote`.`user_id` = $user_id
              )

              ORDER BY RAND($user_id)
              LIMIT 1;";

      $statement = $this->get_connection()->prepare($sql);
      $statement->execute();

      $record = $statement->fetch(); // Fetch single row

      return $record;
    }

    /**
     * Gets the number of art items that are flagged as being in the training set
     *
     * @return Integer of training set size
     */
    public function get_training_set_size()
    {
      $sql = "SELECT count(*) as `count` FROM `artatk_art` WHERE `training_set` = 1;";

      $statement = $this->get_connection()->prepare($sql);
      $statement->execute();

      $size = $statement->fetch()['count']; // Fetch single row

      return $size;
    }

    /**
     * Close the connection and set it to null
     */
    private function disconnect()
    {
      if($this->is_connected()) {
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
