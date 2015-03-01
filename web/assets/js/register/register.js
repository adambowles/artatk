// Generic field triggers
$('#registration-form :input').each(function() {
  $(this).on({
    blur: function(){ // Add a blur (leave field) event handler to each field to trigger a validation

      var valid = validate_input($(this), $(this).attr('type'));
      if(!valid) {
        add_error($(this), $(this).attr('data-error'));
      }

    },
    keyup: function(){ // Add a keyup event handler to each field to clear its error state

      remove_error($(this));

    },
    focus: function(){ // Add a focus event handler to each field to clear its error state

      remove_error($(this));

    }
  });
});


// Specific field triggers
$('#username, #email').on({
  blur: function(){
    if($(this).val().length > 0) {
      check_availability($(this), $(this).attr('type'));
    }
  }
});


// Control AJAX request to availability checker script
function check_availability(field, as) {
  field = $(field);
  var value = field.val();

  $.ajax({
    url: '/availability_checker.php',
    type: 'post',
    data: {'value': value, 'as': as},
    success: function(data, status) {

      data = data.trim();

      if(data == 'available') {
        console.log('available');
        remove_error(field);
      }

      if(data == 'unavailable') {
        console.log('unavailable');
        add_error(field, 'Unfortunately, ' + value + ' is not available :(');
      }

      console.log(data);

    }
  });
}

function validate_form(form)
{

  // Check if any of the unique (user/email) fields are already taken in the DB
  var any_unavailable = $('.invalid_field').length > 0;
  if(any_unavailable) {
    return false;
  }

  // Regardless of errors, check the user filled in the reCAPTCHA
  if(grecaptcha.getResponse() == '') {
    // ask the user to do reCPATCHA
    return false;
  }

  var fail_count = 0;

  var field_is_valid;
  var value;
  var type;

  $(form).find(':input').each(function() {

    value = $(this).val();
    type = $(this).attr('type');

    // Types of field that can be validated
    var valid_type = (type == 'username' || type == 'email' || type == 'password' || type == 'number' || type == 'text');
    var field_needs_validating = $(this).attr('required'); // Field designated as required
    if(field_needs_validating && valid_type) { // Only validate fields that make sense

      remove_error($(this));

      field_is_valid = validate_input($(this), type);
      if(!field_is_valid) {
        fail_count++;
        add_error($(this), $(this).attr('data-error'));
      }
    }

  });

  return fail_count == 0; // return form valid if no fails occurred
}

function validate_input(field, as)
{
  field = $(field);

  var value = field.val();

  // username
  if(as == 'username') {
    return value.length >= 6;
  }

  // email address
  if(as == 'email') {
    var email_regex = /^[a-z0-9._+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/i;
    return email_regex.test(value);
  }

  // Password
  if(as == 'password') {
    var matching_rules = 0;

    if(value.length < 8) {
      return false; // Password absolutely cannot be less than 8 chars long
    }

    /[a-z]/.test(value) ? matching_rules++ : null; // Password contains lower case
    /[A-Z]/.test(value) ? matching_rules++ : null; // Password contains upper case
       /\d/.test(value) ? matching_rules++ : null; // Password contains number
       /\W/.test(value) ? matching_rules++ : null; // Password contains special character

    return matching_rules >= 2; // Password must match at least 2 rules
  }

  // Numerical value
  if(as == 'number') {
    return !isNaN(value);
  }

  // All other cases test value as string
  return "" + value.length > 0;
}

function add_error(field, data)
{
  field = $(field); // Ensure it is a jQuery object

  field.parent().addClass('has-error');
  field.addClass('invalid_field');

  if(field.attr('data-error') != '') {
    field.parent().append('<p class="text-danger">' + data + '</p>');
  } else {
    field.parent().append('<p class="text-danger">' + 'Field cannot be empty' + '</p>');
  }
}

function remove_error(field)
{
  field = $(field); // Ensure it is a jQuery object

  field.removeClass('invalid_field');

  if(field.parent().hasClass('has-error')) {
    field.parent().removeClass('has-error');
  }

  field.siblings('p').each(function(){
    $(this).remove();
  });
}
