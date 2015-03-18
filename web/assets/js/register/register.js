// Functions
function validate_form(form)
{

  // Check if any of the unique (user/email) fields are already taken in the DB
  var any_unavailable = $('.invalid_field').length > 0;

  // Regardless of errors, check the user filled in the reCAPTCHA
  var recaptcha_failed = false;
  if(grecaptcha.getResponse() == '') {
    //TODO ask the user to do reCPATCHA
    recaptcha_failed = true;
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

  var recaptcha_succeeded = !recaptcha_failed;
  var all_available = !any_unavailable;

  return fail_count == 0 && recaptcha_succeeded && all_available; // return form valid if no fails occurred
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

  var no_error_data = field.attr('data-error') == '' || field.attr('data-error') == undefined;
  if(no_error_data) {
    field.parent().append('<p class="text-danger">' + 'Field cannot be empty' + '</p>');
  } else {
    field.parent().append('<p class="text-danger">' + data + '</p>');
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

function add_loading(field)
{
  field = $(field); // Ensure it is a jQuery object

  field.parent().addClass('has-feedback');

  field.parent().append('<span class="glyphicon glyphicon-refresh form-control-feedback glyphicon-spin"></span>');
}

function remove_loading(field)
{
  field = $(field); // Ensure it is a jQuery object

  if(field.parent().hasClass('has-feedback')) {
    field.parent().removeClass('has-feedback');
  }

  field.siblings('span').each(function(){
    $(this).remove();
  });
}

// Control AJAX request to availability checker script
function check_availability(field, as) {
  field = $(field);
  add_loading(field);
  var value = field.val();

  $.ajax({
    url: '/availability_checker.php',
    type: 'post',
    data: {'value': value, 'as': as},
    success: function(data, status) {

      // Make sure the request returns only the string it should
      data = data.replace('/r','');
      data = data.replace('/n','');
      data = data.trim();

      if(data == 'available') {
        remove_error(field);
        remove_loading(field);
      }

      if(data == 'unavailable') {
        add_error(field, 'Unfortunately, ' + value + ' is not available');
        remove_loading(field);
      }

    }
  });
}

// Generic field triggers
$('#registration-form :input').each(function () {
  $(this).on({
    blur: function () { // Add a blur (leave field) event handler to each field to trigger a validation

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
    if(validate_input($(this), $(this).attr('type'))){
      check_availability($(this), $(this).attr('type'));
    }
  }
});

$('#in_education, #not_in_education').change(function(){
  var at_uni = $('#in_education').prop('checked');
  $('#year_of_study').prop('disabled', !at_uni);
  $('#year_of_study').attr('required', at_uni);
  $('#degree_level').prop('disabled', !at_uni);
  $('#degree_level').attr('required', at_uni);
});
