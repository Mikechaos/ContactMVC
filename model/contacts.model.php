<?php
  
  class Contacts {
    
    private $mysqli;
    private $last_contact_id;
    
    public function __construct($mysqli)
    {
      $this->mysqli = $mysqli;
    }
    
    public function index() {
      $rows = $this->mysqli->query("SELECT * FROM contacts ORDER BY last_name, first_name");
      $contacts = [];
      while ($contact = $rows->fetch_assoc())
      {
        $contact = $this->loadNumbers($contact);
        array_push($contacts, $contact);
      }
      return json_encode($contacts);
    }
    
    public function create($post)
    {
      $this->dispatchData($post);
      return $this->getContact($this->last_contact_id);
    }
    
    public function update($post)
    {
      $contact = $this->updateContacts((array)json_decode($post['contacts']));
      $this->updatePhones((array)json_decode($post['phone_numbers']));
      return $this->getContact($contact['id']);
    }

    public function updateContacts($contact)
    {
      $sql = $this->prepare_update_query('contacts', $contact);
      $this->mysqli->query($sql);
      
      return $contact;
    }
    
    public function updatePhones($phones)
    {
      foreach ($phones as $phone)
      {
        $sql = $this->prepare_update_query('phone_numbers', (array)$phone);
        $this->mysqli->query($sql);
      }
    }
    
    public function loadNumbers($contact)
    {
      $contact_id = $contact['id'];
      $contact['phone_numbers'] = [];
      $phones = $this->mysqli->query("SELECT * FROM phone_numbers WHERE contact_id=$contact_id ORDER BY id");
      while ($phone = $phones->fetch_assoc())
      {
        array_push($contact['phone_numbers'], $phone);
      }
      return $contact;
    }
    
    
    public function dispatchData($tables)
    {
      $action_map = array('contacts' => 'insertContact', 'phone_numbers' => 'insertPhones');
      foreach ($tables as $table => $json_data)
      {
        $data = (array)json_decode($json_data);
        $action = $action_map[$table];
        $this->$action($table, $data);
      }
    }
    
    public function insertContact($table, $contact)
    {
      $this->insertData($table, $contact);
      $this->last_contact_id = $this->mysqli->insert_id;
    }
    
    public function insertPhones($table, $phone_array)
    {
      foreach ($phone_array as $phone)
      {
        $phone = (array)$phone;
        $phone = $this->add_contact_id($phone);
        $this->insertData($table, $phone);
      }
    }
    
    public function insertData($table, $data)
    {
      $data = $this->sanitize_data($data);
      if ($data == false) die("VALIDATION ERROR");
      $sql = $this->prepare_insert_query($table, $data);
      $result = $this->mysqli->query($sql);
      if (!$result)
      {
        die("ERROR INSERTING FORM DATA IN DATABASE : " . $this->mysqli->error);
      }
    }
       
    public function add_contact_id($phone) {
      $phone['contact_id'] = $this->last_contact_id;
      return $phone;
    }
    
    public function destroy($id)
    {
      $this->mysqli->query("DELETE FROM contacts WHERE id = $id");
    }
    
    
    public function sanitize_data($data)
    {
      $validated = true;
      foreach ($data as $column => $value)
      {
        $validated = $this->validate_field($column, $value);
        if (!$validated) return false;
        $data[$column] = mysqli_escape_string($this->mysqli, $value);
      }
      return $data;
    }
    
    public function validate_field($column, $value)
    {
      if (empty($value)) return false;
      return true;
    }
    
    public function prepare_insert_query($table, $data) {
      $columns = "(" . implode(',', array_keys($data)) . ")";
      $values = "('" . implode("','", $data) . "')";
      return "INSERT INTO $table $columns VALUES $values";
    }
    
    public function prepare_update_query($table, $data) {
      $sql = "UPDATE $table SET ";
      $fields = "";
      foreach ($data as $key => $value)
      {
        $fields .= (($fields == "") ? '':', ') . $key . "='" . $value . "'";
      }
      return $sql . $fields . " WHERE id = " . $data['id'];
    }
    
    public function getContact($id) {
      $result = $this->mysqli->query("SELECT * FROM contacts WHERE id=$id LIMIT 1");
      $contact = $this->loadNumbers($result->fetch_assoc());
      return $contact;
    }
  }

?>