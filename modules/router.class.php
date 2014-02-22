<?php

  class Router {

    private $registry;
    private $path;
    private $args = array();

    public $file;
    public $controller;
    public $action; 

    function __construct($registry, $site_path)
    {
      $this->registry = $registry;
      $this->setPath($site_path . '/controller');
    }

    function setPath($path)
    {
      if (is_dir($path) == false)
      {
        throw new Exception ('Invalid controller path: `' . $path . '`');
      }
      $this->path = $path;
    }

    public function check404()
    {
      if (is_readable($this->file) == false)
      {
        $this->file = $this->path.'/error404.php';
        $this->controller = 'error404';
      }
    }
    
    public function loader()
    {
      // check the route and redirect to 404 if needed
      $this->getController();
      $this->check404();

      // Include the controller
      include $this->file;

      // Create the controller instance
      // and call the action or default to index
      $controller = $this->loadController();
      $this->executeAction($controller);
    }
    
    public function loadController()
    {
      $class = $this->controller . 'Controller';
      return new $class($this->registry);
    }
    
    public function executeAction($controller)
    {
      $controller_is_defined = is_callable(array($controller, $this->action));
      $action = ($controller_is_defined != false) ? $this->action : 'index';
      $controller->$action();
    }


    private function getController()
    {
      // Figure out the route from the url
      $route = (empty($_GET['rt'])) ? 'index' : $_GET['rt'];

      $parts = explode('/', $route);
      $this->controller = $parts[0];
      $this->action = (isset($parts[1])) ? $parts[1] : 'index';

      // set the file path
      $this->file = $this->path .'/'. $this->controller . 'Controller.php';
    }

  }

?>
