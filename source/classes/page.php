<?php

  class page
  {

    public function construct_page()
    {
      return $this->construct_header() . $this->construct_body() . $this->construct_footer();
    }

    private function construct_header()
    {
      return 'header';
    }

    private function construct_body()
    {
      return 'body';
    }

    private function construct_footer()
    {
      return 'footer';
    }

  }

?>
