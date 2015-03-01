// Generic field triggers
$('#login-form :input').each(function() {
  $(this).on({
    focus: function(){ // Add a focus event handler to each field to clear its error state

      remove_error($(this));

    }
  });
});

function validate_form(form)
{
  var fail_count = 0;

  var value;
  var type;

  $(form).find(':input').each(function() {

    value = $(this).val();
    type = $(this).attr('type');

    // Types of field that can be validated
    var valid_type = (type == 'username' || type == 'password');
    if(valid_type) { // Only validate fields that make sense

      if(value.length == 0) {
        add_error($(this), $(this).attr('data-error'));
        fail_count++;
      }
    }

  });

  return fail_count == 0; // return form valid if no fails occurred
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
