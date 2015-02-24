<?php

  /**
   * @author Adam Bowles <bowlesa@aston.ac.uk>
   */
  class page
  {

    // Some funcationality controllers
    private $asset_controller;
    private $database_controller;

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
      include(ROOT_DIRECTORY . "source/classes/html_asset_controller.php");
      include(ROOT_DIRECTORY . "source/classes/database_controller.php");

      $this->asset_controller = new html_asset_controller();

      $this->database_controller = new database_controller();
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
    public function get_asset_controller()
    {
      return $this->asset_controller;
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
    private function construct_head()
    {
      return "<!doctype html>" .
             "<html>" .
             "<head>" .
               $this->get_charset_meta_tag() .
               "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">" .
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
                                <li id="home" class="activeX"><a href="/"><i class="fa fa-home"></i></a></li>
                                <li><a href="/rate.php"><i class="fa fa-star-half-o"></i> Rate</a></li>
                                <li><a href="/recommendation.php"><i class="fa fa-photo"></i> Get your recommendation</a></li>
                              </ul>' .

//                              "<ul class="nav navbar-nav navbar-right">
//                                <li><p class="nav navbar-text">Logged in as </p></li>
//                                <li class="dropdown">
//                                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Adam Bowles <b class="caret"></b></a>
//                                  <ul class="dropdown-menu" role="menu">
//                                    <li><a href="#"><i class="fa fa-user"></i> Edit profile</a></li>
//                                    <li class="divider"></li>
//                                    <li><a href="#"><i class="fa fa-sign-out"></i> Log out</a></li>
//                                  </ul>
//                                </li>
//                              </ul>"

                              '<ul class="nav navbar-nav navbar-right">
                                <li><a href="/login.php"><i class="fa fa-sign-in"></i> Log in</a></li>
                                <li><a href="register.php"><i class="fa fa-user-plus"></i> Register</a></li>
                              </ul>' .



                            '</div><!--/.nav-collapse -->
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
     *
     */
    public function add_body($content)
    {
      $this->body = $this->body . $content;
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

//      $this->get_database_controller()->connect_read();
//      $this->get_database_controller()->delete_user_by_id(2); //TODO test remove this later
//      $this->get_database_controller()->delete_user_by_username('bowlesa'); //TODO test remove this later

      // Demo content
      $this->add_body('<div class="starter-template">');
      $this->add_body('  <h2>ArtAtk, art aesthetic analyser</h2>');
      $this->add_body('  <p class="lead">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sit amet mollis ante. Duis sollicitudin turpis ut tellus mattis, elementum auctor urna consequat. Ut nibh magna, facilisis sit amet purus quis, dignissim commodo nisi. Nullam ac convallis est. Nam vel sem vel mauris imperdiet pulvinar. Proin nibh tortor, fringilla aliquam magna non, pellentesque finibus nulla. Quisque mi mauris, cursus sed faucibus et, varius at velit. Nullam a eros sed magna viverra interdum. In hac habitasse platea dictumst. In eleifend in tortor quis bibendum.</p>');
      $this->add_body('</div>');
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

      // Demo content
      $this->add_body('<div class="starter-template">');
      $this->add_body('  <h2>Rate some art</h2>');
      $this->add_body('  <p class="lead">image here</p>');
      $this->add_body('</div>');
    }
  }

  /**
   *
   */
  class recommendation_page extends page
  {
    public function __construct(){
      // Perform a superclass construction
      parent::__construct();

    }
  }

  /**
   *
   */
  class register_page extends page
  {
    public function __construct(){
      // Perform a superclass construction
      parent::__construct();

      if($this->validate_registration_form()) {

        $registration_success = $this->get_database_controller()->create_user($_POST['username'],
                                                                              $_POST['email'],
                                                                              sha1($_POST['email']),
                                                                              $_POST['firstname'],
                                                                              $_POST['surname'],
                                                                              password_hash($_POST['password'], PASSWORD_DEFAULT),
                                                                              $_POST['password_hint'],
                                                                              $_SERVER['REMOTE_ADDR']);

        $this->add_body('<div class="row text-center">');
        $this->add_body('  <div class="col-lg-12">');

        if($registration_success) {
          $this->add_body('<h3>Account created!</h3>');
          $this->add_body('<p class="lead"><a href="/login.php">Log in</a></p>');
        } else {
          $this->add_body('<h3>There was an error :(</h3>');
          $this->add_body('<p class="lead"><a href="/register.php">Back to registration form</a></p>');
        }

        $this->add_body('  </div>');
        $this->add_body('</div>');
      } else {

        $this->add_body('<div class="row text-center">');
        $this->add_body('  <div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12">');

        $this->add_body('<form action="/register.php" method="POST" onsubmit="return validate_form(this)" id="registration-form">
                          <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                          </div>

                          <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email address">
                          </div>

                          <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                          </div>

                          <div class="form-group">
                            <label for="password_hint">Password reminder</label>
                            <input type="text" class="form-control" id="password_hint" name="password_hint" placeholder="The name of my first pet, place I grew up, ...">
                          </div>

                          <div class="form-group">
                            <label for="firstname">First name</label>
                            <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First name">
                          </div>

                          <div class="form-group">
                            <label for="surname">Surname</label>
                            <input type="text" class="form-control" id="surname" name="surname" placeholder="Surname">
                          </div>

                          <div class="form-group">' .
                            $this->get_asset_controller()->get_recaptcha_div() .
                          '</div>

                          <button type="submit" class="btn btn-default">Submit</button>
                        </form>');

        $this->add_body('<p class="lead">Already have an account? <a href="/login.php">Log in</a></p>');

        $this->add_body("  </div>");
        $this->add_body("</div>");

        $this->add_extra_script($this->get_asset_controller()->get_specific_js("register/register.js"));
        $this->add_extra_script($this->get_asset_controller()->get_formvalidator_js());
      }

    }

    private function validate_registration_form()
    {
      $required_keys = array("username", "email", "firstname", "surname", "password", "password_hint"); //TODO full list as per database_controller->create_user()
      $something_missing = false;

      foreach($required_keys as $key) {
        if(!isset($_POST[$key])){
          $something_missing = true;
        }
      }
      return !$something_missing;
    }
  }

  /**
   *
   */
  class login_page extends page
  {
    public function __construct(){
      // Perform a superclass construction
      parent::__construct();

      $this->add_body('<div class="row text-center">');
      $this->add_body(  '<div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12">');

      $this->add_body('<form>
                        <div class="form-group">
                          <label for="exampleInputEmail1">Email address</label>
                          <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                          <label for="exampleInputPassword1">Password</label>
                          <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>
                      </form>');

      $this->add_body('<p class="lead">Need an account? <a href="/regiser.php">Regsiter</a></p>');

      $this->add_body(  '</div>');
      $this->add_body('</div>');

    }
  }

  /**
   * //TODO keep this one at the end of the script
   */
  class error_404_page extends page
  {
    public function __construct(){
      // Perform a superclass construction
      parent::__construct();

      $this->set_title('Page not found');

      $this->add_body('<div class="row text-center">');
      $this->add_body(  '<div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12">');

      $this->add_body('<h2>404: not found, sorry! :(</h2>');
      $this->add_body('<h3><a href="/">Home page</a></h3>');

      $this->add_body(  '</div>');
      $this->add_body('</div>');

      // Return proper error code
      http_response_code(404);
    }
  }

?>
