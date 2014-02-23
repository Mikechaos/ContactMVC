$(document).ready(function() {
  console.log('yay', $('body').html());
  Contacts_object.init();
  setHandlers();
});

var Contacts_object = {
  collection: [],
  init: function (contacts) {
    var self = this;
    $.get('index.php?rt=contact', function (contacts) {
      self.load(contacts);
      self.EnhanceTable = $('table.enhanced').table_enhancer({
        collection: self.collection,
        deleteCb: self.destroy.bind(self)});
    })
  },
    
  load: function (json) {
    console.log(json);
    this.collection = eval(json);
    return this.collection
  },
  
  add: function (contact) {
    this.collection.push(contact);
    $('table.enhanced').table_enhancer('add', contact);
  },
  
  destroy: function (id) {
    var self = this;
    $.post('index.php?rt=contact/destroy', {id: id}, function (result) {
      console.log('successfull destroy!', result);
      self.init();
    });
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

function setHandlers() {
  $('#create_contact_action').on("click", function (e) {
    var validated;
    e.preventDefault();
    validated = formObject.validate()
    if (validated === true) formObject.create()
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
      phoneData = this.Phones.collectData(),
      post_data = {contacts: this.getData(), phone_numbers: phoneData};
    console.log("Post data", post_data);
    $.post(route, post_data, function (contact_json) {
      var contact = eval(contact_json)[0];
      console.log('data delivered!', contact_json, contact);
      if (contact !== 'VALIDATION ERROR') {
        Contacts_object.add(contact);
        self.clean();
      }
    });
    return $.extend({}, this.data, {phone_numbers: this.Phones.data});
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
    this.Phones.clean();
  },
    
  Phones: {
    new_field: ".new_phone_number",
    copy_field: ".copy_phone",
    data: [],
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
      var self = this;
      $('.phone_number').each(function (i, $elem) {
          self.data.push({
            type: $('select', $elem).val(),
            number: $('input', $elem).val()
          });
      });
      return JSON.stringify(this.data)
    },

    clean: function () {
      this.data = [];
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