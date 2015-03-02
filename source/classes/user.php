<?php

  /**
   * Model for the the user who logs in and votes on art
   *
   * @author Adam Bowles <bowlesa@aston.ac.uk>
   */
  class user
  {
    // Object to perform database interaction
    private $database_controller;

    private $logged_in = false;

    // Data fields about the user
    private $id = -1;
    private $username = '';
    private $email_address = '';
    private $first_name = '';
    private $surname = '';
    //TODO the rest of these

    /**
     *
     */
    public function __construct()//$username, $email_address, $hashed_password, $firstname, $surname)
    {
      include(ROOT_DIRECTORY . "source/classes/database_controller.php");
      $this->set_database_controller(new database_controller());

      $this->get_from_SESSION();

//      var_dump($_SESSION);
    }

    /**
     *
     */
    private function get_from_SESSION()
    {
      if(isset($_SESSION['username']) &&
         isset($_SESSION['email']) &&
         isset($_SESSION['firstname']) &&
         isset($_SESSION['surname'])
        ) {
        $this->set_username($_SESSION['username']);
        $this->set_email_address($_SESSION['email']);
        $this->set_firstname($_SESSION['firstname']);
        $this->set_surname($_SESSION['surname']);

        $this->set_logged_in(true);
      }
    }

    /**
     *
     */
    private function write_to_SESSION()
    {
      $_SESSION['id'] = $this->get_id();
      $_SESSION['username'] = $this->get_username();
      $_SESSION['email'] = $this->get_email_address();
      $_SESSION['firstname'] = $this->get_firstname();
      $_SESSION['surname'] = $this->get_surname();
    }

    /**
     *
     */
    public function get_database_controller()
    {
      return $this->database_controller;
    }

    /**
     *
     */
    private function set_database_controller($new_database_controller)
    {
      return $this->database_controller = $new_database_controller;
    }

    /**
     *
     */
    private function get_id()
    {
//      echo('session: ' . $_SESSION['id'] . "variable: $this->id");
//      if(isset($_SESSION['id'])) {
//        return $_SESSION['id'];
//      } else {
//        return -1;
//      }
      return $this->id;
//      return $_SESSION['id'];
    }

    /**
     *
     */
    private function set_id($new_id)
    {
      $this->id = $new_id;

      if($this->is_logged_in()) {
        $_SESSION['id'] = $new_id;
      }
    }

    /**
     *
     */
    private function get_username()
    {
      return $this->username;
    }

    /**
     *
     */
    private function set_username($new_username)
    {
      $this->username = $new_username;

      if($this->is_logged_in()) {
        $_SESSION['username'] = $new_username;
      }
    }

    /**
     *
     */
    private function get_email_address()
    {
      return $this->email_address;
    }

    /**
     *
     */
    private function set_email_address($new_email_address)
    {
      $this->email_address = $new_email_address;

      if($this->is_logged_in()) {
        $_SESSION['email'] = $new_email_address;
      }
    }

    /**
     *
     */
    private function get_firstname()
    {
      return $this->firstname;
    }

    /**
     *
     */
    private function set_firstname($new_firstname)
    {
      $this->firstname = $new_firstname;

      if($this->is_logged_in()) {
        $_SESSION['firstname'] = $new_firstname;
      }
    }

    /**
     *
     */
    private function get_surname()
    {
      return $this->surname;
    }

    /**
     *
     */
    public function get_fullname()
    {
      return $this->get_firstname() . ' ' . $this->get_surname();
    }

    /**
     *
     */
    private function set_surname($new_surname)
    {
      $this->surname = $new_surname;

      if($this->is_logged_in()) {
        $_SESSION['surname'] = $new_surname;
      }
    }

    /**
     *
     */
    public function is_logged_in()
    {
      return $this->logged_in;
    }

    /**
     *
     */
    public function set_logged_in($new_logged_in)
    {
      return $this->logged_in = $new_logged_in;
    }

    /**
     *
     */
    public function register($username, $email_address, $email_validate_token, $firstname, $surname, $password, $password_hint, $ip_address)
    {
      $user_id = $this->get_database_controller()->create_user($username, $email_address, $email_validate_token, $firstname, $surname, $password, $password_hint, $ip_address);

      $this->set_id($user_id);

      $success = $this->get_id() > 0;

      return $success; //TODO Should this return success or the id?
    }

    /**
     *
     */
    public function verify_email_address($token)
    {
      $success = $this->get_database_controller()->verify_email_address($token);

      return $success;
    }

    /**
     *
     */
    public function get_user_by_id($user_id)
    {
      $record = $this->get_database_controller()->get_user_by_id($user_id);

//      $this->set_id($user_id);
//
//      $success = $this->get_id() > 0;

      return $record;
    }

    /**
     *
     */
    public function log_in($username, $password) //TODO
    {
      $result = $this->get_database_controller()->log_in($username, $password);

      if($result) {
        $this->set_id($result['user_id']);
        $this->set_username($result['username']);
        $this->set_email_address($result['email']);
//        $this->set_hashed_password($result['']);
        $this->set_firstname($result['firstname']);
        $this->set_surname($result['surname']);

        $this->write_to_SESSION();

        $this->set_logged_in(true);

        return true;
      } else {
        return false;
      }
    }

    /**
     *
     */
    public function log_out()
    {
        unset($_SESSION['id']);
        unset($_SESSION['username']);
        unset($_SESSION['email']);
        unset($_SESSION['firstname']);
        unset($_SESSION['surname']);
        session_destroy();
        $this->logged_in = false;
    }

    /**
     *
     */
    public function vote($art, $vote, $deliberation_time)
    {
      if($this->is_logged_in()) {
        $this->get_database_controller->vote($this->get_id(), $art, $vote, $deliberation_time);
      }
    }

    /**
     *
     */
    public function __toString()
    {
      if($this->is_logged_in()) {
        $str  = 'User ID: ' . $this->get_id() . ', ';
        $str .= 'Username: ' . $this->get_username() . ', ';
        $str .= 'Email Address: ' . $this->get_email() . ', ';
        $str .= 'First name: ' . $this->get_firstname() . ', ';
        $str .= 'Surname: ' . $this->get_surname();
        return $str;
      } else {
        return '';
      }
    }

    /**
     * 'Delete the user (set their deleted flag to 1)
     */
    public function delete()
    {
      $this->get_database_controller()->delete_user_by_id($this->get_id());
    }
  }
?>
