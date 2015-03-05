<?php

  /**
   * @author Adam Bowles <bowlesa@aston.ac.uk>
   */
  class page
  {

    // Some funcationality controllers
    private $user; // Person who is logged in
    private $asset_controller;
    private $parsedown;

    // Strings of page content, append whatever you like in order to add it to the page
    // (hint: use the add_header()/add_body()/add_footer() funcs)
    private $head;
    private $body;
    private $footer;
    private $extra_scripts;

    // Some page settings
    private $charset = "UTF-8";
    private $title;

    /**
     *
     */
    public function __construct()
    {
      session_start();

      include(ROOT_DIRECTORY . "source/classes/user.php");
      $this->set_user(new user());

      include(ROOT_DIRECTORY . "source/libraries/swift_mailer/swift_required.php");

      include(ROOT_DIRECTORY . "source/classes/html_asset_controller.php");
      $this->set_asset_controller(new html_asset_controller());

      include(ROOT_DIRECTORY . "source/libraries/Parsedown.php");
      $this->set_parsedown(new Parsedown());
    }

    /**
     * Returns the page as contiguous HTML string
     */
    public function get_html()
    {
      return $this->construct_head() .
        $this->construct_body() .
        $this->construct_footer();
    }

    /**
     * Helper method to print the HTML string directly
     * Keeps object code tidier
     */
    public function print_html()
    {
      echo($this->get_html());
    }

    /**
     *
     */
    public function get_user()
    {
      return $this->user;
    }

    /**
     *
     */
    public function set_user($new_user)
    {
      return $this->user = $new_user;
    }

    /**
     *
     */
//    public function get_emailer()
//    {
//      return $this->emailer;
//    }
//
//    /**
//     *
//     */
//    public function set_emailer($new_emailer)
//    {
//      return $this->emailer = $new_emailer;
//    }

    /**
     *
     */
    public function get_asset_controller()
    {
      return $this->asset_controller;
    }

    /**
     *
     */
    public function set_asset_controller($new_asset_controller)
    {
      return $this->asset_controller = $new_asset_controller;
    }

    /**
     *
     */
    public function get_parsedown()
    {
      return $this->parsedown;
    }

    /**
     *
     */
    public function set_parsedown($new_parsedown)
    {
      return $this->parsedown = $new_parsedown;
    }

    /**
     *
     */
    private function construct_head()
    {
      return "<!doctype html>" .
             "<html>" .
             "<head>" .
               $this->get_charset_meta_tag() .
               "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1, maximum-scale=1\">" .
               "<title>" .
                 WEBSITE_TITLE . $this->get_title() .
               "</title>" .
               $this->asset_controller->get_bootstrap_css() .
               $this->asset_controller->get_fontawesome_css() .
               $this->asset_controller->get_css() .
               $this->head .
             "</head>" .
             "<body>" .
               "<header>" .
                 $this->get_navbar() .
               "</header>" .
               "<div id=\"content\" class=\"container\">"
        ;
    }

    /**
     *
     */
    public function get_title()
    {
      if ($this->title != "") {
        return " &middot " . $this->title;
      } else {
        return "";
      }
    }

    /**
     * //TODO make this a bit more smart
     * e.g. add_page_navlink() for example will add to here, something like that
     */
    public function get_navbar()
    {
      $navbar_string = '<nav class="navbar navbar-default navbar-fixed-top">
                          <div class="container">
                            <div class="navbar-header">
                              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                              </button>
                              <a class="navbar-brand" href="/"><i class="fa fa-paint-brush"></i></a>
                            </div>
                            <div id="navbar" class="collapse navbar-collapse">
                              <ul class="nav navbar-nav">
                                <li><a href="/rate.php"><i class="fa fa-star-half-o"></i> Rate</a></li>
                                <li><a href="/recommendation.php"><i class="fa fa-photo"></i> Get your recommendation</a></li>
                              </ul>';

      if($this->get_user()->is_logged_in()) {
        $navbar_string .= '<ul class="nav navbar-nav navbar-right">
                             <li class="dropdown">
                               <a href="#" class="dropdown-toggle" data-toggle="dropdown">' . $this->get_user()->get_fullname() . ' <b class="caret"></b></a>
                               <ul class="dropdown-menu" role="menu">
                                 <li><a href="#"><i class="fa fa-user"></i> Edit profile</a></li>
                                 <li class="divider"></li>
                                 <li><a href="/logout.php"><i class="fa fa-sign-out"></i> Log out</a></li>
                               </ul>
                             </li>
                           </ul>';
      } else {
        $navbar_string .= '<ul class="nav navbar-nav navbar-right">
                             <li><a href="/login.php"><i class="fa fa-sign-in"></i> Log in</a></li>
                             <li><a href="/register.php"><i class="fa fa-user-plus"></i> Register</a></li>
                           </ul>';
      }

      $navbar_string .=    '</div><!--/.nav-collapse -->
                          </div>
                        </nav>';
      return $navbar_string;
    }

    /**
     * Set the page title ("login page" appears like "ArtAtk! · login page")
     */
    public function set_title($new_title)
    {
      $this->title = $new_title;
    }

    /**
     * //TODO
     * Set entire title (so that page does not show the ArtAtk prefix, see set_title() for info)
     */
    public function set_whole_title($new_title)
    {
      $this->title = $new_title;
    }

    /**
     *
     */
    public function add_head($content)
    {
      $this->head = $this->head . $content;
    }

    /**
     *
     */
    private function construct_body()
    {
      return $this->body;
    }

    /**
     * Use add_body(content) to put markdown content on page
     */
    public function add_body($content, $markdown = true)
    {
      if($markdown) {
        $this->body = $this->body . $this->get_parsedown()->text($content);
      } else {
        $this->body = $this->body . $content;
      }
    }

    /**
     *
     */
    private function construct_footer()
    {
      return     '</div>' .
                 '<div class="container copyright-footer">' .
                   '<div class="copyright">' .
                     '<p class="copyright-text">&copy; 2015 Adam Bowles</p>' .
                     $this->footer .
                   '</div>' .
                 '</div>' .
                 $this->asset_controller->get_jQuery() .
                 $this->asset_controller->get_bootstrap_js() .
                 $this->asset_controller->get_recaptcha_js() .
                 $this->asset_controller->get_js() .
                 $this->extra_scripts .
               '</body>' .
             '</html>'
        ;
    }

    /**
     *
     */
    public function add_footer($content)
    {
      $this->footer = $this->footer . $content;
    }

    /**
     * assume path from assets/js folder
     */
    public function add_extra_script($script)
    {
      $this->extra_scripts .= $script;
    }

    /**
     *
     */
    public function set_charset($new_charset)
    {
      $this->charset = $new_charset;
    }

    /**
     *
     */
    public function get_charset()
    {
      return $this->charset;
    }

    /**
     *
     */
    private function get_charset_meta_tag()
    {
      return '<meta charset="' . $this->get_charset() . '">';
    }

    /**
     * Send an email using SwiftMailer
     */
    protected function send_email($subject, $to_email, $to_name, $body)
    {
      // GMail credentials
      $transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
        ->setUsername(gmail_username)
        ->setPassword(gmail_password);

      $mailer = Swift_Mailer::newInstance($transporter);

      // Create the message
      $message = Swift_Message::newInstance()

        // Give the message a subject
        ->setSubject($subject)

        // Set the From address with an associative array
        ->setFrom(array(gmail_username . "@gmail.com" => "ArtAtk"))

        // Set the To addresses with an associative array
        ->setTo($to_email)

        // Give it a body
        ->setBody("Hello, $to_name\n\n$body\n\nFrom ArtAtk")
        ;

      // Send
      return $mailer->Send($message);
    }

    /**
     * Send an email with an email confirmation token
     */
    protected function send_email_verification_email($to_email, $to_name, $token)
    {
      $subject = 'Thanks for registering with ArtAtk';

      $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';

      $body = "Thanks for registering with Artatk!\n\n" .
              "To confirm your email address, just click this link:\n\n" .
              $protocol . $_SERVER['HTTP_HOST'] . "/verify.php?token=$token";
      $this->send_email($subject, $to_email, $to_name, $body);
    }

  }

  // The following subclasses are bascially just normal pages with some handy default content added

  /**
   *
   */
  class front_page extends page
  {
    public function __construct(){
      // Perform a superclass construction
      parent::__construct();

      if($this->get_user()->is_logged_in()) {
        $follow = '/rate.php';
      } else {
        $follow = '/register.php';
      }

      $this->add_body('<div class="row well">
                         <div class="col-xs-12 col-sm-6 col-md-8">
                           <h1>ArtAtk!</h1>
                           <p class="lead">ArtAtk (Art attack) is an artistic recommendation engine. It will learn your tatse in art aesthetic and deduce the type of art you like!</p>
                           <p class="lead">Training takes as little as ten minutes, what are you waiting for?</p>
                           <p><a class="btn btn-lg btn-info" href="' . $follow . '">Get started <i class="fa fa-arrow-right"></i></a></p>
                         </div>
                         <div class="col-xs-12 col-sm-6 col-md-4">
                           <img class="img-responsive img-thumbnail" src="http://i.imgur.com/681E4El.jpg">
                         </div>
                       </div>');
      $this->add_body('<div class="row">
                         <div class="col-xs-12 text-center">');
      $this->add_body('Nulla id turpis sit amet enim commodo dapibus. Ut at nunc justo. Aenean dignissim turpis iaculis dolor dignissim, sit amet commodo felis gravida. Suspendisse quis lectus sed est fermentum vulputate et nec nunc. Praesent tempor, massa id rhoncus luctus, sapien odio faucibus felis, quis pulvinar odio urna ut augue. Nunc ut justo nec lacus efficitur vestibulum sit amet a mauris. Cras lacinia enim id diam ultricies elementum. Donec blandit pretium aliquet. Phasellus elementum sapien et eros dignissim, non hendrerit leo convallis. Sed nunc velit, interdum at elementum eu, posuere vel justo.');
      $this->add_body('  </div>
                       </div>');
    }
  }

  /**
   *
   */
  class rating_page extends page
  {
    public function __construct(){
      // Perform a superclass construction
      parent::__construct();

      $this->set_title('Rate');

      if($this->get_user()->is_logged_in()) {

        if($this->vote_cast()) {
          $image_id = $_GET['image_id'];
          $vote = $_GET['vote'];
          $deliberation_time = $_GET['delib_time'];
  //        $this->get_user()->vote($image_id, $vote, $deliberation_time);
//          $this->add_body("You voted: $vote for image: $image_id and thought about it for $deliberation_time ms");
        }

  //      $number_previous_votes = $this->get_user()->get_number_of_votes();
  //      $next_image = $this->get_user()->get_next_image();

        $this->add_body('<div class="row text-center">

                           <div class="col-xs-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
                             <img class="img-responsive img-thumbnail" src="http://i.imgur.com/681E4El.jpg" height="500px" style="max-height:500px">
                           </div>

                           <div class="col-xs-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
                             <div class="btn-group" role="group" aria-label="...">
                               <p class="lead">Image 1/50</p>
                             </div>
                           </div>

                           <div class="col-xs-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
                             <h2><a href="?image_id=1&vote=1" title="1 star" id="star1" class="vote"><i class="fa fa-star-o"></i></a><a href="?image_id=1&vote=2" title="2 stars" id="star2" class="vote"><i class="fa fa-star-o"></i></a><a href="?image_id=1&vote=3" title="3 stars" id="star3" class="vote"><i class="fa fa-star-o"></i></a><a href="?image_id=1&vote=4" title="4 stars" id="star4" class="vote"><i class="fa fa-star-o"></i></a><a href="?image_id=1&vote=5" title="5 stars" id="star5" class="vote"><i class="fa fa-star-o"></i></a></h2>
                           </div>

                         </div>');
        $this->add_extra_script($this->get_asset_controller()->get_specific_asset('js/vote/vote.js'));
      } else { // not logged in

        //TODO change the demo image to a local one
        $this->add_body('<div class="row text-center">
        <div class="col-xs-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
          <img class="img-responsive img-thumbnail" src="http://i.imgur.com/681E4El.jpg" height="500px" style="max-height:500px">
        </div>

        <div class="col-xs-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
          <div class="btn-group" role="group" aria-label="...">
            <p class="lead">Image 1/50</p>
          </div>
        </div>

        <div class="col-xs-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
          <h2><a href="/register.php" title="1 star" id="star1" class="vote"><i class="fa fa-star-o"></i></a><a href="/register.php" title="2 stars" id="star2" class="vote"><i class="fa fa-star-o"></i></a><a href="/register.php" title="3 stars" id="star3" class="vote"><i class="fa fa-star-o"></i></a><a href="/register.php" title="4 stars" id="star4" class="vote"><i class="fa fa-star-o"></i></a><a href="/register.php" title="5 stars" id="star5" class="vote"><i class="fa fa-star-o"></i></a></h2>
        </div>

      </div>');
      }
    }

    private function vote_cast()
    {
      if(!isset($_GET['image_id'])) {
        return false;
      }
      if(!isset($_GET['vote'])) {
        return false;
      }
      if(!isset($_GET['delib_time'])) {
        return false;
      }
      if(!is_numeric($_GET['vote'])) {
        return false;
      }
      if(!($_GET['vote'] >= 1 && $_GET['vote'] <= 5)) {
        return false;
      }
      if(!is_numeric($_GET['delib_time'])) {
        return false;
      }
      return true;
    }

  } // Rating

  /**
   *
   */
  class recommendation_page extends page
  {
    public function __construct(){
      // Perform a superclass construction
      parent::__construct();

      $this->set_title('Get recommendation');

      $this->add_body('<div class="row text-center">');

      $this->add_body('<p class="lead">This isn\'t quite ready yet</p>');
      $this->add_body('<p class="lead">I\'ll send you an email when it\'s done</p>');

      $this->add_body('</div>');

    }
  } // Recommendation

  /**
   *
   */
  class register_page extends page
  {
    public function __construct(){
      // Perform a superclass construction
      parent::__construct();

      $this->set_title('Register');

      if($this->validate_registration_form()) {

        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $email_validate_token = sha1(trim($_POST['email']));
        $firstname = trim($_POST['firstname']);
        $surname = trim($_POST['surname']);
        $password = trim($_POST['password']);
        $password_hint = trim($_POST['password_hint']);
        $ip_address = $_SERVER['REMOTE_ADDR'];

        $username_available = $this->get_user()->get_database_controller()->check_availability($username, 'username');
        $email_available = $this->get_user()->get_database_controller()->check_availability($email, 'email');

        $this->add_body('<div class="row text-center">');
        $this->add_body('  <div class="col-lg-12">');

        if($username_available && $email_available) {
          $registration_success = $this->get_user()->register($username,
                                                              $email, $email_validate_token,
                                                              $firstname, $surname,
                                                              $password, $password_hint,
                                                              $ip_address);
        } else {
          $registration_success = false;
        }

        $is_human = $this->check_recaptcha();

        if($registration_success && $is_human) {
          $email = $email;
          $full_name = $firstname . ' ' . $surname;
          $token = $email_validate_token;

          $this->send_email_verification_email($email, $full_name, $token);

          $this->add_body('#Account created!');
          $this->add_body('We\'ve sent an email to ' . $email . ', just click on the link in the email to complete registration');
          $this->add_body('[Log in](/login.php)');
        } else {
          $this->add_body('#There was an error :(');

          if(!$username_available) {
            $this->add_body('Username already in use');
          }
          if(!$email_available) {
            $this->add_body('Email address already in use');
          }
          $this->add_body('[Back to registration form](/register.php)');
        }

        $this->add_body('  </div>');
        $this->add_body('</div>');
      } else {

        $this->add_body('<div class="row text-center">');
        $this->add_body('  <div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12">');

        $this->add_body('<form action="/register.php" method="POST" onsubmit="return validate_form(this)" id="registration-form">
                          <div class="form-group">
                            <label for="username">Username</label>
                            <input type="username" class="form-control" id="username" name="username" placeholder="Username" required data-error="Username too short (minimum length 6 characters)">
                          </div>

                          <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email address" required data-error="Invalid email">
                          </div>

                          <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required data-error="Password must be 8 characters long and contain 2 of the following: uppercase letters, lowercase letter, numbers, symbols">
                          </div>

                          <div class="form-group">
                            <label for="password_hint">Password reminder</label>
                            <input type="text" class="form-control" id="password_hint" name="password_hint" placeholder="The name of my first pet, place I grew up, ..." required data-error="">
                          </div>

                          <div class="form-group">
                            <label for="firstname">First name</label>
                            <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First name" required data-error="">
                          </div>

                          <div class="form-group">
                            <label for="surname">Surname</label>
                            <input type="text" class="form-control" id="surname" name="surname" placeholder="Surname" required data-error="">
                          </div>

                          <div id="recaptcha-parent" class="form-group">' .
                            $this->get_asset_controller()->get_recaptcha_div() .
                          '</div>

                          <button type="submit" class="btn btn-default">Submit</button>
                        </form>');

        $this->add_body('Already have an account? [Log in here](/login.php)');

        $this->add_body("  </div>");
        $this->add_body("</div>");

        $this->add_extra_script($this->get_asset_controller()->get_specific_asset('js/register/register.js'));
      }

    }

    /**
     * Make sure the $_POST variable has all the necessary fields to register a user
     */
    private function validate_registration_form()
    {
      $required_keys = array("username", "email", "firstname", "surname", "password", "password_hint");
      $something_missing = false;

      foreach($required_keys as $key) {
        if(!isset($_POST[$key])){
          $something_missing = true;
        }
      }
      return !$something_missing;
    }

    /**
     * See model implementation: https://github.com/google/ReCAPTCHA
     */
    private function check_recaptcha()
    {
      include(ROOT_DIRECTORY . 'source/libraries/recaptchalib.php');

      $siteKey = recaptcha_site_key;
      $secret = recaptcha_secret;
      // reCAPTCHA supported 40+ languages listed here: https://developers.google.com/recaptcha/docs/language
      $lang = "en";
      // The response from reCAPTCHA
      $resp = null;
      // The error code from reCAPTCHA, if any
      $error = null;
      $reCaptcha = new ReCaptcha($secret);
      // Was there a reCAPTCHA response?
      if ($_POST["g-recaptcha-response"]) {
        $resp = $reCaptcha->verifyResponse(
            $_SERVER["REMOTE_ADDR"],
            $_POST["g-recaptcha-response"]
        );
        if($resp != null && $resp->success) {
          return true;
        } else {
          return false;
        }
      }
    }

  } // Register

  /**
   *
   */
  class login_page extends page
  {
    public function __construct(){
      // Perform a superclass construction
      parent::__construct();

      $this->set_title('Log in');

      $attempting_login = $this->validate_login_form();
      $success = false;

      if($attempting_login) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $success = $this->get_user()->log_in($username, $password);
      }

      if($this->get_user()->is_logged_in()) {

        $this->add_body('<div class="row text-center">');
        $this->add_body(  '<div class="col-md-12">');

        $this->add_body('You\'re logged in!');
        $this->add_body('[Try voting on some art](/rate.php)');

        $this->add_body('<script>document.location = \'/\'</script>');

        $this->add_body(  '</div>');
        $this->add_body('</div>');

      } else {

        $this->add_body('<div class="row text-center">');
        $this->add_body(  '<div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12">');

        if((!$success) && $attempting_login) {
          $this->add_body('Incorrect username and/or password');
        }

        $this->add_body('<form action="/login.php" method="POST" onsubmit="return validate_form(this)" id="login-form">
                           <div class="form-group">
                             <label for="username">Username</label>
                             <input type="username" class="form-control" id="username" name="username" placeholder="Username" data-error="">
                           </div>
                           <div class="form-group">
                             <label for="password">Password</label>
                             <input type="password" class="form-control" id="password" name="password" placeholder="Password" data-error="">
                           </div>
                           <button type="submit" class="btn btn-default">Log in</button>
                         </form>');

        $this->add_body('Need an account? [Register here](/register.php)');

        $this->add_body(  '</div>');
        $this->add_body('</div>');

        $this->add_extra_script($this->get_asset_controller()->get_specific_asset('js/login/login.js'));
      }

    }

    private function validate_login_form() {
      $required_keys = array("username", "password");
      $something_missing = false;

      foreach($required_keys as $key) {
        if(!isset($_POST[$key])){
          $something_missing = true;
        }
      }
      return !$something_missing;
    }

  } // Login

  /**
   *
   */
  class logout_page extends page
  {
    public function __construct(){
      // Perform a superclass construction
      parent::__construct();

      $this->set_title('Logging out');

      $this->get_user()->log_out();

      $this->add_body('<script>document.location = \'/\'</script>');
    }

  } // Logout

  /**
   *
   */
  class verify_page extends page
  {
    public function __construct(){
      // Perform a superclass construction
      parent::__construct();

      $this->set_title('Verify your email address');


      $this->add_body('<div class="row text-center">');
      $this->add_body(  '<div class="col-md-12">');

      if(isset($_GET['token'])) {
        $success = $this->get_user()->verify_email_address($_GET['token']);

        if($success) {
          $this->add_body('Thanks! We\'ve confirmed your email address');
          $this->add_body('You can now [log in](/login.php) using the username and password you registered with');
        } else {
          $this->add_body('Oops! We don\'t know that email address :(');
          $this->add_body('Did you click the link in your registration email?');
          $this->add_body('Need an account? [Register here](/register.php)');
        }

      } else {

        $this->add_body('Doesn\'t look like you\'ve got an account, trying clicking the link in your registration email');
        $this->add_body('Need an account? [Register here](/register.php)');

      }

      $this->add_body(  '</div>');
      $this->add_body('</div>');

    }
  } // Verify

  /**
   *
   */
  class gather_images_page extends page
  {
    public function __construct(){
      // Perform a superclass construction
      parent::__construct();

      if($this->get_user()->is_logged_in() &&
         $this->get_user()->is_admin()) {

        $this->add_body('<div class="row">', false);

        $this->add_body(  '<div class="col-md-3">', false);
        $this->add_body(    '<a id="VanGogh" class="btn btn-info col-md-12" title="http://www.vangoghgallery.com/catalog/Painting/">Download Van Gogh art</a>', false);
        $this->add_body(  '</div>', false);

        $this->add_body(  '<div class="col-md-3">', false);
        $this->add_body(    '<a id="Gauguin" class="btn btn-info col-md-12" title="http://www.gauguingallery.com/gauguin_paintings_list.aspx">Download Gauguin art</a>', false);
        $this->add_body(  '</div>', false);

        $this->add_body(  '<div class="col-md-3">', false);
        $this->add_body(    '<a id="Caravaggio" class="btn btn-info col-md-12" title="http://www.caravaggiogallery.com/caravaggio-paintings-list.aspx">Download Caravaggio art</a>', false);
        $this->add_body(  '</div>', false);

        $this->add_body(  '<div class="col-md-3">', false);
        $this->add_body(    '<a id="Monet" class="btn btn-info col-md-12" title="http://www.cmonetgallery.com/monet-paintings-list.aspx">Download Monet art</a>', false);
        $this->add_body(  '</div>', false);
        $this->add_body('</div>', false);

        $this->add_body('<div class="row">', false);
        $this->add_body(  '<div class="col-md-12">', false);

        $this->add_body('<pre id="output"></pre>', false);

        $this->add_body(  '</div>', false);
        $this->add_body('</div>', false);

      } else {
        $this->add_body('Unauthorized access');
        //TODO redirect to front page, maybe even 404 it
      }

    }
  } // Gather images

  /**
   * 404 not found page
   */
  class error_404_page extends page
  {
    public function __construct(){
      // Perform a superclass construction
      parent::__construct();

      $this->set_title('Page not found');

      $this->add_body('<div class="row text-center">', false);
      $this->add_body(  '<div class="col-md-12">', false);

      $this->add_body('#Error 404: resource not found, sorry! :(');
      $this->add_body('[Home page](/)');

      $this->add_body(  '</div>', false);
      $this->add_body('</div>', false);

      // Return proper error code
      http_response_code(404);
    }
  }

?>
