<?php

class Template {

  private $registry;
  private $attributes = array();

  function __construct($registry) {
    $this->registry = $registry;
  }

  public function __set($index, $value)
  {
    $this->attributes[$index] = $value;
  }


  function show($name) 
  {
    $path = __PATH . '/views' . '/' . $name . '.php';

    if (file_exists($path) == false)
    {
      throw new Exception('Template not found in '. $path);
      return false;
    }

    // Load variables
    foreach ($this->attributes as $key => $value)
    {
      $$key = $value;
    }

    include ($path);               
  }


}

?>
