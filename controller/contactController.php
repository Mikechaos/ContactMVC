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
    $json_contact = $this->contactModel->create($_POST);
    $this->registry->template->json_contacts = json_encode([$json_contact]);
    $this->registry->template->show('show_contact');
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
