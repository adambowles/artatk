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
    private $password_hint; //string
    private $in_education; //boolean
    private $year_of_study; //integer
    private $degree_level; //string
    private $institution; //string
    private $field_of_study; //integer
    private $interested_in_art; //boolean
    private $art_appreciation_frequency; //integer

    /**
     * Create a new database interaction controller and store it
     * Attempt to retrieve user details from the session
     */
    public function __construct()
    {
      include(ROOT_DIRECTORY . "source/classes/database_controller.php");
      $this->set_database_controller(new database_controller());

      $this->get_from_SESSION();
    }

    /**
     * Retrieves SESSION variables and stores them in private fields
     */
    private function get_from_SESSION()
    {
      if(isset($_SESSION['id']) &&
         isset($_SESSION['username']) &&
         isset($_SESSION['email']) &&
         isset($_SESSION['firstname']) &&
         isset($_SESSION['surname']) &&
         isset($_SESSION['password_hint']) &&
         isset($_SESSION['in_education']) &&
         isset($_SESSION['year_of_study']) &&
         isset($_SESSION['degree_level']) &&
         isset($_SESSION['institution']) &&
         isset($_SESSION['field_of_study']) &&
         isset($_SESSION['interested_in_art']) &&
         isset($_SESSION['art_appreciation_frequency'])
        ) {
        $this->set_logged_in(true);

        $this->set_id($_SESSION['id']);
        $this->set_username($_SESSION['username']);
        $this->set_email_address($_SESSION['email']);
        $this->set_firstname($_SESSION['firstname']);
        $this->set_surname($_SESSION['surname']);
        $this->set_password_hint($_SESSION['password_hint']);
        $this->set_in_education($_SESSION['in_education']);
        $this->set_year_of_study($_SESSION['year_of_study']);
        $this->set_degree_level($_SESSION['degree_level']);
        $this->set_institution($_SESSION['institution']);
        $this->set_field_of_study($_SESSION['field_of_study']);
        $this->set_interested_in_art($_SESSION['interested_in_art']);
        $this->set_art_appreciation_frequency($_SESSION['art_appreciation_frequency']);
      }
    }

    /**
     * Writes the private fields to the SESSION
     */
    private function write_to_SESSION()
    {
      $_SESSION['id'] = $this->get_id();
      $_SESSION['username'] = $this->get_username();
      $_SESSION['email'] = $this->get_email_address();
      $_SESSION['firstname'] = $this->get_firstname();
      $_SESSION['surname'] = $this->get_surname();
      $_SESSION['password_hint'] = $this->get_password_hint();
      $_SESSION['in_education'] = $this->get_in_education();
      $_SESSION['year_of_study'] = $this->get_year_of_study();
      $_SESSION['degree_level'] = $this->get_degree_level();
      $_SESSION['institution'] = $this->get_institution();
      $_SESSION['field_of_study'] = $this->get_field_of_study();
      $_SESSION['interested_in_art'] = $this->get_interested_in_art();
      $_SESSION['art_appreciation_frequency'] = $this->get_art_appreciation_frequency();
    }

    /**
     * @return object of type database_controller()
     */
    public function get_database_controller()
    {
      return $this->database_controller;
    }

    /**
     * @param new object of type database_controller()
     */
    private function set_database_controller($new_database_controller)
    {
      return $this->database_controller = $new_database_controller;
    }

    /**
     * @return Integer of user ID
     */
    public function get_id()
    {
      return $this->id;
    }

    /**
     * @param Integer of user ID
     */
    private function set_id($new_id)
    {
      $this->id = $new_id;

      if($this->is_logged_in()) {
        $_SESSION['id'] = $new_id;
      }
    }

    /**
     * @return String of username
     */
    public function get_username()
    {
      return $this->username;
    }

    /**
     * @param String of username
     */
    private function set_username($new_username)
    {
      $this->username = $new_username;

      if($this->is_logged_in()) {
        $_SESSION['username'] = $new_username;
        //TODO-profile writeback the username to database
      }
    }

    /**
     * @return String of email address
     */
    public function get_email_address()
    {
      return $this->email_address;
    }

    /**
     * @param String of email address
     */
    private function set_email_address($new_email_address)
    {
      $this->email_address = $new_email_address;

      if($this->is_logged_in()) {
        $_SESSION['email'] = $new_email_address;
        //TODO-profile writeback the email address to database
      }
    }

    /**
     * @return String of first name
     */
    public function get_firstname()
    {
      return $this->firstname;
    }

    /**
     * @param String of first name
     */
    private function set_firstname($new_firstname)
    {
      $this->firstname = $new_firstname;

      if($this->is_logged_in()) {
        $_SESSION['firstname'] = $new_firstname;
        //TODO-profile writeback the firstname to database
      }
    }

    /**
     * @return String of surname
     */
    public function get_surname()
    {
      return $this->surname;
    }

    /**
     * @param String of surname
     */
    private function set_surname($new_surname)
    {
      $this->surname = $new_surname;

      if($this->is_logged_in()) {
        $_SESSION['surname'] = $new_surname;
        //TODO-profile writeback the surname to database
      }
    }

    /**
     * @return "firstname surname"
     */
    public function get_fullname()
    {
      return $this->get_firstname() . ' ' . $this->get_surname();
    }

    /**
     * @return String of user's password reminder
     */
    public function get_password_hint()
    {
      return $this->password_hint;
    }

    /**
     * @param String of user's password reminder
     */
    private function set_password_hint($new_password_hint)
    {
      $this->password_hint = $new_password_hint;

      if($this->is_logged_in()) {
        $_SESSION['password_hint'] = $new_password_hint;
        //TODO-profile writeback the password_hint to database
      }
    }

    /**
     * @return Boolean whether the user is at uni
     */
    public function get_in_education()
    {
      return $this->in_education;
    }

    /**
     * @param Boolean whether the user is at uni
     */
    private function set_in_education($new_in_education)
    {
      $this->in_education = $new_in_education;

      if($this->is_logged_in()) {
        $_SESSION['in_education'] = $new_in_education;
        //TODO-profile writeback the in_education to database
      }
    }

    /**
     * @return Integer of user's year of study if still at uni
     */
    public function get_year_of_study()
    {
      return $this->year_of_study;
    }

    /**
     * @param Integer of user's year of study if still at uni
     */
    private function set_year_of_study($new_year_of_study)
    {
      $this->year_of_study = $new_year_of_study;

      if($this->is_logged_in()) {
        $_SESSION['year_of_study'] = $new_year_of_study;
        //TODO-profile writeback the year_of_study to database
      }
    }

    /**
     * @return String of user's degree level if at uni
     */
    public function get_degree_level()
    {
      return $this->degree_level;
    }

    /**
     * @param String of user's degree level if at uni
     */
    private function set_degree_level($new_degree_level)
    {
      $this->degree_level = $new_degree_level;

      if($this->is_logged_in()) {
        $_SESSION['degree_level'] = $new_degree_level;
        //TODO-profile writeback the degree_level to database
      }
    }

    /**
     * @return String of user's university if at uni, or employer if not
     */
    public function get_institution()
    {
      return $this->institution;
    }

    /**
     * @param String of user's university if at uni, or employer if not
     */
    private function set_institution($new_institution)
    {
      $this->institution = $new_institution;

      if($this->is_logged_in()) {
        $_SESSION['institution'] = $new_institution;
        //TODO-profile writeback the institution to database
      }
    }

    /**
     * @return String of user's field of study if at uni, or employ if not
     */
    public function get_field_of_study()
    {
      return $this->field_of_study;
    }

    /**
     * @param String of user's field of study if at uni, or employ if not
     */
    private function set_field_of_study($new_field_of_study)
    {
      $this->field_of_study = $new_field_of_study;

      if($this->is_logged_in()) {
        $_SESSION['field_of_study'] = $new_field_of_study;
        //TODO-profile writeback the field_of_study to database
      }
    }

    /**
     * @return Boolean of whether the user has an interest in visual art
     */
    public function get_interested_in_art()
    {
      return $this->interested_in_art;
    }

    /**
     * @param Boolean of whether the user has an interest in visual art
     */
    private function set_interested_in_art($new_interested_in_art)
    {
      $this->interested_in_art = $new_interested_in_art;

      if($this->is_logged_in()) {
        $_SESSION['interested_in_art'] = $new_interested_in_art;
        //TODO-profile writeback the interested_in_art to database
      }
    }

    /**
     * @return Integer of number of visits to an art exhibition in last year
     */
    public function get_art_appreciation_frequency()
    {
      return $this->art_appreciation_frequency;
    }

    /**
     * @param Integer of number of visits to an art exhibition in last year
     */
    private function set_art_appreciation_frequency($new_art_appreciation_frequency)
    {
      $this->art_appreciation_frequency = $new_art_appreciation_frequency;

      if($this->is_logged_in()) {
        $_SESSION['art_appreciation_frequency'] = $new_art_appreciation_frequency;
        //TODO-profile writeback the art_appreciation_frequency to database
      }
    }

    /**
     * @return boolean logged in status
     */
    public function is_logged_in()
    {
      return $this->logged_in;
    }

    /**
     * @param logged in boolean status
     */
    public function set_logged_in($new_logged_in)
    {
      return $this->logged_in = $new_logged_in;
    }

    /**
     * //TODO this probably isn't really in the scope of this project
     */
    public function is_admin()
    {
      // $is_admin = $this->get_database_controller()->is_admin($this->get_id());
      return $is_admin;
    }

    /**
     * Sends account creation data to database
     * @param $username Username of user (their  public facing unique identifier)
     * @param $email_address User's email address
     * @param $email_validate_token Random string that will be sent to their email address so they can prove the have access to it
     * @param $firstname First name of user
     * @param $surname Surname of user
     * @param $password Password (This will be hashed)
     * @param $password_hint Hint used to remind user what their password is
     * @param $ip_address IP address from which the user registered (For detecting intruders)
     * @param $in_education 1 or 0 whether the user is in education
     * @param $year_of_study 1,2,3 etc
     * @param $degree_level Undergrad, Bachelor's, Master's, PhD, etc
     * @param $institution University or employer
     * @param $field_of_study Field of study or employ
     * @param $interested_in_art 1 or 0 whether the user is interested in visual art
     * @param $art_appreciation_frequency number of times in last year the user visited art exhibitions
     * @return true user was successfully inserted
     */
    public function register($username,
                             $email_address, $email_validate_token,
                             $firstname, $surname,
                             $password, $password_hint,
                             $ip_address,
                             $in_education, $year_of_study, $degree_level,
                             $institution, $field_of_study,
                             $interested_in_art, $art_appreciation_frequency)
    {
      $user_id = $this->get_database_controller()->create_user($username,
                                                               $email_address, $email_validate_token,
                                                               $firstname, $surname,
                                                               $password, $password_hint,
                                                               $ip_address,
                                                               $in_education, $year_of_study, $degree_level,
                                                               $institution, $field_of_study,
                                                               $interested_in_art, $art_appreciation_frequency);

//      $this->set_id($user_id);
//
//      $success = $this->get_id() > 0;
      $success = $user_id > 0;

      return $success;
    }

    /**
     * Checks database for an email token
     * @param email validation token to find in database
     * @return true if token exists and validted flag set true
     */
    public function verify_email_address($token)
    {
      $success = $this->get_database_controller()->verify_email_address($token);

      return $success;
    }

    /**
     * //TODO I don't think this is used
     */
    public function get_user_by_id($user_id)
    {
      $record = $this->get_database_controller()->get_user_by_id($user_id);

      return $record;
    }

    /**
     * Checks to see if login credentials match a user in the database
     * If a match is found, load that data into the session and return true
     * If no match is found, return false
     * (Does not differentiate between incorrect user or pass, only success or failure)
     */
    public function log_in($username, $password)
    {
      $result = $this->get_database_controller()->log_in($username, $password);

      if($result) {
        $this->set_logged_in(true);

        $this->set_id($result['user_id']);
        $this->set_username($result['username']);
        $this->set_email_address($result['email']);
        $this->set_firstname($result['firstname']);
        $this->set_surname($result['surname']);
        $this->set_password_hint($result['password_hint']);
        $this->set_in_education($result['in_education']);
        $this->set_year_of_study($result['year_of_study']);
        $this->set_degree_level($result['degree_level']);
        $this->set_institution($result['institution']);
        $this->set_field_of_study($result['field_of_study']);
        $this->set_interested_in_art($result['interested_in_art']);
        $this->set_art_appreciation_frequency($result['art_appreciation_frequency']);

        return true;
      } else {
        return false;
      }
    }

    /**
     * Removes stored data from the session and destroys it
     */
    public function log_out()
    {
      $this->logged_in = false;

      unset($_SESSION['id']);
      unset($_SESSION['username']);
      unset($_SESSION['email']);
      unset($_SESSION['firstname']);
      unset($_SESSION['surname']);
      unset($_SESSION['password_hint']);
      unset($_SESSION['in_education']);
      unset($_SESSION['year_of_study']);
      unset($_SESSION['degree_level']);
      unset($_SESSION['institution']);
      unset($_SESSION['field_of_study']);
      unset($_SESSION['interested_in_art']);
      unset($_SESSION['art_appreciation_frequency']);

      session_destroy();
    }

    /**
     * Sends vote data to database
     * @param $art ID of art piece
     * @param $vote Vote value, 1-5
     * @param $deliberation_time Time taken to cast that vote
     */
    public function vote($art, $vote, $deliberation_time)
    {
      if($this->is_logged_in()) {
        if(!$this->has_voted_on_image($art)){
          $this->get_database_controller()->vote($this->get_id(), $art, $vote, $deliberation_time);
        }
      }
    }

    /**
     * Check database to see if a user has already voted on a given art piece
     * @param $art_id ID of art piece to check vote against
     * @return true if a user has already cast a vote on a given art piece, false otherwise
     */
    public function has_voted_on_image($art_id)
    {
      if($this->is_logged_in()) {
        return $this->get_database_controller()->has_voted_on_image($this->get_id(), $art_id);
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
     * Ideally this doesn't go in this class, but there isn't a place for general database queries
     */
    public function get_training_set_size()
    {
      $size = $this->get_database_controller()->get_training_set_size();

      return $size;
    }

    /**
     *
     */
    public function __toString()
    {
      if($this->is_logged_in()) {
        $str  = 'User ID: ' . $this->get_id() . ', ' .
                'Username: ' . $this->get_username() . ', ' .
                'Email Address: ' . $this->get_email_address() . ', ' .
                'First name: ' . $this->get_firstname() . ', ' .
                'Surname: ' . $this->get_surname() . ', ' .
                'Password hint: ' . $this->get_password_hint() . ', ' .
                'In education: ' . $this->get_in_education() . ', ' .
                'Year of study: ' . $this->get_year_of_study() . ', ' .
                'Degree level: ' . $this->get_degree_level() . ', ' .
                'Institution: ' . $this->get_institution() . ', ' .
                'Field of study: ' . $this->get_field_of_study() . ', ' .
                'Interested in art: ' . $this->get_interested_in_art() . ', ' .
                'Art appreciation frequency: ' . $this->get_art_appreciation_frequency();

        return $str;
      } else {
        return '';
      }
    }

    /**
     * Delete the user (set their deleted flag to 1)
     */
    public function delete()
    {
      $this->get_database_controller()->delete_user_by_id($this->get_id());
    }
  }
?>
