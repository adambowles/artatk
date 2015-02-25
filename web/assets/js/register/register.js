// Add an onblur event handler to each field to trigger a validation
var valid;
$('#registration-form').find(':input').each(function() {
  $(this).on('blur', function(){

    valid = validate_input($(this).val(), $(this).attr('type'));

    if(valid) {
      $(this).parent().removeClass('has-error');
    } else {
      $(this).parent().addClass('has-error');
    }

  });
});

// Add an onkeyup event handler to each field to clear its error state
$('#registration-form').find(':input').each(function() {
  $(this).on('keydown', function(){

      if ($(this).parent().hasClass('has-error')) {
        $(this).parent().removeClass('has-error');
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
        $(this).parent().addClass('has-error');
      } else {
        $(this).parent().removeClass('has-error');
      }
    }

  });
  return fail_count == 0; // return form valid if no fails occurred
}

function validate_input(value, as)
{
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
