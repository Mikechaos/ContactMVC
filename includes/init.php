<?php

	/*** include the controller class ***/
	include __PATH . '/modules/' . 'controller_base.class.php';

	/*** include the registry class ***/
	include __PATH . '/modules/' . 'registry.class.php';

	/*** include the router class ***/
	include __PATH . '/modules/' . 'router.class.php';

	/*** include the template class ***/
	include __PATH . '/modules/' . 'template.class.php';

	/*** auto load model classes ***/
	function __autoload($class_name) {
    $filename = strtolower($class_name) . '.model.php';
    $file = __PATH . '/model/' . $filename;

    if (file_exists($file) == false)
    {
      return false;
    }
    include ($file);
  }

	/*** a new registry object ***/
	$registry = new registry;

	/*** create the database registry object ***/
	$registry->db = DB::getInstance();
?>
