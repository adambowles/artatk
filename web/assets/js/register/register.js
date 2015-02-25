$('#registration-form').find(':input').each(function() {
  $(this).on({
    blur: function(){ // Add a blur (leave field) event handler to each field to trigger a validation
      var valid = validate_input($(this).val(), $(this).attr('type'));

      remove_error($(this));

      if(!valid) {
        add_error($(this));
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

function validate_form(form)
{

  var fail_count = 0;

  var field_is_valid;
  var value;
  var type;

  $(form).find(':input').each(function() {

    value = $(this).val();
    type = $(this).attr('type');

    if(!(type == 'submit' | type == undefined)) { // don't validate the submit button or the reCAPTCHA
      field_is_valid = validate_input(value, type);

      if(!field_is_valid) {
        fail_count++;
        add_error($(this));
      } else {
        remove_error($(this));
      }
    }

  });

  // firstly, regardless of errors, check the user filled in the reCAPTCHA
  if(grecaptcha.getResponse() == '') {
    // ask the user to do reCPATCHA
    return false;
  }

  return fail_count == 0; // return form valid if no fails occurred
}

function validate_input(value, as)
{
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

function add_error(field)
{
  $(field).parent().addClass('has-error');
  if($(field).attr('data-error') != '') {
    $(field).parent().append('<p class="text-danger">' + $(field).attr('data-error') + '</p>');
  } else {
    $(field).parent().append('<p class="text-danger">' + 'Field cannot be empty' + '</p>');
  }
}

function remove_error(field)
{
  if($(field).parent().hasClass('has-error')) {
    $(field).parent().removeClass('has-error');
  }

  $(field).parent().find('p').remove();
}
