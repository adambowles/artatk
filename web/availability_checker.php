<?php
  // Use this script to check availability of of a username, email address, or other
  // call it via ajax like:
  // /availability_checker.php?data=my_username&as=username
  // where: data = the data to be checked
  //        as = the type to be checked as

  // DO NOT RUN THIS SCRIPT AS ITS OWN PAGE
  // It will produce no results on its own

  if(isset($_POST['value']) && isset($_POST['as'])) {
    $data = $_POST['value'];
    $as = $_POST['as'];
    $response = '';
  } else {
    die('Invalid arguments');
  }

  include('../source/configuration/config.php');

  include(ROOT_DIRECTORY . 'source/classes/database_controller.php');

  $db = new database_controller();

  $available = $db->check_availability($data, $as);

  if($available) {
    $response = 'available';
  } else {
    $response = 'unavailable';
  }

  echo $response;

?>
