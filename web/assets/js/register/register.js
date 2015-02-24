function validate_form(form)
{
  form = $(form);
  var fail_count = 0;

  var field_is_valid;
  var value;
  var type;

  $('#registration-form :input').each(
    function(){

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

    }
  );
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

    return matching_rules >= 3; // Password must match at least 3 rules
  }

  // Numerical value
  if(as == 'number') {
    return !isNaN(value);
  }

  // All other cases test value as string
  return "" + value.length > 0;
}
