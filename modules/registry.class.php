<?php
 
  /*
   * The Registry class provides a namespace
   * to which we can bind any global needed,
   * reducing the global namespace pollution
   * to only one variable
   */
  class Registry
  {

    private $attributes = array();

    public function __set($index, $value)
    {
      $this->attributes[$index] = $value;
    }

    public function __get($index)
    {
      return $this->attributes[$index];
    }
  }

?>
