<?php

class contactController extends BaseController {

  private $contactModel;
  
  public function __construct($registry) {
    parent::__construct($registry);
    $this->contactModel = new Contacts($this->mysqli);
  }
  
  public function index() 
  {
    $json_contacts = $this->contactModel->index();
    $this->registry->template->json_contacts = $json_contacts;
    $this->registry->template->show('show_contact');
  }

  public function create()
  {
    // print_r($_POST);
    $this->contactModel->create($_POST);
    // $data = (array)json_decode($_POST['form']);
    // $columns = "(" . implode(',', array_keys($data)) . ")";
    // $values = "('" . implode("','", $data) . "')";
    // $sql = "INSERT INTO contacts $columns VALUES $values";
    // echo mysqli_escape_string($this->mysqli, $sql);
    // $result = $this->mysqli->query($sql);
    // echo "results: " . $result;
    // print_r(array_keys((array)json_decode($_POST['post'])));
    // print_r(json_decode($_POST['post']));
  }

  public function destroy() {
    $id = $_POST['id'];
    $this->contactModel->destroy($id);
    return true;
  }
  
  public function view()
  {
    $this->registry->template->blog_heading = 'View Contact';
    $this->registry->template->blog_content = 'Content';
    $this->registry->template->show('blog_view');
  }

}
?>
