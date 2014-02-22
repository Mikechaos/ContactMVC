<?php

  class IndexController extends BaseController
  {
    // set a template variable and load the index template
    public function index()
    {
      $this->registry->template->page_title = 'WAJAM MVC';
      $this->registry->template->show('index');
    }

  }

?>
