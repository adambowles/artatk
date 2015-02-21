<?php
  // Future considerations for this file:
  // 1. Turn it into a front controller to handle ALL site requests
  // and handle passing those requests to the right files in the source folder
  // 2.

  // Configuration file
  include('../source/configuration/config.php');

  // Abstract page template
  include(ROOT_DIRECTORY . 'source/classes/page.php');

  $page = new front_page();
//  $page->set_title('front');

  $page->add_body("<div class=\"starter-template\">");
  $page->add_body("  <h2>ArtAtk, art aesthetic analyser</h2>");
  $page->add_body("  <p class=\"lead\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sit amet mollis ante. Duis sollicitudin turpis ut tellus mattis, elementum auctor urna consequat. Ut nibh magna, facilisis sit amet purus quis, dignissim commodo nisi. Nullam ac convallis est. Nam vel sem vel mauris imperdiet pulvinar. Proin nibh tortor, fringilla aliquam magna non, pellentesque finibus nulla. Quisque mi mauris, cursus sed faucibus et, varius at velit. Nullam a eros sed magna viverra interdum. In hac habitasse platea dictumst. In eleifend in tortor quis bibendum.</p>");
  $page->add_body("</div>");

  $page->print_html();

?>
