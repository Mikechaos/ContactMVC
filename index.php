<?php

  /*** error reporting on ***/
  error_reporting(E_ALL);

  /*** define the site path ***/
  $path = realpath(dirname(__FILE__));
  define ('__PATH', $path);

  /*** include the init.php file ***/
  include 'includes/init.php';

  /*** load the router ***/
  $registry->router = new Router($registry, __PATH);

  /*** load up the template ***/
  $registry->template = new Template($registry);

  /*** load the controller ***/
  $registry->router->loader();

?>
