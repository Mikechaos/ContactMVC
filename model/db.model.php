<?php

  // Singleton class to access our DB object across all the app
  class DB{
    
    private static $instance = NULL;

    // Return the instance or create it if it doesnt exist
    public static function getInstance() {
      if (!self::$instance)
      {
        self::$instance = new mysqli("localhost", "root", "", "wajammvc");
        if (!self::$instance)
        {
          die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
        }
      }
      return self::$instance;
    }

    // Private so it can't be directly instantiated or cloned
    private function __construct() {}
    private function __clone() {}

  }

?>
