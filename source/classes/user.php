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
    private $id; //integer
    private $username; //string
    private $email_address; //string
    private $first_name; //string
    private $surname; //string
    private $password_hint; //string //TODO getter/setter
    private $in_education; //boolean //TODO getter/setter
    private $year_of_study; //integer //TODO getter/setter
    private $degree_level; //string //TODO getter/setter
    private $institution; //string //TODO getter/setter
    private $field_of_study; //integer //TODO getter/setter
    private $interested_in_art; //boolean //TODO getter/setter
    private $art_appreciation_frequency; //integer //TODO getter/setter

    /**
     *
     */
    public function __construct()
    {
      include(ROOT_DIRECTORY . "source/classes/database_controller.php");
      $this->set_database_controller(new database_controller());

      $this->get_from_SESSION();
    }

    /**
     *
     */
    private function get_from_SESSION()
    {
      if(isset($_SESSION['id']) &&
         isset($_SESSION['username']) &&
         isset($_SESSION['email']) &&
         isset($_SESSION['firstname']) &&
         isset($_SESSION['surname'])
        ) {
        $this->set_id($_SESSION['id']);
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
    public function get_id()
    {
      if($this->is_logged_in()) {
        return $this->id;
      } else {
        return '';
      }
    }

    /**
     *
     */
    private function set_id($new_id)
    {
      $this->id = $new_id;

      if($this->is_logged_in()) {
        $_SESSION['id'] = $new_id;
        //TODO writeback the firstname to database
      }
    }

    /**
     *
     */
    private function get_username()
    {
      if($this->is_logged_in()) {
        return $this->username;
      } else {
        return '';
      }
    }

    /**
     *
     */
    private function set_username($new_username)
    {
      $this->username = $new_username;

      if($this->is_logged_in()) {
        $_SESSION['username'] = $new_username;
        //TODO writeback the firstname to database
      }
    }

    /**
     *
     */
    private function get_email_address()
    {
      if($this->is_logged_in()) {
        return $this->email_address;
      } else {
        return '';
      }
    }

    /**
     *
     */
    private function set_email_address($new_email_address)
    {
      $this->email_address = $new_email_address;

      if($this->is_logged_in()) {
        $_SESSION['email'] = $new_email_address;
        //TODO writeback the firstname to database
      }
    }

    /**
     *
     */
    private function get_firstname()
    {
      if($this->is_logged_in()) {
        return $this->firstname;
      } else {
        return '';
      }
    }

    /**
     *
     */
    private function set_firstname($new_firstname)
    {
      $this->firstname = $new_firstname;

      if($this->is_logged_in()) {
        $_SESSION['firstname'] = $new_firstname;
        //TODO writeback the firstname to database
      }
    }

    /**
     *
     */
    private function get_surname()
    {
      if($this->is_logged_in()) {
        return $this->surname;
      } else {
        return '';
      }
    }

    /**
     *
     */
    private function set_surname($new_surname)
    {
      $this->surname = $new_surname;

      if($this->is_logged_in()) {
        $_SESSION['surname'] = $new_surname;
        //TODO writeback the firstname to database
      }
    }

    /**
     *
     */
    public function get_fullname()
    {
      if($this->is_logged_in()) {
        return $this->get_firstname() . ' ' . $this->get_surname();
      } else {
        return '';
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
    public function is_admin()
    {
      //TODO properly code admin status
      return true;
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

      return $record;
    }

    /**
     *
     */
    public function log_in($username, $password)
    {
      $result = $this->get_database_controller()->log_in($username, $password);

      if($result) {
        $this->set_id($result['user_id']);
        $this->set_username($result['username']);
        $this->set_email_address($result['email']);
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
        $this->get_database_controller()->vote($this->get_id(), $art, $vote, $deliberation_time);
      }
    }

    /**
     *
     */
    public function get_next_image()
    {
      if($this->is_logged_in()) {
        $image = $this->get_database_controller()->get_next_image($this->get_id());
        //TODO cleanse art filenames of accents, see trello
  //       foreach ($image as $key => $value) {
  // //        $image[$key] = urlencode($image[$key]);
  //         $image[$key] = str_replace('%', '%25', $image[$key]);
  //       }
        return $image;
      }
    }

    /**
     *
     */
    public function get_number_of_votes()
    {
      $count = $this->get_database_controller()->get_number_of_votes($this->get_id());

      return $count;
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
