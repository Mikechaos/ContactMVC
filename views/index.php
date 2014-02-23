<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <title><?php echo $page_title ?></title>
    <script src="app/assets/javascript/lib/jquery.js?body=1" type="text/javascript"></script>
    <script src="app/assets/javascript/plugins/table_enhancer.plugin.js?body=1" type="text/javascript"></script>
    <script src="app/assets/javascript/application.js" type="text/javascript"></script>
    <!-- <link href="/app/assets/css/style.css" media="screen" rel="Stylesheet" type="text/css"/> -->
  </head>
  <body>
    <div id="display_contact" style="float:left;"><table class="enhanced"></table></div>
    <div id="create_contact" style="margin-left:35%;">
      <form>
        <div>
          <label>First Name: </label>
          <input type="text" id="first_name">
        </div>
        <div>
          <label>Last Name: </label>
          <input type="text" id="last_name">
        </div>
        <div class="phone_number">
          <span class="copy_phone">
          <label>Phone Number: </label>
          <select>
            <option value="Mobile">Mobile</option>
            <option value="Home">Home</option>
            <option value="Office">Office</option>
            <option value="Other">Other</option>
          </select>
          <input type="text" id="phone_number">
          </span>
          <button class="add_phone_action">+</button>
        </div>
        <div class="new_phone_number">
        </div>
        <button class="btn btn-success" id="create_contact_action">Submit</button>
      </form>
    </div>
  </body>
  
</html>
<!--

-->