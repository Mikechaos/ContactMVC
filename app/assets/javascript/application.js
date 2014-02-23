$(document).ready(function() {
  console.log('yay', $('body').html());
  Contacts_object.init();
  setHandlers();
});
var EnhanceTable;

var Contacts_object = {
  collection: [],
  init: function (contacts) {
    var self = this;
    $.get('index.php?rt=contact', function (contacts) {
      self.display(self.load(contacts));
      // EnhanceTable = $('table').table_enhancer();
      self.EnhanceTable = $('table.enhanced').table_enhancer({collection: fakeData});
    })
  },
    
  load: function (json) {
    console.log(json);
    this.collection = eval(json);
    return this.collection
  },
  
  add: function () {
    var contact = formObject.create();
    this.collection.push({
      first_name: contact.first_name, 
      last_name: contact.last_name, 
      phone_number: contact.phone_number
    });
  },
  
  destroy: function (id) {
    var self = this;
    $.post('index.php?rt=contact/destroy', {id: id}, function (result) {
      console.log('successfull destroy!', result);
      self.init();
    });
  },
  
  display: function () {
    var i = 0, html = buildTableHeader();
    for (; i < this.collection.length; ++i) {
      html += this.fillTemplate(this.collection[i]);
    }
    html += closeTable();
    $('#display_contact').html(html);
  },
  
  fillTemplate: function (contact) {
    var html = '<tr>';
    html += '<td>' + contact.last_name + '</td>';
    html += '<td>' + contact.first_name + '</td>';
    html += '<td>' + contact.phone_number + '</td>';
    html += '<td><button class="delete_action" data-id="' + contact.id + '">X</button>';
    return html + '</tr>';
  }
}

function buildTableHeader() {
  return '<table><thead><tr><th>First Name</th><th>Last Name</th><th>Phone Number</th><th>Delete contact</th></tr></thead><tbody>';
}

function closeTable() {
  return '</tbody></table>';
}

function setHandlers() {
  $('#create_contact_action').on("click", function (e) {
    var validated;
    e.preventDefault()
    validated = formObject.validate()
    if (validated === true) Contacts_object.add()
  });

  $(document).on('click', '.delete_action', function(e) {
    var validated, id = e.target.getAttribute('data-id');
    e.preventDefault();
    Contacts_object.destroy(id);
  });
  
  $(document).on('click', '.add_phone_action', function(e) {
    e.preventDefault();
    formObject.Phones.add();
  });
  
  $(document).on('click', '.delete_phone_action', function(e) {
    e.preventDefault();
    formObject.Phones.destroy(e.target.getAttribute('data-id'));
  });
}

var formObject = {
  fields: ['first_name', 'last_name'],
  data: {},
  
  validate: function () {
    var i = 0,
        validated = true;
    for (; i < this.fields.length; ++i) {
      validated = this.validateField(this.fields[i]) && validated;
    }
    return validated;
  },
    
  validateField: function (field) {
    var $field = $('#' + field),
        value = $field.val(),
        validated = value !== '';
    this.data[field] = value
    if (validated === false)
    {
      alert("Validation Error : " + field + " field is empty!");
    }
    return true;
  },
  
  create: function () {
    var self = this,
      route = 'index.php?rt=contact/create',
      post_data = {contacts: this.getData(), phone_numbers: this.Phones.collectData()};
    console.log("Post data", post_data);
    $.post(route, post_data, function (result) {
      console.log('data delivered!', result);
      if (result !== 'VALIDATION ERROR') {
        Contacts_object.init()
        self.clean()
      }
    });
    return this.data;
  },
    
  getData: function () {
    return JSON.stringify(this.data)
  },
    
  clean: function () {
    var i = 0;
    this.data = {};
    for (; i < this.fields.length; ++i) {
      $('#' + this.fields[i]).val('');
    }
  },
    
  Phones: {
    new_field: ".new_phone_number",
    copy_field: ".copy_phone",
    count: 0,
    
    add: function () {
      ++this.count
      html = $(this.copy_field).html() + this.deleteTemplate();
      appendTo = $(this.new_field);
      appendTo.append('<div class="phone_number" id="phone_number_' + this.count + '">' + html + '</div>');
    },
    
    destroy: function (id) {
      $("#phone_number_" + id).detach()
    },
         
    deleteTemplate: function () {
      return "<button class='delete_phone_action' data-id='" + this.count + "'>x</button>";
    },
      
    collectData: function () {
      var data = [];
      $('.phone_number').each(function (i, $elem) {
          data.push({
            type: $('select', $elem).val(),
            number: $('input', $elem).val()
          });
      });
      console.log(JSON.stringify(data));
      return JSON.stringify(data)
    },
  },
}

// function loadContact(json) {
  // console.log(json);
  // var contacts = eval(json);
  // return contacts
// }

// function displayContact(contacts) {
  // var i = 0, html = buildTableHeader();
  // for (; i < contacts.length; ++i) {
    // html+=contactTemplate(contacts[i]);
  // }
  // html+= closeTable();
  // $('#display_contact').html(html);
// }

// function contactTemplate(contact) {
    // var html = '<tr>';
    // html += '<td>' + contact.first_name + '</td>';
    // html += '<td>' + contact.last_name + '</td>';
    // html += '<td>' + contact.phone_number + '</td>';
    // return html + '</tr>';
// }