var start_time = new Date().getTime();

$('input[name="delib_time"]').each(function(){
  $(this).val(start_time);
});

$('a.vote').find('i').on({
  mouseenter: function(){
    var star = $(this).parent().attr('id').replace('star','');
    highlight_stars(star);
  },
  click: function(e){
    e.preventDefault();

    var star_number = $(this).parent().attr('id').replace('star','');
    highlight_stars(star_number);

    setTimeout(function(){
      var start_time = $('#delib_time' + star_number).val();
      var end_time = new Date().getTime();
      var deliberation_time = end_time - start_time;
      $('#delib_time' + star_number).val(deliberation_time);
      $('#vote' + star_number).submit();
    },1);
  },
  mouseleave: function(){
    unhighlight_stars();
  }
});

/**
 * Highlight all the stars up to a certain one
 */
function highlight_stars(amount)
{
  unhighlight_stars();

  if(!isNaN(amount)) {
    for(i = 1; i <= amount; i++) {
      $('#star'+i).children().addClass('fa-star');
      $('#star'+i).children().removeClass('fa-star-o');
    }
  }
}

/**
 * Makes all voting stars on the page become unhighlighted
 */
function unhighlight_stars()
{
  $('a.vote').find('i').addClass('fa-star-o');
  $('a.vote').find('i').removeClass('fa-star');
}
