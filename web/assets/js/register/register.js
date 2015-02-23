//do a jq each for all tagged form elements and validate them on mouseout and the same of form submit


function validate_form(form)
{
  $('#registration-form :input').each(
    function(){
      console.log('"' + $(this).val() + '" as ' + $(this).attr('type') + ": " + validate_input($(this).val(), $(this).attr('type')));
    }
  );
  return false;
}

function validate_input(value, as)
{
  if(as == 'email') {
    email_regex = /^[a-z0-9._-]+@[a-z0-9.-]+\.[a-z]{2,4}$/i;
    return email_regex.test(value);
  }

  if(as == 'password') {
    return true; //TODO: pw complexity checker, then hash it and set a hidden field to tell the POST that the pw has been hashed already
  }

  if(as == 'number') {
    return !isNaN(value);
  }

  // all other cases test value as string
  return "" + value.length > 0;
}
