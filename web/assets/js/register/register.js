//do a jq each for all tagged form elements and validate them on mouseout and the same of form submit


function validate_form(form)
{
  var fail_count = 0;
  var field_is_valid;
  $('#registration-form :input').each(
    function(){
      field_is_valid = validate_input($(this).val(), $(this).attr('type'));
      if(!field_is_valid) {
        fail_count++;
      }
//      console.log('"' + $(this).val() + '" as ' + $(this).attr('type') + ": " + validate_input($(this).val(), $(this).attr('type')));
    }
  );
  return fail_count == 0; // return form valid if no fails occurred
}

function validate_input(value, as)
{
  // email address
  if(as == 'email') {
    email_regex = /^[a-z0-9._-]+@[a-z0-9.-]+\.[a-z]{2,4}$/i;
    return email_regex.test(value);
  }

  // Password
  if(as == 'password') {
    var matching_rules = 0;

    if(value.length < 8) {
      return false; // Password less than 8 characters
    } else {
      macthing_rules++;
    }

    /[a-z]/.test(value) ? matching_rules++ : null; // Password contains
    /[A-Z]/.test(value) ? matching_rules++ : null;
    /\d/.test(value) ? matching_rules++ : null;
    /\W/.test(value) ? matching_rules++ : null;

    return matching_rules >= 3; //TODO: pw complexity checker, then hash it and set a hidden field to tell the POST that the pw has been hashed already
  }

  // Numerical value
  if(as == 'number') {
    return !isNaN(value);
  }

  // All other cases test value as string
  return "" + value.length > 0;
}
