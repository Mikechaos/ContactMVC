<?php

  abstract class BaseController
  {

    protected $registry;
    protected $mysqli;

    function __construct($registry) {
      $this->registry = $registry;
      $this->mysqli = $registry->db;
    }

    // Default action for any controller
    abstract function index();
  }

?>
